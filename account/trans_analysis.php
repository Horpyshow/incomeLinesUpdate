<?php 
include_once ('include/session.php');

if (isset($_GET['delete_id']))
{
	$squery = "SELECT * FROM account_general_transaction_new WHERE id=".$_GET['delete_id'];
	$sresult = mysqli_query($dbcon, $squery);
	$staff_details = mysqli_fetch_array($sresult, MYSQLI_ASSOC);
	$staff_id = $staff_details['posting_officer_id'];
	$active_date = $staff_details['date_of_payment'];
	
	$query="DELETE FROM account_general_transaction_new WHERE id=".$_GET['delete_id'];
	$result = mysqli_query($dbcon, $query);
	//header("Location: $_SERVER[PHP_SELF]");
	
	$query="DELETE FROM account_till WHERE id=".$_GET['delete_id'];
	$result = mysqli_query($dbcon, $query);
	//header("Location: $_SERVER[PHP_SELF]");
	
	$query="DELETE FROM account_ledger_turnover WHERE id=".$_GET['delete_id'];
	$result = mysqli_query($dbcon, $query);
	header("Location: trans_analysis.php?staff_id=$staff_id&date=$active_date");
}

$i = 0;

if (isset($_GET['staff_id'])) {
	$staff_id = $_GET['staff_id'];
	
	$staffquery = "SELECT * FROM staffs WHERE user_id='$staff_id'";
	$staffresult = mysqli_query($dbcon, $staffquery);
	$session_staff = mysqli_fetch_array($staffresult, MYSQLI_ASSOC);
	$session_id = $session_staff['user_id'];
	$staff_name = $session_staff['full_name'];
} else {
	$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['staff'];
	$staffresult = mysqli_query($dbcon, $staffquery);
	$session_staff = mysqli_fetch_array($staffresult, MYSQLI_ASSOC);
	$session_id = $session_staff['user_id'];
	$staff_name = $session_staff['full_name'];
}	

if (isset($_POST['btn_view_analysis'])) {
	$date_of_payment = @$_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_collection = "$tiy-$tim-$tid";
} elseif (isset($_GET['date'])) {
	$date_of_payment = @$_GET['date'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_collection = "$tiy-$tim-$tid";
} else {
	$current_date = date('d-m-Y');
	$date_of_payment = $current_date;
	$date_of_collection = $current_date;
}
	
	$query = "SELECT * ";
	$query .= "FROM account_general_transaction_new ";
	$query .= "WHERE posting_officer_id= '$session_id' ";
	$query .= "AND date_of_payment = '$date_of_collection'";
	$query .= "ORDER BY shop_no ASC";
	$post_set = mysqli_query($dbcon,$query);
	$post_no = mysqli_num_rows($post_set);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Collection Analysis | Wealth Creation</title>
	<meta name="description" content="Woobs Resources ERP Management System">
	<meta name="author" content="Woobs Resources Ltd | Rent Collection Analysis">
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
</head>

<body>
	<?php include ('include/staff_navbar.php');
			
			$vp_user_id = $menu["user_id"];
			$vp_staff_name = $menu["full_name"];
			$sessionID = session_id();
			
			$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$url = htmlspecialchars(strip_tags($url));
			
			date_default_timezone_set('Africa/Lagos');
			$now = date('Y-m-d H:i:s');
			
			$vp_query = "INSERT IGNORE INTO visited_pages (id, user_id, staff_name, session_id, uri, time) VALUES ('', '$vp_user_id', '$vp_staff_name', '$sessionID', '$url', '$now')";
			$vp_result = mysqli_query($dbcon,$vp_query); ?>
	<div class="jumbotron"></div>
	<div class="container">
		<div>
			<table class="table table-bordered">
				<tr>
					<td colspan="6">
						<div class="container">
							<div class="row">
								<div class="col-md-5"><span style="font-size:30px;"> <strong>Wealth Creation</strong></span></div>
								
								<div align="right" class="col-md-7">
									<h3><strong>Collection Analysis</strong></h3>
								</div>
							</div>
						</div>	
					</td>
				</tr>
			</table>
			<form  method="post" id="form" class="form-horizontal" action="<?php 
				if (isset($_GET['staff_id'])) {
						$staff_id = $_GET['staff_id'];
						echo 'trans_analysis.php?staff_id='.$staff_id;
				} else {
					echo htmlspecialchars($_SERVER['PHP_SELF']);
				} ?>" >
				<table class="table">
					<thead>
						<tr class="success">
							<th colspan="7"><h3>Account Officer: <strong><?php echo $staff_name; ?></strong></h3></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>
								<div class="form-group form-group-xl">
									<div class="col-md-5 date">
										<div class="input-group input-append date" id="date_of_payment">
											<input type="text" class="form-control input-xl" name="date_of_payment" placeholder="Date of Collection" value="<?php if (isset($_POST['date_of_payment'])) echo @$date_of_payment; ?>" />
											<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										</div>
									</div>
									<button type="submit" name="btn_view_analysis" class="btn btn-danger">View <span class="glyphicon glyphicon-send"></span></button>
								</div>
							</th>
							<th colspan="3"></th>
							<td align="right">
								<h4>Date of Collecion: <strong><?php echo @$date_of_payment; ?></strong></h4>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<table class="table table-bordered">
				<thead>
					<tr class="info text-info">
						<th>S/N</th>
						<th class="text-center">TENURE PAID FOR</th>
						<th>CUSTOMER NAME</th>
						<th class="text-center">SHOP NO</th>
						<th class="text-center">NO OF TICKETS</th>
						<th>TRANSACTION DESCRIPTION</th>
						<th class="text-center">RECEIPT NO.</th>
						<th class="text-right">AMOUNT</th>
						<?php
							if ($menu['department']=="Accounts") {
								echo '
								<th>STATUS</th>
								<th>ACTION</th>';
							}
						?>
						<?php
							if ($menu['department']=="Leasing") {
								echo '
								<th>STATUS</th>';
							}
						?>
					</tr>
				</thead>
				<?php
					 while (@$post = mysqli_fetch_array($post_set, MYSQLI_ASSOC)) {
						 
						 $post_shop_no = $post['shop_no'] ;
						 $amount = $post['amount_paid'];
						 $amount_paid = number_format((float)$amount, 2);
						 
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
						<td><?php echo ++$i.'.'; ?></td>
						<td class="text-center"><?php echo $post["start_date"].' </strong>-</strong> '.$post["end_date"]; ?></td>
						<th><?php echo $post["customer_name"]; ?></th>
						<th class="text-center"><?php echo $post["shop_no"]; ?></th>
						<th class="text-center"><?php echo $post["no_of_tickets"]; ?></th>
						<td><?php echo ucwords(strtolower($post["transaction_desc"])); ?></td>
						<td class="text-center"><?php echo $post["receipt_no"]; ?></td>
						<td class="text-right"><?php  echo "&#8358 {$amount_paid}"; ?></td>
						<?php
							if ($menu['department']=="Accounts" && $post['leasing_post_status']=="Approved") {
								echo '
								<td><a href="#" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span> Approved</a></td>
								<td class="text-center"><a href="javascript:delete_id('.'\''.$txref.'\''.')"><img src="images/delete.png" alt="Delete" title="Delete record completely" /></a></td>';
							} elseif ($menu['department']=="Accounts" && $post['leasing_post_status']=="Pending") {
								echo '
								<td><a href="#" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-stop"></span> Pending</a></td>
								<td class="text-center"><a href="javascript:delete_id('.'\''.$txref.'\''.')"><img src="images/delete.png" alt="Delete" title="Delete record completely" /></a></td>';
							} elseif ($menu['department']=="Accounts" && $post['leasing_post_status']=="Declined") {
								echo '
								<td><a href="#" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Declined</a></td>
								<td class="text-center"><a href="javascript:delete_id('.'\''.$txref.'\''.')"><img src="images/delete.png" alt="Delete" title="Delete record completely" /></a></td>';
							}  else {
								echo "";
							}
						?>
						<?php
							if ($menu['department']=="Leasing" && $post['leasing_post_status']=="Approved") {
								echo '
								<td><a href="#" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span> Approved</a></td>';
							} elseif ($menu['department']=="Leasing" && $post['leasing_post_status']=="Pending") {
								echo '
								<td><a href="#" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-stop"></span> Pending</a></td>';
							} elseif ($menu['department']=="Leasing" && $post['leasing_post_status']=="Declined") {
								echo '
								<td><a href="#" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Declined</a></td>';
							}  else {
								echo "";
							}
						?>
					</tr>
				<?php	 
					 }
				?>
				<?php
					$tquery = "SELECT SUM(amount_paid) as amount_posted ";
					$tquery .= "FROM account_general_transaction_new ";
					$tquery .= "WHERE posting_officer_id= '$session_id' ";
					$tquery .= "AND date_of_payment = '$date_of_collection'";
					$tsum = @mysqli_query($dbcon,$tquery);
					$total = @mysqli_fetch_array($tsum, MYSQLI_ASSOC);
					
					$total_amount = $total['amount_posted'];
				?>
				<tr>
					<td colspan="7"></td>
					<th class="text-right">
						<?php 
							$total_amount = number_format((float)$total_amount, 2);
							echo "&#8358 {$total_amount}";
						?>
					</th>
					<?php
						if ($menu['department']=="Accounts") {
							echo '<td colspan="2"></td>';
						}
					?>
				</tr>
			</table>
		</div>
		
		<div class="row">
			<div class="text-center">	
				<button type="submit" onclick="window.print()" />Print Payment Slip</button>
			</div>
		</div>
		
		<div class="page-header">
			<div class="text-center">	
				<img src="../../images/arena_logo.png" width="100px" height="34px"> ...The right place to be
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
function delete_id(id)
{
	if(confirm('Are you sure you want to COMPLETELY DELETE this record?'))
	{
		window.location.href='trans_analysis.php?delete_id='+id;
	}
}
</script>
</html>