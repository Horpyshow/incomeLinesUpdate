<?php
require_once 'TransactionManager.php';
require_once 'BudgetManager.php';
require_once 'config.php';

// Start session
session_start();

// Mock session data for demonstration
$staff = [
    'user_id' => 1,
    'full_name' => 'John Doe',
    'department' => 'Accounts'
];

class DashboardAnalyzer {
    private $db;
    private $transaction_manager;
    private $budget_manager;
    
    public function __construct() {
        $this->db = new Database();
        $this->transaction_manager = new TransactionManager();
        $this->budget_manager = new BudgetManager();
    }
    
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats() {
        $stats = $this->transaction_manager->getDashboardStats();
        
        // Add budget-related stats
        $current_year = date('Y');
        $current_month = date('n');
        
        // Get current month budget performance
        $month_field = strtolower(date('F')) . '_budget';
        
        $this->db->query("
            SELECT SUM({$month_field}) as monthly_budget
            FROM budget_lines 
            WHERE budget_year = :year 
            AND status = 'Active'
        ");
        $this->db->bind(':year', $current_year);
        $budget_result = $this->db->single();
        
        $this->db->query("
            SELECT COALESCE(SUM(amount_paid), 0) as monthly_achieved
            FROM account_general_transaction_new 
            WHERE MONTH(date_of_payment) = :month 
            AND YEAR(date_of_payment) = :year
            AND (approval_status = 'Approved' OR approval_status = '')
        ");
        $this->db->bind(':month', $current_month);
        $this->db->bind(':year', $current_year);
        $achieved_result = $this->db->single();
        
        $monthly_budget = $budget_result['monthly_budget'] ?? 0;
        $monthly_achieved = $achieved_result['monthly_achieved'] ?? 0;
        $monthly_performance = $monthly_budget > 0 ? ($monthly_achieved / $monthly_budget) * 100 : 0;
        
        $stats['monthly_budget'] = $monthly_budget;
        $stats['monthly_achieved'] = $monthly_achieved;
        $stats['monthly_performance'] = $monthly_performance;
        
        return $stats;
    }
    
    /**
     * Get recent transactions
     */
    public function getRecentTransactions($limit = 10) {
        return $this->transaction_manager->getTransactions(1, $limit);
    }
    
    /**
     * Get top income lines for current month
     */
    public function getTopIncomeLines($limit = 5) {
        $current_month = date('n');
        $current_year = date('Y');
        
        $this->db->query("
            SELECT 
                a.acct_desc as income_line,
                COALESCE(SUM(t.amount_paid), 0) as total_amount,
                COUNT(t.id) as transaction_count
            FROM accounts a
            LEFT JOIN account_general_transaction_new t ON a.acct_id = t.credit_account
                AND MONTH(t.date_of_payment) = :month 
                AND YEAR(t.date_of_payment) = :year
                AND (t.approval_status = 'Approved' OR t.approval_status = '')
            WHERE a.active = 'Yes'
            GROUP BY a.acct_id, a.acct_desc
            ORDER BY total_amount DESC
            LIMIT :limit
        ");
        
        $this->db->bind(':month', $current_month);
        $this->db->bind(':year', $current_year);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
}

$dashboard = new DashboardAnalyzer();

// Check access permissions
$can_view = $dashboard->budget_manager->checkAccess($staff['user_id'], 'can_view_budget');

if (!$can_view) {
    header('Location: index.php?error=access_denied');
    exit;
}

$stats = $dashboard->getDashboardStats();
$recent_transactions = $dashboard->getRecentTransactions();
$top_income_lines = $dashboard->getTopIncomeLines();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900"><?php echo APP_NAME; ?> - Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">Welcome, <?php echo $staff['full_name']; ?></span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                        <?php echo $staff['department']; ?>
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Posts</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['pending_posts']); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending FC Approvals</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['pending_fc_approvals']); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-search text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Audit</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['pending_audit_verifications']); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Declined</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['declined_transactions']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Performance Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Current Month Budget Performance</h3>
                <a href="annual_budget_performance.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-chart-line mr-1"></i>View Annual Summary
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">₦<?php echo number_format($stats['monthly_budget']); ?></div>
                    <div class="text-sm text-gray-500">Monthly Budget</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">₦<?php echo number_format($stats['monthly_achieved']); ?></div>
                    <div class="text-sm text-gray-500">Achieved</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold <?php echo $stats['monthly_performance'] >= 100 ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo number_format($stats['monthly_performance'], 1); ?>%
                    </div>
                    <div class="text-sm text-gray-500">Performance</div>
                </div>
            </div>
            
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="<?php echo $stats['monthly_performance'] >= 100 ? 'bg-green-500' : ($stats['monthly_performance'] >= 80 ? 'bg-yellow-500' : 'bg-red-500'); ?> h-3 rounded-full transition-all duration-500" 
                         style="width: <?php echo min(100, $stats['monthly_performance']); ?>%"></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Management Tools -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Management Tools</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="budget_management.php" 
                       class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Budget Management
                    </a>
                    
                    <a href="annual_budget_performance.php" 
                       class="flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Annual Performance
                    </a>
                    
                    <a href="officer_target_management.php" 
                       class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-bullseye mr-2"></i>
                        Officer Targets
                    </a>
                    
                    <a href="view_transactions.php" 
                       class="flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        View Transactions
                    </a>
                </div>
            </div>

            <!-- Top Income Lines -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Income Lines (Current Month)</h3>
                <div class="space-y-3">
                    <?php foreach ($top_income_lines as $index => $line): ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                <?php echo $index + 1; ?>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900"><?php echo $line['income_line']; ?></p>
                                <p class="text-xs text-gray-500"><?php echo $line['transaction_count']; ?> transactions</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">₦<?php echo number_format($line['total_amount']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                    <a href="view_transactions.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All →
                    </a>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted By</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($recent_transactions as $transaction): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo date('d/m/Y', strtotime($transaction['date_of_payment'])); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <a href="transaction_details.php?txref=<?php echo $transaction['id']; ?>" 
                                   class="text-blue-600 hover:text-blue-800 hover:underline">
                                    <?php echo ucwords(strtolower($transaction['transaction_desc'])); ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-bold">
                                ₦<?php echo number_format($transaction['amount_paid']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo $transaction['posting_officer_full_name']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?php echo $transaction['approval_status'] === 'Approved' ? 'bg-green-100 text-green-800' : 
                                              ($transaction['approval_status'] === 'Declined' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                    <?php echo $transaction['approval_status'] ?: 'Pending'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Add smooth animation to performance bar
        document.addEventListener('DOMContentLoaded', function() {
            const performanceBar = document.querySelector('[style*="width:"]');
            if (performanceBar) {
                const width = performanceBar.style.width;
                performanceBar.style.width = '0%';
                setTimeout(() => {
                    performanceBar.style.width = width;
                }, 100);
            }
        });
    </script>
</body>
</html>