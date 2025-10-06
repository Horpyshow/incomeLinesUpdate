<?php

	header('Content-type: application/json; charset=UTF-8');
	$response = array();
	
	$prefix = "";
	
	if ($_POST['approve']) {
		require_once 'include/session.php';
		$pid = $_POST['approve'];
		$txref = $pid;
		
		date_default_timezone_set('Africa/Lagos');
		$now = date('Y-m-d H:i:s');
		
		if(isset($_SESSION['staff']) ) {
			$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['staff'];
		}
		if(isset($_SESSION['admin']) ) {
			$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['admin'];
		}
		$role_data = mysqli_query($dbcon, $query);
		$role_user = mysqli_fetch_array($role_data, MYSQLI_ASSOC);
		
		$auditor_id = $role_user['user_id'];
		$auditor_name = $role_user['full_name'];
		$verification_status = "Verified";
		$auditor_flag_status = "Resolution Confirmed";
		
		
		$query_acct = "SELECT * FROM {$prefix}account_general_transaction_new WHERE id =".$pid;
		$acct_table_set = mysqli_query($dbcon, $query_acct);		
		$table_row_no = mysqli_num_rows($acct_table_set);
		
		$db_transaction_table = "{$prefix}account_general_transaction_new";
	
		if($table_row_no == 0) {
			$query_acct1 = "SELECT * FROM {$prefix}account_general_transaction WHERE id =".$pid;
			$acct_table_set = mysqli_query($dbcon, $query_acct1);
			
			$db_transaction_table = "{$prefix}account_general_transaction";
		}
		
		$acct_table = mysqli_fetch_array($acct_table_set, MYSQLI_ASSOC);
		
		$flag_status = $acct_table["flag_status"];
		$db_flag_table = "{$prefix}account_flagged_record"; 
		
		
		//Check if record was previously flagged
		if($flag_status == "Flagged") {
			$fquery="UPDATE $db_flag_table SET flag_status='$auditor_flag_status', confirm_officer_id = '$auditor_id', confirm_officer_name = '$auditor_name', confirm_time = '$now' WHERE id=".$txref;
			$fresult = mysqli_query($dbcon, $fquery);
			
			if ($fresult) {
				//If flagged_record table update is successful, do this
				
				$query="UPDATE $db_transaction_table SET verifying_auditor_id = '$auditor_id', verifying_auditor_name = '$auditor_name', verification_status = '$verification_status', verification_time = '$now', flag_status='' WHERE id=".$pid;
				$result = mysqli_query($dbcon, $query);
			}
			
		} else {
		
			$query="UPDATE $db_transaction_table SET verifying_auditor_id = '$auditor_id', verifying_auditor_name = '$auditor_name', verification_status = '$verification_status', verification_time = '$now' WHERE id=".$pid;
			$result = mysqli_query($dbcon, $query);
		}
	
		if ($result) {
			$response['status']  = 'success';
			$response['message'] = 'Record Verified Successfully ...';
		} else {
			$response['status']  = 'error';
			$response['message'] = 'Unable to verify record ...';
		}
		echo json_encode($response);
	}
?>