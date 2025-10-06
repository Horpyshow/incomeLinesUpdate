<?php
$error = false;
$remit_id = "";

//General Processing
if ( isset($_POST['btn_post_general']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_post = $_POST['remitting_staff'];
	list($remitting_id,$remitting_check) = explode("-",@$remitting_post);
	
	if ($remitting_check == "wc"){
		$squery = "SELECT * FROM staffs WHERE user_id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	} else {
		$squery = "SELECT * FROM staffs_others WHERE id='$remitting_id'";
		$sresult = @mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	}

	$transaction_desc = $_POST['transaction_descr'];
	$transaction_desc = strip_tags($transaction_desc);
	$transaction_desc = htmlspecialchars($transaction_desc);
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	if ($posting_officer_dept == "Accounts"){
		$debit_alias = $_POST['debit_account'];
		$credit_alias = $_POST['credit_account'];
	} else {
		$debit_alias = $_POST['debit_alias'];
		$credit_alias = $_POST['credit_alias'];
	}
	
	
	$balance = "";
	
	if (empty($debit_alias)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_alias)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	//Debit Account
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	
	if ($posting_officer_dept == "Accounts"){
		$query_acct1 .= "WHERE acct_id = '$debit_alias'";
	} else {
		$query_acct1 .= "WHERE acct_alias = '$debit_alias'";
	}
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$debit_account = $acct_debit_table["acct_id"];
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	
	//Credit Account
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	
	if ($posting_officer_dept == "Accounts"){
		$query_acct2 .= "WHERE acct_id = '$credit_alias'";
	} else {
		$query_acct2 .= "WHERE acct_id = '$credit_alias'";
	}
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$credit_account = $acct_credit_table["acct_id"];
	$credit_account_desc = $acct_credit_table["acct_desc"];
	$db_credit_table = $acct_credit_table["acct_table_name"];
	
	if ($posting_officer_dept == "Accounts"){
		$income_line = $credit_account_desc;
	} else {
		$income_line = $_POST['income_line'];
	}
	
	$transaction_desc = $credit_account_desc.' - '.$transaction_desc;
	
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$payment_category = "Other Collection";

	
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,plate_no,remit_id) VALUES('$txref','$date_of_payment','$transaction_desc','$receipt_no','$amount_paid','$remitting_id','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','','$remit_id')";
		
		$post_payment = mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

		
if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	
	
}
}




//Car Loading Processing
if ( isset($_POST['btn_post_car_loading']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	$income_line = $_POST['income_line'];
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}

	$no_of_tickets = $_POST['no_of_tickets'];
	
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_post = $_POST['remitting_staff'];
	list($remitting_id,$remitting_check) = explode("-",@$remitting_post);
	
	if ($remitting_check == "wc"){
		$squery = "SELECT * FROM staffs WHERE user_id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	} else {
		$squery = "SELECT * FROM staffs_others WHERE id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	}
	
	$transaction_desc = $_POST['transaction_descr'];
	$transaction_desc = strip_tags($transaction_desc);
	$transaction_desc = htmlspecialchars($transaction_desc);
	
	$transaction_desc = $transaction_desc.' - '.$remitting_staff;
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	$debit_alias = $_POST['debit_alias'];
	$credit_alias = $_POST['credit_alias'];
	$balance = "";
	
	if (empty($debit_alias)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_alias)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	$query_acct1 .= "WHERE acct_alias = '$debit_alias'";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$debit_account = $acct_debit_table["acct_id"];
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	$query_acct2 .= "WHERE acct_alias = '$credit_alias'";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$credit_account = $acct_credit_table["acct_id"];
	$db_credit_table = $acct_credit_table["acct_table_name"];

	
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$payment_category = "Other Collection";
		
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,no_of_tickets,remit_id) VALUES('$txref','$date_of_payment','$transaction_desc','$receipt_no','$amount_paid','$remitting_id','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','$no_of_tickets','$remit_id')";
		
		$post_payment = mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

		
if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
}
}




//Hawkers Processing
if ( isset($_POST['btn_post_hawkers']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	$income_line = $_POST['income_line'];
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}

	$no_of_tickets = $_POST['no_of_tickets'];
	
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_post = $_POST['remitting_staff'];
	list($remitting_id,$remitting_check) = explode("-",@$remitting_post);
	
	if ($remitting_check == "wc"){
		$squery = "SELECT * FROM staffs WHERE user_id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	} else {
		$squery = "SELECT * FROM staffs_others WHERE id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	}
	
	$transaction_desc = $_POST['transaction_descr'];
	$transaction_desc = strip_tags($transaction_desc);
	$transaction_desc = htmlspecialchars($transaction_desc);
	
	$transaction_desc = $transaction_desc.' - '.$remitting_staff;
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	$debit_alias = $_POST['debit_alias'];
	$credit_alias = $_POST['credit_alias'];
	$balance = "";
	
	if (empty($debit_alias)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_alias)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	$query_acct1 .= "WHERE acct_alias = '$debit_alias'";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$debit_account = $acct_debit_table["acct_id"];
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	$query_acct2 .= "WHERE acct_alias = '$credit_alias'";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$credit_account = $acct_credit_table["acct_id"];
	$db_credit_table = $acct_credit_table["acct_table_name"];

	
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$payment_category = "Other Collection";
		
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,no_of_tickets,remit_id) VALUES('$txref','$date_of_payment','$transaction_desc','$receipt_no','$amount_paid','$remitting_id','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','$no_of_tickets','$remit_id')";
		
		$post_payment = mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

		
if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
}
}



//Car Park Processing
if ( isset($_POST['btn_post_car_park']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	$income_line = $_POST['income_line'];
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$category = $_POST['category'];
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	$ticket_category = $_POST['ticket_category'];

	$no_of_tickets = $_POST['no_of_tickets'];
	
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_post = $_POST['remitting_staff'];
	list($remitting_id,$remitting_check) = explode("-",@$remitting_post);
	
	if ($remitting_check == "wc"){
		$squery = "SELECT * FROM staffs WHERE user_id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	} else {
		$squery = "SELECT * FROM staffs_others WHERE id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	}
	
	$transaction_desc = $category.' - '.$remitting_staff;
	$transaction_desc = htmlspecialchars($transaction_desc);
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	$debit_alias = $_POST['debit_alias'];
	$credit_alias = $_POST['credit_alias'];
	$balance = "";
	
	if (empty($debit_alias)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_alias)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	$query_acct1 .= "WHERE acct_alias = '$debit_alias'";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$debit_account = $acct_debit_table["acct_id"];
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	$query_acct2 .= "WHERE acct_alias = '$credit_alias'";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$credit_account = $acct_credit_table["acct_id"];
	$db_credit_table = $acct_credit_table["acct_table_name"];

	
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	
	$payment_category = "Other Collection";
		
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,ticket_category,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,no_of_tickets,remit_id) VALUES('$txref','$date_of_payment','$ticket_category','$transaction_desc','$receipt_no','$amount_paid','$remitting_id','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','$no_of_tickets','$remit_id')";
		
		$post_payment = mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

		
if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
}
}




//WheelBarrow Processing
if ( isset($_POST['btn_post_wheelbarrow']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	$income_line = $_POST['income_line'];
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	
	$no_of_tickets = $_POST['no_of_tickets'];
	
	$amount = $_POST['amount_paid'];
	
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_post = $_POST['remitting_staff'];
	list($remitting_id,$remitting_check) = explode("-",@$remitting_post);
	
	if ($remitting_check == "wc"){
		$squery = "SELECT * FROM staffs WHERE user_id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	} else {
		$squery = "SELECT * FROM staffs_others WHERE id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	}
	
	$transaction_desc = $_POST['transaction_descr'];
	$transaction_desc = strip_tags($transaction_desc);
	$transaction_desc = htmlspecialchars($transaction_desc);
	
	$transaction_desc = $transaction_desc.' - '.$remitting_staff;
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	$debit_alias = $_POST['debit_alias'];
	$credit_alias = $_POST['credit_alias'];
	$balance = "";
	
	if (empty($debit_alias)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_alias)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	$query_acct1 .= "WHERE acct_alias = '$debit_alias'";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$debit_account = $acct_debit_table["acct_id"];
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	$query_acct2 .= "WHERE acct_alias = '$credit_alias'";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$credit_account = $acct_credit_table["acct_id"];
	$db_credit_table = $acct_credit_table["acct_table_name"];

	
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$payment_category = "Other Collection";
		
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,no_of_tickets,remit_id) VALUES('$txref','$date_of_payment','$transaction_desc','$receipt_no','$amount_paid','$remitting_id','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','$no_of_tickets','$remit_id')";
		
		$post_payment = mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

		
if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
}
}



//Daily Trade Processing
if ( isset($_POST['btn_post_daily_trade']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	$income_line = $_POST['income_line'];
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	$ticket_category = $_POST['ticket_category'];
	
	$no_of_tickets = $_POST['no_of_tickets'];
	
	$amount = $_POST['amount_paid'];
	
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_post = $_POST['remitting_staff'];
	list($remitting_id,$remitting_check) = explode("-",@$remitting_post);
	
	if ($remitting_check == "wc"){
		$squery = "SELECT * FROM staffs WHERE user_id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	} else {
		$squery = "SELECT * FROM staffs_others WHERE id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	}
	
	$transaction_desc = $_POST['transaction_descr'];
	$transaction_desc = strip_tags($transaction_desc);
	$transaction_desc = htmlspecialchars($transaction_desc);
	
	$transaction_desc = $transaction_desc.' ('.$ticket_category.') - '.$remitting_staff;
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	$debit_alias = $_POST['debit_alias'];
	$credit_alias = $_POST['credit_alias'];
	$balance = "";
	
	if (empty($debit_alias)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_alias)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	$query_acct1 .= "WHERE acct_alias = '$debit_alias'";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$debit_account = $acct_debit_table["acct_id"];
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	$query_acct2 .= "WHERE acct_alias = '$credit_alias'";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$credit_account = $acct_credit_table["acct_id"];
	$db_credit_table = $acct_credit_table["acct_table_name"];

	
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$payment_category = "Other Collection";
		
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,ticket_category,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,no_of_tickets,remit_id) VALUES('$txref','$date_of_payment','$ticket_category','$transaction_desc','$receipt_no','$amount_paid','$remitting_id','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','$no_of_tickets','$remit_id')";
		
		$post_payment = mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

		
if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
}
}



//Toilet Collection Processing
if ( isset($_POST['btn_post_toilet_collection']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	$income_line = $_POST['income_line'];
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	$ticket_category = $_POST['ticket_category'];
	
	$amount = $_POST['amount_paid'];
	
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_post = $_POST['remitting_staff'];
	list($remitting_id,$remitting_check) = explode("-",@$remitting_post);
	
	if ($remitting_check == "wc"){
		$squery = "SELECT * FROM staffs WHERE user_id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	} else {
		$squery = "SELECT * FROM staffs_others WHERE id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	}
	
	$transaction_desc = $_POST['transaction_descr'];
	$transaction_desc = strip_tags($transaction_desc);
	$transaction_desc = htmlspecialchars($transaction_desc);
	
	$transaction_desc = $transaction_desc.' ('.$ticket_category.') - '.$remitting_staff;
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	$debit_alias = $_POST['debit_alias'];
	$credit_alias = $_POST['credit_alias'];
	$balance = "";
	
	if (empty($debit_alias)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_alias)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	$query_acct1 .= "WHERE acct_alias = '$debit_alias'";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$debit_account = $acct_debit_table["acct_id"];
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	$query_acct2 .= "WHERE acct_alias = '$credit_alias'";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$credit_account = $acct_credit_table["acct_id"];
	$db_credit_table = $acct_credit_table["acct_table_name"];

	
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$payment_category = "Other Collection";
		
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,ticket_category,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,remit_id) VALUES('$txref','$date_of_payment','$ticket_category','$transaction_desc','$receipt_no','$amount_paid','$remitting_id','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','$remit_id')";
		
		$post_payment = mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

		
if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
}
}



//Abattoir Processing
if ( isset($_POST['btn_post_abattoir']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	
	$income_line = $_POST['income_line'];
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	$category = $_POST['category'];
	
	$quantity = $_POST['quantity'];
	
	$amount = $_POST['amount_paid'];
	
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_post = $_POST['remitting_staff'];
	list($remitting_id,$remitting_check) = explode("-",@$remitting_post);
	
	if ($remitting_check == "wc"){
		$squery = "SELECT * FROM staffs WHERE user_id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	} else {
		$squery = "SELECT * FROM staffs_others WHERE id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	}
	
	$transaction_desc = $_POST['transaction_descr'];
	$transaction_desc = strip_tags($transaction_desc);
	$transaction_desc = htmlspecialchars($transaction_desc);
	
	$transaction_desc = $transaction_desc.' - '.$quantity.' '.$category;
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	$debit_alias = $_POST['debit_alias'];
	$credit_alias = $_POST['credit_alias'];
	$balance = "";
	
	if (empty($debit_alias)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_alias)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	$query_acct1 .= "WHERE acct_alias = '$debit_alias'";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$debit_account = $acct_debit_table["acct_id"];
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	$query_acct2 .= "WHERE acct_alias = '$credit_alias'";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$credit_account = $acct_credit_table["acct_id"];
	$db_credit_table = $acct_credit_table["acct_table_name"];

	
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	
	$payment_category = "Other Collection";
		
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,ticket_category,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,no_of_tickets,remit_id) VALUES('$txref','$date_of_payment','$category','$transaction_desc','$receipt_no','$amount_paid','$remitting_id','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','$quantity','$remit_id')";
		
		$post_payment = mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

		
if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
}
}



//Loading and Offloading Processing
if ( isset($_POST['btn_post_loading']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	
	$income_line = $_POST['income_line'];
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$category = $_POST['category'];
	$plate_no = $_POST['plate_no'];
	$no_of_days = $_POST['no_of_days'];
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_post = $_POST['remitting_staff'];
	list($remitting_id,$remitting_check) = explode("-",@$remitting_post);
	
	if ($remitting_check == "wc"){
		$squery = "SELECT * FROM staffs WHERE user_id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	} else {
		$squery = "SELECT * FROM staffs_others WHERE id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	}
	
	$transaction_desc = $category;
	$transaction_desc = htmlspecialchars($transaction_desc);
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	$debit_alias = $_POST['debit_alias'];
	$credit_alias = $_POST['credit_account'];
	$balance = "";
	
	if (empty($debit_alias)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_alias)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	$query_acct1 .= "WHERE acct_alias = '$debit_alias'";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$debit_account = $acct_debit_table["acct_id"];
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	$query_acct2 .= "WHERE acct_id = '$credit_alias'";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$credit_account = $acct_credit_table["acct_id"];
	$credit_account_desc = $acct_credit_table["acct_desc"];
	$db_credit_table = $acct_credit_table["acct_table_name"];

	
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$payment_category = "Other Collection";
		
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,plate_no,no_of_days,remit_id) VALUES('$txref','$date_of_payment','$transaction_desc','$receipt_no','$amount_paid','$remitting_id','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','$plate_no','$no_of_days','$remit_id')";
		
		$post_payment = mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

		
if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
}
}



//Overnight Parking Processing
if ( isset($_POST['btn_post_overnight_parking']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	
	$income_line = $_POST['income_line'];
	
	$txref = time().mt_rand(0,9);
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$type_category = $_POST['type'];
	
	if ($type_category == "Vehicle") {
		$category = "Vehicle";
		$plate_no = $_POST['plate_no'];
		$no_of_nights = $_POST['no_of_nights'];
		
		$transaction_desc = $_POST['vehicle_category'];
		$transaction_desc = htmlspecialchars($transaction_desc);
	} elseif ($type_category == "Forklift Operator") {
		$category = "Forklift Operator";
		$plate_no = "";
		$no_of_nights = $_POST['no_of_nights'];
		
		$transaction_desc = $_POST['transaction_descr'];
		$transaction_desc = htmlspecialchars($transaction_desc);
	} elseif ($type_category == "Artisan") {
		$category = $_POST['artisan_category'];
		$plate_no = "";
		$no_of_nights = $_POST['no_of_nights'];
		
		$transaction_desc = $_POST['transaction_descr'];
		$transaction_desc = htmlspecialchars($transaction_desc);
	} else {
		$error = true;
		$category = "";
		$plate_no = "";
		$no_of_nights = "";
		$transaction_desc = "";
	}
	
	
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$remitting_post = $_POST['remitting_staff'];
	list($remitting_id,$remitting_check) = explode("-",@$remitting_post);
	
	if ($remitting_check == "wc"){
		$squery = "SELECT * FROM staffs WHERE user_id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	} else {
		$squery = "SELECT * FROM staffs_others WHERE id='$remitting_id'";
		$sresult = mysqli_query($dbcon,$squery);
		$remitting_data = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
		$remitting_staff = $remitting_data['full_name'];
	}
	
	
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	$debit_alias = $_POST['debit_alias'];
	$credit_alias = $_POST['credit_alias'];
	$balance = "";
	
	if (empty($debit_alias)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_alias)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	$query_acct1 .= "WHERE acct_alias = '$debit_alias'";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$debit_account = $acct_debit_table["acct_id"];
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	$query_acct2 .= "WHERE acct_alias = '$credit_alias'";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$credit_account = $acct_credit_table["acct_id"];
	$credit_account_desc = $acct_credit_table["acct_desc"];
	$db_credit_table = $acct_credit_table["acct_table_name"];

	
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$payment_category = "Other Collection";
		
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,ticket_category,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,plate_no,no_of_nights,remit_id) VALUES('$txref','$date_of_payment','$category','$transaction_desc','$receipt_no','$amount_paid','$remitting_id','$remitting_staff','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','$plate_no','$no_of_nights','$remit_id')";
		
		$post_payment = mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

		
if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
}
}



//Car Sticker Processing
if ( isset($_POST['btn_post_car_sticker']) ) {
	$posting_officer_dept = $_POST['posting_officer_dept'];
	
	if ($posting_officer_dept == "Accounts"){
		$remit_id = "";
	} else {
		$remit_id = $_POST['remit_id'];
		if($remit_id == "" || $remit_id == " "){
			$error = true;
		} else {
			$remit_id = $_POST['remit_id'];
		}
	}
	
	
	$income_line = $_POST['income_line'];
	
	$txref = time().mt_rand(0,9);
	
	$selected_shop_no = $_POST['shop_no'];
	list($shop_no,$customer_name) = explode("-",$selected_shop_no);

	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	
	$sticker_no = $_POST['sticker_no'];
	$plate_no = $_POST['plate_no'];
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$transaction_desc = "Car Sticker ($sticker_no) $shop_no - $customer_name";
	$transaction_desc = htmlspecialchars($transaction_desc);
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	if ($posting_officer_dept == "Accounts"){
		$leasing_post_status = "";
		$approval_status = "Pending";
		$verification_status = "Pending";
	} else {
		$leasing_post_status = "Pending";
		$approval_status = "";
		$verification_status = "";
	}
	
	$debit_account = $_POST['debit_account'];
	$credit_account = $_POST['credit_account'];
	$balance = "";
	
	if (empty($debit_account)) {
		$error = true;
		$debiterror = "Please select the debit account";
	}
	if (empty($credit_account)) {
		$error = true;
		$crediterror = "Please select the credit account";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM accounts ";
	$query_acct1 .= "WHERE acct_id = '$debit_account'";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM accounts ";
	$query_acct2 .= "WHERE acct_id = '$credit_account'";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$db_credit_table = $acct_credit_table["acct_table_name"];
	$db_transaction_table = "account_general_transaction_new";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$payment_category = "Other Collection";
		
		$query = "INSERT INTO $db_transaction_table (id,date_of_payment,transaction_desc,receipt_no,amount_paid,remitting_id,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,approval_status,verification_status,debit_account,credit_account,payment_category,plate_no,sticker_no,remit_id) VALUES('$txref','$date_of_payment','$transaction_desc','$receipt_no','$amount_paid','','','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category','$plate_no','$sticker_no','$remit_id')";
		$post_payment = mysqli_query($dbcon, $query);
		
		
		$st_query = "UPDATE car_sticker SET trans_id='$txref', shop_no='$shop_no', customer_name='$customer_name', date_of_payment='$date_of_payment', receipt_no='$receipt_no', status='Sold' WHERE sticker_no='$sticker_no'";
		$st_query = @mysqli_query($dbcon, $st_query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,date,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$debit_query = mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,date,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account','$date_of_payment','$receipt_no','$transaction_desc','$amount_paid','$balance','$approval_status')";
		
		$credit_query = mysqli_query($dbcon, $cquery);

if ($debit_query)
	{
		?>
		<script type="text/javascript">
		alert('Payment successfully posted for approval!');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
		alert('Error occured while posting');
		window.location.href='payments_past.php';
		</script>
		<?php
	}
}
}

?>