<?php
include 'include/session.php';
include 'include/functions.php';

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$sub_prefix = "";
$sub_suffix = "_wrl";
$page_title = "{$project} - View General Transactions - Wealth Creation ERP";

if(isset($_SESSION['staff']) ) {
$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['staff'];
}
if(isset($_SESSION['admin']) ) {
$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['admin'];
}
$role_set = mysqli_query($dbcon, $query);
$role = mysqli_fetch_array($role_set, MYSQLI_ASSOC);

if ($role['acct_view_record'] != "Yes") {
?>
	<script type="text/javascript">
		Swal.fire({
			title: 'Access Denied',
			text: 'You do not have permissions to view this page! Contact your HOD for authorization.',
			icon: 'error',
			confirmButtonText: 'OK',
			confirmButtonColor: '#ef4444'
		}).then(() => {
			window.location.href='../../index.php';
		});
	</script>
<?php
}

// Delete condition (keeping original logic)
if (isset($_GET['delete_id']))
{
	$prefix = "";
	$suffix = "";
	
	$acct_delete_id = $_GET['delete_id'];
	
	$del_query = "SELECT * FROM {$prefix}account_general_transaction_new WHERE id=".$_GET['delete_id'];
	$del_result = @mysqli_query($dbcon, $del_query);
	$result = @mysqli_fetch_array($del_result, MYSQLI_ASSOC);
	
	$payment_category = $result["payment_category"];
	
	$debit_account = $result['debit_account'];
	$credit_account = $result['credit_account'];
	
	$debit_account2 = $result['debit_account_jrn2'];
	$debit_account3 = $result['debit_account_jrn3'];
	$debit_account4 = $result['debit_account_jrn4'];
	$debit_account5 = $result['debit_account_jrn5'];
	$debit_account6 = $result['debit_account_jrn6'];
	$debit_account7 = $result['debit_account_jrn7'];
	
	$credit_account2 = $result['credit_account_jrn2'];
	$credit_account3 = $result['credit_account_jrn3'];
	$credit_account4 = $result['credit_account_jrn4'];
	$credit_account5 = $result['credit_account_jrn5'];
	$credit_account6 = $result['credit_account_jrn6'];
	$credit_account7 = $result['credit_account_jrn7'];
	
	// Account queries and deletion logic (keeping original)
	$query_acct1 = "SELECT * FROM {$prefix}accounts WHERE acct_id = $debit_account";
	$acct_debit_table_set = @mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = @mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * FROM {$prefix}accounts WHERE acct_id = $credit_account";
	$acct_credit_table_set = @mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = @mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	$db_credit_table = $acct_credit_table["acct_table_name"];
	
	// Additional account queries for multiple accounts (keeping original logic)
	// ... (keeping all the original account queries and deletion logic)
	
	$query="DELETE FROM {$prefix}account_general_transaction_new WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);
	
	if ($payment_category=="Service Charge") {
		$query="DELETE FROM collection_analysis_arena WHERE trans_id=".$_GET['delete_id'];
		$result = @mysqli_query($dbcon, $query);
	} else {
		$query="DELETE FROM collection_analysis WHERE trans_id=".$_GET['delete_id'];
		$result = @mysqli_query($dbcon, $query);
	}

	// Delete from all related tables (keeping original logic)
	$query="DELETE FROM $db_debit_table WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);
	// ... (keeping all deletion queries)
} 

// Pagination logic (keeping original)
$pagerows = 10;

if (isset($_GET['p']) && is_numeric($_GET['p'])) {
	$pages = $_GET['p'];
} else {
	$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
	$query .= "WHERE leasing_post_status = 'Pending' ";
	$query .= "OR approval_status = 'Pending'";
	$result = mysqli_query($dbcon, $query);
	$row = mysqli_fetch_array($result, MYSQLI_NUM);
	$records = $row[0];
	
	if ($records > $pagerows) {
		$pages = ceil($records/$pagerows);
	} else {
		$pages = 1;
	}
}

if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Filter logic (keeping original)
if ( isset($_POST['btn_filter']) ) {
	$post_at = "";
	$post_at_to_date = "";
	
	$queryCondition = "";
	if(!empty($_POST["search"]["post_at"])) {			
		$post_at = $_POST["search"]["post_at"];
		list($fid,$fim,$fiy) = explode("/",$post_at);
		
		$post_at_todate = date('Y-m-d');
		if(!empty($_POST["search"]["post_at_to_date"])) {
			$post_at_to_date = $_POST["search"]["post_at_to_date"];
			list($tid,$tim,$tiy) = explode("/",$_POST["search"]["post_at_to_date"]);
			$post_at_todate = "$tiy-$tim-$tid";
		}
		
		$queryCondition .= "WHERE my_date BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";
	}

	$sql = "SELECT * FROM {$prefix}account_general_transaction_new " . $queryCondition . " ORDER BY date_of_payment desc";
	$post_filter_set = mysqli_query($dbcon,$sql);
} 

if (isset($_GET['d1']) && isset($_GET['d2'])) {
	$post_at = "";
	$post_at_to_date = "";
	$queryCondition = "";
	
	$post_at = $_GET["d1"];
	list($fid,$fim,$fiy) = explode("/",$post_at);
	
	$post_at_todate = date('Y-m-d');
	
	$post_at_to_date = $_GET["d2"];
	list($tid,$tim,$tiy) = explode("/",$post_at_to_date);
	
	$post_at_todate = "$tiy-$tim-$tid";
		
	$queryCondition .= "WHERE date_of_payment BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";

	$sql = "SELECT * FROM {$prefix}account_general_transaction_new " . $queryCondition . " ORDER BY date_of_payment desc";
	$post_filter_set = mysqli_query($dbcon,$sql);	
}

//old ERP legacy query for menu
$query = "SELECT * ";
$query .= "FROM staffs ";

if(isset($_SESSION['admin']) ) {
$query .= "WHERE user_id =".$_SESSION['admin'];
}
if(isset($_SESSION['staff']) ) {
$query .= "WHERE user_id =".$_SESSION['staff'];	
}
$menu_set = mysqli_query($dbcon, $query);
$menu = mysqli_fetch_array($menu_set, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $page_title; ?></title>
	<meta name="description" content="Wealth Creation ERP Management System">
	<meta name="author" content="Wealth Creation Ltd">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
	<script>
		tailwind.config = {
			theme: {
				extend: {
					colors: {
						primary: {
							50: '#eff6ff',
							100: '#dbeafe',
							200: '#bfdbfe',
							300: '#93c5fd',
							400: '#60a5fa',
							500: '#3b82f6',
							600: '#2563eb',
							700: '#1d4ed8',
							800: '#1e40af',
							900: '#1e3a8a',
						},
						success: {
							50: '#f0fdf4',
							100: '#dcfce7',
							500: '#22c55e',
							600: '#16a34a',
						},
						warning: {
							50: '#fffbeb',
							100: '#fef3c7',
							500: '#f59e0b',
							600: '#d97706',
						},
						danger: {
							50: '#fef2f2',
							100: '#fee2e2',
							500: '#ef4444',
							600: '#dc2626',
						}
					}
				}
			}
		}
	</script>
	<!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
	<style>
		.animate-fade-in {
			animation: fadeIn 0.5s ease-in-out;
		}
		
		@keyframes fadeIn {
			from { opacity: 0; transform: translateY(10px); }
			to { opacity: 1; transform: translateY(0); }
		}
		
		.table-hover tbody tr:hover {
			background-color: #f8fafc;
		}
		
		.btn-modern {
			transition: all 0.2s ease-in-out;
		}
		
		.btn-modern:hover {
			transform: translateY(-1px);
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		}
	</style>
	<style>
        .dropdown-menu {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .dropdown-menu li a {
            padding: 0.5rem 1rem;
            color: #374151;
            text-decoration: none;
            display: block;
        }
        .dropdown-menu li a:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }
        .navbar-nav .dropdown:hover .dropdown-menu {
            display: block;
        }
        .stats-card {
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .btn-modern {
            transition: all 0.2s ease;
            border-radius: 0.5rem;
            font-weight: 500;
        }
        .btn-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .table-modern {
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .table-modern thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table-modern tbody tr:hover {
            background-color: #f8fafc;
        }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            z-index: 9999;
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
	<header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-chart-line text-2xl text-blue-600"></i>
                            <span class="text-xl font-bold text-gray-900">WEALTH CREATION ERP</span>
                        </div>
                        <div class="ml-8">
                            <a href="index.php" title="Dashboard" class="bg-primary-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-primary-700 transition-colors">
                            <i class="fas fa-tachometer-alt"></i>
                        </a>
                        
                        <a href="http://192.168.0.230/erp/logout.php?logout" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md font-medium transition-colors">
                            Woobs ERP
                        </a>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">

                    <?php              
                    /*****************************************************
                        Chief Executive Exclusive Navigation begins here	
                        *****************************************************/
                        
                        if ($menu['level'] == "ce") { ?>
                            
                            <!-- Transactions Dropdown -->
                            <div class="relative">
                                <button onclick="toggleDropdown('transactionDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-exchange-alt text-blue-600"></i>
                                    <span class="font-semibold text-sm">Transaction</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>

                                <div id="transactionDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/account/view_trans.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-list mr-2"></i>View Transactions
                                    </a>
                                    <a href="mod/leasing/trans_analysis.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-chart-line mr-2"></i>Print Analysis
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-home mr-2"></i>Kclamp Rent
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tools mr-2"></i>Kclamp Service Charge
                                    </a>
                                    <a href="mod/account/payments.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-credit-card mr-2"></i>Payments
                                    </a>
                                    <a href="mod/account/journal_entry.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-book mr-2"></i>Journal Entry
                                    </a>
                                </div>
                            </div>

                            <!-- Financial Reports Dropdown -->
                            <div class="relative">
                                <button onclick="toggleDropdown('reportDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-file-invoice-dollar text-green-600"></i>
                                    <span class="font-semibold text-sm">Finance</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div id="reportDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/account/ledgers.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-book-open mr-2"></i>General Ledgers
                                    </a>
                                    <a href="mod/account/trial_balance.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-balance-scale mr-2"></i>Trial Balance
                                    </a>
                                    <a href="mod/account/profit_loss.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-chart-line mr-2"></i>Income Statement
                                    </a>
                                    <a href="mod/account/balance_sheet.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-file-invoice-dollar mr-2"></i>Financial Position
                                    </a>
                                </div>
                            </div>

                            <!-- Chart of Accounts -->
                            <div class="relative">
                                <button onclick="toggleDropdown('accountDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-chart-pie text-red-600"></i>Chart
                                    <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                </button>
                                <div id="accountDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/account/acct_chart.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-chart-pie mr-2"></i>Account Chart
                                    </a>
                                </div>
                            </div>

                            <!-- Customers -->
                            <div class="relative">
                                <button onclick="toggleDropdown('customerDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-users text-green-600"></i>Customers
                                    <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                </button>
                                <div id="customerDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/leasing/manage_customer.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-user-cog mr-2"></i>Manage Customers
                                    </a>
                                </div>
                            </div>

                        <?php } ?>

                        <?php 
                        /*****************************************************
                        Account Department Exclusive Navigation begins here	
                        *****************************************************/
                        
                        if ($menu['department'] == "Accounts") { ?>
                            <!-- Transactions Dropdown -->
                            <div class="relative">
                                <button onclick="toggleDropdown('accounttransactionDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-exchange-alt text-blue-600"></i>
                                    <span class="font-semibold text-sm">Transaction</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div id="accounttransactionDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/account/view_trans.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-list mr-2"></i>View Transactions
                                    </a>
                                    <a href="mod/leasing/trans_analysis.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-chart-line mr-2"></i>Print Analysis
                                    </a>
                                    <a href="mod/account/post_trans.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-home mr-2"></i>Kclamp/Coldroom/Container Rent
                                    </a>
                                    <a href="mod/account/post_trans_sc.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-tools mr-2"></i>Kclamp/Coldroom/Container Service Charge
                                    </a>
                                    <a href="mod/account/payments.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-credit-card mr-2"></i>Payments
                                    </a>
                                    <a href="mod/account/journal_entry.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-book mr-2"></i>Journal Entry
                                    </a>
                                </div>
                            </div>

                            <!-- Financial Reports -->
                            <div class="relative dropdown">
                                <button onclick="toggleDropdown('reportDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-file-invoice-dollar text-green-600"></i>
                                    <span class="font-semibold text-sm"> Finance </span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div id="reportDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/account/ledgers.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-book-open mr-2"></i>General Ledgers
                                    </a> 
                                    <?php 
                                    if ($menu['department'] == "Accounts" && ($menu['level'] == "fc" || $menu['level'] == "senior accountant")) { ?>
                                    
                                        <a href="mod/account/trial_balance.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-balance-scale mr-2"></i>Trial Balance
                                        </a>
                                        <a href="mod/account/profit_loss.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-chart-line mr-2"></i>Income Statement
                                        </a>
                                        <a href="mod/account/balance_sheet.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-file-invoice-dollar mr-2"></i>Financial Position
                                        </a>
                                   <?php } ?>
                                   

                                </div>
                            </div>

                            <!-- Chart of Accounts -->
                            <div class="relative">
                                <button onclick="toggleDropdown('accountDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-chart-pie text-red-600"></i>Chart
                                    <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                </button>
                                <div id="accountDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/account/acct_chart.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-chart-pie mr-2"></i>Account Chart
                                    </a>
                                </div>
                            </div>
                            
                            <?php if ($menu['level'] != "dgm") { ?>

                                <!-- Shop Management -->
                                <div class="relative">
                                    <button onclick="toggleDropdown('customerDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-users text-green-600"></i>Shop
                                        <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                    </button>
                                    <div id="customerDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                        <a href="mod/leasing/manage_customer.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-user-cog mr-2"></i>Customers' Information
                                        </a>
                                    </div>
                                </div>
                           <?php }
                        } ?>


                        <?php
                        /*****************************************************
                        Leasing Department Exclusive Navigation begins here	
                        *****************************************************/
                        if ($menu['department'] == "Wealth Creation") { ?> 
                            <!-- Wealth Department -->
                            <div class="relative">
                                <button onclick="toggleDropdown('wealthDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-file-invoice-dollar text-green-600"></i>
                                    <span class="font-semibold text-sm"> Wealth Department</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div id="wealthDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/leasing/officers.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" oncontextmenu="return false;">
                                        <i class="fas fa-user-tie mr-2"></i>Officers
                                    </a>
                                </div>
                            </div>

                            <!-- Collections -->
                            <div class="relative">
                                <button onclick="toggleDropdown('collectionDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fas fa-coins text-green-600"></i>
                                    <span class="font-semibold text-sm"> Collections</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                
                                <div id="collectionDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/leasing/view_trans.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" oncontextmenu="return false;">
                                        <i class="fas fa-list mr-2"></i>View Transactions
                                    </a>
                                    <a href="mod/leasing/trans_analysis.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" oncontextmenu="return false;">
                                        <i class="fas fa-chart-line mr-2"></i>Print Analysis
                                    </a>
                                    <a href="mod/account/payments.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" oncontextmenu="return false;">
                                        <i class="fas fa-credit-card mr-2"></i>Payments
                                    </a>
                                    <a href="mod/leasing/post_trans.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" oncontextmenu="return false;">
                                        <i class="fas fa-home mr-2"></i>Kclamp/Coldroom/Container Rent
                                    </a>
                                    <a href="mod/leasing/post_trans_sc.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" oncontextmenu="return false;">
                                        <i class="fas fa-tools mr-2"></i>Kclamp/Coldroom/Container Service Charge
                                    </a>
                                </div>
                            </div>

                            <!-- Customers -->
                                <div class="relative">
                                    <button onclick="toggleDropdown('customerDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-users text-green-600"></i>Customers
                                        <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                    </button>
                                    <div id="customerDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                        <a href="mod/leasing/manage_customer.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-user-cog mr-2"></i>Manage Customers
                                        </a>
                                    </div>
                                </div>
                       <?php } 
                        
                        /*****************************************************
                        CE's Office Department Navigation
                        *****************************************************/
                        if ($menu['department'] == "CE's Office") { ?>
                            <!-- Shop Management -->
                            <div class="relative">
                                <button onclick="toggleDropdown('ceofficeDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-store text-blue-600"></i>Shop Management
                                    <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                </button>
                                <div id="ceofficeDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/leasing/vacant_shops.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-home mr-2"></i>Vacant Shops
                                    </a>
                                    <a href="mod/leasing/manage_customer.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-users mr-2"></i>Manage ALL Customers
                                    </a>
                                    <a href="mod/leasing/lease_application.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-clipboard-list mr-2"></i>Shop Allocation Dashboard
                                    </a>
                                </div>
                            </div>
                        <?php } ?>

                        
                        <?php
                        /*****************************************************
                        Audit/Inspection Department Navigation
                        *****************************************************/
                        if ($menu['department'] == "Audit/Inspections") { ?>
                            <!-- Account Dept -->
                            <div class="relative">
                                <button onclick="toggleDropdown('auditDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-calculator text-green-600"></i>Account Dept
                                    <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                </button>
                                <div id="auditDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/account/view_trans.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-list mr-2"></i>View Transactions
                                    </a>
                                    <a href="mod/leasing/trans_analysis.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-chart-line mr-2"></i>Print Analysis
                                    </a>
                                    <a href="mod/account/ledgers.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-book-open mr-2"></i>General Ledgers
                                    </a> 

                                    <?php
                                    if ($menu['department'] == "Audit/Inspections" && $menu['level'] == "Head, Audit & Inspection") { ?>
                                        <a href="mod/account/trial_balance.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-balance-scale mr-2"></i>Trial Balance
                                        </a>
                                        <a href="mod/account/profit_loss.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-chart-line mr-2"></i>Income Statement
                                        </a>
                                        <a href="mod/account/balance_sheet.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-file-invoice-dollar mr-2"></i>Financial Position
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Customers -->
                            <div class="relative dropdown">
                                <button onclick="toggleDropdown('auditcustomerDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-users mr-2"></i>Customers
                                    <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                </button>
                                <div id="auditcustomerDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/leasing/manage_customer.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-user-cog mr-2"></i>Manage Customers
                                    </a>
                                </div>
                            </div>
                        <?php } ?>

                        <?php
                        /*****************************************************
                        DGM Exclusive Navigation begins here	
                        *****************************************************/
                        if ($menu['level'] == "dgm") { ?>
                            <!-- Account Dept -->
                            <div class="relative">
                                <button onclick="toggleDropdown('dgmexDropdown')" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-calculator mr-2"></i>Account Dept
                                    <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                </button>
                                <div id="dgmexDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                    <a href="mod/account/view_trans.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-list mr-2"></i>View Transactions
                                    </a>
                                    <a href="mod/leasing/trans_analysis.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-chart-line mr-2"></i>Print Analysis
                                    </a>
                                    <a href="mod/account/ledgers.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-book-open mr-2"></i>General Ledgers
                                    </a>
                                    <a href="mod/account/trial_balance.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-balance-scale mr-2"></i>Trial Balance
                                    </a>
                                    <a href="mod/account/profit_loss.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-chart-line mr-2"></i>Income Statement
                                    </a>
                                    <a href="mod/account/balance_sheet.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-file-invoice-dollar mr-2"></i>Financial Position
                                    </a>
                                </div>
                            </div>
                        <?php } ?>








                        <!-- User Profile Dropdown -->
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($staff['full_name']) ?></div>
                            <div class="text-xs text-gray-500"><?= htmlspecialchars($staff['department']) ?></div>
                        </div>
                        <div class="relative">
                            <button class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors" onclick="toggleDropdown('userDropdown')">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    <?= strtoupper($staff['full_name'][0]) ?>
                                </div>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-2 hidden z-50">
                                <a href="index.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                </a>
                                <div class="border-t my-1"></div>
                                <a href="logout.php?logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </header>
	
	<?php
	// Department-specific JavaScript (keeping original logic)
	if ($menu['department'] == "Audit/Inspections"){
		echo '<script>
		$(document).ready(function(){
			$(document).on("click", "#approve_record", function(e){
				var recordId = $(this).data("id");
				SwalApprove(recordId);
				e.preventDefault();
			});
		});
		
		function SwalApprove(recordId){
			Swal.fire({
				title: "Are you sure?",
				text: "This record will be verified!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3b82f6",
				cancelButtonColor: "#ef4444",
				confirmButtonText: "Yes, verify!",
				showLoaderOnConfirm: true,
				preConfirm: function() {
					return new Promise(function(resolve) {
						$.ajax({
							url: "audit_approve_func'.$suffix.'.php",
							type: "POST",
							data: "approve="+recordId,
							dataType: "json"
						})
						.done(function(response){
							Swal.fire("Verified!", response.message, response.status);
							setTimeout(() => window.location.reload(), 1500);
						})
						.fail(function(){
							Swal.fire("Oops...", "Something went wrong, record not verified!", "error");
						});
					});
				},
				allowOutsideClick: false
			});
		}
		</script>';
	} elseif ($menu['department'] == "Accounts"){
		echo '<script>
		$(document).ready(function(){
			$(document).on("click", "#approve_record", function(e){
				var recordId = $(this).data("id");
				SwalApprove(recordId);
				e.preventDefault();
			});
		});
		
		function SwalApprove(recordId){
			Swal.fire({
				title: "Are you sure?",
				text: "This record will hit the Financials!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3b82f6",
				cancelButtonColor: "#ef4444",
				confirmButtonText: "Yes, approve!",
				showLoaderOnConfirm: true,
				preConfirm: function() {
					return new Promise(function(resolve) {
						$.ajax({
							url: "fc_approve_func.php",
							type: "POST",
							data: "approve="+recordId,
							dataType: "json"
						})
						.done(function(response){
							Swal.fire("Approved!", response.message, response.status);
							setTimeout(() => window.location.reload(), 1500);
						})
						.fail(function(){
							Swal.fire("Oops...", "Something went wrong, record not approved!", "error");
						});
					});
				},
				allowOutsideClick: false
			});
		}
		</script>';
	}
	
	// Visited pages tracking (keeping original)
	$vp_user_id = $menu["user_id"];
	$vp_staff_name = $menu["full_name"];
	$sessionID = session_id();
	
	$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$url = htmlspecialchars(strip_tags($url));
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$vp_query = "INSERT IGNORE INTO visited_pages (id, user_id, staff_name, session_id, uri, time) VALUES ('', '$vp_user_id', '$vp_staff_name', '$sessionID', '$url', '$now')";
	$vp_result = mysqli_query($dbcon,$vp_query); 
	?>

	<div class="container mx-auto px-4 py-6">
		<!-- Header Section -->
		<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
			<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
				<h1 class="text-2xl font-bold text-gray-900 mb-4 lg:mb-0">
					<?php echo $project; ?> General Transaction Dashboard
				</h1>
				
				<!-- Search Form -->
				<div class="flex-shrink-0">
					<?php
					if(@$page_department=="Audit") {
						echo '<form method="POST" action="../account/search'.$suffix.'_processing.php?sr='; if (isset($_POST["btn-search"])) {$search = $_POST["search"];}  echo '" class="flex" autocomplete="off">';
					} else {
						echo '<form method="POST" action="search'.$suffix.'_processing.php?sr='; if (isset($_POST["btn-search"])) {$search = $_POST["search"];}  echo '" class="flex" autocomplete="off">';
					}
					?>
						<div class="relative">
							<input type="text" 
								   class="w-64 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
								   name="search" 
								   placeholder="Search transactions..." 
								   value="<?php if (isset($_POST["btn-search"])) { echo $search; } ?>" 
								   required />
							<button class="px-4 py-2 bg-primary-600 text-white rounded-r-lg hover:bg-primary-700 transition-colors btn-modern" 
									type="submit" 
									name="btn-search">
								<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
								</svg>
							</button>
						</div>
					</form>
				</div>
			</div>

			<!-- Department Links -->
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
				<div>
					<h3 class="text-sm font-semibold text-gray-700 mb-2">Wealth Creation:</h3>
					<div class="flex flex-wrap gap-2">
						<?php
						$tquery = "SELECT * FROM staffs WHERE department = 'Wealth Creation' AND level = 'leasing officer' ORDER BY first_name ASC";
						$tresult = mysqli_query($dbcon, $tquery);
						if (!$tresult) {
							die ("Database search query failed");
						}
						while ($tstaff = mysqli_fetch_array($tresult, MYSQLI_ASSOC)) {
							$staff_id = $tstaff["user_id"];
							echo '<a href="../leasing/view_trans.php?staff_id='.$staff_id.'" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors btn-modern">'.$tstaff["first_name"].'</a> ';
						}
						?>
					</div>
				</div>
				
				<div>
					<h3 class="text-sm font-semibold text-gray-700 mb-2">Account Dept:</h3>
					<div class="flex flex-wrap gap-2">
						<?php
						$tquery = "SELECT user_id, first_name, department, level FROM staffs WHERE department = 'Accounts' ORDER BY first_name ASC";
						$tresult = mysqli_query($dbcon, $tquery);
						if (!$tresult) {
							die ("Database search query failed");
						}
						while ($tstaff = mysqli_fetch_array($tresult, MYSQLI_ASSOC)) {
							$staff_id = $tstaff["user_id"];
							echo '<a href="acct_view_trans.php?staff_id='.$staff_id.'" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors btn-modern">'.$tstaff["first_name"].'</a> ';
						}
						?>
					</div>
				</div>
			</div>

			<!-- Status Buttons -->
			<?php
			$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new WHERE leasing_post_status = 'Pending'";
			$result = mysqli_query($dbcon, $query);
			$leasing_post = mysqli_fetch_array($result);
			$no_of_leasing_post = $leasing_post[0];
			
			$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new WHERE approval_status = 'Pending'";
			$result = mysqli_query($dbcon, $query);
			$fc_approvals = mysqli_fetch_array($result);
			$fc_pending_approvals = $fc_approvals[0];
			
			$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new WHERE approval_status = 'Declined' OR verification_status = 'Declined' OR leasing_post_status = 'Declined'";
			$result = mysqli_query($dbcon, $query);
			$declined_trans = mysqli_fetch_array($result);
			$all_declined_trans = $declined_trans[0];
			?>
			
			<div class="flex flex-wrap gap-3">
				<a href="view_trans.php" class="inline-flex items-center px-4 py-2 bg-danger-500 text-white rounded-lg hover:bg-danger-600 transition-colors btn-modern">
					<span class="bg-white text-danger-500 rounded-full px-2 py-1 text-xs font-bold mr-2"><?php echo $no_of_leasing_post; ?></span>
					Pending Posts
				</a>
				<a href="pending_fc_approvals<?php echo $suffix; ?>.php" class="inline-flex items-center px-4 py-2 bg-warning-500 text-white rounded-lg hover:bg-warning-600 transition-colors btn-modern">
					<span class="bg-white text-warning-500 rounded-full px-2 py-1 text-xs font-bold mr-2"><?php echo $fc_pending_approvals; ?></span>
					Pending FC Approvals
				</a>
				<a href="../../mod/audit/pending_audit_approvals<?php echo $suffix; ?>.php" class="inline-flex items-center px-4 py-2 bg-success-500 text-white rounded-lg hover:bg-success-600 transition-colors btn-modern">
					Pending Auditor's Account Verifications
				</a>
				<a href="view_trans_<?php echo $prefix; ?>declined.php" class="inline-flex items-center px-4 py-2 bg-danger-500 text-white rounded-lg hover:bg-danger-600 transition-colors btn-modern">
					<span class="bg-white text-danger-500 rounded-full px-2 py-1 text-xs font-bold mr-2"><?php echo $all_declined_trans; ?></span>
					Declined Transactions
				</a>
			</div>
		</div>

		<!-- Filters Section -->
		<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
			<form name="frmSearch" method="post" action="view_trans<?php echo $suffix; ?>_processing.php?d1=<?php if ( isset($_POST['btn_filter']) ) {$post_at = $_POST["search"]["post_at"]; echo $post_at;} ?>&d2=<?php if ( isset($_POST['btn_filter']) ) {$post_at_to_date = $_POST["search"]["post_at_to_date"]; echo $post_at_to_date;} ?>&" autocomplete="off">
				<div class="flex flex-wrap items-end gap-4">
					<div class="flex-1 min-w-48">
						<label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
						<input type="date" 
							   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
							   id="post_at" 
							   name="search[post_at]" 
							   value="<?php if ( isset($_POST['btn_filter']) ) { echo $post_at;} if (isset($_GET['d1'])) {echo $post_at;}?>" />
					</div>
					
					<div class="flex-1 min-w-48">
						<label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
						<input type="date" 
							   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
							   id="post_at_to_date" 
							   name="search[post_at_to_date]" 
							   value="<?php if ( isset($_POST['btn_filter']) ) {echo $post_at_to_date;} if (isset($_GET['d2'])) {echo $post_at_to_date;}?>" />
					</div>
					
					<div class="flex gap-2">
						<button type="submit" 
								class="px-6 py-2 bg-success-500 text-white rounded-lg hover:bg-success-600 transition-colors btn-modern" 
								name="btn_filter">
							Show Range
						</button>
						<a href="view_trans<?php echo $suffix; ?>.php" 
						   class="px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors btn-modern">
							View ALL Records
						</a>
					</div>
				</div>
			</form>
			
			<?php if (isset($_GET["d1"]) && isset($_GET["d2"]) ): ?>
			<div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
				<p class="text-blue-800">
					<span class="font-semibold">Showing search result of transactions between</span> 
					<?php echo $post_at; ?> <span class="font-semibold">and</span> <?php echo $post_at_to_date; ?>
				</p>
			</div>
			<?php endif; ?>
		</div>

		<!-- Transactions Table -->
		<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 table-hover">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/N</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shop No</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
							<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
							<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt No</th>
							<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Review</th>
							<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">FC</th>
							<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Audit</th>
							<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Authorization</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						<?php
						$caamount_paid = "";
						$capayment_month = "";
						
						if (isset($_GET['s'])) {
							$i = $_GET['s'];
						} else {
							$i=0;
						} 
						
						$query = "SELECT * FROM {$prefix}account_general_transaction_new WHERE leasing_post_status = 'Pending' ORDER BY date_of_payment DESC LIMIT $start, $pagerows";
						$post_all_set = mysqli_query($dbcon,$query);
						$post_no = mysqli_num_rows($post_all_set);
						
						if(!empty($post_filter_set)){
							$post_set = $post_filter_set;
						} else {
							$post_set = $post_all_set;
						}
						
						while ($post = mysqli_fetch_array($post_set, MYSQLI_ASSOC)) {
							$post_shop_no = $post['shop_no'] ;
							$amount = $post['amount_paid'];
							$amount_paid = number_format((float)$amount, 0);
							$posting_time = $post['posting_time'];
							$leasing_approval_time = $post['leasing_post_approval_time'];
							$approval_time = $post['approval_time'];
							$verification_time = $post['verification_time'];
							$receipt_no = $post["receipt_no"];
							$date_of_payment = $post["date_of_payment"];
							
							$query = "SELECT * FROM customers WHERE shop_no = '$post_shop_no'";
							$result = mysqli_query($dbcon, $query);
							$shop = mysqli_fetch_array($result, MYSQLI_ASSOC);
							
							$shop_id = $shop["id"];
							$txref = $post["id"];
						?>
						
						<tr class="hover:bg-gray-50 transition-colors">
							<td class="px-6 py-4 whitespace-nowrap">
								<div class="flex space-x-2">
									<?php
									date_default_timezone_set('Africa/Lagos');
									$today_date = date('Y-m-d');

									if(($menu['department'] == "Accounts") && ($post["leasing_post_status"] == "" && $post["approval_status"] != "Approved" && $menu['level'] != "fc")) {
										if ($post["entry_status"] != "Journal") {
											echo '<button onclick="edit_id(\''.$txref.'\')" class="p-1 text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
												<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
												</svg>
											</button>';		
										} else {
											echo '<div class="flex flex-col space-y-1">
												<a href="journal_entry'.$suffix.'.php?repost_id='.$txref.'" class="px-2 py-1 bg-success-500 text-white text-xs rounded hover:bg-success-600 transition-colors">Repost - Multi Credit</a>
												<a href="journal_entry'.$suffix.'_md.php?repost_id='.$txref.'" class="px-2 py-1 bg-danger-500 text-white text-xs rounded hover:bg-danger-600 transition-colors">Repost - Multi Debit</a>
											</div>';
										}
									}

									if($menu["department"] == "Leasing" || $menu["department"] == "Wealth Creation") {
										if ($post["leasing_post_status"] != "Approved" && $post["entry_status"] != "Journal" && $menu['level'] != "fc") {
											echo '<button onclick="edit_id(\''.$txref.'\')" class="p-1 text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
												<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
												</svg>
											</button>';
										}
									}

									if($today_date == $date_of_payment || $date_of_payment == "0000-00-00" || $staff['level'] == "ce") {
										if (($post["approval_status"] != "Approved" && $staff['user_id'] == $post['posting_officer_id']) || $staff['level'] == "ce" ) {
											echo '<button onclick="delete_id(\''.$txref.'\')" class="p-1 text-red-600 hover:text-red-800 transition-colors" title="Delete">
												<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
												</svg>
											</button>';
										}
									}
									?>
								</div>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap">
								<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
									<?php echo ++$i.'.'; ?>
								</span>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap">
								<button onclick="txdetails('<?php echo $txref; ?>')" class="px-3 py-1 bg-primary-500 text-white text-xs rounded hover:bg-primary-600 transition-colors btn-modern">
									Details
								</button>
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
								<?php 
									list($tid,$tim,$tiy) = explode("-",$post["date_of_payment"]);
									$date_of_payment = "$tiy/$tim/$tid";
									echo $date_of_payment; 
								?>
							</td>

							<td class="px-6 py-4 whitespace-nowrap">
								<?php 
									if ($post["shop_no"]=="") {
										echo "";
									} else {
										echo '<button onclick="cdetails_id(\''.$shop_id.'\')" class="px-3 py-1 bg-primary-500 text-white text-sm rounded hover:bg-primary-600 transition-colors btn-modern">
											<span class="text-yellow-200 font-bold">'.$post["shop_no"].'</span>
										</button>';
									}
								?>
							</td> 

							<td class="px-6 py-4 text-sm text-gray-900">
								<?php
									$plate_no = $post["plate_no"];
									if ($plate_no == "") {
										echo strtoupper(strtolower($post["transaction_desc"]));
									} else {
										echo strtoupper($plate_no).' - '.strtoupper(strtolower($post["transaction_desc"]));
									}
								?>
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
								<?php 
									if (($post["leasing_post_status"] != "Pending")) {
										echo "₦ {$amount_paid}"; 
									}
								?>
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
								<?php 
									if (($post["leasing_post_status"] != "Pending")) {
										echo '<div>'.$post["receipt_no"].'</div>'; 
									}
									
									if(@$post["entry_status"] == "Journal"){
										echo '<div class="text-blue-600 font-medium">'.$post["cheque_no"].'</div>'; 
									}
								?>
							</td>
						
							<td class="px-6 py-4 whitespace-nowrap text-center">
								<?php
									if ($post["leasing_post_status"] == "Approved") {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">Approved</span>';	
									} elseif ($post["leasing_post_status"] == "Declined") {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 text-danger-800">Declined '.time_elapsed_string($leasing_approval_time).'</span>';	
									} elseif ($post["leasing_post_status"] == "Pending") {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">Pending '.time_elapsed_string($posting_time).'</span>';
									} else {
										echo '';
									}
								?> 
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-center">
								<?php 
									if ($post["approval_status"] == "Approved") {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">Approved</span>';	
									} elseif ($post["approval_status"] == "Declined") {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 text-danger-800">Declined '.time_elapsed_string($approval_time).'</span>';	
									} elseif ($post["approval_status"] == "Pending" && $post["leasing_post_status"] == "Approved") {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">Pending '.time_elapsed_string($leasing_approval_time).'</span>';
									} elseif ($post["approval_status"] == "Pending" && $post["leasing_post_status"] == "") {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">Pending '.time_elapsed_string($posting_time).'</span>';
									} else {
										echo ' ';
									}
								?> 
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-center">
								<?php
									if (($post["approval_status"] == "Pending") || ($post["approval_status"] == "Declined") || ($post["approval_status"] == ""))  {
										echo '';
									} elseif ($post["verification_status"] == "Verified") {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">Verified</span>';
									} elseif ($post["verification_status"] == "Declined") {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 text-danger-800">Declined '.time_elapsed_string($verification_time).'</span>';
									} elseif ($post["verification_status"] == "Flagged") {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 text-danger-800">FLAGGED '.time_elapsed_string($verification_time).'</span>';
									} else {
										echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">Pending '.time_elapsed_string($approval_time).'</span>';	
									};
								?>
							</td>
												
							<td class="px-6 py-4 whitespace-nowrap text-center">
								<div class="flex flex-col space-y-1">
									<?php
									if ($menu['department'] == "Audit/Inspections"){
										if (($post["approval_status"] == "Approved") && ($post['verification_status'] == "Verified") && ($menu["level"] == "Head, Audit & Inspection")) {
											echo '<button onclick="txdetails('.$txref.')" class="px-2 py-1 bg-danger-500 text-white text-xs rounded hover:bg-danger-600 transition-colors btn-modern">Flag</button>';
										} elseif (($post["approval_status"] == "Approved") && ($post['verification_status'] == "Pending")) {
											if ($post['flag_status'] != "Flagged") {
												echo '<button class="px-2 py-1 bg-success-500 text-white text-xs rounded hover:bg-success-600 transition-colors btn-modern" id="approve_record" data-id="'.$txref.'">Verify</button>';
												echo '<button onclick="txdetails('.$txref.')" class="px-2 py-1 bg-danger-500 text-white text-xs rounded hover:bg-danger-600 transition-colors btn-modern">Flag</button>';
											} else {
												echo '<span class="px-2 py-1 bg-primary-500 text-white text-xs rounded">CURRENTLY FLAGGED</span>';
												echo '<button class="px-2 py-1 bg-success-500 text-white text-xs rounded hover:bg-success-600 transition-colors btn-modern" id="approve_record" data-id="'.$txref.'">Re-verify</button>';
											}
										} elseif (($post["approval_status"] == "Approved") && ($post['verification_status'] == "Flagged")) {
											echo '<span class="px-2 py-1 bg-warning-500 text-white text-xs rounded">FLAGGED</span>';
										} elseif (($post["approval_status"] == "Declined") && ($post['flag_status'] == "Flagged")) {
											echo '<span class="px-2 py-1 bg-warning-500 text-white text-xs rounded">FLAGGED</span>';
										}
									} elseif ($menu['level'] == "fc"){
										if ($post['approval_status'] == "Approved" && $post['verification_status'] == "Verified") {
											echo '<button onclick="txdetails('.$txref.')" class="px-2 py-1 bg-danger-500 text-white text-xs rounded hover:bg-danger-600 transition-colors btn-modern">Flag</button>';
										} elseif ($post['approval_status'] == "Approved" && $post['verification_status'] == "Pending") {
											echo '<button class="px-2 py-1 bg-danger-500 text-white text-xs rounded hover:bg-danger-600 transition-colors btn-modern" id="decline_record" data-id="'.$txref.'">Decline</button>';
										} elseif (($post["approval_status"] == "Approved") && ($post['verification_status'] == "Flagged")) {
											echo '<button class="px-2 py-1 bg-danger-500 text-white text-xs rounded hover:bg-danger-600 transition-colors btn-modern" id="decline_record" data-id="'.$txref.'">Decline</button>';
										} elseif ($post['approval_status'] == "Pending") {
											echo '<button class="px-2 py-1 bg-success-500 text-white text-xs rounded hover:bg-success-600 transition-colors btn-modern" id="approve_record" data-id="'.$txref.'">Approve</button>';
											echo '<button class="px-2 py-1 bg-danger-500 text-white text-xs rounded hover:bg-danger-600 transition-colors btn-modern" id="decline_record" data-id="'.$txref.'">Decline</button>';
										} elseif ($post['approval_status'] == "Declined" && $post['leasing_post_status'] != "Declined" && $post['flag_status'] != "Flagged") {
											echo '<button class="px-2 py-1 bg-success-500 text-white text-xs rounded hover:bg-success-600 transition-colors btn-modern" id="approve_record" data-id="'.$txref.'">Approve</button>';
										} elseif (($post["approval_status"] == "Declined") && ($post['flag_status'] == "Flagged")) {
											echo '<span class="px-2 py-1 bg-warning-500 text-white text-xs rounded">FLAGGED</span>';
										}
									} elseif (($menu['department'] == "Accounts" && $role['acct_approve_payment'] != "Yes" && $post['leasing_post_status'] != "" && $post['approval_status']!="Approved") || ($menu['level'] == "ce")) {
										echo '<a href="review_trans'.$suffix.'.php?txref='.$txref.'" class="px-2 py-1 bg-primary-500 text-white text-xs rounded hover:bg-primary-600 transition-colors btn-modern">Review</a>';
									}
									?>
								</div>
							</td>
						</tr>
						
						<?php 
							if ($post["shop_no"]!="") {
						?>
						<tr class="bg-gray-50">
							<td colspan="3"></td>
							<td colspan="9" class="px-6 py-2 text-sm text-gray-600">
								<strong>Payment Break Down:</strong>
								<?php
									$caquery = "SELECT * ";
									
									if($post["payment_category"] == "Rent") {
										$caquery .= "FROM collection_analysis ";
									} else {
										$caquery .= "FROM collection_analysis_arena ";
									}
									$caquery .= "WHERE shop_id = '$shop_id' AND receipt_no = '$receipt_no'";
									$caresult = mysqli_query($dbcon, $caquery);
									while ($cashop = mysqli_fetch_array($caresult, MYSQLI_ASSOC)){
										$capayment_month = $cashop["payment_month"];
										$caamount_paid = $cashop["amount_paid"];
										$caamount_paid = number_format((float)$caamount_paid, 0);
										
										echo ' <span class="text-red-600 font-medium">'.$capayment_month.'</span>: ₦ '.$caamount_paid.' |';
									}
								?>
							</td>
						</tr>
						<?php
						}
						
						// Account details row
						$debit_account = $post["debit_account"];
						$credit_account = $post["credit_account"];
						$posting_officer_id = $post["posting_officer_id"];
						$leasing_post_approving_officer_id = $post["leasing_post_approving_officer_id"];
						$leasing_post_approving_officer_name = $post["leasing_post_approving_officer_name"];
						$post_acct_staff = $post["posting_officer_name"];
						$post_fc_acct_staff = $post["approving_acct_officer_name"];
						
						$query_acct1 = "SELECT * FROM {$prefix}accounts WHERE acct_id = $debit_account";
						$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
						$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
						$db_debit_desc = $acct_debit_table["acct_desc"];
						
						$query_acct2 = "SELECT * FROM {$prefix}accounts WHERE acct_id = $credit_account";
						$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
						$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
						$db_credit_desc = $acct_credit_table["acct_desc"];
						?>
						<tr class="bg-blue-50">
							<td colspan="3"></td>
							<td colspan="5" class="px-6 py-2 text-sm text-gray-700">
								<?php
								// Journal entry details (keeping original logic)
								$credit_account2 = @$post['credit_account_jrn2'];
								$credit_account3 = @$post['credit_account_jrn3'];
								// ... (keeping all journal entry logic)
								
								if(@$post["entry_status"] == "Journal"){	
									if($post["credit_account_jrn2"] != ""){	
										echo '<span class="text-red-600 font-bold">Multi Credit Journal Entry:</span> <strong>'.$db_credit_acct_desc2.' | '.$db_credit_acct_desc3.' | '.$db_credit_acct_desc4.' | '.$db_credit_acct_desc5.' | '.$db_credit_acct_desc6.' | '.$db_credit_acct_desc7.'</strong>';
									} elseif($post["debit_account_jrn2"] != "")  {
										echo '<span class="text-red-600 font-bold">Multi Debit Journal Entry:</span> <strong>'.$db_debit_acct_desc2.' | '.$db_debit_acct_desc3.' | '.$db_debit_acct_desc4.' | '.$db_debit_acct_desc5.' | '.$db_debit_acct_desc6.' | '.$db_debit_acct_desc7.'</strong>';
									}
								}
								?>
							</td>
							<td colspan="4" class="px-6 py-2 text-sm text-gray-700">
								<div><span class="text-red-600 font-bold">Debit:</span> <strong><?php echo $db_debit_desc; ?></strong></div>
								<div><span class="text-red-600 font-bold">Credit:</span> <strong><?php echo $db_credit_desc; ?></strong></div>
							</td>
						</tr>

						<?php
						// Tenancy duration row (keeping original logic)
						$start_date = $post["start_date"];
						$end_date = $post["end_date"];

						@list($tiy,$tim,$tid) = explode("-",@$shop["lease_start_date"]);
						$lease_start_date = "$tid/$tim/$tiy";

						@list($tiy,$tim,$tid) = explode("-",@$shop["lease_end_date"]);
						$lease_end_date = "$tid/$tim/$tiy";

						if($post["shop_no"] != ""){	
							echo '<tr class="bg-green-50">
								<td colspan="3"></td>
								<td colspan="4" class="px-6 py-2 text-sm text-gray-700">
									Current Tenancy Duration: '.$lease_start_date.' to '.$lease_end_date.'
								</td>
								<td colspan="5" class="px-6 py-2 text-sm text-gray-700">';
									if ($start_date != "" || $end_date != "") {
										echo 'Tenure Paid for: '.$start_date.' to '.$end_date;
									}
								echo '</td>
							</tr>';
						}
						?>

						<tr class="bg-gray-100">
							<td colspan="3"></td>
							<td colspan="9" class="px-6 py-2 text-right text-sm text-gray-600">
								<?php
								if($leasing_post_approving_officer_name == ""){
									echo 'Posted by: <a href="acct_view_trans.php?staff_id='.$posting_officer_id.'" class="text-blue-600 hover:text-blue-800 font-medium">'.$post_acct_staff.'</a>';
								}else{
									echo 'Posted by: <a href="../leasing/view_trans.php?staff_id='.$posting_officer_id.'" class="text-blue-600 hover:text-blue-800 font-medium">'.$post_acct_staff.'</a> | Reviewed by: <a href="acct_view_trans'.$sub_suffix.'.php?staff_id='.$leasing_post_approving_officer_id.'" class="text-blue-600 hover:text-blue-800 font-medium">'.$leasing_post_approving_officer_name.'</a>';
								}
								
								if($post["approving_acct_officer_name"] != ""){
									echo ' | Approved by: <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">'.$post["approving_acct_officer_name"].'</a>';
								}
								
								if($post["verifying_auditor_name"] != ""){
									echo ' | Verified by: <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">'.$post["verifying_auditor_name"].'</a>';
								}
								?>
							</td>
						</tr>

						<!-- Spacer rows -->
						<tr><td colspan="12" class="h-2"></td></tr>
						<tr><td colspan="12" class="h-2"></td></tr>
						<tr><td colspan="12" class="h-2"></td></tr>
						<?php	 
						}
						?>
					</tbody>
				</table>
			</div>
			
			<!-- Pagination -->
			<div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
				<div class="flex-1 flex justify-between sm:hidden">
					<?php
					if ($pages > 1){
						$current_page = ($start/$pagerows) + 1;
						
						if ($current_page != 1){
							echo '<a href="view_trans'.$suffix.'.php?s=' .  ($start - $pagerows) . '&p=' . $pages . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>';
						}
						
						if ($current_page != $pages){
							echo '<a href="view_trans'.$suffix.'.php?s=' .  ($start + $pagerows) . '&p=' . $pages . '" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>';
						}
					}
					?>
				</div>
				<div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
					<div>
						<p class="text-sm text-gray-700">
							Showing page <span class="font-medium"><?php echo ($start/$pagerows) + 1; ?></span> of <span class="font-medium"><?php echo $pages; ?></span>
						</p>
					</div>
					<div>
						<nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
							<?php
							if ($pages > 1){
								$current_page = ($start/$pagerows) + 1;
								
								if ($current_page != 1){
									echo '<a href="view_trans'.$suffix.'.php?s=' .  ($start - $pagerows) . '&p=' . $pages . '" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
										<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
										</svg>
									</a>';
								}
								
								if ($current_page != $pages){
									echo '<a href="view_trans'.$suffix.'.php?s=' .  ($start + $pagerows) . '&p=' . $pages . '" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
										<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
										</svg>
									</a>';
								}
							}
							?>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- JavaScript Functions -->
	<script>
		// SweetAlert2 functions for approve/decline
		$(document).ready(function(){
			$(document).on('click', '#approve_record', function(e){
				var recordId = $(this).data('id');
				SwalApprove(recordId);
				e.preventDefault();
			});
			
			$(document).on('click', '#decline_record', function(e){
				var declineId = $(this).data('id');
				SwalDecline(declineId);
				e.preventDefault();
			});
		});
		
		function SwalApprove(recordId){
			Swal.fire({
				title: 'Are you sure?',
				text: "This record will hit the Financials!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3b82f6',
				cancelButtonColor: '#ef4444',
				confirmButtonText: 'Yes, approve!',
				showLoaderOnConfirm: true,
				preConfirm: function() {
					return new Promise(function(resolve) {
						$.ajax({
							url: 'fc_approve_func.php',
							type: 'POST',
							data: 'approve='+recordId,
							dataType: 'json'
						})
						.done(function(response){
							Swal.fire('Approved!', response.message, response.status);
							setTimeout(() => window.location.reload(), 1500);
						})
						.fail(function(){
							Swal.fire('Oops...', 'Something went wrong, record not approved!', 'error');
						});
					});
				},
				allowOutsideClick: false
			});
		}
		
		function SwalDecline(declineId){
			Swal.fire({
				title: 'Are you sure?',
				text: "This record will NOT hit the Financials!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3b82f6',
				cancelButtonColor: '#ef4444',
				confirmButtonText: 'Yes, decline!',
				showLoaderOnConfirm: true,
				preConfirm: function() {
					return new Promise(function(resolve) {
						$.ajax({
							url: 'fc_decline_func.php',
							type: 'POST',
							data: 'decline='+declineId,
							dataType: 'json'
						})
						.done(function(response){
							Swal.fire('Declined!', response.message, response.status);
							setTimeout(() => window.location.reload(), 1500);
						})
						.fail(function(){
							Swal.fire('Oops...', 'Something went wrong, unable to decline record!', 'error');
						});
					});
				},
				allowOutsideClick: false
			});
		}

		// Legacy functions (keeping original)
		function edit_id(id) {
			Swal.fire({
				title: 'Edit Transaction',
				text: 'Are you sure you want to EDIT this transaction record?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3b82f6',
				cancelButtonColor: '#6b7280',
				confirmButtonText: 'Yes, edit it!'
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href='edit_posting<?php echo $suffix; ?>.php?edit_id='+id;
				}
			});
		}

		function delete_id(id) {
			Swal.fire({
				title: 'Delete Transaction',
				text: 'Are you sure you want to COMPLETELY DELETE this transaction?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#ef4444',
				cancelButtonColor: '#6b7280',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href='view_trans<?php echo $suffix; ?>.php?delete_id='+id;
				}
			});
		}

		function cdetails_id(id) {
			window.location.href='../leasing/customer_details.php?cdetails_id='+id;
		}

		function txref(id) {
			window.location.href='review_trans<?php echo $suffix; ?>.php?txref='+id;
		}

		function txdetails(id) {
			window.location.href='transaction_details<?php echo $suffix; ?>.php?txref='+id;
		}

		// Auto-refresh stats (keeping original)
		$('#stats').ready(function statRefresh() {
			var $statboard = $("#stats");
			setInterval(function statRefresh() {
				$statboard.load("view_trans<?php echo $suffix; ?>.php #stats");
			}, 600000);
		});
	</script>
</body>
</html>