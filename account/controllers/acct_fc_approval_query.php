<?php


if(isset($_GET['approve_id']))
	{
		$query_acct = "SELECT * ";
		$query_acct .= "FROM {$prefix}account_general_transaction_new ";
		$query_acct .= "WHERE id =".$_GET['approve_id'];
		$acct_table_set = @mysqli_query($dbcon, $query_acct);
		$acct_table = @mysqli_fetch_array($acct_table_set, MYSQLI_ASSOC);
		
		$customer_status = $acct_table["customer_status"];
		$customer_sc_amount = $acct_table["amount_paid"];
		
		//$debit_account = $acct_table["debit_account"];
		//$credit_account = $acct_table["credit_account"];
		
		$debit_account = $acct_table['debit_account'];
		$credit_account = $acct_table['credit_account'];
		
		$debit_account2 = $acct_table['debit_account_jrn2'];
		$debit_account3 = $acct_table['debit_account_jrn3'];
		$debit_account4 = $acct_table['debit_account_jrn4'];
		$debit_account5 = $acct_table['debit_account_jrn5'];
		$debit_account6 = $acct_table['debit_account_jrn6'];
		$debit_account7 = $acct_table['debit_account_jrn7'];
		
		
		$credit_account2 = $acct_table['credit_account_jrn2'];
		$credit_account3 = $acct_table['credit_account_jrn3'];
		$credit_account4 = $acct_table['credit_account_jrn4'];
		$credit_account5 = $acct_table['credit_account_jrn5'];
		$credit_account6 = $acct_table['credit_account_jrn6'];
		$credit_account7 = $acct_table['credit_account_jrn7'];
		
		
		
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
							
		
		$query="UPDATE {$prefix}account_general_transaction_new SET approving_acct_officer_id = '$fc_id', approving_acct_officer_name = '$fc_name', approval_status = 'Approved', approval_time = '$now' WHERE id=".$_GET['approve_id'];
		$result = @mysqli_query($dbcon, $query);

		
		$cquery="UPDATE $db_credit_table SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$cresult = @mysqli_query($dbcon, $cquery);
		
		$cquery2="UPDATE $db_credit_table2 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$cresult2 = @mysqli_query($dbcon, $cquery2);
		
		$cquery3="UPDATE $db_credit_table3 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$cresult3 = @mysqli_query($dbcon, $cquery3);
		
		$cquery4="UPDATE $db_credit_table4 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$cresult4 = @mysqli_query($dbcon, $cquery4);
		
		$cquery5="UPDATE $db_credit_table5 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$cresult5 = @mysqli_query($dbcon, $cquery5);
		
		$cquery6="UPDATE $db_credit_table6 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$cresult6 = @mysqli_query($dbcon, $cquery6);
		
		$cquery7="UPDATE $db_credit_table7 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$cresult7 = @mysqli_query($dbcon, $cquery7);
		
		
		
		$bquery="UPDATE $db_debit_table SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$bresult = @mysqli_query($dbcon, $bquery);
		
		$bquery2="UPDATE $db_debit_table2 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$bresult2 = @mysqli_query($dbcon, $bquery2);
		
		$bquery3="UPDATE $db_debit_table3 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$bresult3 = @mysqli_query($dbcon, $bquery3);
		
		$bquery4="UPDATE $db_debit_table4 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$bresult4 = @mysqli_query($dbcon, $bquery4);
		
		$bquery5="UPDATE $db_debit_table5 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$bresult5 = @mysqli_query($dbcon, $bquery5);
		
		$bquery6="UPDATE $db_debit_table6 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$bresult6 = @mysqli_query($dbcon, $bquery6);
		
		$bquery7="UPDATE $db_debit_table7 SET approval_status = 'Approved' WHERE id=".$_GET['approve_id'];
		$bresult7 = @mysqli_query($dbcon, $bquery7);
		
		
		if ($customer_status == "New" && $prefix ==  "arena_") {
			$cuquery="UPDATE customers_new_registration SET service_charge_paid = '$customer_sc_amount' WHERE ref_no=".$_GET['approve_id'];
			$curesult = @mysqli_query($dbcon, $cuquery);
		} elseif ($customer_status == "New" && $prefix ==  "") {
			$cuquery="UPDATE customers_new_registration SET rent_paid = '$customer_rent_amount' WHERE ref_no=".$_GET['approve_id'];
			$curesult = @mysqli_query($dbcon, $cuquery);
		} else {
			
		}
	}

?>