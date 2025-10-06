<?php
include 'include/session.php';
include ('include/functions.php');

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$sub_prefix = "";
$sub_suffix = "_wrl";
$page_title = "{$project} - Pending FC Approvals - Wealth Creation ERP";


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

<?php
	//Set the number of rows per display
	$pagerows = 20;
?>

<?php include ("controllers/acct_delete_query.php"); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $page_title; ?></title>
		<meta name="description" content="Wealth Creation ERP Management System">
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../style.css" type="text/css" />
		<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
		<script src="../../js/bootstrap.js"></script>
		<script src="../../js/sub_menu.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">
		
		<link rel="stylesheet" href="../../scripts/swal2/sweetalert2.min.css" type="text/css" />
		<script src="../../scripts/swal2/sweetalert2.min.js"></script>
		
		<script src="../../js/jquery.dataTables.min.js"></script>  
		<script src="../../js/dataTables.bootstrap.min.js"></script>            
		<link rel="stylesheet" href="../../css/dataTables.bootstrap.min.css" /> 
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
		window.location.href='pending_fc_approvals<?php echo $suffix; ?>.php?delete_id='+id;
	}
}
</script>

<?php include ('fc_js_script'.$suffix.'.php'); ?>

</head>
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
<div id="container">
<div class="container-fluid">
	<div class="col-sm-12">
	<div class="page-header">
		<h2><strong><?php echo $project; ?> Pending FC Approvals</strong><?php echo ' <a href="view_trans'.$suffix.'.php" class="btn btn-primary btn-sm">Pending Posts</a>'; ?> </h2>
		
		<?php 
			//include ('include/acct_header'.$suffix.'.php'); 
			
			$fcquery = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
			$fcquery .= "WHERE verification_status = 'Declined' ";
			$fcquery .= "AND approval_status = 'Approved' ";
			$fcresult = mysqli_query($dbcon, $fcquery);
			$fc_declined_trans = mysqli_fetch_array($fcresult);
			$all_fc_declined_trans = $fc_declined_trans[0];
			
			
			if ($menu["level"] == "fc" && $all_fc_declined_trans > 0) {
				header("Location: view_trans_{$prefix}declined.php");
			} 
		?>
	</div>
	
		<div class="row">
		<div id="stats">
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
					 
					 $wrl_query = "SELECT * ";
					 $wrl_query .= "FROM {$prefix}account_general_transaction_new ";
					 $wrl_query .= "WHERE approval_status = 'Pending' ";
					 //$wrl_query .= "ORDER BY leasing_post_approval_time ASC ";
					 $wrl_query .= "ORDER BY posting_time ASC ";
					 $wrl_query .= "LIMIT 20";
					 $post_set = mysqli_query($dbcon,$wrl_query);
					 $post_no = mysqli_num_rows($post_set);

					 while ($post = mysqli_fetch_array($post_set, MYSQLI_ASSOC)) {
						 
						 $post_shop_no = $post['shop_no'] ;
						 $amount = $post['amount_paid'];
						 $amount_paid = number_format((float)$amount, 2);
						 $posting_time = $post['posting_time'];
						 $leasing_approval_time = $post['leasing_post_approval_time'];
						 $approval_time = $post['approval_time'];
						 $receipt_no = $post["receipt_no"];
						 $date_of_payment = $post["date_of_payment"];
					
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

			<script type="text/javascript">
			function txdetails(id)
			{
				if(confirm)
				{
					window.location.href='transaction_details<?php echo $suffix; ?>.php?txref='+id;
				}
			}

			$(document).ready(
					function() {
						$('[data-toggle="tooltip"]').tooltip();
					}
				);
			</script>
			</div>
		</div>
	</div>
</div>
</div>
</body>
</html>