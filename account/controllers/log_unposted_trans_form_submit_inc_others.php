<?php
$error = false;
$remit_id = "";
 
if ( isset($_POST['btn_post']) ) {
	
	$remit_id = $_POST['remit_id'];
	if($remit_id == "" || $remit_id == " "){
		$error = true;
	} else {
		$remit_id = $_POST['remit_id'];
	}
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$income_line = $_POST['income_line'];
	
	$transaction_desc = $_POST['transaction_desc'];
	
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$loggable = $_POST['loggable'];
	$loggable = preg_replace('/[,]/', '', $loggable);
	
	if($amount_paid > $loggable){
		$error = true;
		$loggable_payment = "<h4><strong>ATTENTION:</strong> Transaction log failed! <strong>&#8358 {$amount_paid} is MORE THAN the loggable balance of &#8358 {$loggable}</strong> . Please resolve accordingly!</h4>";
	}
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM unposted_transactions WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	
	$count = mysqli_num_rows($result);
	if($count != 0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer!</h4>";
	}
	
	$category = "Other Collection";
	$reason = $_POST['reason'];

	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	$payment_status = "unposted";

if (!$error) {
		$ca_query1 = "INSERT INTO unposted_transactions (id,remit_id,date_of_payment, transaction_desc, amount_paid,receipt_no,category,income_line,posting_time,posting_officer_id,posting_officer_name,payment_status,reason) VALUES('','$remit_id','$date_of_payment','$transaction_desc','$amount_paid','$receipt_no','$category','$income_line','$now','$posting_officer_id','$posting_officer_name','$payment_status','$reason')";
		$ca_query1 = @mysqli_query($dbcon, $ca_query1);

	if ($ca_query1)
		{
			?>
			<script type="text/javascript">
			alert('Payment successfully logged in the system!');
			window.location.href='log_unposted_trans_others.php';
			</script>
			<?php
		}
		else
		{
			?>
			<script type="text/javascript">
			alert('Error occured while logging payment');
			window.location.href='log_unposted_trans_others.php';
			</script>
			<?php
		}
	}
} 
?>