<?php
include 'include/session.php';
include ('include/functions.php');

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$sub_prefix = "";
$sub_suffix = "_wrl";
$page_title = "{$project} - Search Records | Wealth Creation ERP";


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
function edit_id(id)
{
	if(confirm('Are you sure you want to EDIT this transaction record?'))
	{
		window.location.href='edit_posting<?php echo $suffix; ?>.php?edit_id='+id;
	}
}
function delete_id(id)
{
	if(confirm('Are you sure you want to COMPLETELY DELETE details?'))
	{
		window.location.href='search<?php echo $suffix; ?>.php?delete_id='+id;
	}
}
</script>

</head>
<body>
<?php 
include ('include/staff_navbar.php');

if ($menu['department'] == "Audit/Inspections"){
	include ('../audit/audit_js_script'.$suffix.'.php'); 
} elseif ($menu['department'] == "Accounts"){
	include ('fc_js_script'.$suffix.'.php'); 
} else {
	echo '';
}
			
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
			<h2><strong><?php echo $project; ?> Search Result</strong></h2>
			
			<?php include ('include/acct_header'.$suffix.'.php'); ?>
		</div>
	<?php
		$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new";
		$result = mysqli_query($dbcon, $query);
		$record_row = mysqli_fetch_array($result);
		$no_of_records = $record_row[0];
	?>
	<?php
		$search = isset($_GET['sr']) ? $_GET['sr'] : '';
		$search_error = "No such record found. Try again!";
		
		$squery = "(SELECT * FROM {$prefix}account_general_transaction_new WHERE receipt_no LIKE '%$search%' OR cheque_no LIKE '%$search%' OR plate_no LIKE '%$search%' OR teller_no LIKE '%$search%' OR shop_no LIKE '%$search%' OR transaction_desc LIKE '%$search%' OR id = '$search') UNION (SELECT * FROM {$prefix}account_general_transaction WHERE receipt_no LIKE '%$search%' OR cheque_no LIKE '%$search%' OR plate_no LIKE '%$search%' OR teller_no LIKE '%$search%' OR shop_no LIKE '%$search%' OR transaction_desc LIKE '%$search%' OR id = '$search') ";
		
		$squery .= "ORDER BY date_of_payment ASC";
		$sresult = mysqli_query($dbcon, $squery);
		$total_shop_no = mysqli_num_rows($sresult);
	?>
	<div>
		<h3><strong>Showing search result for: <span style="color:#ec7063;">"<?php echo $search; ?>"</span></strong></h3>
	</div>
		<div class="row">
		<div class="table-responsive">
			  <table id="simple-table" class="table table-bordered table-hover">
				<?php include ('../../include/acct_table_titles.php'); ?>
				
					 <?php
					 $caamount_paid = "";
					 $capayment_month = "";
					 
					 if (isset($_GET['s'])) {
							$i = $_GET['s'];
						} else {
							$i=0;
						} 
					 
						while ($post = mysqli_fetch_array($sresult, MYSQLI_ASSOC)) {
					
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
						
						<?php include '../../include/acct_table_body_content.php'; ?>
						
						<?php include '../../include/approval_rights.php'; ?>
					</tr>
					
					<?php include '../../include/acct_additional_rows.php'; ?>
					<?php	 
					 }
					?>
					
			  </table>
			  </div>
			</div>
		</div>
	</div>
</div>
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
</script>
</body>
</html>