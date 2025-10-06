<?php
include 'include/session.php';

if(isset($_SESSION['staff']) ) {
$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['staff'];
}
if(isset($_SESSION['admin']) ) {
$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['admin'];
}
$role_set = mysqli_query($dbcon, $query);
$role = mysqli_fetch_array($role_set, MYSQLI_ASSOC);

// delete condition
if(isset($_GET['delete_id']))
{
	$query="DELETE FROM customers WHERE id=".$_GET['delete_id'];
	$result = mysqli_query($dbcon, $query);
	header("Location: $_SERVER[PHP_SELF]");
}
?>
<?php
if (isset($_GET['bal_verify_id']))
{
	$staff_query = "SELECT * FROM staffs WHERE user_id =".$_SESSION['staff'];
	$staff_set = @mysqli_query($dbcon, $staff_query); 
	$verifying_staff = mysqli_fetch_array($staff_set, MYSQLI_ASSOC);
	
	$staff_id = $verifying_staff['user_id'];
	$staff_name = $verifying_staff['full_name'];
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$query = "UPDATE customers SET balance_verification_status='Verified', acct_bal_verifying_officer_id='$staff_id', acct_bal_verifying_officer_name='$staff_name', balance_verification_time='$now' WHERE id=".$_GET['bal_verify_id'];
	$result = mysqli_query($dbcon, $query);
}
?>
<?php
	//Set the number of rows per display
	$pagerows = 50;
	
	//Has the total number of pages already been calculated?
	if (isset($_GET['p']) && is_numeric($_GET['p'])) {
		$pages = $_GET['p'];
	} else {
	//First, check for the total number of records
		$query = "SELECT COUNT(id) FROM customers";
		$result = mysqli_query($dbcon, $query);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		$records = $row[0];
		
	//Now calculate the number of pages
	if ($records > $pagerows) {
		$pages = ceil($records/$pagerows);
	} else {
		$pages = 1;
	}
	}
	//Declare which record to start with
	if (isset($_GET['s']) && is_numeric($_GET['s'])) {
		$start = $_GET['s'];
	} else {
		$start = 0;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Customer Management Dashboard | Woobs Resources ERP</title>
		<meta http-equiv="Content-Type" name="description" content="Woobs Resources ERP Management System; text/html; charset=utf-8" />
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/formValidation.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/datepicker.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/datepicker3.min.css">
		<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../../js/formValidation.min.js"></script>
		<script type="text/javascript" src="../../js/framework/bootstrap.min.js"></script>
		<script type='text/javascript' src="../../js/bootstrap-datepicker.min.js"></script>
		<script type="text/javascript" src="../../js/bootstrap.js"></script>
		
		<script src="../../js/fv.js"></script>
		<script src="../../js/idle.js"></script>
		<script src="js/fn_lmc.js"></script>
		<script src="../../js/sub_menu.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">
</head>
<body>
<?php 
//Located within hr module
include ('include/staff_navbar.php');
			
			$vp_user_id = $menu["user_id"];
			$vp_staff_name = $menu["full_name"];
			$sessionID = session_id();
			
			$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$url = htmlspecialchars(strip_tags($url));
			
			date_default_timezone_set('Africa/Lagos');
			$now = date('Y-m-d H:i:s');
			
			$vp_query = "INSERT IGNORE INTO visited_pages (id, user_id, staff_name, session_id, uri, time) VALUES ('', '$vp_user_id', '$vp_staff_name', '$sessionID', '$url', '$now')";
			$vp_result = mysqli_query($dbcon,$vp_query); 

if ($staff['department']!="Accounts" && $menu['level']!="ce" && $menu['level']!="dgm" && $menu['level']!="Head, Wealth Creation" && $menu['level']!="Head, Risk Management" && $menu['level']!="Head, Audit & Inspection" && $menu['level']!="IT") {
?>
	<script type="text/javascript">
		alert('You do not have permissions to view this page! Contact your HOD for authorization. Thanks');
		window.location.href='../../index.php';
	</script>
<?php
}
?>
<div class="well"></div>
<div class="container-fluid">
	<div class="col-md-12">
	<?php
		$query = "SELECT COUNT(id) FROM customers ";
		$query .= "WHERE update_status = 'Updated'";
		$result = mysqli_query($dbcon, $query);
		$customer_row = mysqli_fetch_array($result);
		$no_of_customers = $customer_row[0];
		
		$query = "SELECT COUNT(id) FROM customers";
		$result = mysqli_query($dbcon, $query);
		$total_customer_row = mysqli_fetch_array($result);
		$total_no_of_customers = $total_customer_row[0];
		
		$aquery = "SELECT COUNT(id) FROM customers ";
		$aquery .= "WHERE balance_verification_status = 'Verified' ";
		$aquery .= "OR balance_verification_status = 'Declined'";
		$aresult = mysqli_query($dbcon, $aquery);
		$verified_customer_row = mysqli_fetch_array($aresult);
		$total_verified_customers = $verified_customer_row[0];
	?>
	<div class="page-header">
		<h1>Customer's Account Balance Verification Dashboard</h1>
		<h3 style="line-height: 30px;">
			<?php echo $total_no_of_customers; ?> <small>Customers records uploaded by Leasing Dept.</small></br>
			<?php echo $no_of_customers; ?> of <?php echo $total_no_of_customers; ?> <small>Customer records are duely updated by Leasing Dept.</small></br>
			<?php echo $total_verified_customers; ?> of <?php echo $total_no_of_customers; ?> <small> Customer(s) Payment Record (Rent/Service Charge Balances) Verified by Account Dept.</small></br>
			<?php
				if (isset($_GET['s'])) {
					$i = $_GET['s'];
				} else {
					$i=0;
				} 
				
				$query = "SELECT * ";
				$query .= "FROM staffs ";
				$query .= "WHERE department = 'leasing' ";
				$query .= "AND level = 'leasing officer' ";
				$query .= "ORDER BY first_name ASC";
				
				$result = mysqli_query($dbcon, $query);

				if (!$result) {
				die ("Database search query failed");
				}
				while ($staff = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						$staff_id = $staff["user_id"];
	
						$query = "SELECT * ";
						$query .= "FROM customers ";
						$query .= "WHERE staff_id = $staff_id";
						$new_result = mysqli_query($dbcon, $query);
						$customer_no = mysqli_num_rows($new_result);
						
						if(isset($_SESSION['staff']) ) {
						$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['staff'];
						}
						if(isset($_SESSION['admin']) ) {
						$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['admin'];
						}
						$staffresult = mysqli_query($dbcon, $staffquery);
						$session_staff = mysqli_fetch_array($staffresult, MYSQLI_ASSOC);
						$session_id = $session_staff['user_id'];
						
						if ($staff_id == $session_id) {
						echo '<a href="../leasing/assigned_shops.php?shops_id='.$session_id.'" class="btn btn-danger">'.$staff["first_name"].': '.$customer_no.' shops</a>';
						} else {
							echo '<a href="../leasing/assigned_shops.php?shops_id='.$staff_id.'" class="btn btn-primary">'.$staff["first_name"].': '.$customer_no.' shops</a>';
						}
				}
			?>
		</h3>
	</div>
		<div class="row">
		<div class="table-responsive">
			  <table class="table table-hover">
			  <thead>
				<tr class="text-info">
						<td colspan="10">
						</td>
						<td class="text-right" colspan="4">
							<?php
							$query = "SELECT COUNT(id) FROM customers";
							$result = mysqli_query($dbcon, $query);
							$row = mysqli_fetch_array($result);
							$staff_no = $row[0];
							
							//echo "<p>Total number of registered staff: $staff_no</p>";
							if ($pages > 1){
								echo '<p>';
								
								//What number is the current page?
								$current_page = ($start/$pagerows) + 1;
								
								//If the page is not the first page then create a Previous link
								if ($current_page != 1){
									echo '<a href="balance_verification.php?s=' .  ($start - $pagerows) . '&p=' . $pages . '" class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Previous</a> ';
								}
								
								//Create a Next link
								if ($current_page != $pages){
									echo '<a href="balance_verification.php?s=' .  ($start + $pagerows) . '&p=' . $pages . '" class="btn btn-primary">Next <span class="glyphicon glyphicon-forward"></span></a> ';
								}
								echo '</p>';
							}
							?>
						</td>
					</tr>
				</thead>
				<thead>
					<tr>
						<th colspan="5"></th>
						<th colspan="2" class="danger text-center">ALLOCATION DATE</th>
						
						<th colspan="4" class="danger text-center">PAYMENT RECORD</th>
						<th colspan="6">
							<form method="POST" action="search.php">
								<div class="input-group col-md-12">
									<input type="text" class="search-query form-control" name="search" placeholder="Search" value="" />
									<span class="input-group-btn">
										<button class="btn btn-primary" type="submit" name="btn-search">
											<span class=" glyphicon glyphicon-search"></span>
										</button>
									</span>
								</div>
							</form>
						</th>
					</tr>
				</thead>
				
				
				<thead>
					<tr class="text-info">
						<th class="info text-center">S/N</th>
						<th class="info">SHOP NO</th>
						<th class="info">CUSTOMER NAME</th>
						<th class="info">CURRENT OCCUPANT</th>
						<th class="info text-center">SIZE</th>
						<th class="danger text-center">START</th>
						<th class="danger text-center">EXPIRY</th>
						<th class="success text-center">TOTAL</br>EXPECTED</br>RENT</th>
						<th class="success text-center">RENT</br>BALANCE</th>
						<th class="success text-center">TOTAL</br>EXPECTED</br>SERVICE</br>CHARGE</th>
						<th class="success text-center">SERVICE</br>CHARGE</br>BALANCE</th>
						<th class="info text-center">STAFF</br>IN-CHARGE</th>
						
						<th class="info" colspan="5"></th>
					</tr>
				</thead>
					 <?php
					 $query = "SELECT * ";
					 $query .= "FROM customers ";
					 $query .= "WHERE update_status = 'Updated' ";
					 $query .= "AND balance_verification_status = '' ";
					 $query .= "ORDER BY balance_verification_status ASC ";
					 $query .= "LIMIT $start, $pagerows";
					 $result = mysqli_query($dbcon, $query);
					 $total_shop_no = mysqli_num_rows($result);
					 
					 if (!$result) {
						 die ("Database query actually failed");
					 }
					 while ($customer = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					?>
					<?php
						$shop_id = $customer["id"];
						$shop_no = $customer["shop_no"];
						$customer_name = $customer["customer_name"];
						$customer_id = $customer["id"];
					?>
					<tr>
						<th class="info text-center"><?php echo ++$i.'.'; ?> </th>
						<td>
							<?php
							echo '
							<a href="javascript:cdetails_id('.'\''.$shop_id.'\''.')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Get more information about Shop '.$customer["shop_no"].' .Current owner, payment status, Off-takers and more.">'.$customer["shop_no"].'</a>';
							
							
							/*
							if ($customer_detail['update_status']!="Updated") {
								echo '
									<a href="javascript:edit_id('.'\''.$shop_id.'\''.')" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="Update of shop '.$shop["shop_no"].' is required">'.$shop["shop_no"].'</a>';
							} else {
								echo '
									<a href="javascript:cdetails_id('.'\''.$shop_id.'\''.')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Get more information about Shop '.$shop["shop_no"].' .Current owner, payment status, Off-takers and more.">'.$shop["shop_no"].'</a>';
							}
							
							*/
							?>
						</td>

						<th><span class="glyphicon glyphicon-user"></span> <?php echo ucwords(strtolower($customer["customer_name"])); ?></th>
						<td class="info"><?php echo ucwords(strtolower($customer["off_takers_name"])); ?></td>
						<td class="text-center"><?php echo ucwords(strtolower($customer["shop_size"])); ?> </td>
						<td class="warning text-center"><?php echo ucwords(strtolower($customer["lease_start_date"])); ?> </td>
						<td class="warning text-center"><?php echo ucwords(strtolower($customer["lease_end_date"])); ?> </td>
						
						<?php
							$query = "SELECT SUM(amount_paid) as amount_paid ";
							$query .= "FROM account_general_transaction ";
							$query .= "WHERE shop_id = '$shop_id' ";
							$query .= "AND approval_status = 'Approved'";
							$sum = mysqli_query($dbcon,$query);
							$total = mysqli_fetch_array($sum, MYSQLI_ASSOC);
							
							
							$query_n = "SELECT SUM(amount_paid) as amount_paid ";
							$query_n .= "FROM account_general_transaction_new ";
							$query_n .= "WHERE shop_id = '$shop_id' ";
							$query_n .= "AND approval_status = 'Approved'";
							$sum_n = @mysqli_query($dbcon,$query_n);
							$total_n = @mysqli_fetch_array($sum_n, MYSQLI_ASSOC);
						 
						 
							$expected_rent = $customer["total_expected_rent"];
							$expected_rent = preg_replace('/[,]/', '', $expected_rent);
							$expected_rent = ($expected_rent + 0);
							if (!is_int($expected_rent)) {
							$expected_rent = (int)$expected_rent;
							}
							
							$total_till1_payments = @$total['amount_paid'];
							$total_till2_payments = @$total_n['amount_paid'];
							$acct_ledger_paid = $total_till1_payments + $total_till2_payments;
							
							$cbal_query = "SELECT * FROM customers ";
							$cbal_query .= "WHERE id = '$shop_id'";
							$cbal_result = @mysqli_query($dbcon, $cbal_query);
							$customer_acct = @mysqli_fetch_array($cbal_result, MYSQLI_ASSOC);
							
							$record_amt_paid = $customer_acct['rent_paid'];
							
							$paid = $acct_ledger_paid + $record_amt_paid;
							
							$balance = ($expected_rent - $paid);
						?>
						
						
						<td class="success text-center">
							<?php								
								$expected_rent = number_format($expected_rent, 0);
								echo "&#8358 {$expected_rent}";
								
							?>
						</td>
						<td class="success text-center">
							<?php 
								$balance = number_format($balance, 0);
								echo "&#8358 {$balance}";
							?>
						</td>
						
						<td class="warning">
							<?php
								$query = "SELECT SUM(amount_paid) as amount_paid ";
								$query .= "FROM arena_account_general_transaction ";
								$query .= "WHERE shop_id = '$shop_id' ";
								$query .= "AND approval_status = 'Approved'";
								$sum = @mysqli_query($dbcon,$query);
								$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);
							 
							 
								$query_n = "SELECT SUM(amount_paid) as amount_paid ";
								$query_n .= "FROM arena_account_general_transaction_new ";
								$query_n .= "WHERE shop_id = '$shop_id' ";
								$query_n .= "AND approval_status = 'Approved'";
								$sum_n = @mysqli_query($dbcon,$query_n);
								$total_n = @mysqli_fetch_array($sum_n, MYSQLI_ASSOC);
								
								
								$expected_sc = $customer["expected_service_charge_yearly"];
								$expected_monthly_sc = $customer["expected_service_charge"];
								
								$expected_sc = preg_replace('/[,]/', '', $expected_sc);
								$expected_sc = ($expected_sc + 0);
								if (!is_float($expected_sc)) {
								$expected_sc = (float)$expected_sc;
								}
								
								$total_till1_payments = @$total['amount_paid'];
								$total_till2_payments = @$total_n['amount_paid'];
								$acct_ledger_paid_sc = $total_till1_payments + $total_till2_payments;
								
								$cbal_query_sc = "SELECT * FROM customers ";
								$cbal_query_sc .= "WHERE id = '$shop_id'";
								$cbal_result_sc = @mysqli_query($dbcon, $cbal_query_sc);
								$customer_acct_sc = @mysqli_fetch_array($cbal_result_sc, MYSQLI_ASSOC);
								
								$record_amt_paid_sc = $customer_acct_sc['service_charge_paid'];
								
								$paid_sc = $acct_ledger_paid_sc + $record_amt_paid_sc;
								
								$balance_sc = ($expected_sc - $paid_sc);
							?>
							<?php 									
								$expected_sc = number_format($expected_sc, 2);
								echo "&#8358 {$expected_sc}";
							?>
							<?php
								//$paid_sc = $fetched_row['service_charge_paid'];
								//$paid = number_format($paid, 2);
							?>
							
						</td>
						<td class="warning">
							<?php 
								$balance_sc = number_format($balance_sc, 2);
								echo "&#8358 {$balance_sc}";
							?>
						</td>
						<td class="info text-center"><?php echo ucwords(strtolower($customer["staff_name"])); ?> </td>
						
						<?php

							$allocation_date = $customer['lease_start_date'];
							//$allocation_date = '30-12-2015';
							$allocation_date = str_replace('/', '-', $allocation_date);
							date('Y-m-d', strtotime($allocation_date));


							$expiry_date = $customer['lease_end_date'];
							//$expiry_date = '29-1-2016';
							$expiry_date = str_replace('/', '-', $expiry_date);
							date('Y-m-d', strtotime($expiry_date));

							$current_date = date('d-m-Y');
							$current_date = str_replace('/', '-', $current_date);
							date('Y-m-d', strtotime($current_date));


							$expiry = new DateTime($expiry_date);
							$today = new DateTime($current_date);
							$interval = $today->diff($expiry);
							?>
								
						<th class="text-right">
							<?php
								if ($today>=$expiry && $interval->days <= 31) {
									echo '<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Get more information about '.ucwords(strtolower($customer["customer_name"])).'">Expired '.$interval->days.' days ago</a>
									';
								} elseif ($today>=$expiry && $interval->days < 365) {
									echo '<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Get more information about '.ucwords(strtolower($customer["customer_name"])).'">Expired '.$interval->m.' months, '.$interval->d.' days ago</a>
									';
								} elseif ($today>=$expiry && $interval->days >= 365) {
									echo '<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Get more information about '.ucwords(strtolower($customer["customer_name"])).'">Expired '.$interval->y . ' years, ' . $interval->m.' months, '.$interval->d.' days ago</a>';  
								} elseif ($today<=$expiry && $interval->days <= 31) {
									echo '<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Get more information about '.ucwords(strtolower($customer["customer_name"])).'">'.$interval->days . ' days left</a>
									';
								} elseif ($today<=$expiry && $interval->days <= 60) {
									echo '<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Get more information about '.ucwords(strtolower($customer["customer_name"])).'">'.$interval->m.' months, '.$interval->d.' days left</a>
									';
								} elseif ($today<=$expiry && $interval->days < 365) {
									echo '<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-success btn-sm" data-toggle="tooltip" title="Get more information about '.ucwords(strtolower($customer["customer_name"])).'">'.$interval->m.' months, '.$interval->d.' days left</a>';
								} elseif ($today<=$expiry && $interval->days >= 365) {
									echo '<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-success btn-sm" data-toggle="tooltip" title="Get more information about '.ucwords(strtolower($customer["customer_name"])).'">'.$interval->y . ' years, ' . $interval->m.' months, '.$interval->d.' days left</a>';
								} else {
									echo "";
								}
							?>
						</th>
						<th class="text-right">
							<?php
								if ($customer['balance_verification_status'] != "Verified") {
									echo '<a href="javascript:bal_verify_id('.'\''.$shop_id.'\''.')" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Verify '.$customer["shop_no"].' payment">Verify Payments</a>';
								} 
								elseif ($customer['balance_verification_status'] == "Verified") {
									echo '<a href="#" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="'.$customer["shop_no"].' payment already verified">Verified</a>';
								}
							?>
						</th>								

						<?php	 
						 }
						?>
					<tr class="text-info">
						<td colspan="10"></td>
						<td class="text-right" colspan="6">
							<?php
							$query = "SELECT COUNT(id) FROM customers";
							$result = mysqli_query($dbcon, $query);
							$row = mysqli_fetch_array($result);
							$no_of_shops = $row[0];
							
							//echo "<p>Total number of registered staff: $staff_no</p>";
							if ($pages > 1){
								echo '<p>';
								
								//What number is the current page?
								$current_page = ($start/$pagerows) + 1;
								
								//If the page is not the first page then create a Previous link
								if ($current_page != 1){
									echo '<a href="balance_verification.php?s=' .  ($start - $pagerows) . '&p=' . $pages . '" class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Previous</a> ';
								}
								//Create a Next link
								if ($current_page != $pages){
									echo '<a href="balance_verification.php?s=' .  ($start + $pagerows) . '&p=' . $pages . '" class="btn btn-primary">Next <span class="glyphicon glyphicon-forward"></span></a> ';
								}
								echo '</p>';
							}
							?>
						</td>
					</tr>
			  </table>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
function bal_verify_id(id)
{
	if(confirm('Are you sure you have duely verified this balances?'))
	{
		window.location.href='search.php?bal_verify_id='+id; 
	}
}

	$(document).ready(
		function() {
			$('[data-toggle="tooltip"]').tooltip();
		}
	);
</script>