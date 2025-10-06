<?php
	$prefix = "";
	$suffix = "";
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	$today_date = date('Y-m-d');

	$del_query = "SELECT * ";
	$del_query .= "FROM {$prefix}account_general_transaction_new ";
	$del_query .= "WHERE (leasing_post_status = 'Pending' OR approval_status = 'Pending') ";
	
	$del_query .= "AND date_of_payment < DATE_FORMAT(NOW() ,'%Y-%m-%d') ";
	$del_result = @mysqli_query($dbcon, $del_query);
	$del_count = mysqli_num_rows($del_result);
	
	
	//Insert into pending_delete_report table
	$pd_query = "SELECT * ";
	$pd_query .= "FROM pending_delete_report ";
	$pd_query .= "WHERE date = '$today_date' ";
	$pd_result = @mysqli_query($dbcon, $pd_query);
	$pd_count = mysqli_num_rows($pd_result);
	
	if($pd_count == 0) {
		$count_query = "INSERT INTO pending_delete_report (id,date,delete_time,count,status) VALUES ('','$today_date','$now','$del_count','')";
		$count_result = @mysqli_query($dbcon, $count_query);
	}
	
	
	while ($result = @mysqli_fetch_array($del_result, MYSQLI_ASSOC)) {
	
		$delete_id = $result['id'];
		
		$debit_account = $result['debit_account'];
		$credit_account = $result['credit_account'];
		
		$debit_account2 = $result['debit_account_jrn2'];
		$debit_account3 = $result['debit_account_jrn3'];
		$debit_account4 = $result['debit_account_jrn4'];
		$debit_account5 = $result['debit_account_jrn5'];
		$debit_account6 = $result['debit_account_jrn6'];
		$debit_account7 = $result['debit_account_jrn7'];
		
		
		$credit_account2 = $result['credit_account_jrn2'];
		$credit_account3 = $result['credit_account_jrn3'];
		$credit_account4 = $result['credit_account_jrn4'];
		$credit_account5 = $result['credit_account_jrn5'];
		$credit_account6 = $result['credit_account_jrn6'];
		$credit_account7 = $result['credit_account_jrn7'];
		
		
		$query_acct1 = "SELECT * ";
		$query_acct1 .= "FROM {$prefix}accounts ";
		$query_acct1 .= "WHERE acct_id = $debit_account";
		$acct_debit_table_set = @mysqli_query($dbcon, $query_acct1);
		$acct_debit_table = @mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
		
		$db_debit_table = $acct_debit_table["acct_table_name"];
		
		
		$query_acct2 = "SELECT * ";
		$query_acct2 .= "FROM {$prefix}accounts ";
		$query_acct2 .= "WHERE acct_id = $credit_account";
		$acct_credit_table_set = @mysqli_query($dbcon, $query_acct2);
		$acct_credit_table = @mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
		
		$db_credit_table = $acct_credit_table["acct_table_name"];
		
		
		//Credit Account 2 Input Query 
		$cquery_acct2 = "SELECT * ";
		$cquery_acct2 .= "FROM {$prefix}accounts ";
		$cquery_acct2 .= "WHERE acct_id = $credit_account2";
		$acct_credit_table_set2 = @mysqli_query($dbcon, $cquery_acct2);
		$acct_credit_table2 = @mysqli_fetch_array($acct_credit_table_set2, MYSQLI_ASSOC);

		$db_credit_table2 = $acct_credit_table2["acct_table_name"];


		//Credit Account 3 Input Query 
		$cquery_acct3 = "SELECT * ";
		$cquery_acct3 .= "FROM {$prefix}accounts ";
		$cquery_acct3 .= "WHERE acct_id = $credit_account3";
		$acct_credit_table_set3 = @mysqli_query($dbcon, $cquery_acct3);
		$acct_credit_table3 = @mysqli_fetch_array($acct_credit_table_set3, MYSQLI_ASSOC);

		$db_credit_table3 = $acct_credit_table3["acct_table_name"];


		//Credit Account 4 Input Query 
		$cquery_acct4 = "SELECT * ";
		$cquery_acct4 .= "FROM {$prefix}accounts ";
		$cquery_acct4 .= "WHERE acct_id = $credit_account4";
		$acct_credit_table_set4 = @mysqli_query($dbcon, $cquery_acct4);
		$acct_credit_table4 = @mysqli_fetch_array($acct_credit_table_set4, MYSQLI_ASSOC);

		$db_credit_table4 = $acct_credit_table4["acct_table_name"];


		//Credit Account 5 Input Query 
		$cquery_acct5 = "SELECT * ";
		$cquery_acct5 .= "FROM {$prefix}accounts ";
		$cquery_acct5 .= "WHERE acct_id = $credit_account5";
		$acct_credit_table_set5 = @mysqli_query($dbcon, $cquery_acct5);
		$acct_credit_table5 = @mysqli_fetch_array($acct_credit_table_set5, MYSQLI_ASSOC);

		$db_credit_table5 = $acct_credit_table5["acct_table_name"];


		//Credit Account 6 Input Query 
		$cquery_acct6 = "SELECT * ";
		$cquery_acct6 .= "FROM {$prefix}accounts ";
		$cquery_acct6 .= "WHERE acct_id = $credit_account6";
		$acct_credit_table_set6 = @mysqli_query($dbcon, $cquery_acct6);
		$acct_credit_table6 = @mysqli_fetch_array($acct_credit_table_set6, MYSQLI_ASSOC);

		$db_credit_table6 = $acct_credit_table6["acct_table_name"];


		//Credit Account 7 Input Query 
		$cquery_acct7 = "SELECT * ";
		$cquery_acct7 .= "FROM {$prefix}accounts ";
		$cquery_acct7 .= "WHERE acct_id = $credit_account7";
		$acct_credit_table_set7 = @mysqli_query($dbcon, $cquery_acct7);
		$acct_credit_table7 = @mysqli_fetch_array($acct_credit_table_set7, MYSQLI_ASSOC);

		$db_credit_table7 = $acct_credit_table7["acct_table_name"];

		//Debit Account 2 Input Query 
		$dquery_acct2 = "SELECT * ";
		$dquery_acct2 .= "FROM {$prefix}accounts ";
		$dquery_acct2 .= "WHERE acct_id = $debit_account2";
		$acct_debit_table_set2 = @mysqli_query($dbcon, $dquery_acct2);
		$acct_debit_table2 = @mysqli_fetch_array($acct_debit_table_set2, MYSQLI_ASSOC);

		$db_debit_table2 = $acct_debit_table2["acct_table_name"];

		//Debit Account 3 Input Query 
		$dquery_acct3 = "SELECT * ";
		$dquery_acct3 .= "FROM {$prefix}accounts ";
		$dquery_acct3 .= "WHERE acct_id = $debit_account3";
		$acct_debit_table_set3 = @mysqli_query($dbcon, $dquery_acct3);
		$acct_debit_table3 = @mysqli_fetch_array($acct_debit_table_set3, MYSQLI_ASSOC);

		$db_debit_table3 = $acct_debit_table3["acct_table_name"];

		//Debit Account 4 Input Query 
		$dquery_acct4 = "SELECT * ";
		$dquery_acct4 .= "FROM {$prefix}accounts ";
		$dquery_acct4 .= "WHERE acct_id = $debit_account4";
		$acct_debit_table_set4 = @mysqli_query($dbcon, $dquery_acct4);
		$acct_debit_table4 = @mysqli_fetch_array($acct_debit_table_set4, MYSQLI_ASSOC);

		$db_debit_table4 = $acct_debit_table4["acct_table_name"];


		//Debit Account 5 Input Query 
		$dquery_acct5 = "SELECT * ";
		$dquery_acct5 .= "FROM {$prefix}accounts ";
		$dquery_acct5 .= "WHERE acct_id = $debit_account5";
		$acct_debit_table_set5 = @mysqli_query($dbcon, $dquery_acct5);
		$acct_debit_table5 = @mysqli_fetch_array($acct_debit_table_set5, MYSQLI_ASSOC);

		$db_debit_table5 = $acct_debit_table5["acct_table_name"];


		//Debit Account 6 Input Query 
		$dquery_acct6 = "SELECT * ";
		$dquery_acct6 .= "FROM {$prefix}accounts ";
		$dquery_acct6 .= "WHERE acct_id = $debit_account6";
		$acct_debit_table_set6 = @mysqli_query($dbcon, $dquery_acct6);
		$acct_debit_table6 = @mysqli_fetch_array($acct_debit_table_set6, MYSQLI_ASSOC);

		$db_debit_table6 = $acct_debit_table6["acct_table_name"];


		//Debit Account 7 Input Query 
		$dquery_acct7 = "SELECT * ";
		$dquery_acct7 .= "FROM {$prefix}accounts ";
		$dquery_acct7 .= "WHERE acct_id = $debit_account7";
		$acct_debit_table_set7 = @mysqli_query($dbcon, $dquery_acct7);
		$acct_debit_table7 = @mysqli_fetch_array($acct_debit_table_set7, MYSQLI_ASSOC);

		$db_debit_table7 = $acct_debit_table7["acct_table_name"];

		
		$query="DELETE FROM {$prefix}account_general_transaction_new WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);
		
		$query="DELETE FROM collection_analysis{$suffix} WHERE trans_id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_debit_table WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_credit_table WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_credit_table2 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_credit_table3 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_credit_table4 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_credit_table5 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_credit_table6 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_credit_table7 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_debit_table2 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_debit_table3 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_debit_table4 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_debit_table5 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_debit_table6 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

		$query="DELETE FROM $db_debit_table7 WHERE id=".$delete_id;
		$result = @mysqli_query($dbcon, $query);

	}
?>