<?php
include 'include/session.php';

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$page_title = "{$project} Journal - Wealth Creation ERP";

$out_of_balanceERROR = false;
$error = false;
$repost_id = "";

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


//Correction of Previously Posted Journal Entry
if (isset($_GET['repost_id']) && isset($_POST['btn_post_journal_edit'])) {
	$repost_id = $_GET['repost_id'];
	$txref = $repost_id;
	
	$query = "SELECT * FROM {$prefix}account_general_transaction_new WHERE id='$repost_id'";
	$result = mysqli_query($dbcon,$query);
	$pdata = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	$flag_status = $pdata['flag_status'];
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	if($date_of_payment == "" || $date_of_payment == "00-00-0000" || $date_of_payment == "0000-00-00"){
		$error = true;
		$date_Error = "<h4><strong>ATTENTION:</strong> Invalid date format! $date_of_payment </h4>";
	}
	
	$reference_no = $_POST['reference_no'];
	
	if($reference_no != "") {
		$query = "SELECT * FROM {$prefix}account_general_transaction_new WHERE cheque_no='$reference_no'";
		$result = mysqli_query($dbcon,$query);
		while ($reference_data = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$reference_posting_officer = $reference_data['posting_officer_name'];
			$reference_date_of_payment = $reference_data['date_of_payment'];
			$reference_txref = $reference_data['id'];

			if($txref != $reference_txref){
				$error = true;
				$reference_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>reference no: $reference_no</strong> you entered has already been used by $reference_posting_officer on $reference_date_of_payment!</h4>";
			}
		}
	}
	
	$plate_no = $_POST['plate_no'];
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM {$prefix}account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count > 1){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	$approval_status = "Pending";
	$verification_status = "";
	$entry_status = "Journal";
	
	
	if(!$error){
		//Posting of Form Variables of Journal Row 1 begins here		
			$debit_account1 = $_POST['debit_account1'];
			$dtransaction_desc1 = $_POST['dtransaction_desc1'];
			$debit_amount1 = $_POST['debit_amount1'];
			$debit_amount1 = preg_replace('/[,]/', '', $debit_amount1);

		//Posting of Form Variables of Journal Row 2 begins here	
			$credit_account1 = $_POST['credit_account1'];
			$ctransaction_desc1 = $_POST['ctransaction_desc1'];
			$credit_amount1 = $_POST['credit_amount1'];
			$credit_amount1 = preg_replace('/[,]/', '', $credit_amount1);
		
	if ($debit_account1 != "" && $debit_amount1 != "" && $credit_account1 != "") {
		
		//If no error, initiate a delete query of previous entry
		$_GET['delete_id'] = $repost_id;
		include ('controllers/acct_delete_query.php');
		
		//Posting of Form Variables of Journal Row 1 begins here		
			$debit_account1 = $_POST['debit_account1'];
			$dtransaction_desc1 = $_POST['dtransaction_desc1'];
			$debit_amount1 = $_POST['debit_amount1'];
			$debit_amount1 = preg_replace('/[,]/', '', $debit_amount1);

		//Posting of Form Variables of Journal Row 2 begins here	
			$credit_account1 = $_POST['credit_account1'];
			$ctransaction_desc1 = $_POST['ctransaction_desc1'];
			$credit_amount1 = $_POST['credit_amount1'];
			$credit_amount1 = preg_replace('/[,]/', '', $credit_amount1);


		//Posting of Form Variables of Journal Row 3 begins here	
			$credit_account2 = $_POST['credit_account2'];
			$ctransaction_desc2 = $_POST['ctransaction_desc2'];
			$credit_amount2 = $_POST['credit_amount2'];
			$credit_amount2 = preg_replace('/[,]/', '', $credit_amount2);
			
		//Posting of Form Variables of Journal Row 4 begins here	
			$credit_account3 = $_POST['credit_account3'];
			$ctransaction_desc3 = $_POST['ctransaction_desc3'];
			$credit_amount3 = $_POST['credit_amount3'];
			$credit_amount3 = preg_replace('/[,]/', '', $credit_amount3);

		//Posting of Form Variables of Journal Row 5 begins here	
			$credit_account4 = $_POST['credit_account4'];
			$ctransaction_desc4 = $_POST['ctransaction_desc4'];
			$credit_amount4 = $_POST['credit_amount4'];
			$credit_amount4 = preg_replace('/[,]/', '', $credit_amount4);

		//Posting of Form Variables of Journal Row 6 begins here	
			$credit_account5 = $_POST['credit_account5'];
			$ctransaction_desc5 = $_POST['ctransaction_desc5'];
			$credit_amount5 = $_POST['credit_amount5'];
			$credit_amount5 = preg_replace('/[,]/', '', $credit_amount5);		
			
		//Posting of Form Variables of Journal Row 7 begins here	
			$credit_account6 = $_POST['credit_account6'];
			$ctransaction_desc6 = $_POST['ctransaction_desc6'];
			$credit_amount6 = $_POST['credit_amount6'];
			$credit_amount6 = preg_replace('/[,]/', '', $credit_amount6);

		//Posting of Form Variables of Journal Row 8 begins here	
			$credit_account7 = $_POST['credit_account7'];
			$ctransaction_desc7 = $_POST['ctransaction_desc7'];
			$credit_amount7 = $_POST['credit_amount7'];
			$credit_amount7 = preg_replace('/[,]/', '', $credit_amount7);
		
		//Arena General Transaction Input Query
		$db_transaction_table1 = "{$prefix}account_general_transaction_new";

		$query1 = "INSERT INTO $db_transaction_table1 (id,shop_id,customer_name,shop_no,shop_size,date_of_payment,start_date,end_date,payment_type,transaction_desc,bank_name,cheque_no,teller_no,receipt_no,amount_paid,remitting_customer,remitting_staff,posting_officer_id,posting_officer_name,posting_time,approval_status,verification_status,debit_account,credit_account,entry_status,credit_account_jrn2,credit_account_jrn3,credit_account_jrn4,credit_account_jrn5,credit_account_jrn6,credit_account_jrn7,debit_amount_jrn1,credit_amount_jrn1,credit_amount_jrn2,credit_amount_jrn3,credit_amount_jrn4,credit_amount_jrn5,credit_amount_jrn6,credit_amount_jrn7,plate_no,flag_status) VALUES('$txref','','','','','$date_of_payment','','','','$dtransaction_desc1','','$reference_no','','$receipt_no','$debit_amount1','','','$posting_officer_id','$posting_officer_name',NOW(),'$approval_status','$verification_status','$debit_account1','$credit_account1','$entry_status','$credit_account2','$credit_account3','$credit_account4','$credit_account5','$credit_account6','$credit_account7','$debit_amount1','$credit_amount1','$credit_amount2','$credit_amount3','$credit_amount4','$credit_amount5','$credit_amount6','$credit_amount7','$plate_no','$flag_status')";
		
		$gen_trans_acct1 = @mysqli_query($dbcon, $query1);
		
		
		//Debit Account 1 Input Query 
		$dquery_acct1 = "SELECT * ";
		$dquery_acct1 .= "FROM {$prefix}accounts ";
		$dquery_acct1 .= "WHERE acct_id = $debit_account1";
		$acct_debit_table_set1 = @mysqli_query($dbcon, $dquery_acct1);
		$acct_debit_table1 = @mysqli_fetch_array($acct_debit_table_set1, MYSQLI_ASSOC);
		
		$db_debit_table1 = $acct_debit_table1["acct_table_name"];
		
		
		$d_dbquery1 = "INSERT INTO $db_debit_table1 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account1','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$dtransaction_desc1','$debit_amount1','','$approval_status')";
		
		$debit_query1 = @mysqli_query($dbcon, $d_dbquery1);
	
				
		//Credit Account 1 Input Query 
		$cquery_acct1 = "SELECT * ";
		$cquery_acct1 .= "FROM {$prefix}accounts ";
		$cquery_acct1 .= "WHERE acct_id = $credit_account1";
		$acct_credit_table_set1 = @mysqli_query($dbcon, $cquery_acct1);
		$acct_credit_table1 = @mysqli_fetch_array($acct_credit_table_set1, MYSQLI_ASSOC);
		
		$db_credit_table1 = $acct_credit_table1["acct_table_name"];
		
		$c_dbquery1 = "INSERT INTO $db_credit_table1 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account1','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc1','$credit_amount1','','$approval_status')";
		
		$credit_query1 = @mysqli_query($dbcon, $c_dbquery1);
		
		
		//Credit Account 2 Input Query 
		$cquery_acct2 = "SELECT * ";
		$cquery_acct2 .= "FROM {$prefix}accounts ";
		$cquery_acct2 .= "WHERE acct_id = $credit_account2";
		$acct_credit_table_set2 = @mysqli_query($dbcon, $cquery_acct2);
		$acct_credit_table2 = @mysqli_fetch_array($acct_credit_table_set2, MYSQLI_ASSOC);
		
		$db_credit_table2 = $acct_credit_table2["acct_table_name"];
		
		$c_dbquery2 = "INSERT INTO $db_credit_table2 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account2','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc2','$credit_amount2','','$approval_status')";
		
		$credit_query2 = @mysqli_query($dbcon, $c_dbquery2);
		
		
		
		//Credit Account 3 Input Query 
		$cquery_acct3 = "SELECT * ";
		$cquery_acct3 .= "FROM {$prefix}accounts ";
		$cquery_acct3 .= "WHERE acct_id = $credit_account3";
		$acct_credit_table_set3 = @mysqli_query($dbcon, $cquery_acct3);
		$acct_credit_table3 = @mysqli_fetch_array($acct_credit_table_set3, MYSQLI_ASSOC);
		
		$db_credit_table3 = $acct_credit_table3["acct_table_name"];
		
		$c_dbquery3 = "INSERT INTO $db_credit_table3 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account3','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc3','$credit_amount3','','$approval_status')";
		
		$credit_query3 = @mysqli_query($dbcon, $c_dbquery3);
		
		
		
		//Credit Account 4 Input Query 
		$cquery_acct4 = "SELECT * ";
		$cquery_acct4 .= "FROM {$prefix}accounts ";
		$cquery_acct4 .= "WHERE acct_id = $credit_account4";
		$acct_credit_table_set4 = @mysqli_query($dbcon, $cquery_acct4);
		$acct_credit_table4 = @mysqli_fetch_array($acct_credit_table_set4, MYSQLI_ASSOC);
		
		$db_credit_table4 = $acct_credit_table4["acct_table_name"];
		
		$c_dbquery4 = "INSERT INTO $db_credit_table4 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account4','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc4','$credit_amount4','','$approval_status')";
		
		$credit_query4 = @mysqli_query($dbcon, $c_dbquery4);
		
		
		
		//Credit Account 5 Input Query 
		$cquery_acct5 = "SELECT * ";
		$cquery_acct5 .= "FROM {$prefix}accounts ";
		$cquery_acct5 .= "WHERE acct_id = $credit_account5";
		$acct_credit_table_set5 = @mysqli_query($dbcon, $cquery_acct5);
		$acct_credit_table5 = @mysqli_fetch_array($acct_credit_table_set5, MYSQLI_ASSOC);
		
		$db_credit_table5 = $acct_credit_table5["acct_table_name"];
		
		$c_dbquery5 = "INSERT INTO $db_credit_table5 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account5','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc5','$credit_amount5','','$approval_status')";
		
		$credit_query5 = @mysqli_query($dbcon, $c_dbquery5);
		
		
		
		//Credit Account 6 Input Query 
		$cquery_acct6 = "SELECT * ";
		$cquery_acct6 .= "FROM {$prefix}accounts ";
		$cquery_acct6 .= "WHERE acct_id = $credit_account6";
		$acct_credit_table_set6 = @mysqli_query($dbcon, $cquery_acct6);
		$acct_credit_table6 = @mysqli_fetch_array($acct_credit_table_set6, MYSQLI_ASSOC);
		
		$db_credit_table6 = $acct_credit_table6["acct_table_name"];
		
		$c_dbquery6 = "INSERT INTO $db_credit_table6 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account6','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc6','$credit_amount6','','$approval_status')";
		
		$credit_query6 = @mysqli_query($dbcon, $c_dbquery6);
		
		
		
		//Credit Account 7 Input Query 
		$cquery_acct7 = "SELECT * ";
		$cquery_acct7 .= "FROM {$prefix}accounts ";
		$cquery_acct7 .= "WHERE acct_id = $credit_account7";
		$acct_credit_table_set7 = @mysqli_query($dbcon, $cquery_acct7);
		$acct_credit_table7 = @mysqli_fetch_array($acct_credit_table_set7, MYSQLI_ASSOC);
		
		$db_credit_table7 = $acct_credit_table7["acct_table_name"];
		
		$c_dbquery7 = "INSERT INTO $db_credit_table7 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account7','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc7','$credit_amount7','','$approval_status')";
		
		$credit_query7 = @mysqli_query($dbcon, $c_dbquery7);
		
		
		if($gen_trans_acct1){
		?>
			<script type="text/javascript">
			alert('Journal entry successfully raised for approval!');
			window.location.href='pending_fc_approvals<?php echo $suffix; ?>.php';
			</script>
		<?php
		} else {
		?>
			<script type="text/javascript">
			alert('Error occured while posting');
			window.location.href='journal_entry<?php echo $suffix; ?>.php?repost_id=<?php echo $repost_id; ?>';
			</script>
		<?php
		}
	} else {
		?>
			<script type="text/javascript">
			alert('Error occured while posting');
			window.location.href='journal_entry<?php echo $suffix; ?>.php?repost_id=<?php echo $repost_id; ?>';
			</script>
		<?php
	}
	}
}



//Fresh Journal Posting
if (isset($_POST['btn_post_journal'])) {
	$error = false;
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	
	if($date_of_payment == "" || $date_of_payment == "00-00-0000" || $date_of_payment == "0000-00-00"){
		$error = true;
		$date_Error = "<h4><strong>ATTENTION:</strong> Invalid date format! $date_of_payment </h4>";
	}
	
	$reference_no = $_POST['reference_no'];	
	if($reference_no != "") {
		$query = "SELECT * FROM {$prefix}account_general_transaction_new WHERE cheque_no='$reference_no'";
		$result = mysqli_query($dbcon,$query);
		$reference_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
		$reference_posting_officer = $reference_data['posting_officer_name'];
		
		$count = mysqli_num_rows($result);
		if($count!=0){
			$error = true;
			$reference_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>reference no: $reference_no</strong> you entered has already been used by $reference_posting_officer!</h4>";
		}
	}
	
	$plate_no = $_POST['plate_no'];
	
	$receipt_no = $_POST['receipt_no'];
	$query = "SELECT * FROM {$prefix}account_general_transaction_new WHERE receipt_no='$receipt_no'";
	$result = mysqli_query($dbcon,$query);
	$receipt_data = @mysqli_fetch_array($result, MYSQLI_ASSOC);
	$receipt_posting_officer = $receipt_data['posting_officer_name'];
	$receipt_date_of_payment = $receipt_data['date_of_payment'];
	
	$count = mysqli_num_rows($result);
	if($count!=0){
		$error = true;
		$receipt_Error = "<h4><strong>ATTENTION:</strong> Transaction failed! The <strong>receipt No: $receipt_no</strong> you entered has already been used by $receipt_posting_officer on $receipt_date_of_payment!</h4>";
	}
	
	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	$approval_status = "Pending";
	$verification_status = "";
	$entry_status = "Journal";

//Posting of Form Variables of Journal Row 1 begins here		
	$debit_account1 = $_POST['debit_account1'];
	$dtransaction_desc1 = $_POST['dtransaction_desc1'];
	$debit_amount1 = $_POST['debit_amount1'];
	$debit_amount1 = preg_replace('/[,]/', '', $debit_amount1);

//Posting of Form Variables of Journal Row 2 begins here	
	$credit_account1 = $_POST['credit_account1'];
	$ctransaction_desc1 = $_POST['ctransaction_desc1'];
	$credit_amount1 = $_POST['credit_amount1'];
	$credit_amount1 = preg_replace('/[,]/', '', $credit_amount1);


//Posting of Form Variables of Journal Row 3 begins here	
	$credit_account2 = $_POST['credit_account2'];
	$ctransaction_desc2 = $_POST['ctransaction_desc2'];
	$credit_amount2 = $_POST['credit_amount2'];
	$credit_amount2 = preg_replace('/[,]/', '', $credit_amount2);

	
//Posting of Form Variables of Journal Row 4 begins here	
	$credit_account3 = $_POST['credit_account3'];
	$ctransaction_desc3 = $_POST['ctransaction_desc3'];
	$credit_amount3 = $_POST['credit_amount3'];
	$credit_amount3 = preg_replace('/[,]/', '', $credit_amount3);


//Posting of Form Variables of Journal Row 5 begins here	
	$credit_account4 = $_POST['credit_account4'];
	$ctransaction_desc4 = $_POST['ctransaction_desc4'];
	$credit_amount4 = $_POST['credit_amount4'];
	$credit_amount4 = preg_replace('/[,]/', '', $credit_amount4);


//Posting of Form Variables of Journal Row 6 begins here	
	$credit_account5 = $_POST['credit_account5'];
	$ctransaction_desc5 = $_POST['ctransaction_desc5'];
	$credit_amount5 = $_POST['credit_amount5'];
	$credit_amount5 = preg_replace('/[,]/', '', $credit_amount5);
	
	
//Posting of Form Variables of Journal Row 7 begins here	
	$credit_account6 = $_POST['credit_account6'];
	$ctransaction_desc6 = $_POST['ctransaction_desc6'];
	$credit_amount6 = $_POST['credit_amount6'];
	$credit_amount6 = preg_replace('/[,]/', '', $credit_amount6);
	

//Posting of Form Variables of Journal Row 8 begins here	
	$credit_account7 = $_POST['credit_account7'];
	$ctransaction_desc7 = $_POST['ctransaction_desc7'];
	$credit_amount7 = $_POST['credit_amount7'];
	$credit_amount7 = preg_replace('/[,]/', '', $credit_amount7);
	
	
	$txref = time().mt_rand(0,99);
	
	if(!$error){
	if ($debit_account1 != "" && $debit_amount1 != "" && $credit_account1 != "") {	
		
		//Arena General Transaction Input Query
		$db_transaction_table1 = "{$prefix}account_general_transaction_new";

		$query1 = "INSERT INTO $db_transaction_table1 (id,shop_id,customer_name,shop_no,shop_size,date_of_payment,start_date,end_date,payment_type,transaction_desc,bank_name,cheque_no,teller_no,receipt_no,amount_paid,remitting_customer,remitting_staff,posting_officer_id,posting_officer_name,posting_time,approval_status,verification_status,debit_account,credit_account,entry_status,credit_account_jrn2,credit_account_jrn3,credit_account_jrn4,credit_account_jrn5,credit_account_jrn6,credit_account_jrn7,debit_amount_jrn1,credit_amount_jrn1,credit_amount_jrn2,credit_amount_jrn3,credit_amount_jrn4,credit_amount_jrn5,credit_amount_jrn6,credit_amount_jrn7,plate_no) VALUES('$txref','','','','','$date_of_payment','','','','$dtransaction_desc1','','$reference_no','','$receipt_no','$debit_amount1','','','$posting_officer_id','$posting_officer_name',NOW(),'$approval_status','$verification_status','$debit_account1','$credit_account1','$entry_status','$credit_account2','$credit_account3','$credit_account4','$credit_account5','$credit_account6','$credit_account7','$debit_amount1','$credit_amount1','$credit_amount2','$credit_amount3','$credit_amount4','$credit_amount5','$credit_amount6','$credit_amount7','$plate_no')";
		
		$gen_trans_acct1 = @mysqli_query($dbcon, $query1);
		
		
		//Debit Account 1 Input Query 
		$dquery_acct1 = "SELECT * ";
		$dquery_acct1 .= "FROM {$prefix}accounts ";
		$dquery_acct1 .= "WHERE acct_id = $debit_account1";
		$acct_debit_table_set1 = @mysqli_query($dbcon, $dquery_acct1);
		$acct_debit_table1 = @mysqli_fetch_array($acct_debit_table_set1, MYSQLI_ASSOC);
		
		$db_debit_table1 = $acct_debit_table1["acct_table_name"];
		
		
		$d_dbquery1 = "INSERT INTO $db_debit_table1 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,debit_amount,balance,approval_status) VALUES('$txref','$debit_account1','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$dtransaction_desc1','$debit_amount1','','$approval_status')";
		
		$debit_query1 = @mysqli_query($dbcon, $d_dbquery1);
	
				
		//Credit Account 1 Input Query 
		$cquery_acct1 = "SELECT * ";
		$cquery_acct1 .= "FROM {$prefix}accounts ";
		$cquery_acct1 .= "WHERE acct_id = $credit_account1";
		$acct_credit_table_set1 = @mysqli_query($dbcon, $cquery_acct1);
		$acct_credit_table1 = @mysqli_fetch_array($acct_credit_table_set1, MYSQLI_ASSOC);
		
		$db_credit_table1 = $acct_credit_table1["acct_table_name"];
		
		$c_dbquery1 = "INSERT INTO $db_credit_table1 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account1','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc1','$credit_amount1','','$approval_status')";
		
		$credit_query1 = @mysqli_query($dbcon, $c_dbquery1);
		
		
		//Credit Account 2 Input Query 
		$cquery_acct2 = "SELECT * ";
		$cquery_acct2 .= "FROM {$prefix}accounts ";
		$cquery_acct2 .= "WHERE acct_id = $credit_account2";
		$acct_credit_table_set2 = @mysqli_query($dbcon, $cquery_acct2);
		$acct_credit_table2 = @mysqli_fetch_array($acct_credit_table_set2, MYSQLI_ASSOC);
		
		$db_credit_table2 = $acct_credit_table2["acct_table_name"];
		
		$c_dbquery2 = "INSERT INTO $db_credit_table2 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account2','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc2','$credit_amount2','','$approval_status')";
		
		$credit_query2 = @mysqli_query($dbcon, $c_dbquery2);
		
		
		
		//Credit Account 3 Input Query 
		$cquery_acct3 = "SELECT * ";
		$cquery_acct3 .= "FROM {$prefix}accounts ";
		$cquery_acct3 .= "WHERE acct_id = $credit_account3";
		$acct_credit_table_set3 = @mysqli_query($dbcon, $cquery_acct3);
		$acct_credit_table3 = @mysqli_fetch_array($acct_credit_table_set3, MYSQLI_ASSOC);
		
		$db_credit_table3 = $acct_credit_table3["acct_table_name"];
		
		$c_dbquery3 = "INSERT INTO $db_credit_table3 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account3','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc3','$credit_amount3','','$approval_status')";
		
		$credit_query3 = @mysqli_query($dbcon, $c_dbquery3);
		
		
		
		//Credit Account 4 Input Query 
		$cquery_acct4 = "SELECT * ";
		$cquery_acct4 .= "FROM {$prefix}accounts ";
		$cquery_acct4 .= "WHERE acct_id = $credit_account4";
		$acct_credit_table_set4 = @mysqli_query($dbcon, $cquery_acct4);
		$acct_credit_table4 = @mysqli_fetch_array($acct_credit_table_set4, MYSQLI_ASSOC);
		
		$db_credit_table4 = $acct_credit_table4["acct_table_name"];
		
		$c_dbquery4 = "INSERT INTO $db_credit_table4 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account4','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc4','$credit_amount4','','$approval_status')";
		
		$credit_query4 = @mysqli_query($dbcon, $c_dbquery4);
		
		
		
		//Credit Account 5 Input Query 
		$cquery_acct5 = "SELECT * ";
		$cquery_acct5 .= "FROM {$prefix}accounts ";
		$cquery_acct5 .= "WHERE acct_id = $credit_account5";
		$acct_credit_table_set5 = @mysqli_query($dbcon, $cquery_acct5);
		$acct_credit_table5 = @mysqli_fetch_array($acct_credit_table_set5, MYSQLI_ASSOC);
		
		$db_credit_table5 = $acct_credit_table5["acct_table_name"];
		
		$c_dbquery5 = "INSERT INTO $db_credit_table5 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account5','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc5','$credit_amount5','','$approval_status')";
		
		$credit_query5 = @mysqli_query($dbcon, $c_dbquery5);
		
		
		
		//Credit Account 6 Input Query 
		$cquery_acct6 = "SELECT * ";
		$cquery_acct6 .= "FROM {$prefix}accounts ";
		$cquery_acct6 .= "WHERE acct_id = $credit_account6";
		$acct_credit_table_set6 = @mysqli_query($dbcon, $cquery_acct6);
		$acct_credit_table6 = @mysqli_fetch_array($acct_credit_table_set6, MYSQLI_ASSOC);
		
		$db_credit_table6 = $acct_credit_table6["acct_table_name"];
		
		$c_dbquery6 = "INSERT INTO $db_credit_table6 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account6','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc6','$credit_amount6','','$approval_status')";
		
		$credit_query6 = @mysqli_query($dbcon, $c_dbquery6);
		
		
		
		//Credit Account 7 Input Query 
		$cquery_acct7 = "SELECT * ";
		$cquery_acct7 .= "FROM {$prefix}accounts ";
		$cquery_acct7 .= "WHERE acct_id = $credit_account7";
		$acct_credit_table_set7 = @mysqli_query($dbcon, $cquery_acct7);
		$acct_credit_table7 = @mysqli_fetch_array($acct_credit_table_set7, MYSQLI_ASSOC);
		
		$db_credit_table7 = $acct_credit_table7["acct_table_name"];
		
		$c_dbquery7 = "INSERT INTO $db_credit_table7 (id,acct_id,shop_no,date,ref_cheque_no,journal_no,receipt_no,trans_desc,credit_amount,balance,approval_status) VALUES('$txref','$credit_account7','','$date_of_payment','$reference_no','$reference_no','$receipt_no','$ctransaction_desc7','$credit_amount7','','$approval_status')";
		
		$credit_query7 = @mysqli_query($dbcon, $c_dbquery7);
		
		
		if($gen_trans_acct1){
		?>
			<script type="text/javascript">
			alert('Journal entry successfully raised for approval!');
			window.location.href='journal_entry<?php echo $suffix; ?>.php';
			</script>
		<?php
		} else {
		?>
			<script type="text/javascript">
			alert('Error occured while posting');
			window.location.href='journal_entry<?php echo $suffix; ?>.php';
			</script>
		<?php
		}
	} else {
		?>
			<script type="text/javascript">
			alert('Error occured while posting');
			window.location.href='journal_entry<?php echo $suffix; ?>.php';
			</script>
		<?php
	}
	}
}
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
	
	$dcount_query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
	$dcount_query .= "WHERE posting_officer_id = '$session_id' ";
	$dcount_query .= "AND (approval_status = 'Declined' OR verification_status = 'Declined')";
	$result = mysqli_query($dbcon, $dcount_query);
	$acct_office_post = mysqli_fetch_array($result);
	$no_of_declined_post = $acct_office_post[0];
	
	
	$ac_dcount_query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
	$ac_dcount_query .= "WHERE leasing_post_approving_officer_id = '$session_id' ";
	$ac_dcount_query .= "AND leasing_post_status = 'Approved' ";
	$ac_dcount_query .= "AND (approval_status = 'Declined' OR verification_status = 'Declined')";
	$ac_result = mysqli_query($dbcon, $ac_dcount_query);
	$account_post = mysqli_fetch_array($ac_result);
	$no_of_declined_acct_post = $account_post[0];
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
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<div class="row">
			<div class="page-header">
				<div class="container-fluid">
					<div class="col-md-8">
						<h2><strong><?php echo $project; ?> Journal Entry <span style="color:#007308;">(Multi Credit)</span></strong>
						
						<?php
							if (isset($_GET['repost_id'])) {
								echo "";
							} else {
								echo '<a href="journal_entry'.$suffix.'_md.php" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-plus-sign"></span> Switch to Multi Debit</a>';
							}
						?>
						</h2>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<?php include ("controllers/journal_entry_form_inc{$suffix}.php"); ?>
		</div>
	</div>
	<div class="col-md-1"></div>
</div>

</body>
<script type="text/javascript">
function balCalc() {
	//Debit amount
	var debit_amount1 = isNaN(parseFloat(document.getElementById('debit_amount1').value))? 0 : parseFloat(document.getElementById('debit_amount1').value);
	
	//Credit amounts
	var credit_amount1 = isNaN(parseFloat(document.getElementById('credit_amount1').value))? 0 : parseFloat(document.getElementById('credit_amount1').value);
	var credit_amount2 = isNaN(parseFloat(document.getElementById('credit_amount2').value))? 0 : parseFloat(document.getElementById('credit_amount2').value);
	var credit_amount3 = isNaN(parseFloat(document.getElementById('credit_amount3').value))? 0 : parseFloat(document.getElementById('credit_amount3').value);
	var credit_amount4 = isNaN(parseFloat(document.getElementById('credit_amount4').value))? 0 : parseFloat(document.getElementById('credit_amount4').value);
	var credit_amount5 = isNaN(parseFloat(document.getElementById('credit_amount5').value))? 0 : parseFloat(document.getElementById('credit_amount5').value);
	var credit_amount6 = isNaN(parseFloat(document.getElementById('credit_amount6').value))? 0 : parseFloat(document.getElementById('credit_amount6').value);
	var credit_amount7 = isNaN(parseFloat(document.getElementById('credit_amount7').value))? 0 : parseFloat(document.getElementById('credit_amount7').value);


	document.getElementById('total_debit_amount').value = (debit_amount1);
	
	document.getElementById('total_credit_amount').value = (credit_amount1 + credit_amount2 + credit_amount3 + credit_amount4 + credit_amount5 + credit_amount6 + credit_amount7);
	
	
	var total_debit_amount = isNaN(parseFloat(document.getElementById('total_debit_amount').value))? 0 : parseFloat(document.getElementById('total_debit_amount').value);
	var total_credit_amount = isNaN(parseFloat(document.getElementById('total_credit_amount').value))? 0 : parseFloat(document.getElementById('total_credit_amount').value);
	
	
	document.getElementById('balance').value = (total_debit_amount - total_credit_amount);
	var out_of_balance = document.getElementById('balance').value;
	if(out_of_balance != 0){
		document.getElementById("btn_post_journal").disabled = true;
	}
}
</script>
</html>
<?php ob_end_flush(); ?>