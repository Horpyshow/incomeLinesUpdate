<?php
include 'include/session.php';
include ('include/functions.php');

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$sub_prefix = "";
$sub_suffix = "_wrl";
$page_title = "{$project} - Flagged Transactions - Wealth Creation ERP";

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
		alert('You do not have permissions to view this page! Contact your HOD for authorization. Thanks');
		window.location.href='../../index.php';
	</script>
<?php
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
function cdetails_id(id)
{
	if(confirm)
	{
		window.location.href='../leasing/customer_details.php?cdetails_id='+id;
	}
}
</script>

<?php include ('fc_js_script'.$suffix.'.php'); ?>

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
	<div class="page-header">
		<h2><strong><?php echo $project; ?> Flagged Transactions</strong></h2>
		
		<?php include ('include/acct_header'.$suffix.'.php'); ?>
	</div>
	
		<div class="row">
		<div id="stats">
		<div class="table-responsive">
			  <table class="table table-hover">
				<thead>
				<tr class="info text-info">
					<th colspan="2" width="5%">ACTION</th>
					<th class="danger" width="3%">S/N</th>
					<th>DETAILS</th>
					<th width="8%">DATE</th>
					<th width="10%">SHOP NO</th>
					<th width="28%">TRANSACTION DESCRIPTION</th>
					<th width="10%">AMOUNT</th>
					<th width="8%">RECEIPT NO</th>
					<th class="text-center" width="20%">FLAG REPORT</th>
					<th class="text-center danger" width="10%">STATUS</th>
				</tr>
				</thead>
					 <?php
					 $caamount_paid = "";
					 $capayment_month = "";
					 
					 if (isset($_GET['s'])) {
							$i = $_GET['s'];
						} else {
							$i=0;
						} 
					 
					 $query = "SELECT * ";
					 $query .= "FROM {$prefix}account_flagged_record ";
					 $query .= "ORDER BY flag_time DESC ";
					 $post_all_set = @mysqli_query($dbcon,$query);
					 $post_no = @mysqli_num_rows($post_all_set);
					 
					
						while ($post = @mysqli_fetch_array($post_all_set, MYSQLI_ASSOC)) {
					
						 $post_shop_no = $post['shop_no'] ;
						 $amount = $post['amount_paid'];
						 $amount_paid = number_format((float)$amount, 0);
						 $posting_time = $post['posting_time'];
						 $leasing_approval_time = $post['leasing_post_approval_time'];
						 $approval_time = $post['approval_time'];
						 $verification_time = $post['verification_time'];
						 $receipt_no = $post["receipt_no"];
						 $date_of_payment = $post["date_of_payment"];
					?>
					<?php
						date_default_timezone_set('Africa/Lagos');
						$now = date('Y-m-d H:i:s');
						
						$txref = $post["id"];
						if(isset($_SESSION['staff']) ) {
							$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['staff'];
						}
						if(isset($_SESSION['admin']) ) {
							$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['admin'];
						}
						$role_data = mysqli_query($dbcon, $query);
						$role_user = mysqli_fetch_array($role_data, MYSQLI_ASSOC);
						
						$fc_id = $role_user['user_id'];
						$fc_name = $role_user['full_name'];

					?>
					<?php
					 $query = "SELECT * ";
					 $query .= "FROM customers ";
					 $query .= "WHERE shop_no = '$post_shop_no'";
					 $result = mysqli_query($dbcon, $query);
					 $shop = mysqli_fetch_array($result, MYSQLI_ASSOC);
					
						$shop_id = $shop["id"];
						$txref = $post["id"];
					?>
					
					<tr>
						<td colspan="2">
							<?php include '../../include/edit_rights.php'; ?>
						</td>
						
						<th class="danger"><?php echo ++$i.'.'; ?></th>
						<td>
							<a href="javascript:txdetails('<?php echo $txref; ?>')" class="btn btn-primary btn-xs" title="View details of <?php echo ucwords(strtolower($shop["customer_name"])).', '.$post["shop_no"]; ?> payment record">Details</a>
						</td>
						
						<td><?php 
								list($tid,$tim,$tiy) = explode("-",$post["date_of_payment"]);
								$date_of_payment = "$tiy/$tim/$tid";
								echo $date_of_payment; 
							?>
						</td>
						
						<th>
							<?php 
								
								if ($post["shop_no"]=="") {
									echo "";
								} else {
								echo 
									'<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-primary btn-sm" title="Get more information about Shop '.$post["shop_no"].', '.$shop["customer_name"].'">'.$post["shop_no"].'</a>';
								}
							?>
						</th> 

						<td>
							<?php
								$plate_no = $post["plate_no"];
								if ($plate_no == "") {
									echo strtoupper(strtolower($post["transaction_desc"]));
								} else {
									echo strtoupper($plate_no).' - '.strtoupper(strtolower($post["transaction_desc"]));
								}
							?>
						</td>
						
						<td><?php echo "&#8358 {$amount_paid}"; ?></td>
						
						<td class="text-center"><?php echo $post["receipt_no"]; ?></td>
						
						
						<td rowspan="5" class="warning">
							<p><strong><?php echo $post['comment']; ?></strong></p>
							<p>&nbsp;</p>
							<p><?php echo '<a href="#" class="btn btn-danger btn-xs">Flagged '.time_elapsed_string($post["flag_time"]).'</a></br>'.$post["flag_time"]; ?></p>
							<strong><span style="color:#ec7063;">Flagged By:</span> </br><a href="#"><?php echo $post["flag_officer_name"]; ?></a></strong>
							
						</td>						

						<td rowspan="5">
							<?php
								 $query = "SELECT * ";
								 $query .= "FROM {$prefix}account_general_transaction_new ";
								 $query .= "WHERE id = '$txref'";
								 $result = mysqli_query($dbcon, $query);
								 $gpost = mysqli_fetch_array($result, MYSQLI_ASSOC);
								 
								 
								 $g_approval_status = $gpost['approval_status'];
								 $g_approval_time = $gpost['approval_time'];
								
									
							?>
							<?php
							if ($menu['level'] == "fc"){
								if (($gpost["approval_status"] == "Approved") && ($gpost['flag_status'] == "Flagged")) {
									echo '<a class="btn btn-xs btn-danger" id="decline_record" data-id="'.$txref.'" href="javascript:void(0)">Decline</a>';
								} elseif (($gpost["approval_status"] == "Declined") && ($gpost['verification_status'] == "Flagged")) {
									echo '<a href="#" class="btn btn-xs btn-warning">FLAGGED</a>';
								} elseif ($post["flag_status"] == "Resolution Confirmed") {
									if ($g_approval_status == "Approved") {
										echo '<p><a href="#" class="btn btn-success btn-xs">Resolved: '.time_elapsed_string($g_approval_time).'</a></br>'.$g_approval_time.'</p>
										
										<span style="color:#ec7063;"><strong>Confirmed by:</strong></span> </br><a href="#"><strong>'.$post["confirm_officer_name"].'</strong></a>';
									}
								} else {}
							} elseif ($post["flag_status"] == "Resolution Confirmed") {
								if ($g_approval_status == "Approved") {
									echo '<p><a href="#" class="btn btn-success btn-xs">Resolved: '.time_elapsed_string($g_approval_time).'</a></br>'.$g_approval_time.'</p>
									
									<span style="color:#ec7063;"><strong>Confirmed by:</strong></span> </br><a href="#"><strong>'.$post["confirm_officer_name"].'</strong></a>';
								}
							} else {}
							?>
						</td>
					</tr>
					
					
					<tr>
						<td colspan="2"></td>
						<td class="danger"></td>
						<td colspan="6"><strong>Payment Break Down:</strong> 
							<?php
								$caquery = "SELECT * ";
								
								if($post["payment_category"] == "Rent") {
									$caquery .= "FROM collection_analysis ";
								} else {
									$caquery .= "FROM collection_analysis_arena ";
								}
								
								$caquery .= "WHERE shop_id = '$shop_id' ";
								 $caquery .= "AND receipt_no = '$receipt_no'";
								 $caresult = mysqli_query($dbcon, $caquery);
								 while ($cashop = mysqli_fetch_array($caresult, MYSQLI_ASSOC)){
									 $capayment_month = $cashop["payment_month"];
									 $caamount_paid = $cashop["amount_paid"];
									 $caamount_paid = number_format((float)$caamount_paid, 0);
									 
									 
									 //if (($post["leasing_post_status"] != "Pending") && ($post["leasing_post_status"] != "Declined")) {
										echo ' <span style="color:#ec7063;">'.$capayment_month.'</span>'; echo ": &#8358 {$caamount_paid}"; echo ' |';
									 //}
								 }
							?>
						</td>
					</tr>
					
					<?php
						$debit_account = $post["debit_account"];
						$credit_account = $post["credit_account"];
						$posting_officer_id = $post["posting_officer_id"];
						$leasing_post_approving_officer_id = $post["leasing_post_approving_officer_id"];
						$leasing_post_approving_officer_name = $post["leasing_post_approving_officer_name"];
						$post_acct_staff = $post["posting_officer_name"];
						
						
						$query_acct1 = "SELECT * ";
						$query_acct1 .= "FROM {$prefix}accounts ";
						$query_acct1 .= "WHERE acct_id = $debit_account";
						$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
						$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
						
						$db_debit_desc = $acct_debit_table["acct_desc"];
						
						$query_acct2 = "SELECT * ";
						$query_acct2 .= "FROM {$prefix}accounts ";
						$query_acct2 .= "WHERE acct_id = $credit_account";
						$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
						$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
						
						$db_credit_desc = $acct_credit_table["acct_desc"];
					?>

					<?php
						$start_date = $post["start_date"];
						$end_date = $post["end_date"];

						@list($tiy,$tim,$tid) = explode("-",@$shop["lease_start_date"]);
						$lease_start_date = "$tid/$tim/$tiy";

						@list($tiy,$tim,$tid) = explode("-",@$shop["lease_end_date"]);
						$lease_end_date = "$tid/$tim/$tiy";

						@list($tiy,$tim,$tid) = explode("-",@$shop["service_charge_start_date"]);
						$service_charge_start_date = "$tid/$tim/$tiy";

						@list($tiy,$tim,$tid) = explode("-",@$shop["service_charge_end_date"]);
						$service_charge_end_date = "$tid/$tim/$tiy";

					if($post["shop_no"] != ""){	
						echo '
						<tr>
							<td colspan="2"></td>
							<td class="danger"></td>
							<td colspan="6">';
								echo '<strong>Tenure Paid for:</strong> '.$start_date.' to '.$end_date.' | <strong>Current Tenancy Duration:</strong> '.$lease_start_date.' to '.$lease_end_date;
							echo '
							</td>
						</tr>';
						}
					?>

					<tr>
						<td colspan="2"></td>
						<td class="danger"></td>
						<td colspan="3"><span style="color:#ec7063;"><strong>Debit:</strong></span> <?php echo $db_debit_desc; ?> |</br> <span style="color:#ec7063;"><strong>Credit:</strong></span> <?php echo $db_credit_desc; ?></td>
						<td colspan="3">
							
						</td>
						<td></td>
					</tr>


					<?php
						include ("controllers/journal_accts_inc.php");
						
						if(@$post["entry_status"] == "Journal"){	
						echo '
						<tr>
							<td colspan="2"></td>
							<td class="danger"></td>
							<td colspan="6" class="danger">';
								if($post["debit_account_jrn2"] == ""){	
									echo '
										<strong>Multi Credit Journal Entry:</strong> '.$db_credit_acct_desc2.' | '.$db_credit_acct_desc3.' | '.$db_credit_acct_desc4.' | '.$db_credit_acct_desc5.' | '.$db_credit_acct_desc6.' | '.$db_credit_acct_desc7;
								} else {
									echo '
										<strong>Multi Debit Journal Entry:</strong>: '.$db_debit_acct_desc2.' | '.$db_debit_acct_desc3.' | '.$db_debit_acct_desc4.' | '.$db_debit_acct_desc5.' | '.$db_debit_acct_desc6.' | '.$db_debit_acct_desc7;
								}	
							echo
							'</td>
						</tr>';
						}
					?>
					
					
					
					
					<tr>
						<td colspan="2"></td>
						<td class="danger"></td>
						<td colspan="6">
							<div class="col-sm-12">
								<div class="col-sm-3">
									<p><?php echo '<a href="#" class="btn btn-success btn-xs">Posted '.time_elapsed_string($posting_time).'</a></br>'.$posting_time; ?></p>
									
									<?php echo '<span style="color:#ec7063;"><strong>Posted by:</strong></span> </br><a href="#"><strong>'.$post_acct_staff.'</strong></a>'; ?>
								</div>
								
								<?php
								if ($leasing_approval_time != "0000-00-00 00:00:00") {
									echo '
									<div class="col-sm-3">
										<p><a href="#" class="btn btn-success btn-xs">Reviewed: '.time_elapsed_string($leasing_approval_time).'</a></br>'.$leasing_approval_time.'</p>
										
										<span style="color:#ec7063;"><strong>Reviewed by:</strong></span> </br><a href="#"><strong>'.$leasing_post_approving_officer_name.'</strong></a>
									</div>';
								}
								?>
								
								<div class="col-sm-3">
									<p><?php echo '<a href="#" class="btn btn-success btn-xs">Approved: '.time_elapsed_string($approval_time).'</a></br>'.$approval_time; ?></p>
									
									<?php echo '<span style="color:#ec7063;"><strong>Approved by:</strong></span> </br><a href="#"><strong>'.$post["approving_acct_officer_name"].'</strong></a>'; ?>
								</div>
								
								<?php
								if ($verification_time != "0000-00-00 00:00:00") {
									echo '
									<div class="col-sm-3">
										<p><a href="#" class="btn btn-success btn-xs">Verified: '.time_elapsed_string($verification_time).'</a></br>'.$verification_time.'</p>
										
										<span style="color:#ec7063;"><strong>Verified by:</strong></span> </br><a href="#"><strong>'.$post["verifying_auditor_name"].'</strong></a>
									</div>';
								}
								?>
							</div>
						</td>
					</tr>
					

					<tr>
						<td colspan="11" class="info"></td>
					</tr>

					<?php	 
					 }
					?>
			  </table>
			  </div>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
function txref(id)
{
	if(confirm)
	{
		window.location.href='review_trans<?php echo $suffix; ?>.php?txref='+id;
	}
}
function txdetails(id)
{
	if(confirm)
	{
		window.location.href='transaction_details<?php echo $suffix; ?>.php?txref='+id;
	}
}
$('#stats').ready 
	(function statRefresh() {
    var $statboard = $("#stats");
	setInterval(function statRefresh() {
    $statboard.load("flagged_record<?php echo $suffix; ?>.php #stats");
	}, 600000);
})
</script>
</html>