/*
  # Accounting System - Triggers and Functions

  ## Overview
  This migration creates database functions and triggers that automate:
  - Running balance calculations
  - Journal entry validation
  - Audit trail recording
  - Timestamp management
  - Data integrity enforcement

  ## Functions Created
  1. `update_timestamp()` - Auto-update updated_at fields
  2. `validate_journal_entry()` - Ensure debits = credits
  3. `calculate_journal_totals()` - Auto-sum entry lines
  4. `post_to_general_ledger()` - Automatically post approved entries to GL
  5. `update_account_balance()` - Maintain current account balances
  6. `create_audit_log()` - Record all changes for audit trail
  7. `generate_entry_number()` - Auto-generate sequential entry numbers

  ## Triggers Created
  - Timestamp triggers on all tables
  - Balance validation triggers
  - Audit trail triggers
  - GL posting triggers
*/

-- ============================================================================
-- FUNCTION: Update Timestamp
-- ============================================================================

CREATE OR REPLACE FUNCTION update_timestamp()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = now();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- ============================================================================
-- FUNCTION: Calculate Journal Entry Totals
-- ============================================================================

CREATE OR REPLACE FUNCTION calculate_journal_totals()
RETURNS TRIGGER AS $$
DECLARE
  v_total_debit DECIMAL(18,2);
  v_total_credit DECIMAL(18,2);
BEGIN
  -- Calculate totals from journal entry lines
  SELECT 
    COALESCE(SUM(debit_amount), 0),
    COALESCE(SUM(credit_amount), 0)
  INTO v_total_debit, v_total_credit
  FROM journal_entry_lines
  WHERE journal_entry_id = COALESCE(NEW.journal_entry_id, OLD.journal_entry_id);
  
  -- Update the journal entry totals
  UPDATE journal_entries
  SET 
    total_debit = v_total_debit,
    total_credit = v_total_credit,
    updated_at = now()
  WHERE id = COALESCE(NEW.journal_entry_id, OLD.journal_entry_id);
  
  RETURN COALESCE(NEW, OLD);
END;
$$ LANGUAGE plpgsql;

-- ============================================================================
-- FUNCTION: Validate Journal Entry Before Status Change
-- ============================================================================

CREATE OR REPLACE FUNCTION validate_journal_entry()
RETURNS TRIGGER AS $$
DECLARE
  v_total_debit DECIMAL(18,2);
  v_total_credit DECIMAL(18,2);
  v_line_count INTEGER;
BEGIN
  -- Only validate when changing status from DRAFT
  IF NEW.status != 'DRAFT' AND OLD.status = 'DRAFT' THEN
    
    -- Check if entry has lines
    SELECT COUNT(*) INTO v_line_count
    FROM journal_entry_lines
    WHERE journal_entry_id = NEW.id;
    
    IF v_line_count = 0 THEN
      RAISE EXCEPTION 'Cannot submit journal entry without any lines';
    END IF;
    
    IF v_line_count < 2 THEN
      RAISE EXCEPTION 'Journal entry must have at least 2 lines (debit and credit)';
    END IF;
    
    -- Validate balance
    IF NEW.total_debit != NEW.total_credit THEN
      RAISE EXCEPTION 'Journal entry is not balanced: Debits (%) != Credits (%)', 
        NEW.total_debit, NEW.total_credit;
    END IF;
    
    -- Set submission timestamp
    IF NEW.status = 'PENDING' AND NEW.approved_at IS NULL THEN
      NEW.updated_at = now();
    END IF;
  END IF;
  
  -- Record approval
  IF NEW.status = 'APPROVED' AND OLD.status = 'PENDING' THEN
    NEW.approved_at = now();
    NEW.approved_by = auth.uid();
  END IF;
  
  -- Record decline
  IF NEW.status = 'DECLINED' THEN
    NEW.declined_at = now();
    NEW.declined_by = auth.uid();
  END IF;
  
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- ============================================================================
-- FUNCTION: Post to General Ledger
-- ============================================================================

CREATE OR REPLACE FUNCTION post_to_general_ledger()
RETURNS TRIGGER AS $$
DECLARE
  v_line RECORD;
  v_account RECORD;
  v_running_balance DECIMAL(18,2);
  v_gl_id UUID;
BEGIN
  -- Only post when status changes to POSTED
  IF NEW.status = 'POSTED' AND OLD.status != 'POSTED' THEN
    
    -- Set posted timestamp
    NEW.posted_at = now();
    NEW.posted_by = auth.uid();
    NEW.posting_date = CURRENT_DATE;
    
    -- Process each line
    FOR v_line IN 
      SELECT * FROM journal_entry_lines 
      WHERE journal_entry_id = NEW.id 
      ORDER BY line_number
    LOOP
      -- Get account information
      SELECT * INTO v_account
      FROM chart_of_accounts
      WHERE id = v_line.account_id;
      
      -- Calculate running balance for this account
      SELECT COALESCE(running_balance, 0) INTO v_running_balance
      FROM general_ledger
      WHERE account_id = v_line.account_id
      ORDER BY posting_date DESC, created_at DESC
      LIMIT 1;
      
      -- Adjust running balance based on normal balance
      IF v_account.normal_balance = 'DEBIT' THEN
        v_running_balance := v_running_balance + v_line.debit_amount - v_line.credit_amount;
      ELSE
        v_running_balance := v_running_balance + v_line.credit_amount - v_line.debit_amount;
      END IF;
      
      -- Insert into general ledger
      INSERT INTO general_ledger (
        account_id,
        journal_entry_id,
        journal_entry_line_id,
        transaction_date,
        posting_date,
        fiscal_period_id,
        description,
        reference_number,
        debit_amount,
        credit_amount,
        running_balance
      ) VALUES (
        v_line.account_id,
        NEW.id,
        v_line.id,
        NEW.entry_date,
        CURRENT_DATE,
        NEW.fiscal_period_id,
        COALESCE(v_line.description, NEW.description),
        NEW.reference_number,
        v_line.debit_amount,
        v_line.credit_amount,
        v_running_balance
      )
      RETURNING id INTO v_gl_id;
      
      -- Insert into sub-ledger
      INSERT INTO sub_ledgers (
        account_id,
        journal_entry_id,
        general_ledger_id,
        transaction_date,
        value_date,
        transaction_type,
        reference_number,
        description,
        debit_amount,
        credit_amount,
        balance,
        metadata
      ) VALUES (
        v_line.account_id,
        NEW.id,
        v_gl_id,
        NEW.entry_date,
        NEW.entry_date,
        NEW.entry_type,
        NEW.reference_number,
        COALESCE(v_line.description, NEW.description),
        v_line.debit_amount,
        v_line.credit_amount,
        v_running_balance,
        jsonb_build_object(
          'department', v_line.department,
          'cost_center', v_line.cost_center,
          'project_code', v_line.project_code
        )
      );
      
      -- Update account current balance
      UPDATE chart_of_accounts
      SET 
        current_balance = v_running_balance,
        updated_at = now()
      WHERE id = v_line.account_id;
      
    END LOOP;
  END IF;
  
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- ============================================================================
-- FUNCTION: Create Audit Log
-- ============================================================================

CREATE OR REPLACE FUNCTION create_audit_log()
RETURNS TRIGGER AS $$
DECLARE
  v_old_values JSONB;
  v_new_values JSONB;
  v_changed_fields TEXT[];
BEGIN
  -- Prepare old and new values
  IF TG_OP = 'DELETE' THEN
    v_old_values := to_jsonb(OLD);
    v_new_values := NULL;
  ELSIF TG_OP = 'INSERT' THEN
    v_old_values := NULL;
    v_new_values := to_jsonb(NEW);
  ELSE -- UPDATE
    v_old_values := to_jsonb(OLD);
    v_new_values := to_jsonb(NEW);
    
    -- Identify changed fields
    SELECT array_agg(key)
    INTO v_changed_fields
    FROM jsonb_each(v_new_values)
    WHERE v_old_values->key IS DISTINCT FROM v_new_values->key;
  END IF;
  
  -- Insert audit record
  INSERT INTO audit_trail (
    table_name,
    record_id,
    operation,
    old_values,
    new_values,
    changed_fields,
    user_id,
    ip_address
  ) VALUES (
    TG_TABLE_NAME,
    COALESCE(NEW.id, OLD.id),
    TG_OP,
    v_old_values,
    v_new_values,
    v_changed_fields,
    auth.uid(),
    inet_client_addr()
  );
  
  RETURN COALESCE(NEW, OLD);
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- ============================================================================
-- FUNCTION: Generate Entry Number
-- ============================================================================

CREATE OR REPLACE FUNCTION generate_entry_number()
RETURNS TRIGGER AS $$
DECLARE
  v_year TEXT;
  v_sequence INTEGER;
  v_entry_number TEXT;
BEGIN
  IF NEW.entry_number IS NULL THEN
    -- Get current year
    v_year := to_char(NEW.entry_date, 'YYYY');
    
    -- Get next sequence for the year
    SELECT COALESCE(MAX(
      CAST(
        SUBSTRING(entry_number FROM '\d+$') AS INTEGER
      )
    ), 0) + 1
    INTO v_sequence
    FROM journal_entries
    WHERE entry_number LIKE 'JE-' || v_year || '-%';
    
    -- Generate entry number: JE-YYYY-NNNN
    v_entry_number := 'JE-' || v_year || '-' || LPAD(v_sequence::TEXT, 6, '0');
    
    NEW.entry_number := v_entry_number;
  END IF;
  
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- ============================================================================
-- APPLY TRIGGERS
-- ============================================================================

-- Update timestamp triggers
DO $$
DECLARE
  t TEXT;
BEGIN
  FOR t IN 
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = 'public' 
    AND table_type = 'BASE TABLE'
    AND table_name IN (
      'account_categories', 'account_classes', 'chart_of_accounts',
      'fiscal_periods', 'journal_entries', 'journal_entry_lines',
      'cash_remittances', 'bank_accounts', 'approval_workflows'
    )
  LOOP
    -- Check if updated_at column exists
    IF EXISTS (
      SELECT 1 FROM information_schema.columns 
      WHERE table_name = t AND column_name = 'updated_at'
    ) THEN
      EXECUTE format('
        DROP TRIGGER IF EXISTS update_timestamp_%I ON %I;
        CREATE TRIGGER update_timestamp_%I
          BEFORE UPDATE ON %I
          FOR EACH ROW
          EXECUTE FUNCTION update_timestamp();
      ', t, t, t, t);
    END IF;
  END LOOP;
END $$;

-- Journal entry lines trigger (calculate totals)
DROP TRIGGER IF EXISTS calculate_totals_on_line_change ON journal_entry_lines;
CREATE TRIGGER calculate_totals_on_line_change
  AFTER INSERT OR UPDATE OR DELETE ON journal_entry_lines
  FOR EACH ROW
  EXECUTE FUNCTION calculate_journal_totals();

-- Journal entry validation trigger
DROP TRIGGER IF EXISTS validate_entry_before_submit ON journal_entries;
CREATE TRIGGER validate_entry_before_submit
  BEFORE UPDATE ON journal_entries
  FOR EACH ROW
  EXECUTE FUNCTION validate_journal_entry();

-- General ledger posting trigger
DROP TRIGGER IF EXISTS post_entry_to_gl ON journal_entries;
CREATE TRIGGER post_entry_to_gl
  BEFORE UPDATE ON journal_entries
  FOR EACH ROW
  EXECUTE FUNCTION post_to_general_ledger();

-- Generate entry number trigger
DROP TRIGGER IF EXISTS auto_generate_entry_number ON journal_entries;
CREATE TRIGGER auto_generate_entry_number
  BEFORE INSERT ON journal_entries
  FOR EACH ROW
  EXECUTE FUNCTION generate_entry_number();

-- Audit trail triggers for critical tables
DO $$
DECLARE
  t TEXT;
BEGIN
  FOR t IN 
    SELECT unnest(ARRAY[
      'journal_entries', 'journal_entry_lines', 
      'general_ledger', 'chart_of_accounts',
      'cash_remittances'
    ])
  LOOP
    EXECUTE format('
      DROP TRIGGER IF EXISTS audit_log_%I ON %I;
      CREATE TRIGGER audit_log_%I
        AFTER INSERT OR UPDATE OR DELETE ON %I
        FOR EACH ROW
        EXECUTE FUNCTION create_audit_log();
    ', t, t, t, t);
  END LOOP;
END $$;

-- ============================================================================
-- HELPER FUNCTIONS FOR REPORTS
-- ============================================================================

-- Function to get account balance at a specific date
CREATE OR REPLACE FUNCTION get_account_balance(
  p_account_id UUID,
  p_as_of_date DATE DEFAULT CURRENT_DATE
)
RETURNS DECIMAL(18,2) AS $$
DECLARE
  v_balance DECIMAL(18,2);
BEGIN
  SELECT COALESCE(running_balance, 0)
  INTO v_balance
  FROM general_ledger
  WHERE account_id = p_account_id
    AND posting_date <= p_as_of_date
  ORDER BY posting_date DESC, created_at DESC
  LIMIT 1;
  
  RETURN COALESCE(v_balance, 0);
END;
$$ LANGUAGE plpgsql;

-- Function to get trial balance
CREATE OR REPLACE FUNCTION get_trial_balance(
  p_start_date DATE DEFAULT CURRENT_DATE,
  p_end_date DATE DEFAULT CURRENT_DATE
)
RETURNS TABLE (
  account_code VARCHAR,
  account_name VARCHAR,
  debit_amount DECIMAL,
  credit_amount DECIMAL
) AS $$
BEGIN
  RETURN QUERY
  SELECT 
    coa.account_code,
    coa.account_name,
    CASE 
      WHEN coa.normal_balance = 'DEBIT' 
      THEN COALESCE(SUM(gl.debit_amount - gl.credit_amount), 0)
      ELSE 0
    END AS debit_amount,
    CASE 
      WHEN coa.normal_balance = 'CREDIT' 
      THEN COALESCE(SUM(gl.credit_amount - gl.debit_amount), 0)
      ELSE 0
    END AS credit_amount
  FROM chart_of_accounts coa
  LEFT JOIN general_ledger gl ON coa.id = gl.account_id
    AND gl.posting_date BETWEEN p_start_date AND p_end_date
  WHERE coa.is_active = true
  GROUP BY coa.id, coa.account_code, coa.account_name, coa.normal_balance
  HAVING COALESCE(SUM(gl.debit_amount), 0) != 0 
      OR COALESCE(SUM(gl.credit_amount), 0) != 0
  ORDER BY coa.account_code;
END;
$$ LANGUAGE plpgsql;
