<?php
include 'include/session.php';
include ('include/functions.php');

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
		alert('You do not have permissions to view this page! Contact your HOD for authorization. Thanks');
		window.location.href='../../index.php';
	</script>
<?php
}
?>
<?php include ("controllers/acct_delete_query.php"); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Woobs Resources ERP</title>
		<meta name="description" content="Woobs Resources ERP Management System">
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../style.css" type="text/css" />
		<script src="../../js/jquery.js"></script>
		<script src="../../js/bootstrap.js"></script>
		<script src="../../js/sub_menu.js"></script>
		<script src="../../js/js_mul_script.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">

<script type="text/javascript">
function edit_id(id)
{
	if(confirm('Are you sure you want to EDIT this transaction record?'))
	{
		window.location.href='edit_posting.php?edit_id='+id;
	}
}
function delete_id(id)
{
	if(confirm('Are you sure you want to COMPLETELY DELETE details?'))
	{
		window.location.href='acct_view_trans.php?delete_id='+id;
	}
}
function cdetails_id(id)
{
	if(confirm)
	{
		window.location.href='../leasing/customer_details.php?cdetails_id='+id;
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
	<div class="col-md-12">
	<div class="page-header">
	<div class="row">
			<div class="col-md-9">
			<?php	
				if(isset($_SESSION['staff']) ) {
				$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['staff'];
				}
				if(isset($_SESSION['admin']) ) {
				$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['admin'];
				}
				$staffresult = mysqli_query($dbcon, $staffquery);
				$session_staff = mysqli_fetch_array($staffresult, MYSQLI_ASSOC);
				
				if (isset($_GET['staff_id'])) {
					$session_id = $_GET['staff_id'];
					
					$lsquery = "SELECT * FROM staffs WHERE user_id='$session_id'";
					$lsresult = mysqli_query($dbcon, $lsquery);
					$ls_staff = mysqli_fetch_array($lsresult, MYSQLI_ASSOC);
				} 
				elseif (!isset($_GET['staff_id'])) {
					$session_id = $session_staff['user_id'];
				} else {
					header("Location: ../account/view_trans.php");
				} 
			?>
			<h2><strong><?php 
						if (isset($_GET['staff_id'])) {
							echo $ls_staff['full_name'].'\'s';
						}
						?></strong> Wealth Creation Account Till 
				<?php

					$till_query = "SELECT SUM(amount_paid) as amount_posted ";
					$till_query .= "FROM account_general_transaction_new ";
					$till_query .= "WHERE posting_officer_id = '$session_id' ";
					$till_query .= "AND approval_status = 'Pending'";
					$sum = @mysqli_query($dbcon,$till_query);
					$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);
					
					$till = $total['amount_posted'];
					
					$declined_query = "SELECT SUM(amount_paid) as amount_posted ";
					$declined_query .= "FROM account_general_transaction_new ";
					$declined_query .= "WHERE posting_officer_id = '$session_id' ";
					$declined_query .= "AND approval_status = 'Declined'";
					$dsum = @mysqli_query($dbcon,$declined_query);
					$dtotal = @mysqli_fetch_array($dsum, MYSQLI_ASSOC);
					
					$till_declined = $dtotal['amount_posted'];
					
					$total_till = ($till + $till_declined);
					$total_till = number_format((float)$total_till, 2);
					echo '<a href="#" class="btn btn-danger"> &#8358 '.$total_till.' </a>';
				?>
			</h2>
			</div>
			<div class="col-md-3">
				<div class="text-center">	
					<?php
						if (isset($_GET['staff_id'])) {
							$d1 = date('d/m/Y');
							$d2 = $d1;
							
							$staff_id = $_GET['staff_id'];
							echo '<a href="../leasing/trans_analysis.php?d1='.$d1.'&d2='.$d2.'&staff_id='.$staff_id.'" class="btn btn-primary">View Transactions Analysis</a>';
						} else {
							echo '<a href="../leasing/trans_analysis.php" class="btn btn-primary">View Transactions Analysis</a>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
<!-- Declined Transactions Table Begin Here -->
		<div class="row">
		<div class="table-responsive">
			  <table class="table table-hover">
				<thead>
				<tr>
					<th colspan="12"><h3><strong>Declined Transactions</strong></h3></th>
				</tr>
				</thead>
				<thead>
				<tr class="info text-info">
					<th colspan="2">ACTION</th>
					<th class="danger">S/N</th>
					<th class="text-center">TRANS</br>DETAILS</th>
					<th class="text-center">PAYMENT</br>DATE</th>
					<th class="text-center">SPACE NO</th>
					<th class="text-center">TRANSACTION</br>DESCRIPTION</th>
					<th class="text-center">CHEQUE/</br>TELLER NO</th>
					<th>AMOUNT</th>
					<th class="text-center">RECEIPT NO</th>
					<th class="text-center danger">FC</br>APPROVAL</th>
					<th class="text-center danger">AUDIT</br>STATUS</th>
				</tr>
				</thead>		
					 <?php
					 $i = 0;
					 $staffid = $staff['user_id'];
					 
					 $query = "SELECT * ";
					 $query .= "FROM account_general_transaction_new ";
					 $query .= "WHERE posting_officer_id= '$session_id' ";
					 $query .= "AND (approval_status = 'Declined' OR verification_status = 'Declined') ";
					 $query .= "ORDER BY approval_time DESC";
					 $post_set = mysqli_query($dbcon,$query);
					 $post_no = mysqli_num_rows($post_set);
					 
					 if (!$post_set) {
						 die ("Database query actually failed");
					 }

					 while ($post = mysqli_fetch_array($post_set, MYSQLI_ASSOC)) {
						 
						 $post_shop_no = $post['shop_no'] ;
						 $amount = $post['amount_paid'];
						 $amount_paid = number_format((float)$amount, 2);
						 $posting_time = $post['posting_time'];
						 $leasing_approval_time = $post['leasing_post_approval_time'];
						 $approval_time = $post['approval_time'];
						 $date_of_payment = $post['date_of_payment'];
					?>
					<?php
					 $query = "SELECT * ";
					 $query .= "FROM customers ";
					 $query .= "WHERE shop_no = '$post_shop_no'";
					 $result = mysqli_query($dbcon, $query);
					 $shop = mysqli_fetch_array($result, MYSQLI_ASSOC);
					?>
					<?php
						$shop_id = $shop["id"];
						$txref = $post["id"];
					?>
					<tr>
						<td colspan="2">
							<?php include '../../include/edit_rights.php'; ?>
						</td>
						<th class="danger"><?php echo ++$i.'.'; ?></th>
						<td>
							<a href="javascript:txdetails('<?php echo $txref; ?>')" class="btn btn-primary btn-sm" title="View details of <?php echo ucwords(strtolower($shop["customer_name"])).', '.$post["shop_no"]; ?> payment record">Details</a>
						</td>
						<td class="text-center"><?php echo $post["date_of_payment"]; ?></td>
						<th class="text-center">
							<?php 
								if ($post["shop_no"]=="") {
									echo "";
								} else {
								echo 
									'<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-danger btn-sm" title="Get more information about Shop '.$post["shop_no"].', '.$shop["customer_name"].'">'.$post["shop_no"].'</a>';
								}
							?>
						</th> 
						<td><?php echo ucwords(strtolower($post["transaction_desc"])); ?></td>
						<td class="text-center">
							<?php 
								if ($post["cheque_no"]=="") {
									echo $post["teller_no"];
								} else {
									echo $post["cheque_no"];
								} 
							?>
						</td>
						<td><?php echo "&#8358 {$amount_paid}"; ?></td>
						<td class="text-center"><?php echo $post["receipt_no"]; ?></td>
						<td>
							<?php
								if ($post["approval_status"] == "Approved") {
									echo '<a href="#" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span> Approved</a>';	
								} elseif ($post["approval_status"] == "Declined") {
									echo '<a href="#" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Declined '.time_elapsed_string($approval_time).'</a>';	
								} elseif ($post["approval_status"] == "Pending") {
									echo '<a href="#" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-stop"></span> Pending '.time_elapsed_string($posting_time).'</a>';
								} else {
									echo '';
								}
							?>
						</td>
						<td>
							<?php
								if ($post["verification_status"] == "Declined") {
									echo '<a href="#" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Declined '.time_elapsed_string($approval_time).'</a>';	
								} elseif ($post["verification_status"] == "Pending") {
									echo '<a href="#" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-stop"></span> Pending '.time_elapsed_string($posting_time).'</a>';
								} else {
									echo '';
								}
							?>
						</td>
					<?php	 
					 }
					?>
					</tr>
			  </table>
			</div>
		</div>

		<div class="row">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th colspan="11"><h3><strong>Pending Transactions</strong></h3></th>
					</tr>
				</thead>
				<thead>
				<tr class="info text-info">
					<th colspan="2">ACTION</th>
					<th class="danger">S/N</th>
					<th class="text-center">TRANS</br>DETAILS</th>
					<th class="text-center">PAYMENT</br>DATE</th>
					<th class="text-center">SPACE NO</th>
					<th class="text-center">TRANSACTION</br>DESCRIPTION</th>
					<th class="text-center">CHEQUE/</br>TELLER NO</th>
					<th>AMOUNT</th>
					<th class="text-center">RECEIPT NO</th>
					<th class="text-center danger">FC</br>APPROVAL</th>
				</tr>
				</thead>
					 <?php
					 $i = 0;
					 $staffid = $staff['user_id'];
					 
					 $query = "SELECT * ";
					 $query .= "FROM account_general_transaction_new ";
					 $query .= "WHERE posting_officer_id= '$session_id' ";
					 $query .= "AND approval_status = 'Pending' ";
					 $query .= "ORDER BY posting_time DESC ";
					 $post_set = mysqli_query($dbcon,$query);
					 $post_no = mysqli_num_rows($post_set);
					 
					 if (!$post_set) {
						 die ("Database query actually failed");
					 }

					 while ($post = mysqli_fetch_array($post_set, MYSQLI_ASSOC)) {
						 
						 $post_shop_no = $post['shop_no'] ;
						 $amount = $post['amount_paid'];
						 $amount_paid = number_format((float)$amount, 2);
						 $posting_time2 = $post['posting_time'];
						 $leasing_approval_time2 = $post['leasing_post_approval_time'];
						 $approval_time2 = $post['approval_time'];
						 $date_of_payment = $post['date_of_payment'];
					?>
					<?php
					 $query = "SELECT * ";
					 $query .= "FROM customers ";
					 $query .= "WHERE shop_no = '$post_shop_no'";
					 $result = mysqli_query($dbcon, $query);
					 $shop = mysqli_fetch_array($result, MYSQLI_ASSOC);
					?>
					<?php
						$shop_id = $shop["id"];
						$txref = $post["id"];
					?>
					<tr>
						<td colspan="2">
							<?php include '../../include/edit_rights.php'; ?>
						</td>
						<th class="danger"><?php echo ++$i.'.'; ?></th>
						<td>
							<a href="javascript:txdetails('<?php echo $txref; ?>')" class="btn btn-primary btn-sm" title="View details of <?php echo ucwords(strtolower($shop["customer_name"])).', '.$post["shop_no"]; ?> payment record">Details</a>
						</td>
						<td class="text-center"><?php echo $post["date_of_payment"]; ?></td>
						<th class="text-center">
							<?php 
								if ($post["shop_no"]=="") {
									echo "";
								} else {
								echo 
									'<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-danger btn-sm" title="Get more information about Shop '.$post["shop_no"].', '.$shop["customer_name"].'">'.$post["shop_no"].'</a>';
								}
							?>
						</th> 
						<td><?php echo ucwords(strtolower($post["transaction_desc"])); ?></td>
						<td class="text-center">
							<?php 
								if ($post["cheque_no"]=="") {
									echo $post["teller_no"];
								} else {
									echo $post["cheque_no"];
								} 
							?>
						</td>
						<td><?php echo "&#8358 {$amount_paid}"; ?></td>
						<td class="text-center"><?php echo $post["receipt_no"]; ?></td>
						<td>
							<?php
								if ($post["approval_status"] == "Approved") {
									echo '<a href="#" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span> Approved</a>';	
								} elseif ($post["approval_status"] == "Declined") {
									echo '<a href="#" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Declined '.time_elapsed_string($approval_time2).'</a>';	
								} elseif ($post["approval_status"] == "Pending") {
									echo '<a href="#" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-stop"></span> Pending '.time_elapsed_string($posting_time2).'</a>';
								} else {
									echo '';
								}
							?> 
						</td>
					<?php	 
					 }
					?>
					</tr>
			  </table>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
function txdetails(id)
{
	if(confirm)
	{
		window.location.href='transaction_details.php?txref='+id;
	}
}
</script>
</html>