<?php
$error = false;
 
if ( isset($_POST['btn_post']) ) {
	$expected_rent = $_POST['monthly_expected'];
	$total_expected_rent = $_POST['yearly_expected'];
	
	
	$year1 = $_POST['year1'];
	$amount1 = $_POST['amount1'];
	if($amount1 == $expected_rent){
		$payment_status1 = "Full";
	}else{
		$payment_status1 = "Part";
	}
	
	$year2 = $_POST['year2'];
	$amount2 = $_POST['amount2'];
	if($amount2 == $expected_rent){
		$payment_status2 = "Full";
	}else{
		$payment_status2 = "Part";
	}
	
	$year3 = $_POST['year3'];
	$amount3 = $_POST['amount3'];
	if($amount3 == $expected_rent){
		$payment_status3 = "Full";
	}else{
		$payment_status3 = "Part";
	}
	
	$year4 = $_POST['year4'];
	$amount4 = $_POST['amount4'];
	if($amount4 == $expected_rent){
		$payment_status4 = "Full";
	}else{
		$payment_status4 = "Part";
	}
	
	$year5 = $_POST['year5'];
	$amount5 = $_POST['amount5'];
	if($amount5 == $expected_rent){
		$payment_status5 = "Full";
	}else{
		$payment_status5 = "Part";
	}
	
	$year6 = $_POST['year6'];
	$amount6 = $_POST['amount6'];
	if($amount6 == $expected_rent){
		$payment_status6 = "Full";
	}else{
		$payment_status6 = "Part";
	}
	
	$year7 = $_POST['year7'];
	$amount7 = $_POST['amount7'];
	if($amount7 == $expected_rent){
		$payment_status7 = "Full";
	}else{
		$payment_status7 = "Part";
	}
	
	$year8 = $_POST['year8'];
	$amount8 = $_POST['amount8'];
	if($amount8 == $expected_rent){
		$payment_status8 = "Full";
	}else{
		$payment_status8 = "Part";
	}
	
	$year9 = $_POST['year9'];
	$amount9 = $_POST['amount9'];
	if($amount9 == $expected_rent){
		$payment_status9 = "Full";
	}else{
		$payment_status9 = "Part";
	}
	
	$year10 = $_POST['year10'];
	$amount10 = $_POST['amount10'];
	if($amount10 == $expected_rent){
		$payment_status10 = "Full";
	}else{
		$payment_status10 = "Part";
	}
	
	$year11 = $_POST['year11'];
	$amount11 = $_POST['amount11'];
	if($amount11 == $expected_rent){
		$payment_status11 = "Full";
	}else{
		$payment_status11 = "Part";
	}
	
	$year12 = $_POST['year12'];
	$amount12 = $_POST['amount12'];
	if($amount12 == $expected_rent){
		$payment_status12 = "Full";
	}else{
		$payment_status12 = "Part";
	}
	
	
  // clean user inputs to prevent sql injections
	$shop_id = $_POST['shopid'];
	$customer = $_POST['customername'];
	$shop_no = $_POST['shopno'];
	$shop_size = $_POST['shopsize'];
	$facility_type = $_POST['facility_type'];
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	$date_on_receipt = $_POST['date_on_receipt'];
	list($tid,$tim,$tiy) = explode("/",$date_on_receipt);
	$date_on_receipt = "$tiy-$tim-$tid";
	
	$start_date = "";
	$end_date = "";
	$payment_type = $_POST['payment_type'];
	$payment_category = "Rent";
		
	$transaction_desc = $_POST['transaction_desc'];
	$transaction_desc = $shop_no.' - '.$transaction_desc;
	
	$add_transaction_desc = $_POST['add_transaction_desc'];
	
	if ($payment_type == "Transfer") {
		$transaction_desc = $transaction_desc.' - '.$add_transaction_desc;
	} 

	$cheque_no = $_POST['cheque_no'];
	$ref_cheque_no = $cheque_no;
	$journal_no = "";
	$teller_no = $_POST['teller_no'];
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM {$prefix}account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer!</h4>";
	}
	
	$amount = $_POST['amount_paid'];
	
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	$leasing_post_status = "";
	$approval_status = "Pending";
	$verification_status = "";
	$balance = "";
	
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
	
	$txref = time().mt_rand(0,99);

						
	$query = "SELECT SUM(amount_paid) as amount_paid ";
	$query .= "FROM {$prefix}account_general_transaction ";
	$query .= "WHERE shop_id = '$shop_id' ";
	$query .= "AND payment_category = 'Rent' ";
	$query .= "AND approval_status = 'Approved'";
	$sum = @mysqli_query($dbcon,$query);
	$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);


	$query_n = "SELECT SUM(amount_paid) as amount_paid ";
	$query_n .= "FROM {$prefix}account_general_transaction_new ";
	$query_n .= "WHERE shop_id = '$shop_id'";
	$query_n .= "AND payment_category = 'Rent' ";
	$sum_n = @mysqli_query($dbcon,$query_n);
	$total_n = @mysqli_fetch_array($sum_n, MYSQLI_ASSOC);


	
	
	$total_expected_rent = preg_replace('/[,]/', '', $total_expected_rent);
	$total_expected_rent = ($total_expected_rent + 0);
	if (!is_float($total_expected_rent)) {
		$total_expected_rent = (float)$total_expected_rent;
	}

	$total_till1_payments = @$total['amount_paid'];
	$total_till2_payments = @$total_n['amount_paid'];
	$acct_ledger_paid = $total_till1_payments + $total_till2_payments;


	$cbal_query = "SELECT * FROM customers ";
	$cbal_query .= "WHERE id = '$shop_id'";
	$cbal_result = @mysqli_query($dbcon, $cbal_query);
	$customer_acct = @mysqli_fetch_array($cbal_result, MYSQLI_ASSOC);

	$record_amt_paid = $customer_acct['rent_paid'];
	@$paid = $acct_ledger_paid + $record_amt_paid;
	$cbalance = ($total_expected_rent - $paid);

	$customer_bal = $cbalance;
	$customer_bal = preg_replace('/[,]/', '', $customer_bal);
	
	
	//Check for duplicate entries
	$array = array();
	if ($year1 != '' || $year1 != 0){
	$array[] = $year1;}
	if ($year2 != '' || $year2 != 0){
	$array[] = $year2;}
	if ($year3 != '' || $year3 != 0){
	$array[] = $year3;}
	if ($year4 != '' || $year4 != 0){
	$array[] = $year4;}
	if ($year5 != '' || $year5 != 0){
	$array[] = $year5;}
	if ($year6 != '' || $year6 != 0){
	$array[] = $year6;}
	if ($year7 != '' || $year7 != 0){
	$array[] = $year7;}
	if ($year8 != '' || $year8 != 0){
	$array[] = $year8;}
	if ($year9 != '' || $year9 != 0){
	$array[] = $year9;}
	if ($year10 != '' || $year10 != 0){
	$array[] = $year10;}
	if ($year11 != '' || $year11 != 0){
	$array[] = $year11;}
	if ($year12 != '' || $year12 != 0){
	$array[] = $year12;}
		
	$unique = array_unique($array);

	if ( count($array) != count($unique) ) {
	$error = true;
		$error = true;
		$duplicate_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! DUPLICATE month and year found!</h4>";
	} 
	
	if($customer_bal == 0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>customer's service charge balance is: &#8358 {$customer_bal}</strong>. You MUST renew the lease before posting fresh payment!</h4>";
	}
	
	if($customer_bal < 0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>customer's service charge balance CANNOT be: &#8358{$customer_bal}</strong>. Please resolve this before posting fresh payment!</h4>";
	}
	
	if($customer_bal < $amount_paid){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>amount paid: &#8358 {$amount_paid} is MORE THAN the customer's balance of &#8358 {$customer_bal}</strong>. This will result in a negative balance. Please resolve before posting fresh payment!</h4>";
	}
	
	//Balance Check1
	$mbal_query1 = "SELECT SUM(amount_paid) as ctn_amount_paid1 FROM collection_analysis{$suffix} ";
	$mbal_query1 .= "WHERE shop_no='$shop_no' AND payment_month='$year1'";
	$mbal_result1 = @mysqli_query($dbcon, $mbal_query1);
	$mbal_acct1 = @mysqli_fetch_array($mbal_result1, MYSQLI_ASSOC);

	$ctn_amount_paid1 = $mbal_acct1['ctn_amount_paid1'];
	$ctn_bal_remaining1 = ($expected_rent - $ctn_amount_paid1);
	
	if($amount1 > $ctn_bal_remaining1){
		$error = true;
		$payment_Error1 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount1} paid for {$year1} is MORE THAN the customer's {$year1} balance of &#8358 {$ctn_bal_remaining1}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	
	//Balance Check2
	$mbal_query2 = "SELECT SUM(amount_paid) as ctn_amount_paid2 FROM collection_analysis{$suffix} ";
	$mbal_query2 .= "WHERE shop_no='$shop_no' AND payment_month='$year2'";
	$mbal_result2 = @mysqli_query($dbcon, $mbal_query2);
	$mbal_acct2 = @mysqli_fetch_array($mbal_result2, MYSQLI_ASSOC);

	$ctn_amount_paid2 = $mbal_acct2['ctn_amount_paid2'];
	$ctn_bal_remaining2 = ($expected_rent - $ctn_amount_paid2);
	
	if($amount2 > $ctn_bal_remaining2){
		$error = true;
		$payment_Error2 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount2} paid for {$year2} is MORE THAN the customer's {$year2} balance of &#8358 {$ctn_bal_remaining2}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	
	//Balance Check3
	$mbal_query3 = "SELECT SUM(amount_paid) as ctn_amount_paid3 FROM collection_analysis{$suffix} ";
	$mbal_query3 .= "WHERE shop_no='$shop_no' AND payment_month='$year3'";
	$mbal_result3 = @mysqli_query($dbcon, $mbal_query3);
	$mbal_acct3 = @mysqli_fetch_array($mbal_result3, MYSQLI_ASSOC);

	$ctn_amount_paid3 = $mbal_acct3['ctn_amount_paid3'];
	$ctn_bal_remaining3 = ($expected_rent - $ctn_amount_paid3);
	
	if($amount3 > $ctn_bal_remaining3){
		$error = true;
		$payment_Error3 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount3} paid for {$year3} is MORE THAN the customer's {$year3} balance of &#8358 {$ctn_bal_remaining3}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	
	//Balance Check4
	$mbal_query4 = "SELECT SUM(amount_paid) as ctn_amount_paid4 FROM collection_analysis{$suffix} ";
	$mbal_query4 .= "WHERE shop_no='$shop_no' AND payment_month='$year4'";
	$mbal_result4 = @mysqli_query($dbcon, $mbal_query4);
	$mbal_acct4 = @mysqli_fetch_array($mbal_result4, MYSQLI_ASSOC);

	$ctn_amount_paid4 = $mbal_acct4['ctn_amount_paid4'];
	$ctn_bal_remaining4 = ($expected_rent - $ctn_amount_paid4);
	
	if($amount4 > $ctn_bal_remaining4){
		$error = true;
		$payment_Error4 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount4} paid for {$year4} is MORE THAN the customer's {$year4} balance of &#8358 {$ctn_bal_remaining4}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	
	//Balance Check5
	$mbal_query5 = "SELECT SUM(amount_paid) as ctn_amount_paid5 FROM collection_analysis{$suffix} ";
	$mbal_query5 .= "WHERE shop_no='$shop_no' AND payment_month='$year5'";
	$mbal_result5 = @mysqli_query($dbcon, $mbal_query5);
	$mbal_acct5 = @mysqli_fetch_array($mbal_result5, MYSQLI_ASSOC);

	$ctn_amount_paid5 = $mbal_acct5['ctn_amount_paid5'];
	$ctn_bal_remaining5 = ($expected_rent - $ctn_amount_paid5);
	
	if($amount5 > $ctn_bal_remaining5){
		$error = true;
		$payment_Error5 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount5} paid for {$year5} is MORE THAN the customer's {$year5} balance of &#8358 {$ctn_bal_remaining5}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	//Balance Check6
	$mbal_query6 = "SELECT SUM(amount_paid) as ctn_amount_paid6 FROM collection_analysis{$suffix} ";
	$mbal_query6 .= "WHERE shop_no='$shop_no' AND payment_month='$year6'";
	$mbal_result6 = @mysqli_query($dbcon, $mbal_query6);
	$mbal_acct6 = @mysqli_fetch_array($mbal_result6, MYSQLI_ASSOC);

	$ctn_amount_paid6 = $mbal_acct6['ctn_amount_paid6'];
	$ctn_bal_remaining6 = ($expected_rent - $ctn_amount_paid6);
	
	if($amount6 > $ctn_bal_remaining6){
		$error = true;
		$payment_Error6 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount6} paid for {$year6} is MORE THAN the customer's {$year6} balance of &#8358 {$ctn_bal_remaining6}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	//Balance Check7
	$mbal_query7 = "SELECT SUM(amount_paid) as ctn_amount_paid7 FROM collection_analysis{$suffix} ";
	$mbal_query7 .= "WHERE shop_no='$shop_no' AND payment_month='$year7'";
	$mbal_result7 = @mysqli_query($dbcon, $mbal_query7);
	$mbal_acct7 = @mysqli_fetch_array($mbal_result7, MYSQLI_ASSOC);

	$ctn_amount_paid7 = $mbal_acct7['ctn_amount_paid7'];
	$ctn_bal_remaining7 = ($expected_rent - $ctn_amount_paid7);
	
	if($amount7 > $ctn_bal_remaining7){
		$error = true;
		$payment_Error7 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount7} paid for {$year7} is MORE THAN the customer's {$year7} balance of &#8358 {$ctn_bal_remaining7}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	//Balance Check8
	$mbal_query8 = "SELECT SUM(amount_paid) as ctn_amount_paid8 FROM collection_analysis{$suffix} ";
	$mbal_query8 .= "WHERE shop_no='$shop_no' AND payment_month='$year8'";
	$mbal_result8 = @mysqli_query($dbcon, $mbal_query8);
	$mbal_acct8 = @mysqli_fetch_array($mbal_result8, MYSQLI_ASSOC);

	$ctn_amount_paid8 = $mbal_acct8['ctn_amount_paid8'];
	$ctn_bal_remaining8 = ($expected_rent - $ctn_amount_paid8);
	
	if($amount8 > $ctn_bal_remaining8){
		$error = true;
		$payment_Error8 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount8} paid for {$year8} is MORE THAN the customer's {$year8} balance of &#8358 {$ctn_bal_remaining8}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	//Balance Check9
	$mbal_query9 = "SELECT SUM(amount_paid) as ctn_amount_paid9 FROM collection_analysis{$suffix} ";
	$mbal_query9 .= "WHERE shop_no='$shop_no' AND payment_month='$year9'";
	$mbal_result9 = @mysqli_query($dbcon, $mbal_query9);
	$mbal_acct9 = @mysqli_fetch_array($mbal_result9, MYSQLI_ASSOC);

	$ctn_amount_paid9 = $mbal_acct9['ctn_amount_paid9'];
	$ctn_bal_remaining9 = ($expected_rent - $ctn_amount_paid9);
	
	if($amount9 > $ctn_bal_remaining9){
		$error = true;
		$payment_Error9 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount9} paid for {$year9} is MORE THAN the customer's {$year9} balance of &#8358 {$ctn_bal_remaining9}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	//Balance Check10
	$mbal_query10 = "SELECT SUM(amount_paid) as ctn_amount_paid10 FROM collection_analysis{$suffix} ";
	$mbal_query10 .= "WHERE shop_no='$shop_no' AND payment_month='$year10'";
	$mbal_result10 = @mysqli_query($dbcon, $mbal_query10);
	$mbal_acct10 = @mysqli_fetch_array($mbal_result10, MYSQLI_ASSOC);

	$ctn_amount_paid10 = $mbal_acct10['ctn_amount_paid10'];
	$ctn_bal_remaining10 = ($expected_rent - $ctn_amount_paid10);
	
	if($amount10 > $ctn_bal_remaining10){
		$error = true;
		$payment_Error10 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount10} paid for {$year10} is MORE THAN the customer's {$year10} balance of &#8358 {$ctn_bal_remaining10}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	//Balance Check11
	$mbal_query11 = "SELECT SUM(amount_paid) as ctn_amount_paid11 FROM collection_analysis{$suffix} ";
	$mbal_query11 .= "WHERE shop_no='$shop_no' AND payment_month='$year11'";
	$mbal_result11 = @mysqli_query($dbcon, $mbal_query11);
	$mbal_acct11 = @mysqli_fetch_array($mbal_result11, MYSQLI_ASSOC);

	$ctn_amount_paid11 = $mbal_acct11['ctn_amount_paid11'];
	$ctn_bal_remaining11 = ($expected_rent - $ctn_amount_paid11);
	
	if($amount11 > $ctn_bal_remaining11){
		$error = true;
		$payment_Error11 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount11} paid for {$year11} is MORE THAN the customer's {$year11} balance of &#8358 {$ctn_bal_remaining11}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	
	//Balance Check12
	$mbal_query12 = "SELECT SUM(amount_paid) as ctn_amount_paid12 FROM collection_analysis{$suffix} ";
	$mbal_query12 .= "WHERE shop_no='$shop_no' AND payment_month='$year12'";
	$mbal_result12 = @mysqli_query($dbcon, $mbal_query12);
	$mbal_acct12 = @mysqli_fetch_array($mbal_result12, MYSQLI_ASSOC);

	$ctn_amount_paid12 = $mbal_acct12['ctn_amount_paid12'];
	$ctn_bal_remaining12 = ($expected_rent - $ctn_amount_paid12);
	
	if($amount12 > $ctn_bal_remaining12){
		$error = true;
		$payment_Error12 = "<h4><strong>ATTENTION:</strong> Transaction failed! <strong>&#8358 {$amount12} paid for {$year12} is MORE THAN the customer's {$year12} balance of &#8358 {$ctn_bal_remaining12}</strong> . This will result in over payment for the period. Please resolve accordingly!</h4>";
	}
	
	
//Check Payment Month 
	list($fdy,$fdm,$fdd) = explode("-",$customer_acct["lease_start_date"]);
	$falling_due_month = $fdm;
	$current_year = date('Y');
		
	if($fdm == "01"){
		$falling_due_month = "January {$current_year}";
	}
	elseif($fdm == "02"){
		$falling_due_month = "February {$current_year}";
	}
	elseif($fdm == "03"){
		$falling_due_month = "March {$current_year}";
	}
	elseif($fdm == "04"){
		$falling_due_month = "April {$current_year}";
	}
	elseif($fdm == "05"){
		$falling_due_month = "May {$current_year}";
	}
	elseif($fdm == "06"){
		$falling_due_month = "June {$current_year}";
	}
	elseif($fdm == "07"){
		$falling_due_month = "July {$current_year}";
	}
	elseif($fdm == "08"){
		$falling_due_month = "August {$current_year}";
	}
	elseif($fdm == "09"){
		$falling_due_month = "September {$current_year}";
	}
	elseif($fdm == "10"){
		$falling_due_month = "October {$current_year}";
	}
	elseif($fdm == "11"){
		$falling_due_month = "November {$current_year}";
	}
	elseif($fdm == "12"){
		$falling_due_month = "December {$current_year}";
	}else {
		echo "";
	}
	
	
if (!$error) {
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM {$prefix}accounts ";
	$query_acct1 .= "WHERE acct_id = $debit_account";
	$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
	$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
	
	$db_debit_table = $acct_debit_table["acct_table_name"];
	
	
	$query_acct2 = "SELECT * ";
	$query_acct2 .= "FROM {$prefix}accounts ";
	$query_acct2 .= "WHERE acct_id = $credit_account";
	$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
	$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
	
	$db_credit_table = $acct_credit_table["acct_table_name"];
	
	$db_transaction_table_new = "{$prefix}account_general_transaction_new";	
	$db_collection_table = "collection_analysis{$suffix}";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
		
		
		$query = "INSERT INTO $db_transaction_table_new (id,shop_id,customer_name,shop_no,shop_size,date_of_payment,date_on_receipt,start_date,end_date,payment_type,transaction_desc,bank_name,cheque_no,teller_no,receipt_no,amount_paid,remitting_customer,remitting_staff,posting_officer_id,posting_officer_name,posting_time,leasing_post_status,leasing_post_approving_officer_id,leasing_post_approving_officer_name,leasing_post_approval_time,approval_status,verification_status,debit_account,credit_account,payment_category) VALUES('$txref','$shop_id','$customer','$shop_no','$shop_size','$date_of_payment','$date_on_receipt','$start_date','$end_date','$payment_type','$transaction_desc','','$cheque_no','$teller_no','$receipt_no','$amount_paid','','','$posting_officer_id','$posting_officer_name','$now','$leasing_post_status','','','','$approval_status','$verification_status','$debit_account','$credit_account','$payment_category')";
		$post_payment = @mysqli_query($dbcon, $query);


		$dquery = "INSERT INTO $db_debit_table (id,acct_id,shop_no,date,date_on_receipt,ref_cheque_no,journal_no,receipt_no,trans_desc,debit_amount,approval_status) VALUES('$txref','$debit_account','$shop_no','$date_of_payment','$date_on_receipt','$ref_cheque_no','$journal_no','$receipt_no','$transaction_desc','$amount_paid','$approval_status')";
		$debit_query = @mysqli_query($dbcon, $dquery);
		
	
		$cquery = "INSERT INTO $db_credit_table (id,acct_id,shop_no,date,date_on_receipt,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,approval_status) VALUES('$txref','$credit_account','$shop_no','$date_of_payment','$date_on_receipt','$ref_cheque_no','$journal_no','$receipt_no','$transaction_desc','$amount_paid','$approval_status')";
		$credit_query = @mysqli_query($dbcon, $cquery);
		
		
		$ca_query1 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year1','$amount1','$receipt_no','$expected_rent','$payment_status1','$now','$posting_officer_id','$posting_officer_name')";
		$ca_query1 = @mysqli_query($dbcon, $ca_query1);
		
		if($year2 != "" && $amount2 != ""){
			$ca_query2 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year2','$amount2','$receipt_no','$expected_rent','$payment_status2','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query2 = @mysqli_query($dbcon, $ca_query2);
		}
		
		if($year3 != "" && $amount3 != ""){
			$ca_query3 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year3','$amount3','$receipt_no','$expected_rent','$payment_status3','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query3 = mysqli_query($dbcon, $ca_query3);
		}
		
		if($year4 != "" && $amount4 != ""){
			$ca_query4 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year4','$amount4','$receipt_no','$expected_rent','$payment_status4','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query4 = @mysqli_query($dbcon, $ca_query4);
		}

		if($year5 != "" && $amount5 != ""){
			$ca_query5 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year5','$amount5','$receipt_no','$expected_rent','$payment_status5','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query5 = @mysqli_query($dbcon, $ca_query5);
		}
		
		if($year6 != "" && $amount6 != ""){
			$ca_query6 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year6','$amount6','$receipt_no','$expected_rent','$payment_status6','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query6 = @mysqli_query($dbcon, $ca_query6);
		}
		
		
		if($year7 != "" && $amount7 != ""){
			$ca_query7 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year7','$amount7','$receipt_no','$expected_rent','$payment_status7','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query7 = @mysqli_query($dbcon, $ca_query7);
		}
		
		if($year8 != "" && $amount8 != ""){
			$ca_query8 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year8','$amount8','$receipt_no','$expected_rent','$payment_status8','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query8 = @mysqli_query($dbcon, $ca_query8);
		}
		
		if($year9 != "" && $amount9 != ""){
			$ca_query9 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year9','$amount9','$receipt_no','$expected_rent','$payment_status9','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query9 = @mysqli_query($dbcon, $ca_query9);
		}
		
		if($year10 != "" && $amount10 != ""){
			$ca_query10 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year10','$amount10','$receipt_no','$expected_rent','$payment_status10','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query10 = @mysqli_query($dbcon, $ca_query10);
		}
		
		
		if($year11 != "" && $amount11 != ""){
			$ca_query11 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year11','$amount11','$receipt_no','$expected_rent','$payment_status11','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query11 = @mysqli_query($dbcon, $ca_query11);
		}
		
		if($year12 != "" && $amount12 != ""){
			$ca_query12 = "INSERT INTO $db_collection_table (id,trans_id,shop_id,shop_no,customer_name,date_of_payment,falling_due_month,payment_month,amount_paid,receipt_no,expected_rent,payment_status,posting_time,posting_officer_id,posting_officer_name) VALUES('','$txref','$shop_id','$shop_no','$customer','$date_of_payment','$falling_due_month','$year12','$amount12','$receipt_no','$expected_rent','$payment_status12','$now','$posting_officer_id','$posting_officer_name')";
			$ca_query12 = @mysqli_query($dbcon, $ca_query12);
		}
		
	if ($credit_query)
		{
			?>
			<script type="text/javascript">
			alert('Payment successfully posted for approval!');
			window.location.href='post_trans.php';
			</script>
			<?php
		}
		else
		{
			?>
			<script type="text/javascript">
			alert('Error occured while posting');
			window.location.href='post_trans.php';
			</script>
			<?php
		}
	}
} 
?>