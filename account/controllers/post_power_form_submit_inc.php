<?php
$error = false;
 
if ( isset($_POST['btn_post_rent']) ) {
	 
  // clean user inputs to prevent sql injections
	$shop_id = $_POST['shop_id'];
	$power_id = $_POST['power_id'];
	$customer = $_POST['customer_name'];
	$shop_no = $_POST['shop_no'];
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$payment_type = $_POST['payment_type'];
	$payment_category = $_POST['payment_category'];
	$transaction_desc = $_POST['transaction_desc'];
	$bank_name = $_POST['bank_name'];
	
	$cheque_no = $_POST['cheque_no'];

	$ref_cheque_no = $cheque_no;
	$journal_no = "";
	
	$teller_no = $_POST['teller_no'];
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$count = mysqli_num_rows($result);
	if($count>=1){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used!</h4>";
	}
	
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$count = mysqli_num_rows($result);
	if($count>=1){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used!</h4>";
	}
	
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_customer = $_POST['remitting_customer'];
	$remitting_staff = $_POST['remitting_staff'];
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	$approval_status = $_POST['approval_status'];
	$verification_status = "Pending";
	
	$debit_account = $_POST['debit_account'];
	$credit_account = $_POST['credit_account'];
	
	if (empty($debit_account)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_account)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
//	$txref = "pay".time().mt_rand(0,10);
	$txref = time().mt_rand(99,250);
	
if (!$error) {

	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM arena_accounts ";
	$query_acct1 .= "WHERE acct_id = $debit_account";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM arena_accounts ";
	$query_acct2 .= "WHERE acct_id = $credit_account";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$db_credit_table = $acct_credit_table["acct_table_name"];
	
	$db_transaction_table = "arena_account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	// if there's no error, continue to signup
		
		$query = "INSERT INTO $db_transaction_table (id,shop_id,customer_name,shop_no,shop_size,date_of_payment,start_date,end_date,payment_type,transaction_desc,bank_name,cheque_no,teller_no,receipt_no,amount_paid,remitting_customer,remitting_staff,posting_officer_id,posting_officer_name,posting_time,approval_status,verification_status,debit_account,credit_account,payment_category) VALUES('$txref','$power_id','$customer','$shop_no','','$date_of_payment','$start_date','$end_date','$payment_type','$transaction_desc','$bank_name','$cheque_no','$teller_no','$receipt_no','$amount_paid','$remitting_customer','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category')";
		$post_payment = @mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$shop_no','$date_of_payment','$ref_cheque_no','$journal_no','$receipt_no','$transaction_desc','$amount_paid','','$approval_status')";
		$debit_query = @mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$shop_no','$date_of_payment','$ref_cheque_no','$journal_no','$receipt_no','$transaction_desc','$amount_paid','','$approval_status')";
		$credit_query = @mysqli_query($dbcon, $cquery);

  
if ($credit_query)
	//	if ($post_payment)
		{
			?>
			<script type="text/javascript">
			alert('Payment successfully posted for approval!');
			window.location.href='payments_power.php';
			</script>
			<?php
		}
		else
		{
			?>
			<script type="text/javascript">
			alert('Error occured while posting');
			window.location.href='payments_power.php';
			</script>
			<?php
		}
	}
} 
?>