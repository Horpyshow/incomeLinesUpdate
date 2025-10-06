<?php
include 'include/session.php';
include ('include/functions.php');

$page_title = "Account Dashboard";


if (isset($_GET['d1']) ) {
	$current_date = $_GET["d1"];
	list($tid,$tim,$tiy) = explode("/",$current_date);
	
	$current_date = "$tiy-$tim-$tid";

} else {
	$current_date = date('Y-m-d');
}

// delete unposted transaction on same day
if (isset($_GET['cash_delete_id'])) {
	$query="DELETE FROM cash_remittance WHERE id=".$_GET['cash_delete_id'];
	$result = @mysqli_query($dbcon, $query);
} 
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $page_title; ?></title>
		<meta name="description" content="Woobs Resources ERP Management System">
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/formValidation.min.css">

		<link rel="stylesheet" type="text/css" href="../../css/datepicker.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/datepicker3.min.css">
		<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../../js/formValidation.min.js"></script>
		<script type="text/javascript" src="../../js/framework/bootstrap.min.js"></script>
		<script type='text/javascript' src="../../js/bootstrap-datepicker.min.js"></script>
		<script type='text/javascript' src="../../js/fv.js"></script>
		<!--
		Date Options didnt work, form validation worked, menu worked
		<script type="text/javascript" src="../../js/bootstrap.js"></script> -->
		<script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>

		<link rel="stylesheet" type="text/css" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrapValidator.min.css">
		<script type="text/javascript" src="../../js/jquery.min.js"></script>
		<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
		<script src="../../js/sub_menu.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">
		
		<link rel="stylesheet" href="../../scripts/swal2/sweetalert2.min.css" type="text/css" />
		<script src="../../scripts/swal2/sweetalert2.min.js"></script>
		
		
<script type="text/javascript">
function cash_delete_id(id)
{
	if(confirm('Are you sure you want to COMPLETELY DELETE this remittance?'))
	{
		window.location.href='account_dashboard.php?cash_delete_id='+id;
	}
}
</script>
</head>
<body>
<?php 
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
<div class="well"></div>
<div class="container-fluid">
	<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-6">
					<div class="page-header">
						<h2><strong><?php //echo $project; ?> Account Remittance Dashboard</strong></h2>
						<?php 
							//if ($menu["department"] == "Wealth Creation" || $menu["level"] == "ce") {
								include ('../leasing/include/countdown_script.php'); 
							//}
						?>
					</div>
				</div>
				
				
				<div class="col-sm-6">
					<div class="containter">
					
							<form name="form1" method="post" action="account_dashboard_processing.php" autocomplete="off">
							
							
								<div class="form-group form-group-sm">
									<label class="col-sm-3 control-label">Date of Remittance:</label>
									<div class="col-sm-4 date">
										<div class="input-group input-append date" id="date_of_payment">
											<input type="text" class="form-control input-sm" name="date_of_remittance" value="<?php 
											if (isset($_POST['date_of_payment'])) {
												list($tiy,$tim,$tid) = explode("-", $date_of_remittance); 
												$date_of_remittance = "$tid/$tim/$tiy";
												echo @$date_of_remittance;
											}
											?>" required />
											<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										</div>
									</div>
								</div>
							
							<div class="col-sm-3">
								<input type="submit" class="btn btn-success btn-sm" name="btn_filter" value="View Remittance" />
							</div>
							</form>
						
					</div>
				</div>
				
		</div>
		

		<!--Row begins here -->
		<div class="row">
			<?php
			//if (($staff['department']=="Accounts" || $menu['level']=="ce") && $staff['user_id']!="177"){
			if ($staff['department']=="Accounts" || $menu['level']=="ce"){		
			?>
			<div class="col-md-4">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h4 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> Cash Remittance</h4>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								
								<?php 
									date_default_timezone_set('Africa/Lagos');
									$today = date('Y-m-d');
									list($tiy,$tim,$tid) = explode("-", $today); 
									
									//Today as today
									$today = "$tid/$tim/$tiy";
									
									//Today as any other day
									//$today = "11/12/2023";
								?>
							
								
								<?php include ('controllers/cash_remittance_form_inc.php'); ?>

							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			}
			?>
			
			
			<?php
				if (($staff['department']=="Accounts" || $menu['level']=="ce") && date('D') == 'Mon'){		
			?>
			<div class="col-md-4">
				<div class="panel panel-success">
					<div class="panel-heading">
						<h4 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> Sunday Market Cash Remittance</h4>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								
								<?php 
									date_default_timezone_set('Africa/Lagos');
									$yesterday = date('Y-m-d',strtotime("-1 days"));
									list($tiy,$tim,$tid) = explode("-", $yesterday); 
									$yesterday = "$tid/$tim/$tiy";
								?>
							
								
								<?php include ('controllers/cash_remittance_form_inc_sunday.php'); ?>

							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			}
			?>
			
		</div>
		<!-- Row ends here -->
		
		
		
		<!--Row begins here -->
		<div class="row">
			
			<div class="col-md-4">
				<div class="panel panel-danger">
					<div class="panel-heading text-right">
						<h4 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> <?php echo $current_date; ?> Rent Remittance</h4>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<table class="table table-bordered-sm">
									<thead>
										<tr class="info">
											<th>Officer</th>
											<th class="text-info text-right">Rent Remitted</th>
											<th class="text-info text-right">Amount Posted</th>
										</tr>
									</thead>
									
									<?php
										$total_rent_remitted = 0;
										$rent_remitted = 0;

										$total_rent_amount_posted = 0;
										$rent_amount_posted = 0;										
										
										$squery2 = "SELECT user_id, first_name ";
										$squery2 .= "FROM staffs ";
										$squery2 .= "WHERE department = 'Wealth Creation' ";
										$squery2 .= "AND (level = 'leasing officer' AND inputter_status = 'inputter') ";
										$squery2 .= "ORDER BY first_name ASC";
										$sresult2 = mysqli_query($dbcon, $squery2);
																				
										while ($staff2 = mysqli_fetch_array($sresult2, MYSQLI_ASSOC)) {
										$staffid = $staff2["user_id"];

										
										//Rent Remittance
										$suquery = "SELECT *, SUM(no_of_receipts) as total_no_of_receipts, SUM(amount_paid) as rent_paid ";
										$suquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$staffid' AND category='Rent' AND date='$current_date') ";
										$suresult = mysqli_query($dbcon, $suquery);
										
										$rsum = mysqli_fetch_array($suresult, MYSQLI_ASSOC); 
										$no_of_receipts = $rsum['no_of_receipts'];
										$total_no_of_receipts = $rsum['total_no_of_receipts'];
										
										$rent_remitted = $rsum['rent_paid'];
										$total_rent_remitted += $rent_remitted;
										
										
										$ca_query = "SELECT posting_officer_id, date_of_payment, SUM(amount_paid) as rent_amount_posted ";
										$ca_query .= "FROM collection_analysis ";
										$ca_query .= "WHERE (posting_officer_id = '$staffid' AND date_of_payment='$current_date') ";
										$ca_sum = @mysqli_query($dbcon,$ca_query);
										$ca_total = @mysqli_fetch_array($ca_sum, MYSQLI_ASSOC);
										
										$rent_amount_posted = $ca_total['rent_amount_posted'];
										$total_rent_amount_posted += $rent_amount_posted;
									?>

									<tr>
										<th><?php echo ucwords(strtolower($staff2["first_name"])); ?></th>
										<td class="text-right">
											<?php
												$rent_remitted = number_format((float)$rent_remitted, 0);
												echo $rent_remitted.' ('.$total_no_of_receipts.')';
											?>
										</td>
										<td class="text-right">
											<?php
												$rent_amount_posted = number_format((float)$rent_amount_posted, 0);
												echo $rent_amount_posted;
											?>
										</td>
									</tr>
									
									<?php
										
										}
									?>
									<tr>
										<td></td>
										<th class="text-right">
											<?php
												$total_rent_remitted = number_format((float)$total_rent_remitted, 0);
												echo $total_rent_remitted;
											?>
										</th>
										<th class="text-right">
											<?php
												$total_rent_amount_posted = number_format((float)$total_rent_amount_posted, 0);
												echo $total_rent_amount_posted;
											?>
										</th>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			
			
			
			<div class="col-md-4">
				<div class="panel panel-danger">
					<div class="panel-heading text-right">
						<h4 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> <?php echo $current_date; ?> Service Charge Remittance</h4>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<table class="table table-bordered-sm">
									<thead>
										<tr class="info">
											<th>Officer</th>
											<th class="text-info text-right">S/Charge Remitted</th>
											<th class="text-info text-right">Amount Posted</th>
										</tr>
									</thead>
									
									<?php
										$total_sc_remitted = 0;
										$sc_remitted = 0;
										
										$total_sc_amount_posted = 0;
										$sc_amount_posted = 0;

										
										$squery2 = "SELECT user_id, first_name ";
										$squery2 .= "FROM staffs ";
										$squery2 .= "WHERE department = 'Wealth Creation' ";
										$squery2 .= "AND (level = 'leasing officer' AND inputter_status = 'inputter') ";
										$squery2 .= "ORDER BY first_name ASC";
										$sresult2 = mysqli_query($dbcon, $squery2);
																				
										while ($staff2 = mysqli_fetch_array($sresult2, MYSQLI_ASSOC)) {
										$staffid = $staff2["user_id"];
										
										
										//Service Charge Remittance
										$suquery = "SELECT *, SUM(no_of_receipts) as total_no_of_receipts, SUM(amount_paid) as sc_paid ";
										$suquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$staffid' AND category='Service Charge' AND date='$current_date') ";
										$suresult = mysqli_query($dbcon, $suquery);
										
										$rsum = mysqli_fetch_array($suresult, MYSQLI_ASSOC); 
										$no_of_receipts = $rsum['no_of_receipts'];
										$total_no_of_receipts = $rsum['total_no_of_receipts'];
										
										$sc_remitted = $rsum['sc_paid'];
										$total_sc_remitted += $sc_remitted;
										
										
										
										$ca_query = "SELECT posting_officer_id, date_of_payment, SUM(amount_paid) as sc_amount_posted ";
										$ca_query .= "FROM collection_analysis_arena ";
										$ca_query .= "WHERE (posting_officer_id = '$staffid' AND date_of_payment='$current_date') ";
										$ca_sum = @mysqli_query($dbcon,$ca_query);
										$ca_total = @mysqli_fetch_array($ca_sum, MYSQLI_ASSOC);
										
										$sc_amount_posted = $ca_total['sc_amount_posted'];
										$total_sc_amount_posted += $sc_amount_posted;

									?>

									<tr>
										<th><?php echo ucwords(strtolower($staff2["first_name"])); ?></th>
										<td class="text-right">
											<?php
												$sc_remitted = number_format((float)$sc_remitted, 0);
												echo $sc_remitted.' ('.$total_no_of_receipts.')';
											?>
										</td>
										<td class="text-right">
											<?php
												$sc_amount_posted = number_format((float)$sc_amount_posted, 0);
												echo $sc_amount_posted;
											?>
										</td>
									</tr>
									
									<?php			
										}
									?>
									<tr>
										<td></td>
										<th class="text-right">
											<?php
												$total_sc_remitted = number_format((float)$total_sc_remitted, 0);
												echo $total_sc_remitted;
											?>
										</th>
										<th class="text-right">
											<?php
												$total_sc_amount_posted = number_format((float)$total_sc_amount_posted, 0);
												echo $total_sc_amount_posted;
											?>
										</th>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			
			
			<div class="col-md-4">
				<div class="panel panel-danger">
					<div class="panel-heading text-right">
						<h4 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> <?php echo $current_date; ?> Other Collection Remittance</h4>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<table class="table table-bordered-sm">
									<thead>
										<tr class="info">
											<th>Officer</th>
											<th class="text-info text-right">Amount Remitted</th>
											<th class="text-info text-right">Amount Posted</th>
										</tr>
									</thead>
									
									<?php
										$total_other_remitted = 0;
										$other_remitted = 0;

										$total_other_amount_posted = 0;
										$other_amount_posted = 0;										
										
										$squery2 = "SELECT user_id, first_name ";
										$squery2 .= "FROM staffs ";
										$squery2 .= "WHERE department = 'Wealth Creation' ";
										$squery2 .= "AND (level = 'leasing officer' AND inputter_status = 'inputter') ";
										$squery2 .= "ORDER BY first_name ASC";
										$sresult2 = mysqli_query($dbcon, $squery2);
																				
										while ($staff2 = mysqli_fetch_array($sresult2, MYSQLI_ASSOC)) {
										$staffid = $staff2["user_id"];

										
										//Other Remittance
										$oquery = "SELECT *, SUM(no_of_receipts) as total_no_of_receipts, SUM(amount_paid) as other_paid ";
										$oquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$staffid' AND category='Other Collection' AND date='$current_date') ";
										$oresult = mysqli_query($dbcon, $oquery);
										
										$osum = mysqli_fetch_array($oresult, MYSQLI_ASSOC); 
										$no_of_receipts = $osum['no_of_receipts'];
										$total_no_of_receipts = $osum['total_no_of_receipts'];
										
										$other_remitted = $osum['other_paid'];
										$total_other_remitted += $other_remitted;
										
										
										$co_query = "SELECT posting_officer_id, date_of_payment, payment_category, SUM(amount_paid) as other_amount_posted ";
										$co_query .= "FROM account_general_transaction_new ";
										$co_query .= "WHERE (posting_officer_id = '$staffid' AND payment_category='Other Collection' AND date_of_payment='$current_date') ";
										$co_sum = mysqli_query($dbcon,$co_query);
										$co_total = mysqli_fetch_array($co_sum, MYSQLI_ASSOC);
										
										$other_amount_posted = $co_total['other_amount_posted'];
										$total_other_amount_posted += $other_amount_posted;
									?>

									<tr>
										<th><?php echo ucwords(strtolower($staff2["first_name"])); ?></th>
										<td class="text-right">
											<?php
												$other_remitted = number_format((float)$other_remitted, 0);
												echo $other_remitted.' ('.$total_no_of_receipts.')';
											?>
										</td>
										<td class="text-right">
											<?php
												$other_amount_posted = number_format((float)$other_amount_posted, 0);
												echo $other_amount_posted;
											?>
										</td>
									</tr>
									
									<?php
										
										}
									?>
									<tr>
										<td></td>
										<th class="text-right">
											<?php
												$total_other_remitted = number_format((float)$total_other_remitted, 0);
												echo $total_other_remitted;
											?>
										</th>
										<th class="text-right">
											<?php
												$total_other_amount_posted = number_format((float)$total_other_amount_posted, 0);
												echo $total_other_amount_posted;
											?>
										</th>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<!-- Row ends here -->
		
		
		
		<!--Row begins here -->
		<div class="row">
			<div class="col-md-8">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h4 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> <?php echo $current_date; ?> Remitances</h4>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<table class="table table-bordered-sm">
									<thead>
										<tr class="info">
											<th>Action</th>
											<th>Timestamp</th>
											<th>Officer</th>
											<th class="text-info">Category</th>
											<th class="text-info">Amount</th>
											<th class="text-danger">Sub Total</th>
											<th>Details</th>
										</tr>
									</thead>
									
									<?php
										$officer_remittance = 0;
										$total_officer_remittance = 0;
										$raw_officer_remittance = 0;
										
										
										//List of all Remittances
										$squery2 = "SELECT user_id, first_name ";
										$squery2 .= "FROM staffs ";
										$squery2 .= "WHERE department = 'Wealth Creation' ";
										$squery2 .= "AND (level = 'leasing officer' AND inputter_status = 'inputter')";
										$sresult2 = mysqli_query($dbcon, $squery2);
																				
										while ($staff2 = mysqli_fetch_array($sresult2, MYSQLI_ASSOC)) {
										$staffid = $staff2["user_id"];
										$staff_name = $staff2["first_name"];
										
										$suquery3 = "SELECT * FROM cash_remittance WHERE (remitting_officer_id = '$staffid' AND date='$current_date') ";
										$suresult3 = mysqli_query($dbcon, $suquery3);
										
										while ($qremit = mysqli_fetch_array($suresult3, MYSQLI_ASSOC)) {
											$cash_id = $qremit['id'];
											$remit_id = $qremit['remit_id'];
											$remitting_officer_id = $qremit['remitting_officer_id'];
											$amount_remitted = $qremit['amount_paid'];
											
											$officer_remittance += $amount_remitted;
											$raw_officer_remittance = $officer_remittance;
											
											
											$category = $qremit['category'];
											$remittance_date = $qremit['date'];
											$no_of_receipts = $qremit['no_of_receipts'];
											$posting_officer_name = $qremit['posting_officer_name'];
											$remitting_time = $qremit['remitting_time'];
									?>

									<tr>
										<td>
											
											<?php
												if ($staff['department']=="Accounts" || $menu['level']=="ce"){	
													//echo '<a href="edit_remittance.php?edit_id='.$cash_id.'" title="Edit"><img src="images/edit.png" /></a> - ';
													
												
													$query = "SELECT * FROM account_general_transaction_new WHERE (posting_officer_id = '$staffid' AND date_of_payment='$current_date' AND payment_category='$category' AND remit_id='$remit_id')";
													$result = mysqli_query($dbcon,$query);
													$del_count = mysqli_num_rows($result);
													
													
													$uquery = "SELECT * FROM unposted_transactions WHERE (posting_officer_id = '$staffid' AND date_of_payment='$current_date' AND category='$category' AND remit_id='$remit_id')";
													$uresult = mysqli_query($dbcon,$uquery);
													$unposted_count = mysqli_num_rows($uresult);
													
													if($del_count==0 && $unposted_count == 0){
														echo '<a href="javascript:cash_delete_id('.'\''.$cash_id.'\''.')" title="Delete"><img src="images/delete.png" /></a>';
													}
												}
											?>
										</td>
										<td><?php echo $remitting_time; ?></td>
										<th><?php echo $staff_name; ?></th>
										<td><?php echo $category; ?></td>
										<th>
											<?php
												$amount_remitted = number_format((float)$amount_remitted, 0);
												echo $amount_remitted.' ('.$no_of_receipts.')';
											?>
										</th>
										<th class="danger"></th>
										<td><?php echo 'Posted by: '.$posting_officer_name; ?><td>
									</tr>
									
									<?php
										}
									?>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<th class="text-right"></th>
										<td></td>
										<th class="danger">
											<?php
												$officer_remittance = number_format((float)$officer_remittance, 0);
												echo $officer_remittance; 
											?>
										</th>
										<td></td>
									</tr>
									<tr class="info">
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<th class="text-right"></th>
										<th class="info"></th>
										<td></td>
									</tr>
										
									<?php
										$total_officer_remittance += $raw_officer_remittance;
										$officer_remittance = 0;
										$raw_officer_remittance = 0;
										}
									?>
									
									<tr class="info">
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<th class="text-right">Overall Total</th>
										<th class="danger">
											<?php
												$total_officer_remittance = number_format((float)$total_officer_remittance, 0);
												echo $total_officer_remittance; 
											?>
										</th>
										<td></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<!-- Row ends here -->
		
	</div>
</div>
</body>

</html>
<script type="text/javascript">
$('#amount_paid, #confirm_amount_paid').on('keyup', function () {
  if ($('#amount_paid').val() == $('#confirm_amount_paid').val()) {
    $('#message').html('Confirmed!').css('color', 'green');
  } else 
    $('#message').html('Amount mismatch!').css('color', 'red');
});
</script>