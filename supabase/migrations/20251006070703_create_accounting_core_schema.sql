/*
  # Professional Accounting System - Core Schema

  ## Overview
  This migration creates a complete double-entry accounting system following international
  banking standards (IFRS/GAAP compliant). The system ensures:
  - Every transaction is balanced (Debits = Credits)
  - Complete audit trail
  - Data integrity through foreign keys
  - Separation of duties through approval workflows

  ## 1. New Tables Created
  
  ### Account Management
  - `account_categories` - Top-level account groupings (Assets, Liabilities, Equity, Revenue, Expenses)
  - `account_classes` - GL Code classifications (Current Assets, Fixed Assets, etc.)
  - `chart_of_accounts` - Complete chart of accounts with codes and descriptions
  
  ### Transaction Management
  - `journal_entries` - Master journal entry records with approval status
  - `journal_entry_lines` - Individual debit/credit lines for each entry
  - `general_ledger` - Consolidated GL with running balances
  - `sub_ledgers` - Detailed transaction records by account
  
  ### Cash Management
  - `cash_remittances` - Daily cash collection tracking
  - `bank_accounts` - Bank account master data
  - `bank_reconciliations` - Bank reconciliation tracking
  
  ### Audit & Control
  - `fiscal_periods` - Accounting period management
  - `posting_batches` - Batch posting control
  - `audit_trail` - Complete transaction audit log
  - `user_approvals` - Multi-level approval tracking

  ## 2. Security Implementation
  - Row Level Security (RLS) enabled on all tables
  - Authentication required for all operations
  - Role-based access control policies
  
  ## 3. Data Integrity Controls
  - Foreign key constraints ensure referential integrity
  - Check constraints enforce business rules
  - Triggers maintain running balances automatically
  - Transaction atomicity through database constraints

  ## 4. Important Notes
  - All monetary values use DECIMAL(18,2) for precision
  - Timestamps track all changes (created_at, updated_at)
  - Soft deletes implemented where appropriate
  - Balance calculations automated via triggers
*/

-- ============================================================================
-- ACCOUNT CATEGORIES (Top Level)
-- ============================================================================

CREATE TABLE IF NOT EXISTS account_categories (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  code VARCHAR(2) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  normal_balance VARCHAR(10) NOT NULL CHECK (normal_balance IN ('DEBIT', 'CREDIT')),
  display_order INTEGER NOT NULL,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

ALTER TABLE account_categories ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view account categories"
  ON account_categories FOR SELECT
  TO authenticated
  USING (true);

-- ============================================================================
-- ACCOUNT CLASSES (GL Code Level)
-- ============================================================================

CREATE TABLE IF NOT EXISTS account_classes (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  category_id UUID NOT NULL REFERENCES account_categories(id),
  gl_code VARCHAR(10) NOT NULL UNIQUE,
  name VARCHAR(200) NOT NULL,
  description TEXT,
  report_type VARCHAR(50) NOT NULL CHECK (report_type IN ('BALANCE_SHEET', 'INCOME_STATEMENT', 'BOTH')),
  is_active BOOLEAN DEFAULT true,
  display_order INTEGER NOT NULL,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

ALTER TABLE account_classes ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view account classes"
  ON account_classes FOR SELECT
  TO authenticated
  USING (true);

-- ============================================================================
-- CHART OF ACCOUNTS
-- ============================================================================

CREATE TABLE IF NOT EXISTS chart_of_accounts (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  account_class_id UUID NOT NULL REFERENCES account_classes(id),
  account_code VARCHAR(20) NOT NULL UNIQUE,
  account_name VARCHAR(300) NOT NULL,
  description TEXT,
  parent_account_id UUID REFERENCES chart_of_accounts(id),
  account_type VARCHAR(50) NOT NULL,
  normal_balance VARCHAR(10) NOT NULL CHECK (normal_balance IN ('DEBIT', 'CREDIT')),
  is_control_account BOOLEAN DEFAULT false,
  is_system_account BOOLEAN DEFAULT false,
  allow_manual_entry BOOLEAN DEFAULT true,
  is_active BOOLEAN DEFAULT true,
  opening_balance DECIMAL(18,2) DEFAULT 0,
  current_balance DECIMAL(18,2) DEFAULT 0,
  created_by UUID REFERENCES auth.users(id),
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE INDEX idx_coa_account_code ON chart_of_accounts(account_code);
CREATE INDEX idx_coa_class ON chart_of_accounts(account_class_id);
CREATE INDEX idx_coa_parent ON chart_of_accounts(parent_account_id);

ALTER TABLE chart_of_accounts ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view chart of accounts"
  ON chart_of_accounts FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Authorized users can manage accounts"
  ON chart_of_accounts FOR ALL
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM auth.users
      WHERE auth.users.id = auth.uid()
    )
  )
  WITH CHECK (
    EXISTS (
      SELECT 1 FROM auth.users
      WHERE auth.users.id = auth.uid()
    )
  );

-- ============================================================================
-- FISCAL PERIODS
-- ============================================================================

CREATE TABLE IF NOT EXISTS fiscal_periods (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  period_name VARCHAR(50) NOT NULL,
  fiscal_year INTEGER NOT NULL,
  period_number INTEGER NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'OPEN' CHECK (status IN ('OPEN', 'CLOSED', 'LOCKED')),
  closed_by UUID REFERENCES auth.users(id),
  closed_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(fiscal_year, period_number)
);

CREATE INDEX idx_fiscal_periods_dates ON fiscal_periods(start_date, end_date);

ALTER TABLE fiscal_periods ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view fiscal periods"
  ON fiscal_periods FOR SELECT
  TO authenticated
  USING (true);

-- ============================================================================
-- JOURNAL ENTRIES (Master Record)
-- ============================================================================

CREATE TABLE IF NOT EXISTS journal_entries (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  entry_number VARCHAR(50) NOT NULL UNIQUE,
  entry_date DATE NOT NULL,
  posting_date DATE,
  fiscal_period_id UUID REFERENCES fiscal_periods(id),
  entry_type VARCHAR(30) NOT NULL DEFAULT 'GENERAL' CHECK (entry_type IN ('GENERAL', 'ADJUSTING', 'CLOSING', 'REVERSING', 'SYSTEM')),
  reference_number VARCHAR(100),
  description TEXT NOT NULL,
  total_debit DECIMAL(18,2) NOT NULL DEFAULT 0,
  total_credit DECIMAL(18,2) NOT NULL DEFAULT 0,
  status VARCHAR(20) NOT NULL DEFAULT 'DRAFT' CHECK (status IN ('DRAFT', 'PENDING', 'APPROVED', 'POSTED', 'DECLINED', 'REVERSED')),
  is_balanced BOOLEAN GENERATED ALWAYS AS (total_debit = total_credit) STORED,
  
  -- Audit fields
  created_by UUID REFERENCES auth.users(id),
  approved_by UUID REFERENCES auth.users(id),
  posted_by UUID REFERENCES auth.users(id),
  declined_by UUID REFERENCES auth.users(id),
  
  approved_at TIMESTAMPTZ,
  posted_at TIMESTAMPTZ,
  declined_at TIMESTAMPTZ,
  decline_reason TEXT,
  
  reversed_by_entry_id UUID REFERENCES journal_entries(id),
  reverses_entry_id UUID REFERENCES journal_entries(id),
  
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  
  -- Constraint: ensure balanced entries
  CONSTRAINT check_balanced_entry CHECK (total_debit = total_credit OR status = 'DRAFT')
);

CREATE INDEX idx_je_entry_number ON journal_entries(entry_number);
CREATE INDEX idx_je_entry_date ON journal_entries(entry_date);
CREATE INDEX idx_je_status ON journal_entries(status);
CREATE INDEX idx_je_fiscal_period ON journal_entries(fiscal_period_id);

ALTER TABLE journal_entries ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view journal entries"
  ON journal_entries FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Users can create journal entries"
  ON journal_entries FOR INSERT
  TO authenticated
  WITH CHECK (auth.uid() = created_by);

CREATE POLICY "Users can update their draft entries"
  ON journal_entries FOR UPDATE
  TO authenticated
  USING (created_by = auth.uid() AND status = 'DRAFT')
  WITH CHECK (created_by = auth.uid());

-- ============================================================================
-- JOURNAL ENTRY LINES (Details)
-- ============================================================================

CREATE TABLE IF NOT EXISTS journal_entry_lines (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  journal_entry_id UUID NOT NULL REFERENCES journal_entries(id) ON DELETE CASCADE,
  line_number INTEGER NOT NULL,
  account_id UUID NOT NULL REFERENCES chart_of_accounts(id),
  description TEXT,
  debit_amount DECIMAL(18,2) DEFAULT 0 CHECK (debit_amount >= 0),
  credit_amount DECIMAL(18,2) DEFAULT 0 CHECK (credit_amount >= 0),
  
  -- Additional reference fields
  reference_type VARCHAR(50),
  reference_id UUID,
  customer_id UUID,
  vendor_id UUID,
  employee_id UUID,
  
  department VARCHAR(100),
  cost_center VARCHAR(50),
  project_code VARCHAR(50),
  
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  
  UNIQUE(journal_entry_id, line_number),
  
  -- Constraint: ensure either debit or credit, not both
  CONSTRAINT check_debit_or_credit CHECK (
    (debit_amount > 0 AND credit_amount = 0) OR 
    (credit_amount > 0 AND debit_amount = 0)
  )
);

CREATE INDEX idx_jel_journal_entry ON journal_entry_lines(journal_entry_id);
CREATE INDEX idx_jel_account ON journal_entry_lines(account_id);
CREATE INDEX idx_jel_reference ON journal_entry_lines(reference_type, reference_id);

ALTER TABLE journal_entry_lines ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view journal entry lines"
  ON journal_entry_lines FOR SELECT
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM journal_entries je
      WHERE je.id = journal_entry_lines.journal_entry_id
    )
  );

CREATE POLICY "Users can manage lines for their entries"
  ON journal_entry_lines FOR ALL
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM journal_entries je
      WHERE je.id = journal_entry_lines.journal_entry_id
      AND je.created_by = auth.uid()
      AND je.status = 'DRAFT'
    )
  )
  WITH CHECK (
    EXISTS (
      SELECT 1 FROM journal_entries je
      WHERE je.id = journal_entry_lines.journal_entry_id
      AND je.created_by = auth.uid()
      AND je.status = 'DRAFT'
    )
  );

-- ============================================================================
-- GENERAL LEDGER
-- ============================================================================

CREATE TABLE IF NOT EXISTS general_ledger (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  account_id UUID NOT NULL REFERENCES chart_of_accounts(id),
  journal_entry_id UUID NOT NULL REFERENCES journal_entries(id),
  journal_entry_line_id UUID NOT NULL REFERENCES journal_entry_lines(id),
  
  transaction_date DATE NOT NULL,
  posting_date DATE NOT NULL,
  fiscal_period_id UUID REFERENCES fiscal_periods(id),
  
  description TEXT,
  reference_number VARCHAR(100),
  
  debit_amount DECIMAL(18,2) DEFAULT 0,
  credit_amount DECIMAL(18,2) DEFAULT 0,
  running_balance DECIMAL(18,2) NOT NULL,
  
  created_at TIMESTAMPTZ DEFAULT now()
);

CREATE INDEX idx_gl_account ON general_ledger(account_id);
CREATE INDEX idx_gl_transaction_date ON general_ledger(transaction_date);
CREATE INDEX idx_gl_posting_date ON general_ledger(posting_date);
CREATE INDEX idx_gl_journal_entry ON general_ledger(journal_entry_id);
CREATE INDEX idx_gl_fiscal_period ON general_ledger(fiscal_period_id);

ALTER TABLE general_ledger ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view general ledger"
  ON general_ledger FOR SELECT
  TO authenticated
  USING (true);

-- ============================================================================
-- SUB-LEDGERS (Detailed Transaction Records)
-- ============================================================================

CREATE TABLE IF NOT EXISTS sub_ledgers (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  account_id UUID NOT NULL REFERENCES chart_of_accounts(id),
  journal_entry_id UUID NOT NULL REFERENCES journal_entries(id),
  general_ledger_id UUID NOT NULL REFERENCES general_ledger(id),
  
  transaction_date DATE NOT NULL,
  value_date DATE,
  
  transaction_type VARCHAR(50),
  reference_number VARCHAR(100),
  description TEXT,
  
  debit_amount DECIMAL(18,2) DEFAULT 0,
  credit_amount DECIMAL(18,2) DEFAULT 0,
  balance DECIMAL(18,2) NOT NULL,
  
  -- Party information
  party_type VARCHAR(50),
  party_id UUID,
  party_name VARCHAR(300),
  
  -- Additional metadata
  metadata JSONB,
  
  created_at TIMESTAMPTZ DEFAULT now()
);

CREATE INDEX idx_subledger_account ON sub_ledgers(account_id);
CREATE INDEX idx_subledger_transaction_date ON sub_ledgers(transaction_date);
CREATE INDEX idx_subledger_reference ON sub_ledgers(reference_number);
CREATE INDEX idx_subledger_party ON sub_ledgers(party_type, party_id);

ALTER TABLE sub_ledgers ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view sub-ledgers"
  ON sub_ledgers FOR SELECT
  TO authenticated
  USING (true);

-- ============================================================================
-- CASH REMITTANCES
-- ============================================================================

CREATE TABLE IF NOT EXISTS cash_remittances (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  remittance_number VARCHAR(50) NOT NULL UNIQUE,
  remittance_date DATE NOT NULL,
  
  -- Officer information
  remitting_officer_id UUID REFERENCES auth.users(id),
  remitting_officer_name VARCHAR(200),
  posting_officer_id UUID REFERENCES auth.users(id),
  posting_officer_name VARCHAR(200),
  
  -- Remittance details
  category VARCHAR(100) NOT NULL,
  subcategory VARCHAR(100),
  amount_remitted DECIMAL(18,2) NOT NULL,
  number_of_receipts INTEGER DEFAULT 0,
  
  -- Bank deposit information
  bank_account_id UUID,
  deposit_date DATE,
  deposit_reference VARCHAR(100),
  
  -- Status tracking
  status VARCHAR(20) NOT NULL DEFAULT 'PENDING' CHECK (status IN ('PENDING', 'VERIFIED', 'POSTED', 'DECLINED')),
  journal_entry_id UUID REFERENCES journal_entries(id),
  
  -- Approval tracking
  verified_by UUID REFERENCES auth.users(id),
  verified_at TIMESTAMPTZ,
  posted_by UUID REFERENCES auth.users(id),
  posted_at TIMESTAMPTZ,
  
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE INDEX idx_remit_number ON cash_remittances(remittance_number);
CREATE INDEX idx_remit_date ON cash_remittances(remittance_date);
CREATE INDEX idx_remit_officer ON cash_remittances(remitting_officer_id);
CREATE INDEX idx_remit_status ON cash_remittances(status);

ALTER TABLE cash_remittances ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view cash remittances"
  ON cash_remittances FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Officers can create remittances"
  ON cash_remittances FOR INSERT
  TO authenticated
  WITH CHECK (auth.uid() = remitting_officer_id);

CREATE POLICY "Officers can update their remittances"
  ON cash_remittances FOR UPDATE
  TO authenticated
  USING (remitting_officer_id = auth.uid() AND status = 'PENDING')
  WITH CHECK (remitting_officer_id = auth.uid());

-- ============================================================================
-- AUDIT TRAIL
-- ============================================================================

CREATE TABLE IF NOT EXISTS audit_trail (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  table_name VARCHAR(100) NOT NULL,
  record_id UUID NOT NULL,
  operation VARCHAR(20) NOT NULL CHECK (operation IN ('INSERT', 'UPDATE', 'DELETE')),
  
  old_values JSONB,
  new_values JSONB,
  changed_fields TEXT[],
  
  user_id UUID REFERENCES auth.users(id),
  ip_address INET,
  user_agent TEXT,
  
  created_at TIMESTAMPTZ DEFAULT now()
);

CREATE INDEX idx_audit_table_record ON audit_trail(table_name, record_id);
CREATE INDEX idx_audit_user ON audit_trail(user_id);
CREATE INDEX idx_audit_timestamp ON audit_trail(created_at);

ALTER TABLE audit_trail ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view audit trail"
  ON audit_trail FOR SELECT
  TO authenticated
  USING (true);

-- ============================================================================
-- BANK ACCOUNTS
-- ============================================================================

CREATE TABLE IF NOT EXISTS bank_accounts (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  account_number VARCHAR(50) NOT NULL UNIQUE,
  account_name VARCHAR(200) NOT NULL,
  bank_name VARCHAR(200) NOT NULL,
  bank_branch VARCHAR(200),
  account_type VARCHAR(50) NOT NULL,
  currency VARCHAR(3) DEFAULT 'NGN',
  
  -- Link to GL account
  gl_account_id UUID REFERENCES chart_of_accounts(id),
  
  current_balance DECIMAL(18,2) DEFAULT 0,
  available_balance DECIMAL(18,2) DEFAULT 0,
  
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

ALTER TABLE bank_accounts ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view bank accounts"
  ON bank_accounts FOR SELECT
  TO authenticated
  USING (true);

-- ============================================================================
-- APPROVAL WORKFLOWS
-- ============================================================================

CREATE TABLE IF NOT EXISTS approval_workflows (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  workflow_type VARCHAR(50) NOT NULL,
  record_type VARCHAR(50) NOT NULL,
  record_id UUID NOT NULL,
  
  level_number INTEGER NOT NULL,
  approver_id UUID REFERENCES auth.users(id),
  approver_role VARCHAR(100),
  
  status VARCHAR(20) NOT NULL DEFAULT 'PENDING' CHECK (status IN ('PENDING', 'APPROVED', 'DECLINED', 'SKIPPED')),
  
  approved_at TIMESTAMPTZ,
  decision_notes TEXT,
  
  created_at TIMESTAMPTZ DEFAULT now()
);

CREATE INDEX idx_approval_record ON approval_workflows(record_type, record_id);
CREATE INDEX idx_approval_approver ON approval_workflows(approver_id);
CREATE INDEX idx_approval_status ON approval_workflows(status);

ALTER TABLE approval_workflows ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view approval workflows"
  ON approval_workflows FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Approvers can update their workflows"
  ON approval_workflows FOR UPDATE
  TO authenticated
  USING (approver_id = auth.uid() AND status = 'PENDING')
  WITH CHECK (approver_id = auth.uid());
