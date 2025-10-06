<?php
include 'include/session.php';

date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s');

if(isset($_SESSION['staff']) ) {
	$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['staff'];
}
if(isset($_SESSION['admin']) ) {
	$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['admin'];
}
$role_set = mysqli_query($dbcon, $query);
$role = mysqli_fetch_array($role_set, MYSQLI_ASSOC);

$fc_id = $role['user_id'];
$fc_name = $role['full_name'];
	
if ($role['acct_view_record'] != "Yes") {
	header("Location: ../../../index.php");
}

$error = false;
$leasing_officer_id = NULL;
$leasing_officer_name = NULL;
$posted_receipt_no = NULL;
$posted_amount = NULL;

if (isset($_GET['txref'])) {
	$lease_query = "SELECT * FROM account_general_transaction_new WHERE id=".$_GET['txref'];
	$lease_result = @mysqli_query($dbcon, $lease_query);
	$post = @mysqli_fetch_array($lease_result, MYSQLI_ASSOC);
	
	$leasing_officer_id = $post['posting_officer_id'];
	$leasing_officer_name = $post['posting_officer_name'];
	$posted_receipt_no = $post['receipt_no'];
	$posted_amount = $post['amount_paid'];
	$txref = $post['id'];
	$payment_category = $post['payment_category'];
}

//Approve Pending Post
if (isset($_POST['mybutton']) && $_POST['mybutton'] == "Approve Payment" && isset($_GET['txref'])) {
	$txref = $_GET['txref'];
	
	$lease_query = "SELECT * FROM account_general_transaction_new WHERE id=".$_GET['txref'];
	$lease_result = mysqli_query($dbcon, $lease_query);
	$post = mysqli_fetch_array($lease_result, MYSQLI_ASSOC);
	
	$payment_category = $post['payment_category'];
	
	$leasing_officer_id = $post['posting_officer_id'];
	$leasing_officer_name = $post['posting_officer_name'];
	$posted_receipt_no = $post['receipt_no'];
	$posted_amount = $post['amount_paid'];
	
	$receipt_no = $_POST['receipt_no'];
	$amount_paid = $_POST['amount_paid'];
		
	if ($receipt_no != $posted_receipt_no){
		$error = true;
	}
	if ($amount_paid != $posted_amount){
		$error = true;
	}
	
	if($payment_category == "Rent") {
		$ca_query = "SELECT SUM(amount_paid) as ca_amount_paid ";
		$ca_query .= "FROM collection_analysis ";
		$ca_query .= "WHERE receipt_no = '$posted_receipt_no'";
		$ca_result = mysqli_query($dbcon, $ca_query);
		$ca_shop = mysqli_fetch_array($ca_result, MYSQLI_ASSOC);
		
		@$ca_paid = $ca_shop["ca_amount_paid"];
		
		if ($posted_amount != $ca_paid){
			$error = true;
			$amount_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>amount posted: $posted_amount</strong> is not equal to the narrated amount of <strong>$ca_paid!</strong></h4>";
		}
	} 
	else if ($payment_category == "Service Charge") {
		$ca_query = "SELECT SUM(amount_paid) as ca_amount_paid ";
		$ca_query .= "FROM collection_analysis_arena ";
		$ca_query .= "WHERE receipt_no = '$posted_receipt_no'";
		$ca_result = mysqli_query($dbcon, $ca_query);
		$ca_shop = mysqli_fetch_array($ca_result, MYSQLI_ASSOC);
		
		@$ca_paid = $ca_shop["ca_amount_paid"];
		
		if ($posted_amount != $ca_paid){
			$error = true;
			$amount_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>amount posted: $posted_amount</strong> is not equal to the narrated amount of <strong>$ca_paid!</strong></h4>";
		}
	} else {
		
	}
	
	$query = "SELECT * FROM account_general_transaction_new WHERE (receipt_no='$receipt_no' AND leasing_post_status != 'Pending')";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	
	$count = mysqli_num_rows($result);
	if($count != 0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer!</h4>";
	}
	
	
	if (!$error) {
		$query_acct = "SELECT * ";
		$query_acct .= "FROM account_general_transaction_new ";
		$query_acct .= "WHERE id =".$_GET['txref'];
		$acct_table_set = mysqli_query($dbcon, $query_acct);
		$acct_table = mysqli_fetch_array($acct_table_set, MYSQLI_ASSOC);

		$debit_account = $acct_table["debit_account"];
		$credit_account = $acct_table["credit_account"];


		$query_acct1 = "SELECT * ";
		$query_acct1 .= "FROM accounts ";
		$query_acct1 .= "WHERE acct_id = $debit_account";
		$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
		$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);

		$db_debit_table = $acct_debit_table["acct_table_name"];


		$query_acct2 = "SELECT * ";
		$query_acct2 .= "FROM accounts ";
		$query_acct2 .= "WHERE acct_id = $credit_account";
		$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
		$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);

		$db_credit_table = $acct_credit_table["acct_table_name"];

		date_default_timezone_set('Africa/Lagos');
		$now = date('Y-m-d H:i:s');

		$aquery="UPDATE account_general_transaction_new SET leasing_post_approving_officer_id = '$fc_id', leasing_post_approving_officer_name = '$fc_name', leasing_post_status = 'Approved', leasing_post_approval_time = '$now', approval_status = 'Pending' WHERE id=".$_GET['txref'];
		$result = mysqli_query($dbcon, $aquery);
		
		$cquery="UPDATE $db_debit_table SET approval_status = 'Pending' WHERE id=".$_GET['txref'];
		$cresult = mysqli_query($dbcon, $cquery);
		
		$bquery="UPDATE $db_credit_table SET approval_status = 'Pending' WHERE id=".$_GET['txref'];
		$bresult = mysqli_query($dbcon, $bquery);
		
		
		$lease_query = "SELECT * FROM account_general_transaction_new ";
		$lease_query .= "WHERE (posting_officer_id='$leasing_officer_id' AND leasing_post_status = 'Pending') ";
		$lease_query .= "LIMIT 1 ";
		$lease_result = mysqli_query($dbcon, $lease_query);
		$post = mysqli_fetch_array($lease_result, MYSQLI_ASSOC);
		
		$post_id = $post["id"];
		
		if ($aquery)
		{
			?>
			<script type="text/javascript">
			alert('Record Successfully Approved!');
			window.location.href='review_trans.php?txref=<?php echo $post_id; ?>';
			</script>
			<?php
		} 
	} else {
		$errMSG = "<h4>Incorrect Credentials, Try again...</h4>";
	}
}



//Decline Pending Post
elseif (isset($_POST['mybutton']) && $_POST['mybutton'] == "Decline Payment" && isset($_GET['txref'])) {
	
	$reason = $_POST['reason'];
	
	$query = "SELECT * FROM account_general_transaction_new WHERE id=".$_GET['txref'];
	$result = mysqli_query($dbcon, $query);
	$post1 = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$leasing_officer_id = $post1['posting_officer_id'];

	$query="UPDATE account_general_transaction_new SET leasing_post_approving_officer_id = '$fc_id', leasing_post_approving_officer_name = '$fc_name', leasing_post_status = 'Declined', leasing_post_approval_time = '$now', comment='$reason' WHERE id=".$_GET['txref'];
	$result = mysqli_query($dbcon, $query);
	
	$lease_query = "SELECT * FROM account_general_transaction_new ";
	$lease_query .= "WHERE (posting_officer_id='$leasing_officer_id' AND leasing_post_status = 'Pending') ";
	$lease_query .= "LIMIT 1 ";
	$lease_result = mysqli_query($dbcon, $lease_query);
	$post = mysqli_fetch_array($lease_result, MYSQLI_ASSOC);
	
	$post_id = $post["id"];
	
	if($query)
	{
	?>
		<script type="text/javascript">
		alert('Record Successfully Declined!');
		window.location.href='review_trans.php?txref=<?php echo $post_id; ?>';
		</script>
	<?php
	}
} else {
}
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
				<div class="col-md-12">
					<h2><strong><?php echo $leasing_officer_name; ?>'s Pending Approvals</strong> <?php echo '<a href="view_trans.php" class="btn btn-danger btn-sm">View more posts</a>'; ?></h2>
					<?php
						$current_date = date('d-m-Y');
						$current_date = str_replace('-', '/', $current_date);
					?>
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
					</h5>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-2"><?php include ('include/left_navbar.php'); ?></div>
	
	<div class="col-md-6">
		<?php
		   if ( isset($receipt_Error) ) {
		?>
			<div class="form-group form-group-sm">
				<div class="alert alert-success fade in">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<?php echo @$receipt_Error; ?>
				</div>
			</div>
		<?php
		   }
		?>
		
		<?php
		   if ( isset($amount_Error) ) {
		?>
			<div class="form-group form-group-sm">
				<div class="alert alert-success fade in">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<?php echo @$amount_Error; ?>
				</div>
			</div>
		<?php
		   }
		?>
		
		<?php
		   if ( isset($errMSG) ) {
		?>
			<div class="form-group form-group-sm">
				<div class="alert alert-danger fade in">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<?php echo @$errMSG; ?>
				</div>
			</div>
		<?php
		   }
		?>
		<div class="row">
			<?php include ('controllers/leasing_posted_trans.php'); ?>
		</div>	
	</div>
	
	<div class="col-md-4"><?php include ('include/right_navbar.php'); ?></div>
</div>
</body>
<script type="text/javascript">
function txref(id)
{
	if(confirm)
	{
		window.location.href='review_trans.php?txref='+id;
	}
}
function entryCheck(){
	var amount_paid = document.getElementById('amount_paid').value;
	var receipt_no = document.getElementById('receipt_no').value;
	var reason = document.getElementById('reason').value;
	
	if (amount_paid.length >= 2 && receipt_no.length == 7 && reason.length == 0){
		document.getElementById('span_reject').style.display = 'none';
		document.getElementById('span_approve').style.display = 'block';
	}else if (amount_paid.length >= 2 && receipt_no.length == 7 && reason.length > 1){
		document.getElementById('span_reject').style.display = 'block';
		document.getElementById('span_approve').style.display = 'none';
	} else {
		document.getElementById('span_reject').style.display = 'none';
		document.getElementById('span_approve').style.display = 'none';
	}
}
</script>
</html>