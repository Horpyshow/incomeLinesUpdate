<?php
include 'include/session.php';

$error = false;

if (isset($_POST["btn_submit_comment"])) {
	$comment = trim($_POST['comment']);
	$comment = strip_tags($comment);
	$comment = htmlspecialchars($comment);
	
	$shop_id = $_POST["shop_id"];
	$shop_no = $_POST["shop_no"];
	$customer_name = $_POST["customer_name"];
	
	$staff_query = "SELECT * FROM staffs WHERE user_id =".$_SESSION['staff'];
	$staff_set = @mysqli_query($dbcon, $staff_query); 
	$verifying_staff = mysqli_fetch_array($staff_set, MYSQLI_ASSOC);
	
	$acct_bal_verifying_officer_id = $verifying_staff['user_id'];
	$acct_bal_verifying_officer_name = $verifying_staff['full_name'];
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	if(!$error){
		$sql_query = "INSERT INTO customer_balance_verification_report (id, shop_id, shop_no, customer_name, comment, acct_bal_verifying_officer_id, acct_bal_verifying_officer_name, verification_time) VALUES ('', '$shop_id', '$shop_no', '$customer_name', '$comment', '$acct_bal_verifying_officer_id', '$acct_bal_verifying_officer_name', '$now')";
		$sql_result = mysqli_query($dbcon, $sql_query);
		
		$query = "UPDATE customers SET balance_verification_status='Declined', acct_bal_verifying_officer_id='$acct_bal_verifying_officer_id', acct_bal_verifying_officer_name='$acct_bal_verifying_officer_name', balance_verification_time='$now' WHERE id='$shop_id'";
		$result = mysqli_query($dbcon, $query);
	}
	if ($sql_result)
	{
		?>
		<script type="text/javascript">
		alert('Record DECLINED! Your comment was successfully posted.');
		window.top.close();
		window.location.href='balance_verification.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while processing your request');
		window.location.href='balance_verification_comment.php?bal_decline_id=<?php echo $shop_id; ?>';
		</script>
		<?php
	}
}
?>
<?php include ('include/staff_header.php'); ?>
<body>
<div class="well"></div>
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
?>
<?php
if (isset($_GET["bal_decline_id"])){
	$bal_decline_id = $_GET["bal_decline_id"];
	$dsquery = "SELECT * ";
	$dsquery .= "FROM customers ";
	$dsquery .= "WHERE id = '$bal_decline_id'";
	$sresult = mysqli_query($dbcon, $dsquery);
	//$total_shop_no = mysqli_num_rows($sresult);
}
?>
<div class="container-fluid">
	<div class="col-md-2"><?php include ('include/staff_side_navbar.php'); ?></div>
	<div class="col-md-10">
		<div class="well"></div>
		<div class="row">
			<div class="table-responsive">
				  <table class="table table-hover">
				  <thead>
					<tr class="text-info">
							<td colspan="8">
								<a href="balance_verification.php" class="btn btn-danger"><span class="glyphicon glyphicon-backward"></span> View more customers</a>
							</td>
							<td class="text-right" colspan="3">
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
							$i = 0;
							
							while ($customer = @mysqli_fetch_array($sresult, MYSQLI_ASSOC)) {
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
								 
								 
								 
									$expected_rent = $customer["total_expected_rent"];
									$expected_rent = preg_replace('/[,]/', '', $expected_rent);
									$expected_rent = ($expected_rent + 0);
									if (!is_int($expected_rent)) {
									$expected_rent = (int)$expected_rent;
									}
									
									$acct_ledger_paid = $total['amount_paid'];
									
									$cbal_query = "SELECT * FROM customers ";
									$cbal_query .= "WHERE id = '$shop_id'";
									$cbal_result = mysqli_query($dbcon, $cbal_query);
									$customer_acct = mysqli_fetch_array($cbal_result, MYSQLI_ASSOC);
									
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
					
									$query_sc = "SELECT SUM(amount_paid) as amount_paid ";
									$query_sc .= "FROM arena_account_general_transaction ";
									$query_sc .= "WHERE shop_id = '$customer_id' ";
									$query_sc .= "AND approval_status = 'Approved'";
									$sum_sc = mysqli_query($dbcon,$query_sc);
									$total_sc = mysqli_fetch_array($sum_sc, MYSQLI_ASSOC);
								 ?> 
								 <?php 
									$expected_sc = $customer["expected_service_charge_yearly"];
									$expected_sc = preg_replace('/[,]/', '', $expected_sc);
									$expected_sc = ($expected_sc + 0);
									if (!is_int($expected_sc)) {
									$expected_sc = (int)$expected_sc;
									}
									$acct_ledger_paid_sc = $total_sc['amount_paid'];
									
									$cbal_query_sc = "SELECT * FROM customers ";
									$cbal_query_sc .= "WHERE id = '$customer_id'";
									$cbal_result_sc = mysqli_query($dbcon, $cbal_query_sc);
									$customer_acct_sc = mysqli_fetch_array($cbal_result_sc, MYSQLI_ASSOC);
									
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
							</th>
							<?php	 
							 }
							?>
				  </table>
				</div>
			</div>
		
		<div class="row">
			<div class="col-md-7">
				<div class="row">
					<div class="page-header">
						<div class="container-fluid">
							<div class="col-md-8">
								<h2>Balance Verification Report</h2>
							</div>
						
							<div class="col-md-4">
							</div>
						</div>
					</div>
				</div>
				<?php
				   if ( isset($comment_Error) ) {
				?>
					<div class="form-group form-group-sm">
						<div class="alert alert-success fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<?php echo @$comment_Error; ?>
						</div>
					</div>
				<?php
				   }
				?>
				<div class="row">
					<form  method="post" id="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
					<?php
						if (isset($_GET["bal_decline_id"])){
							$bal_decline_id = $_GET["bal_decline_id"];
							$dsquery = "SELECT * ";
							$dsquery .= "FROM customers ";
							$dsquery .= "WHERE id = '$bal_decline_id'";
							$dsresult = mysqli_query($dbcon, $dsquery);
							
							$dsrecord = mysqli_fetch_array($dsresult, MYSQLI_ASSOC);
							
							$shop_id = $dsrecord["id"];
							$shop_no = $dsrecord["shop_no"];
							$customer_name = $dsrecord["customer_name"];
						}
					?>
						<input type="hidden" name="shop_id" class="form-control" placeholder=" " value="<?php if (isset($_GET["bal_decline_id"])) echo $shop_id; ?>" />
						<input type="hidden" name="shop_no" class="form-control" placeholder=" " value="<?php if (isset($_GET["bal_decline_id"])) echo $shop_no; ?>" />
						<input type="hidden" name="customer_name" class="form-control" placeholder=" " value="<?php if (isset($_GET["bal_decline_id"])) echo $customer_name; ?>" />
						
						<div class="form-group form-group-md">
							<label for="comment" class="col-md-2 control-label">Comment</label>
							<div class="col-md-8 inputgroupContainer">
								<textarea name="comment" class="form-control" id="comment" rows="10" placeholder="Please explain why you are declining this record?"></textarea>
							</div>
						</div>
						
						<div class="form-group form-group-sm text-center">
							<div>
								<button type="submit" name="btn_submit_comment" class="btn btn-danger">Send Comment <span class="glyphicon glyphicon-send"></span></button>
								<button type="submit" name="btn_clear" class="btn btn-primary">Clear</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-3"></div>
		</div>
	</div>	
</div>
</body>
</html>
<?php ob_end_flush(); ?>