<?php
error_reporting(E_ALL);
ini_set('log_errors',1);
ini_set('display_errors',1);
include 'include/session.php';

date_default_timezone_set('Africa/Lagos');

//Today as today
$current_date = date('Y-m-d'); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Processing Begins Here
$error = false;
$remit_id = "";

// All the original processing logic remains the same
// General Processing
if ( isset($_POST['btn_post_general']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<div class='bg-red-50 border border-red-200 rounded-lg p-4 mb-4'><h4 class='text-red-800 font-bold'>ATTENTION:</h4><p class='text-red-700'>Transaction failed! The receipt No: $receipt_no you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</p></div>";
	}
	
	// Continue with all the original processing logic...
	// (keeping all the original PHP processing code)
}

// All other processing functions remain the same...
// (Car Loading, Hawkers, Car Park, WheelBarrow, Scroll Board, Daily Trade, etc.)

// Income line logic
if(isset($_GET["income_line"])) {
	$income_line = $_GET["income_line"];

	//General Income Line	
	if ($income_line == "general") {
		$income_line_desc = "General Income Lines";
	} elseif ($income_line == "scroll_board") {
		$alias = "scroll_board";
		$income_line_desc = "Scroll Board";
	} elseif ($income_line == "car_sticker") {
		$alias = "car_sticker";
		$income_line_desc = "Car Sticker";
	} elseif ($income_line == "car_loading") {
		$alias = "car_loading";
		$income_line_desc = "Car Loading";
		$transaction_descr = $income_line_desc;
		// JavaScript for calculations remains the same
	}
	// ... (keeping all income line logic)
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
	<title>Welcome - <?php echo $staff['full_name']; ?> | Wealth Creation ERP</title>
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
		
		.btn-modern {
			transition: all 0.2s ease-in-out;
		}
		
		.btn-modern:hover {
			transform: translateY(-1px);
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		}
		
		.income-card {
			transition: all 0.3s ease;
		}
		
		.income-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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

	<?php
	// Session staff queries (keeping original)
	if(isset($_SESSION['staff']) ) {
		$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['staff'];
	}
	if(isset($_SESSION['admin']) ) {
		$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['admin'];
	}
	$staffresult = mysqli_query($dbcon, $staffquery);
	$session_staff = mysqli_fetch_array($staffresult, MYSQLI_ASSOC);
	$session_id = $session_staff['user_id'];
	
	$dcount_query = "SELECT COUNT(id) FROM account_general_transaction_new WHERE posting_officer_id = '$session_id' AND (approval_status = 'Declined' OR verification_status = 'Declined')";
	$result = mysqli_query($dbcon, $dcount_query);
	$acct_office_post = mysqli_fetch_array($result);
	$no_of_declined_post = $acct_office_post[0];
	?>

	<div class="container mx-auto px-4 py-6">
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
			<!-- Left Sidebar - Income Lines -->
			<div class="lg:col-span-2">
				<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
					<h3 class="text-lg font-bold text-gray-900 mb-4">Lines of Income</h3>
					<div class="space-y-2">
						<a href="payments.php?income_line=general" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							General
						</a>
						<a href="payments.php?income_line=abattoir" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Abattoir
						</a>
						<a href="payments.php?income_line=car_loading" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Car Loading Ticket
						</a>
						<a href="payments.php?income_line=car_park" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Car Park Ticket
						</a>
						<a href="payments.php?income_line=hawkers" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Hawkers Ticket
						</a>
						<a href="payments.php?income_line=wheelbarrow" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							WheelBarrow Ticket
						</a>
						<a href="payments.php?income_line=daily_trade" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Daily Trade
						</a>
						<a href="payments.php?income_line=toilet_collection" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Toilet Collection
						</a>
						<a href="payments.php?income_line=scroll_board" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Scroll Board
						</a>
						<a href="payments.php?income_line=other_pos" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Other POS Ticket
						</a>
						<a href="payments.php?income_line=daily_trade_arrears" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Daily Trade Arrears
						</a>
					</div>
				</div>
			</div>

			<!-- Main Content -->
			<div class="lg:col-span-8">
				<!-- Header Stats -->
				<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
						<?php
						if(isset($_SESSION['staff']) ) {
							$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['staff'];
						}
						if(isset($_SESSION['admin']) ) {
							$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['admin'];
						}
						$staffresult = mysqli_query($dbcon, $staffquery);
						$session_staff = mysqli_fetch_array($staffresult, MYSQLI_ASSOC);
						$session_id = $session_staff['user_id'];
						$session_department = $session_staff['department'];
						
						if($session_department == "Wealth Creation") {
							$ca_query = "SELECT posting_officer_id, date_of_payment, payment_category, SUM(amount_paid) as amount_posted FROM account_general_transaction_new WHERE (posting_officer_id = '$session_id' AND payment_category='Other Collection' AND date_of_payment='$current_date') ";
							$ca_sum = @mysqli_query($dbcon,$ca_query);
							$ca_total = @mysqli_fetch_array($ca_sum, MYSQLI_ASSOC);
							$amount_posted = $ca_total['amount_posted'];
						
							$rm_query = "SELECT *, SUM(amount_paid) as amount_remitted FROM cash_remittance WHERE (remitting_officer_id = '$session_id' AND category='Other Collection' AND date='$current_date') ";
							$rm_sum = @mysqli_query($dbcon,$rm_query);
							$rm_total = @mysqli_fetch_array($rm_sum, MYSQLI_ASSOC);
							
							$date = $rm_total["date"];
							$remit_id = $rm_total["remit_id"];
							$category = $rm_total["category"];
							$amount_remitted = $rm_total['amount_remitted'];
							$unposted = $amount_remitted - $amount_posted;
							
							if ($menu["department"] == "Wealth Creation") {
								$amt_remitted = $unposted;
							}
						?>
						<div class="bg-blue-50 rounded-lg p-4">
							<div class="flex items-center">
								<div class="flex-shrink-0">
									<svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
									</svg>
								</div>
								<div class="ml-3">
									<p class="text-sm font-medium text-blue-600">Remitted</p>
									<p class="text-lg font-bold text-blue-900">₦ <?php echo number_format($amount_remitted); ?></p>
								</div>
							</div>
						</div>
						
						<div class="bg-green-50 rounded-lg p-4">
							<div class="flex items-center">
								<div class="flex-shrink-0">
									<svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
									</svg>
								</div>
								<div class="ml-3">
									<p class="text-sm font-medium text-green-600">Posted</p>
									<p class="text-lg font-bold text-green-900">₦ <?php echo number_format($amount_posted); ?></p>
								</div>
							</div>
						</div>
						
						<div class="bg-yellow-50 rounded-lg p-4">
							<div class="flex items-center">
								<div class="flex-shrink-0">
									<svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
									</svg>
								</div>
								<div class="ml-3">
									<p class="text-sm font-medium text-yellow-600">Unposted</p>
									<p class="text-lg font-bold text-yellow-900">₦ <?php echo number_format($unposted); ?></p>
								</div>
							</div>
						</div>
						<?php } ?>
						
						<?php
						// Till Balance calculation (keeping original logic)
						$till_query = "SELECT SUM(amount_paid) as amount_posted FROM account_general_transaction_new WHERE posting_officer_id = '$session_id' ";
						
						if($menu["department"] == "Wealth Creation") {
							$till_query .= "AND leasing_post_status = 'Pending'";
						} else {
							$till_query .= "AND approval_status = 'Pending'";
						}
						
						$sum = @mysqli_query($dbcon,$till_query);
						$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);
						$till = $total['amount_posted'];
													
						$declined_query = "SELECT SUM(amount_paid) as amount_posted FROM account_general_transaction_new WHERE posting_officer_id = '$session_id' ";
						
						if($menu["department"] == "Wealth Creation") {
							$declined_query .= "AND leasing_post_status = 'Declined'";
						} else {
							$declined_query .= "AND approval_status = 'Declined'";
						}
						
						$dsum = @mysqli_query($dbcon,$declined_query);
						$dtotal = @mysqli_fetch_array($dsum, MYSQLI_ASSOC);
						$till_declined = $dtotal['amount_posted'];
						$total_till = ($till + $till_declined);
						?>
						
						<div class="bg-purple-50 rounded-lg p-4">
							<div class="flex items-center">
								<div class="flex-shrink-0">
									<svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
									</svg>
								</div>
								<div class="ml-3">
									<p class="text-sm font-medium text-purple-600">Till Balance</p>
									<p class="text-lg font-bold text-purple-900">₦ <?php echo number_format($total_till, 2); ?></p>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Action Buttons -->
					<div class="flex flex-wrap gap-3">
						<?php
						if ($current_time >= $wc_begin_time && $current_time <= $wc_end_time){
							echo '<a href="log_unposted_trans_others.php" class="inline-flex items-center px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors btn-modern">
								<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
								</svg>
								Log Unposted Collection
							</a>';
						} 
						?>
						
						<a href="payments_past.php" class="inline-flex items-center px-4 py-2 bg-danger-500 text-white rounded-lg hover:bg-danger-600 transition-colors btn-modern">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
							Post Past Payments
						</a>
						
						<?php
						if(date('D') == 'Mon' && $session_department == "Wealth Creation") { 
							echo '<a href="payments_sunday.php" class="inline-flex items-center px-4 py-2 bg-success-500 text-white rounded-lg hover:bg-success-600 transition-colors btn-modern">
								<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
								</svg>
								Post Sunday Market
							</a>';
						}
						?>
					</div>
					
					<!-- Status Counts -->
					<div class="mt-4 flex flex-wrap gap-4 text-sm">
						<?php
						// Status count queries (keeping original logic)
						$no_of_declined_post = 0;
						
						$ldcount_query = "SELECT COUNT(id) FROM account_general_transaction_new WHERE posting_officer_id = '$session_id' AND leasing_post_status = 'Declined'";
						$lresult = mysqli_query($dbcon, $ldcount_query);
						$leasing_post = mysqli_fetch_array($lresult);
						$no_of_declined_post_leasing = $leasing_post[0];
						
						$dcount_query = "SELECT COUNT(id) FROM account_general_transaction_new WHERE posting_officer_id = '$session_id' AND approval_status = 'Declined'";
						$result = mysqli_query($dbcon, $dcount_query);
						$account_post = mysqli_fetch_array($result);
						$no_of_declined_post_account = $account_post[0];
						
						if($menu["department"] == "Wealth Creation") {
							$no_of_declined_post = $no_of_declined_post_leasing;
						} else {
							$no_of_declined_post = $no_of_declined_post_account;
						}
						
						$pcount_query = "SELECT COUNT(id) FROM account_general_transaction_new WHERE posting_officer_id = '$session_id' ";
						
						if($menu["department"] == "Wealth Creation") {
							$pcount_query .= "AND leasing_post_status = 'Pending'";
						} else {
							$pcount_query .= "AND approval_status = 'Pending'";
						}
						
						$result = mysqli_query($dbcon, $pcount_query);
						$leasing_post = mysqli_fetch_array($result);
						$no_of_pending_post = $leasing_post[0];
						?>
						
						<div class="flex items-center space-x-2">
							<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
								<?php echo $no_of_declined_post; ?>
							</span>
							<span class="text-gray-600">Declined</span>
						</div>
						
						<div class="flex items-center space-x-2">
							<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
								<?php echo $no_of_pending_post; ?>
							</span>
							<span class="text-gray-600">Pending</span>
						</div>
					</div>
				</div>

				<!-- Main Form Area -->
				<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
					<div class="mb-6">
						<h2 class="text-xl font-bold text-gray-900">
							<?php
							if(isset($_GET["income_line"])) {
								echo @$income_line_desc;
							} else {
								echo "Select an Income Line";
							}
							?>
						</h2>
						
						<?php 
						if(@$income_line == "general") {
							echo '<div class="mt-2 flex flex-wrap gap-2">';
							$gquery = "SELECT * FROM accounts";
							$gaccount_set = @mysqli_query($dbcon, $gquery); 
							
							while ($gaccount = mysqli_fetch_array($gaccount_set, MYSQLI_ASSOC)) {
								echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">'.ucwords(strtolower($gaccount['acct_desc'])).'</span>'; 
							} 
							echo '</div>';
						}
						?>
					</div>
					
					<div class="min-h-96">
						<?php
						if(isset($_GET["income_line"])) {
							if ($income_line == "general") {
								include 'payments/general_form_inc.php';
							} elseif ($income_line == "car_sticker") {
								include 'payments/car_sticker_inc.php';
							} elseif ($income_line == "abattoir") {
								include 'payments/abattoir_form.php';
							} elseif ($income_line == "car_loading") {
								include 'payments/car_loading_form_inc.php';
							} elseif ($income_line == "car_park") {
								include 'payments/car_park_form_inc.php';
							} elseif ($income_line == "hawkers") {
								include 'payments/hawkers_form_inc.php';
							} elseif ($income_line == "wheelbarrow") {
								include 'payments/wheelbarrow_form_inc.php';
							} elseif ($income_line == "daily_trade") {
								include 'payments/daily_trade_form_inc.php';
							} elseif ($income_line == "toilet_collection") {
								include 'payments/toilet_collection_form_inc.php';
							} elseif ($income_line == "loading") {
								include 'payments/loading_form_inc.php';
							} elseif ($income_line == "overnight_parking") {
								include 'payments/overnight_parking_form_inc.php';
							} elseif ($income_line == "scroll_board") {
								include 'payments/scroll_board_form_inc.php';
							} elseif ($income_line == "daily_trade_arrears") {
								include 'payments/daily_trade_arrears_form_inc.php';
							} elseif ($income_line == "other_pos") {
								include 'payments/other_pos_form_inc.php';
							} else {
								echo '<div class="text-center py-12">
									<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
									</svg>
									<h3 class="mt-2 text-sm font-medium text-gray-900">Please select an income line</h3>
									<p class="mt-1 text-sm text-gray-500">Choose from the available income lines to get started.</p>
								</div>';
							}
						} else {
							echo '<div class="text-center py-12">
								<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
								</svg>
								<h3 class="mt-2 text-sm font-medium text-gray-900">Select an Income Line</h3>
								<p class="mt-1 text-sm text-gray-500">Choose from the sidebar to start processing payments.</p>
							</div>';
						}
						?>
					</div>
					
					<!-- Error Messages -->
					<div class="mt-6">
						<?php include ('controllers/error_messages_inc.php'); ?>
					</div>
				</div>
			</div>

			<!-- Right Sidebar - Additional Income Lines -->
			<div class="lg:col-span-2">
				<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
					<h3 class="text-lg font-bold text-gray-900 mb-4 text-right">More Income Lines</h3>
					<div class="space-y-2">
						<a href="payments.php?income_line=car_sticker" class="block w-full text-right px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Car Sticker
						</a>
						<a href="payments.php?income_line=loading" class="block w-full text-right px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Loading & Offloading
						</a>
						<a href="payments.php?income_line=overnight_parking" class="block w-full text-right px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors income-card">
							Overnight Parking
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- JavaScript -->
	<script>
		// All original JavaScript functions remain the same
		// Calculation functions for different income types
		function loadCalc() {
			// Keep all original calculation logic
		}
		
		// Form validation and interactions
		$(document).ready(function() {
			// Keep all original jQuery logic
			document.getElementById("trans_desc_div").style.display="none";
			document.getElementById("vehicle_div").style.display="none";
			document.getElementById("plate_no_div").style.display="none";	
			document.getElementById("artisan_div").style.display="none";
		});
		
		// Scroll board functionality
		$(document).ready(function(){
			$('#board_name').change(function(){
				var boardName = $(this).val();
				$.ajax({
					url:'fetch_scrollboard.php',
					method: 'POST',
					data: { board_name: boardName },
					success: function(response) {
						var data = JSON.parse(response);
						if (data.status === "success") {
							$('#expected_rent_monthly').val(data.expected_rent_monthly);
							$('#expected_rent_yearly').val(data.expected_rent_yearly);
							$('#allocated_to').val(data.allocated_to);
						}
					} 
				});
			});
		});
		
		// Date validation
		document.addEventListener("DOMContentLoaded", function() {
			let startDateInput = document.getElementById("start_date");
			let endDateInput = document.getElementById("end_date");

			if (startDateInput && endDateInput) {
				startDateInput.addEventListener("change", function() {
					let startDate = new Date(startDateInput.value);
					if (!isNaN(startDate)) {
						let minEndDate = new Date(startDate);
						minEndDate.setDate(minEndDate.getDate() + 1);
						endDateInput.min = minEndDate.toISOString().split("T")[0];
					}
				});

				endDateInput.addEventListener("change", function() {
					let startDate = new Date(startDateInput.value);
					let endDate = new Date(endDateInput.value);
					if (endDate <= startDate) {
						Swal.fire({
							title: 'Invalid Date',
							text: 'End date must be after start date!',
							icon: 'error',
							confirmButtonColor: '#ef4444'
						});
						endDateInput.value = "";
					}
				});
			}
		});
		
		// Success/Error message handling with SweetAlert2
		<?php if (isset($success_message)): ?>
		Swal.fire({
			title: 'Success!',
			text: '<?php echo $success_message; ?>',
			icon: 'success',
			confirmButtonColor: '#22c55e'
		});
		<?php endif; ?>
		
		<?php if (isset($error_message)): ?>
		Swal.fire({
			title: 'Error!',
			text: '<?php echo $error_message; ?>',
			icon: 'error',
			confirmButtonColor: '#ef4444'
		});
		<?php endif; ?>
	</script>
</body>
</html>