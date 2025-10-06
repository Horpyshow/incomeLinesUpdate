/*
  # Seed Chart of Accounts - International Standard

  ## Overview
  This migration populates the accounting system with a complete Chart of Accounts
  following international standards (IFRS/GAAP compliant).

  ## Account Structure
  1. Assets (1xxxx)
     - Current Assets (10xxx)
     - Fixed Assets (11xxx)
     - Other Assets (12xxx)
  
  2. Liabilities (2xxxx)
     - Current Liabilities (20xxx)
     - Long-term Liabilities (21xxx)
  
  3. Equity (3xxxx)
     - Capital (30xxx)
     - Retained Earnings (31xxx)
  
  4. Revenue (4xxxx)
     - Operating Revenue (40xxx)
     - Other Revenue (41xxx)
  
  5. Expenses (5xxxx)
     - Operating Expenses (50xxx)
     - Administrative Expenses (51xxx)
     - Financial Expenses (52xxx)
*/

-- ============================================================================
-- ACCOUNT CATEGORIES
-- ============================================================================

INSERT INTO account_categories (code, name, description, normal_balance, display_order) VALUES
('1', 'ASSETS', 'Resources owned by the organization', 'DEBIT', 1),
('2', 'LIABILITIES', 'Obligations owed to others', 'CREDIT', 2),
('3', 'EQUITY', 'Owner''s residual interest in assets', 'CREDIT', 3),
('4', 'REVENUE', 'Income from operations and other sources', 'CREDIT', 4),
('5', 'EXPENSES', 'Costs incurred to generate revenue', 'DEBIT', 5)
ON CONFLICT (code) DO NOTHING;

-- ============================================================================
-- ACCOUNT CLASSES
-- ============================================================================

-- Assets Classes
INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '1'),
  '1000',
  'Current Assets',
  'Assets expected to be converted to cash within one year',
  'BALANCE_SHEET',
  1
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '1000');

INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '1'),
  '1100',
  'Fixed Assets',
  'Long-term tangible assets used in operations',
  'BALANCE_SHEET',
  2
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '1100');

INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '1'),
  '1200',
  'Other Assets',
  'Intangible and other long-term assets',
  'BALANCE_SHEET',
  3
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '1200');

-- Liabilities Classes
INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '2'),
  '2000',
  'Current Liabilities',
  'Obligations due within one year',
  'BALANCE_SHEET',
  1
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '2000');

INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '2'),
  '2100',
  'Long-term Liabilities',
  'Obligations due beyond one year',
  'BALANCE_SHEET',
  2
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '2100');

-- Equity Classes
INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '3'),
  '3000',
  'Capital',
  'Owner''s capital contributions',
  'BALANCE_SHEET',
  1
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '3000');

INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '3'),
  '3100',
  'Retained Earnings',
  'Accumulated profits and losses',
  'BALANCE_SHEET',
  2
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '3100');

-- Revenue Classes
INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '4'),
  '4000',
  'Operating Revenue',
  'Revenue from primary business operations',
  'INCOME_STATEMENT',
  1
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '4000');

INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '4'),
  '4100',
  'Other Revenue',
  'Revenue from secondary sources',
  'INCOME_STATEMENT',
  2
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '4100');

-- Expense Classes
INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '5'),
  '5000',
  'Cost of Sales',
  'Direct costs of generating revenue',
  'INCOME_STATEMENT',
  1
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '5000');

INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '5'),
  '5100',
  'Operating Expenses',
  'Expenses for running daily operations',
  'INCOME_STATEMENT',
  2
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '5100');

INSERT INTO account_classes (category_id, gl_code, name, description, report_type, display_order)
SELECT 
  (SELECT id FROM account_categories WHERE code = '5'),
  '5200',
  'Administrative Expenses',
  'General administrative and overhead costs',
  'INCOME_STATEMENT',
  3
WHERE NOT EXISTS (SELECT 1 FROM account_classes WHERE gl_code = '5200');

-- ============================================================================
-- CHART OF ACCOUNTS - ASSETS
-- ============================================================================

-- Current Assets
INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1000'),
  '10001',
  'Cash on Hand',
  'Physical cash and currency',
  'ASSET',
  'DEBIT',
  true,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '10001');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1000'),
  '10002',
  'Cash in Bank - Current Account',
  'Bank current/checking account',
  'ASSET',
  'DEBIT',
  true,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '10002');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1000'),
  '10003',
  'Cash in Bank - Savings Account',
  'Bank savings account',
  'ASSET',
  'DEBIT',
  true,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '10003');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1000'),
  '10100',
  'Accounts Receivable',
  'Amounts owed by customers',
  'ASSET',
  'DEBIT',
  true,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '10100');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1000'),
  '10105',
  'Allowance for Doubtful Accounts',
  'Estimated uncollectible receivables',
  'ASSET',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '10105');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1000'),
  '10200',
  'Inventory',
  'Goods held for sale',
  'ASSET',
  'DEBIT',
  true,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '10200');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1000'),
  '10300',
  'Prepaid Expenses',
  'Expenses paid in advance',
  'ASSET',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '10300');

-- Fixed Assets
INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1100'),
  '11000',
  'Land',
  'Land and land improvements',
  'ASSET',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '11000');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1100'),
  '11100',
  'Buildings',
  'Building structures',
  'ASSET',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '11100');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1100'),
  '11105',
  'Accumulated Depreciation - Buildings',
  'Accumulated depreciation on buildings',
  'ASSET',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '11105');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1100'),
  '11200',
  'Furniture and Fixtures',
  'Office furniture and fixtures',
  'ASSET',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '11200');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1100'),
  '11205',
  'Accumulated Depreciation - Furniture',
  'Accumulated depreciation on furniture',
  'ASSET',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '11205');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1100'),
  '11300',
  'Equipment',
  'Machinery and equipment',
  'ASSET',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '11300');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1100'),
  '11305',
  'Accumulated Depreciation - Equipment',
  'Accumulated depreciation on equipment',
  'ASSET',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '11305');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1100'),
  '11400',
  'Vehicles',
  'Motor vehicles',
  'ASSET',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '11400');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '1100'),
  '11405',
  'Accumulated Depreciation - Vehicles',
  'Accumulated depreciation on vehicles',
  'ASSET',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '11405');

-- ============================================================================
-- CHART OF ACCOUNTS - LIABILITIES
-- ============================================================================

-- Current Liabilities
INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '2000'),
  '20001',
  'Accounts Payable',
  'Amounts owed to suppliers',
  'LIABILITY',
  'CREDIT',
  true,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '20001');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '2000'),
  '20100',
  'Accrued Expenses',
  'Expenses incurred but not yet paid',
  'LIABILITY',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '20100');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '2000'),
  '20200',
  'Taxes Payable',
  'Tax obligations',
  'LIABILITY',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '20200');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '2000'),
  '20300',
  'Deferred Revenue',
  'Revenue received in advance',
  'LIABILITY',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '20300');

-- Long-term Liabilities
INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '2100'),
  '21000',
  'Long-term Loans',
  'Loans payable beyond one year',
  'LIABILITY',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '21000');

-- ============================================================================
-- CHART OF ACCOUNTS - EQUITY
-- ============================================================================

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_system_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '3000'),
  '30001',
  'Owner''s Capital',
  'Owner''s invested capital',
  'EQUITY',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '30001');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_system_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '3100'),
  '31001',
  'Retained Earnings',
  'Accumulated profits/losses',
  'EQUITY',
  'CREDIT',
  true,
  false
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '31001');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_system_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '3100'),
  '31002',
  'Current Year Earnings',
  'Net income for current period',
  'EQUITY',
  'CREDIT',
  true,
  false
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '31002');

-- ============================================================================
-- CHART OF ACCOUNTS - REVENUE
-- ============================================================================

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '4000'),
  '40001',
  'Rent Revenue',
  'Revenue from property rentals',
  'REVENUE',
  'CREDIT',
  true,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '40001');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '4000'),
  '40002',
  'Service Charge Revenue',
  'Service charge collections',
  'REVENUE',
  'CREDIT',
  true,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '40002');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '4100'),
  '41001',
  'Other Income',
  'Miscellaneous income',
  'REVENUE',
  'CREDIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '41001');

-- ============================================================================
-- CHART OF ACCOUNTS - EXPENSES
-- ============================================================================

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '5100'),
  '51001',
  'Salaries and Wages',
  'Employee compensation',
  'EXPENSE',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '51001');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '5100'),
  '51002',
  'Utilities Expense',
  'Electricity, water, gas expenses',
  'EXPENSE',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '51002');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '5100'),
  '51003',
  'Rent Expense',
  'Office/facility rent',
  'EXPENSE',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '51003');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '5200'),
  '52001',
  'Office Supplies',
  'Office supplies and materials',
  'EXPENSE',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '52001');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '5200'),
  '52002',
  'Professional Fees',
  'Legal, accounting, consulting fees',
  'EXPENSE',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '52002');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '5200'),
  '52003',
  'Depreciation Expense',
  'Depreciation of fixed assets',
  'EXPENSE',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '52003');

INSERT INTO chart_of_accounts (account_class_id, account_code, account_name, description, account_type, normal_balance, is_control_account, allow_manual_entry)
SELECT 
  (SELECT id FROM account_classes WHERE gl_code = '5200'),
  '52004',
  'Bank Charges',
  'Banking fees and charges',
  'EXPENSE',
  'DEBIT',
  false,
  true
WHERE NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE account_code = '52004');

-- ============================================================================
-- CREATE CURRENT FISCAL PERIOD
-- ============================================================================

INSERT INTO fiscal_periods (
  period_name,
  fiscal_year,
  period_number,
  start_date,
  end_date,
  status
)
SELECT 
  to_char(CURRENT_DATE, 'Mon YYYY'),
  EXTRACT(YEAR FROM CURRENT_DATE)::INTEGER,
  EXTRACT(MONTH FROM CURRENT_DATE)::INTEGER,
  date_trunc('month', CURRENT_DATE)::DATE,
  (date_trunc('month', CURRENT_DATE) + interval '1 month - 1 day')::DATE,
  'OPEN'
WHERE NOT EXISTS (
  SELECT 1 FROM fiscal_periods 
  WHERE fiscal_year = EXTRACT(YEAR FROM CURRENT_DATE)::INTEGER
  AND period_number = EXTRACT(MONTH FROM CURRENT_DATE)::INTEGER
);
