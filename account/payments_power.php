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

if ($role['acct_post_record'] != "Yes") {
?>
	<script type="text/javascript">
		alert('You do not have permissions to view this page! Contact your HOD for authorization. Thanks');
		window.location.href='../../index.php';
	</script>
<?php
}
if (isset($_POST['btn_load_customer_data'])){
	$shop_no = $_POST['selected_shop_no'];
	
	$query = "SELECT * ";
	$query .= "FROM customers_power_consumption ";
	$query .= "WHERE shop_no = '$shop_no'";
	$data = mysqli_query($dbcon, $query);
	$selected_shop_data = mysqli_fetch_array($data, MYSQLI_ASSOC);
	
} elseif (isset($_POST['btn_load_customer_data'])){
	$account_desc = NULL;;
	$shop_no = $_POST['selected_shop_no'];
	
	$query = "SELECT * ";
	$query .= "FROM customers_power_consumption ";
	
	
	$query .= "WHERE shop_no = '$shop_no'";
	$data = mysqli_query($dbcon, $query);
	$selected_shop_data = mysqli_fetch_array($data, MYSQLI_ASSOC);
}
$mode = NULL;

if ((isset($_POST['btn_mode'])) && (mode == "active")) {
	$mode = "inactive";
}
include ('controllers/post_power_form_submit_inc.php');
?>
<?php include ('include/staff_header.php'); ?>
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
			$vp_result = mysqli_query($dbcon,$vp_query); ?>

<div class="container-fluid">
	<div class="row">
			<div class="page-header">
				<div class="container-fluid">
					<div class="col-md-8">
						<h2>Power Consumption Payment Dashboard</h2>
						<a href="view_trans_arena.php" class="btn btn-warning">View ARENA Transactions</a>
						<a href="post_new_lease.php" class="btn btn-danger"><span class="glyphicon glyphicon-user"></span> New Lease Payment</a>
						<a href="post_trans_arena.php" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Receive Money</a>
						<a href="payments_arena.php" class="btn btn-success"><span class="glyphicon glyphicon-minus-sign"></span> Make Payments</a>
						<a href="ledgers_arena.php" class="btn btn-primary"><span class="glyphicon glyphicon-send"></span> General Ledgers </a>
					</div>
				
					<div class="col-md-4">
						<form  method="post" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
							<span class="col-md-5 text-right"><button type="" name="btn_mode" class="btn btn-success">Activate</button></span>
							<span class="col-md-7 text-left" style="background: yellow;"><h5><strong>Bulk Mode Deactivated! <?php echo $mode; ?></strong></h5></span>
						</form>
					</div>
				</div>
			</div>
		</div>
	<div class="col-md-2"></div>
	<div class="col-md-10">
		<div class="row">
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
				
				$dcount_query = "SELECT COUNT(id) FROM account_general_transaction_new ";
				$dcount_query .= "WHERE posting_officer_id = '$session_id' ";
				$dcount_query .= "AND (approval_status = 'Declined' OR verification_status = 'Declined')";
				$result = mysqli_query($dbcon, $dcount_query);
				$acct_office_post = mysqli_fetch_array($result);
				$no_of_declined_post = $acct_office_post[0];
			?>
			<?php include ('controllers/post_power_form_inc.php'); ?>
		</div>
	</div>			
</div>
</body>
</html>
<?php ob_end_flush(); ?>