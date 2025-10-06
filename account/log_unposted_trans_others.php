<?php
include 'include/session.php';

date_default_timezone_set('Africa/Lagos');
$current_date = date('Y-m-d');

$project = "Other";
$prefix = "";
$suffix = "";
$sub_prefix = "";
$sub_suffix = "";
$page_title = "Log Unposted {$project} Collection - Woobs Resources ERP";

include ('controllers/log_unposted_trans_form_submit_inc_others.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $project; ?></title>
		<meta http-equiv="Content-Type" name="description" content="Woobs Resources ERP Management System; text/html; charset=utf-8" />
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../../css/datepicker.min.css">
		<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../../js/framework/bootstrap.min.js"></script>
		<script type='text/javascript' src="../../js/bootstrap-datepicker.min.js"></script>
		<script type='text/javascript' src="../../js/fv2.js"></script>
		<script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../../css/bootstrapValidator.min.css">
		<script type="text/javascript" src="../../js/jquery.min.js"></script>
		<script type="text/javascript" src="../../js/bootstrap.min.js"></script>

<style type="text/css">
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.blinking{
	animation:blinkingText 1.0s infinite;
}
</style>

		
<script type="text/javascript">
function refresh_box() 
{
    $("#postcount").load('get_posting_count<?php echo $sub_suffix; ?>.php');
    setTimeout(refresh_box, 30000);
}

$(document).ready(function(){
   refresh_box();
   
});


</script>
</head>
<body>
<div class="well"></div>
	<?php include ('include/staff_navbar.php');
				
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

<div class="container-fluid">
	<div class="col-md-12">
		<div class="row">
			<div class="page-header">
				<div class="container-fluid">
					<div class="col-md-6">
						<h2><strong>Log Unposted <?php echo $project; ?> Collection</strong></h2>
						
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
								
								
								$ca_query = "SELECT posting_officer_id, date_of_payment, payment_category, SUM(amount_paid) as amount_posted ";
								$ca_query .= "FROM account_general_transaction_new ";
								$ca_query .= "WHERE (posting_officer_id = '$session_id' AND payment_category='Other Collection' AND date_of_payment='$current_date') ";
								$ca_sum = @mysqli_query($dbcon,$ca_query);
								$ca_total = @mysqli_fetch_array($ca_sum, MYSQLI_ASSOC);
								
								$amount_posted = $ca_total['amount_posted'];
							
								
								$rm_query = "SELECT SUM(amount_paid) as amount_remitted ";
								$rm_query .= "FROM cash_remittance ";
								$rm_query .= "WHERE (remitting_officer_id = '$session_id' AND category='Other Collection' AND date='$current_date') ";
								$rm_sum = @mysqli_query($dbcon,$rm_query);
								$rm_total = @mysqli_fetch_array($rm_sum, MYSQLI_ASSOC);
								
								$amount_remitted = $rm_total['amount_remitted'];
								
								$unposted = $amount_remitted - $amount_posted;
								
								$uquery = "SELECT COUNT(id), remit_id, date_of_payment, category, posting_officer_id, SUM(amount_paid) as amount_logged ";
								$uquery .= "FROM unposted_transactions ";
								$uquery .= "WHERE (posting_officer_id = '$session_id' AND category='Other Collection' AND date_of_payment='$current_date') ";
								$uset = @mysqli_query($dbcon, $uquery); 
								

								$unp_cash = mysqli_fetch_array($uset, MYSQLI_ASSOC);
								
								$udate = $unp_cash["date_of_payment"];
								$uremit_id = $unp_cash["remit_id"];
								$ucategory = $unp_cash["category"];
								
								$uamount_logged = $unp_cash["amount_logged"];

								echo '<h4>';
								echo 'Remitted: <span style="color:#ec7063; font-weight:bold;">&#8358 '.$amount_remitted.'</span> | Posted: <span style="color:#ec7063; font-weight:bold;">&#8358 '.$amount_posted.'</span> | Unposted: <span style="color:#ec7063; font-weight:bold;">&#8358 '.$unposted.' </span> | Logged: <span style="color:#ec7063; font-weight:bold;">&#8358 '.$uamount_logged.'</span> ';

								echo '</h4>';
						?>
					</div>
				
					<div class="col-md-6">
						<?php
							$till_query = "SELECT SUM(amount_paid) as amount_posted ";
							$till_query .= "FROM {$prefix}account_general_transaction_new ";
							$till_query .= "WHERE posting_officer_id = '$session_id' ";
							$till_query .= "AND leasing_post_status = 'Pending'";
							$sum = @mysqli_query($dbcon,$till_query);
							$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);
							
							$till = $total['amount_posted'];
														
							$declined_query = "SELECT SUM(amount_paid) as amount_posted ";
							$declined_query .= "FROM {$prefix}account_general_transaction_new ";
							$declined_query .= "WHERE posting_officer_id = '$session_id' ";
							$declined_query .= "AND leasing_post_status = 'Declined'";
							$dsum = @mysqli_query($dbcon,$declined_query);
							$dtotal = @mysqli_fetch_array($dsum, MYSQLI_ASSOC);
							
							$till_declined = $dtotal['amount_posted'];
							
							$total_till = ($till + $till_declined);
							$total_till = number_format((float)$total_till, 2);
							echo '<h4><a href="view_trans.php"><span style="color:#ec7063; font-weight:bold;">&#8358 '.$total_till.'</span> Till Balance | ';
						?>
						
						<?php
							$dcount_query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
							$dcount_query .= "WHERE posting_officer_id = '$session_id' ";
							$dcount_query .= "AND leasing_post_status = 'Declined'";
							$result = mysqli_query($dbcon, $dcount_query);
							$leasing_post = mysqli_fetch_array($result);
							$no_of_declined_post = $leasing_post[0];
							
							$pcount_query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
							$pcount_query .= "WHERE posting_officer_id = '$session_id' ";
							$pcount_query .= "AND leasing_post_status = 'Pending'";
							$result = mysqli_query($dbcon, $pcount_query);
							$leasing_post = mysqli_fetch_array($result);
							$no_of_pending_post = $leasing_post[0];
							
							
							$uquery2 = "SELECT COUNT(id) ";
							$uquery2 .= "FROM unposted_transactions ";
							$uquery2 .= "WHERE (posting_officer_id = '$session_id' AND category='Rent' AND date_of_payment='$current_date') ";
							$uset2 = @mysqli_query($dbcon, $uquery2); 						
							$logged_post = mysqli_fetch_array($uset2);
							$no_of_logged_post = $logged_post[0];
							
							
							echo '<span style="color:#ec7063; font-weight:bold;">'.$no_of_declined_post.'</span> Declined | <span style="color:#ec7063; font-weight:bold;">'.$no_of_pending_post.'</span> Pending | <span style="color:#ec7063; font-weight:bold;">'.$no_of_logged_post.'</span> Logged</a> | ';
						?>
						
						<?php
							$query2 = "SELECT COUNT(id) FROM shops_renewal_authorization ";
							$query2 .= "WHERE first_level_approval_officer_id = '$session_id' ";
							$query2 .= "AND status = 'Declined'";
							$result2 = mysqli_query($dbcon, $query2);
							$row2 = mysqli_fetch_array($result2, MYSQLI_NUM);
							$declined_renewal = $row2[0];
							
							
							$query4 = "SELECT COUNT(id) FROM record_update_authorization ";
							$query4 .= "WHERE first_level_approval_officer_id = '$session_id' ";
							$query4 .= "AND status = 'Declined'";
							$result4 = mysqli_query($dbcon, $query4);
							$row4 = mysqli_fetch_array($result4, MYSQLI_NUM);
							$declined_correction = $row4[0];
							
							$query5 = "SELECT COUNT(id) FROM record_verification_authorization ";
							$query5 .= "WHERE first_level_approval_officer_id = '$session_id' ";
							$query5 .= "AND status = 'Declined'";
							$result5 = mysqli_query($dbcon, $query5);
							$row5 = mysqli_fetch_array($result5, MYSQLI_NUM);
							$declined_verification = $row5[0];
							
							echo '<a href="renewed_records.php"><span style="color:#ec7063; font-weight:bold;">'.$declined_renewal.' </span></strong> Lease Renewal Declined</a> | <a href="corrected_records.php"><span style="color:#ec7063; font-weight:bold;">'.$declined_correction.' </span></strong> Correction Declined</a> | <a href="verification_request.php"><span style="color:#ec7063; font-weight:bold;">'.$declined_verification.' </span></strong> Verification Declined</a></h4>'; 
							
							echo '<h6><strong><span style="color:#ec7063;" class="glyphicon glyphicon-arrow-up"></span> Click up to view details</strong></h6>';
						?>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="row">
			<form  method="post" id="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
				<fieldset>
				<div class="col-sm-3"></div>

				<div class="col-sm-6">
					<?php include ('controllers/error_messages_inc.php'); ?>
					
					<div class="row">
								
								<table class="table table-bordered">
								<tr>
									<td colspan="3">
									
										<input type="hidden" name="posting_officer_id" id="officer_id" class="form-control" value="<?php echo $staff['user_id']; ?>" maxlength="50" />
										<input type="hidden" name="posting_officer_name" class="form-control" value="<?php echo $staff['full_name']; ?>" maxlength="50" />
										<input type="hidden" name="shopid" id="shopid" class="form-control input-sm" value="<?php if (isset($_POST["shopid"])) echo @$shop_id; ?>"/>

										<?php
											$staff_name = $staff['full_name'];
											$staffid = $staff['user_id'];

											$rquery = "SELECT remit_id, date, category, remitting_officer_id ";
											$rquery .= "FROM cash_remittance ";
											$rquery .= "WHERE (remitting_officer_id = '$staffid' AND category='Other Collection' AND date='$current_date') ";
											$rset = @mysqli_query($dbcon, $rquery); 

											$cash = mysqli_fetch_array($rset, MYSQLI_ASSOC);
											$date = $cash["date"];
											$remit_id = $cash["remit_id"];
											$category = $cash["category"];


											$uquery = "SELECT remit_id, date_of_payment, category, posting_officer_id, SUM(amount_paid) as amount_logged ";
											$uquery .= "FROM unposted_transactions ";
											$uquery .= "WHERE (posting_officer_id = '$staffid' AND category='Other Collection' AND date_of_payment='$current_date') ";
											$uset = @mysqli_query($dbcon, $uquery); 

											$unp_cash = mysqli_fetch_array($uset, MYSQLI_ASSOC);
											$udate = $unp_cash["date_of_payment"];
											$uremit_id = $unp_cash["remit_id"];
											$ucategory = $unp_cash["category"];
											
											$uamount_logged = $unp_cash["amount_logged"];
											
											$unlogged = $unposted - $uamount_logged;
										?>

										<?php 
											date_default_timezone_set('Africa/Lagos');
											$today = date('Y-m-d');
											list($tiy,$tim,$tid) = explode("-", $today); 
											$today = "$tid/$tim/$tiy";
										?>
										
										
										
										<div class="form-group form-group-sm">
											<label class="col-sm-3 control-label">Date of Payment:</label>
											<div class="col-sm-4">
												<div class="input-group input-append">
													<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date of Payment" value="<?php 
													if (isset($_POST['date_of_payment'])) {
														list($tiy,$tim,$tid) = explode("-", $date_of_payment); 
														$date_of_payment = "$tid/$tim/$tiy";
														echo @$date_of_payment;
													} else {
														echo @$today;
													}
													?>" readonly />
													<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
												</div>
											</div>
										</div>
										
										
										<div class="form-group form-group-sm"> 
										  <label for="income_line" class="col-sm-3 control-label">Income Line:</label>
											<div class="col-sm-4 selectContainer">
												<div class="input-group">
													<span class="input-group-addon"><i class="glyphicon glyphicon-signal"></i></span>
													<select name="income_line" class="form-control selectpicker" id="income_line" required >
														<option value="">Select...</option>
														<option value="general">General</option>
														<option value="car_sticker">Car Sticker</option>
														<option value="abattoir">Abattoir</option>
														<option value="car_loading">Car Loading</option>
														<option value="car_park">Car Park</option>
														<option value="hawkers">Hawkers</option>
														<option value="wheelbarrow">Wheelbarrow</option>
														<option value="daily_trade">Daily Trade</option>
														<option value="toilet_collection">Toilet Collection</option>
														<option value="loading">Loading & Offloading</option>
														<option value="overnight_parking">Overnight Parking</option>
													</select>
												</div>
											</div>
										</div>
										
										
										<div class="form-group form-group-sm"> 
										  <label for="remit_id" class="col-sm-3 control-label">Remittances:</label>
											<div class="col-sm-6 selectContainer">
												<div class="input-group">
													<span class="input-group-addon"><i class="glyphicon glyphicon-signal"></i></span>
													<select name="remit_id" class="form-control selectpicker" id="remit_id" required >
														<option value="">Select...</option>
														
														<option value="<?php echo $remit_id; ?>"><?php echo $date.': Loggable Remittance - N'.$unlogged; ?></option>

													</select>
												</div>
											</div>
										</div>
		
									
										<!-- Text input-->			
										<div class="form-group form-group-sm">
											<label for="transaction_desc" class="control-label col-sm-3">Trans. Description:</label>
											<div class="col-sm-6 inputGroupContainer">
												<div class="input-group">
													<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
													<input type="text" name="transaction_desc" class="form-control input-sm" placeholder="Transaction description" value="<?php if (isset($_POST['transaction_desc'])) echo @$transaction_desc; ?>" maxlength="100" required />
												</div>
												
											</div>
										</div>
										
										
										<!-- Text input-->			
										<div class="form-group form-group-sm">
											<label for="receipt_no" class="control-label col-sm-3">Receipt No:</label>
											<div class="col-sm-4 inputGroupContainer">
												<div class="input-group">
													<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
													<input type="text" name="receipt_no" class="form-control input-sm" placeholder="Receipt No" pattern="^\d{7}$" value="<?php if (isset($_POST['receipt_no'])) echo @$receipt_no; ?>" maxlength="7" required />
												</div>
											</div>
										</div>
										
										
										<!-- Text input-->			
										<div class="form-group form-group-sm">
											<label for="amount_paid" class="control-label col-sm-3">Total Amount Paid:</label>
											<div class="col-sm-4 inputGroupContainer">
												<div class="input-group">
													<span class="input-group-addon">&#8358;</span>
													<input type="text" name="amount_paid" id="amount_paid" class="form-control input-sm" placeholder="Amount Paid" value="<?php if (isset($_POST['amount_paid'])) echo @$amount_paid; ?>" maxlength="20" />
												</div>
											</div>
										</div>
										
										
										<div class="form-group form-group-sm"> 
										  <label for="reason" class="col-sm-3 control-label">Reason:</label>
											<div class="col-sm-4 selectContainer">
												<div class="input-group">
													<span class="input-group-addon"><i class="glyphicon glyphicon-signal"></i></span>
													<select name="reason" class="form-control selectpicker" id="reason" required >
														<option value="">Select...</option>
														<option value="Missing Amount">Missing Amount</option>
														<option value="Wrong Remittance Amount">Wrong Remittance Amount</option>
														<option value="Posting Deadline">Posting Deadline</option>
													</select>
												</div>
											</div>
										</div>
										
									</td>
									
									<input type="hidden" name="loggable" id="loggable" class="form-control input-sm" value="<?php if (isset($_POST["loggable"])) {echo $unlogged;} else {echo $unlogged;} ?>"/>
								</tr>
								
								<tr>
									<td colspan="3">
										<div class="text-center">
											<div>
												<?php
												if ($unposted == $uamount_logged) {
													echo '<h4><span style="color:#ec7063; font-weight:bold;">You do not have any UNLOGGED remittances for today.';
												} else {
													echo '
														<button type="submit" id="btn_post" name="btn_post" class="btn btn-sm btn-danger">Log Payment <span class="glyphicon glyphicon-send"></span></button>
														<button type="reset" name="btn_clear" class="btn btn-sm btn-primary">Clear</button>';
												}
												?>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
				</div>
				
				
				<div class="col-sm-3"></div>
				
				</fieldset>
				</form>
		</div>
	</div>			
</div>
</body>
</html>