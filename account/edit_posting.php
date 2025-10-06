<?php
include 'include/session.php';

date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s');

$error = false;
	$posted_receipt_no = NULL;
	$posted_amount = NULL;
	$date_of_payment = NULL;

//Populate the form fields with the previously saved records
if (isset($_GET['edit_id'])) {
	$lease_query = "SELECT * FROM account_general_transaction_new WHERE id=".$_GET['edit_id'];
	$lease_result = mysqli_query($dbcon, $lease_query);
	$post = mysqli_fetch_array($lease_result, MYSQLI_ASSOC);
	
	$posted_receipt_no = $post['receipt_no'];
	$posted_amount = $post['amount_paid'];
	
	$date_of_payment = $post['date_of_payment'];
	list($tiy,$tim,$tid) = explode("-",$date_of_payment);
	$form_date_of_payment = "$tid/$tim/$tiy";
} 

if (isset($_POST['btn_update_post']) && isset($_GET['edit_id'])) {
	$edit_id = $_GET['edit_id'];
	$form_date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$form_date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$start_date = "";
	$end_date = "";
	
	$transaction_desc = $_POST['transaction_desc'];
	$ref_cheque_no = $_POST['ref_cheque_no'];
	$teller_no = $_POST['teller_no'];
	
	if($ref_cheque_no == "" && $teller_no == ""){
		$ref_cheque_teller_no = "";
	}elseif($ref_cheque_no != "" && $teller_no == ""){
		$ref_cheque_teller_no = $ref_cheque_no;
	}elseif($ref_cheque_no == "" && $teller_no != ""){
		$ref_cheque_teller_no = $teller_no;
	}elseif($ref_cheque_no != "" && $teller_no != ""){
		$ref_cheque_teller_no = $cheque_no;
	}else{
		$ref_cheque_teller_no = "";
	}
	
	$plate_no = strtoupper($_POST['plate_no']);
	$receipt_no = $_POST['receipt_no'];
	
	$pquery = "SELECT * FROM account_general_transaction_new WHERE (receipt_no='$receipt_no' AND id='$edit_id')";
	$presult = mysqli_query($dbcon,$pquery);
	$preceipt_data = @mysqli_fetch_array($presult, MYSQLI_ASSOC);
	$preceipt_posting_officer = $preceipt_data['posting_officer_name'];
	$preceipt_no = $preceipt_data['receipt_no'];
	$payment_category = $preceipt_data['payment_category'];
	

	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	
	$count = mysqli_num_rows($result);
	
	if($receipt_no == $preceipt_no) {
		$receipt_no = $preceipt_no;
	} else {
		if($count != 0){
			$error = true;
			$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer!</h4>";
		}
	}
	
	$approval_status = "Pending";

	$debit_account = $_POST['debit_account'];
	$credit_account = $_POST['credit_account'];
	
	if (!$error) {
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
		$db_transaction_table = "account_general_transaction_new";
		
		if($payment_category == "Rent") {
			$db_collection_table = "collection_analysis";
		} elseif ($payment_category == "Service Charge") {
			$db_collection_table = "collection_analysis_arena";
		} else {
			$db_collection_table = "";
		}
			
  
	// if there's no error, continue to signup
		$query = "UPDATE $db_transaction_table SET date_of_payment='$date_of_payment', start_date='$start_date', end_date='$end_date', transaction_desc='$transaction_desc', cheque_no='$ref_cheque_no', teller_no='$teller_no', receipt_no='$receipt_no', debit_account='$debit_account', credit_account='$credit_account', posting_time='$now', approving_acct_officer_id='', approving_acct_officer_name='', approval_time='', approval_status='$approval_status', verifying_auditor_id='', verifying_auditor_name='', verification_time='', verification_status='', plate_no='$plate_no' WHERE id=".$_GET['edit_id'];
		$post_payment = @mysqli_query($dbcon, $query);


		$dquery = "UPDATE $db_debit_table SET date='$date_of_payment', ref_cheque_no='$ref_cheque_teller_no', receipt_no='$receipt_no', trans_desc='$transaction_desc', approval_status='$approval_status' WHERE id=".$_GET['edit_id'];
		$debit_query = @mysqli_query($dbcon, $dquery);
		
		
		$cquery = "UPDATE $db_credit_table SET date='$date_of_payment', ref_cheque_no='$ref_cheque_teller_no', receipt_no='$receipt_no', trans_desc='$transaction_desc', approval_status='$approval_status' WHERE id=".$_GET['edit_id'];
		$credit_query = @mysqli_query($dbcon, $cquery);
		
		
		if($payment_category == "Rent" || $payment_category == "Service Charge") {
			$ca_query1 = "UPDATE $db_collection_table SET date_of_payment='$date_of_payment', receipt_no='$receipt_no' WHERE trans_id=".$_GET['edit_id'];
			$ca_result = @mysqli_query($dbcon, $ca_query1);
		}

if ($credit_query)
		{
			?>
			<script type="text/javascript">
			alert('Payment successfully UPDATED for approval!');
			window.location.href='acct_view_trans.php';
			</script>
			<?php
		}
		else
		{
			?>
			<script type="text/javascript">
			alert('Error occured while UPDATING record');
			window.location.href='acct_view_trans.php';
			</script>
			<?php
		}
	} 
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
				<div class="col-md-8">
					<h3><strong>Wealth Creation - Edit Dashboard <a href="acct_view_trans.php" class="btn btn-success">View more PENDING transactions</a></strong></h3>
				</div>
				<div class="col-md-4">
				</div>
			</div>
		</div>
		
		<?php
			if (isset($receipt_Error) ) {
			echo '
			<div class="form-group form-group-sm">
				<div class="alert alert-success fade in">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$receipt_Error.'
				</div>
			</div>';
		   }
		?>
	</div>
	
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-6">
			<?php include ('controllers/edit_posted_trans.php'); ?>
		</div>
		
		<div class="col-md-3">
			<table class="table table-hover table-bordered">
				<thead>
					<tr class="danger">
						<td colspan="2"><strong>Payment Breakdown</strong></td>
					</tr>
				</thead>
				<tbody>
					<?php
					if (isset($_GET['edit_id'])) {
						$txref_id = $_GET["edit_id"];
						
						$query = "SELECT * FROM collection_analysis ";
						$query .= "WHERE trans_id = '$txref_id'";
						$collection_post_set = mysqli_query($dbcon,$query);

						while ($collection_post = mysqli_fetch_array($collection_post_set, MYSQLI_ASSOC)) {
					?>
					<tr>
						<td><strong><?php echo $collection_post['payment_month']; ?></strong></td>
						<td class="text-right"><strong><span style="color:#ec7063;"><?php echo $collection_post['amount_paid']; ?></span></strong></td>
					</tr>
					<?php
						}
					} else {
						echo "";
					}
					?>					
				</tbody>
			</table>
		</div>
	</div>
</div>
</body>
</html>
<?php ob_end_flush(); ?>