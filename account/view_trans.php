suffix<?php
include 'include/session.php';
include 'include/functions.php';

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$sub_prefix = "";
$sub_suffix = "_wrl";
$page_title = "{$project} - View General Transactions - Wealth Creation ERP";

if(isset($_SESSION['staff']) ) {
$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['staff'];
}
if(isset($_SESSION['admin']) ) {
$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['admin'];
}
$role_set = mysqli_query($dbcon, $query);
$role = mysqli_fetch_array($role_set, MYSQLI_ASSOC);

if ($role['acct_view_record'] != "Yes") {
?>
	<script type="text/javascript">
		alert('You do not have permissions to view this page! Contact your HOD for authorization. Thanks');
		window.location.href='../../index.php';
	</script>
<?php
}
?>
<?php
// delete condition
if (isset($_GET['delete_id']))
{
	$prefix = "";
	$suffix = "";
	
	$acct_delete_id = $_GET['delete_id'];
	
	$del_query = "SELECT * FROM {$prefix}account_general_transaction_new WHERE id=".$_GET['delete_id'];
	$del_result = @mysqli_query($dbcon, $del_query);
	$result = @mysqli_fetch_array($del_result, MYSQLI_ASSOC);
	
	$payment_category = $result["payment_category"];
	
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

	
	$query="DELETE FROM {$prefix}account_general_transaction_new WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);
	
	if ($payment_category=="Service Charge") {
		$query="DELETE FROM collection_analysis_arena WHERE trans_id=".$_GET['delete_id'];
		$result = @mysqli_query($dbcon, $query);
	} else {
		$query="DELETE FROM collection_analysis WHERE trans_id=".$_GET['delete_id'];
		$result = @mysqli_query($dbcon, $query);
	}

	$query="DELETE FROM $db_debit_table WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_credit_table WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_credit_table2 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_credit_table3 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_credit_table4 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_credit_table5 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_credit_table6 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_credit_table7 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_debit_table2 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_debit_table3 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_debit_table4 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_debit_table5 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_debit_table6 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);

	$query="DELETE FROM $db_debit_table7 WHERE id=".$_GET['delete_id'];
	$result = @mysqli_query($dbcon, $query);
} 

?>
<?php
	//Set the number of rows per display
	$pagerows = 10;
	
	//Has the total number of pages already been calculated?
	if (isset($_GET['p']) && is_numeric($_GET['p'])) {
		$pages = $_GET['p'];
	} else {
	//First, check for the total number of records
		$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
		$query .= "WHERE leasing_post_status = 'Pending' ";
		$query .= "OR approval_status = 'Pending'";
		$result = mysqli_query($dbcon, $query);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		$records = $row[0];
		
	//Now calculate the number of pages
	if ($records > $pagerows) {
		$pages = ceil($records/$pagerows);
	} else {
		$pages = 1;
	}
	}
	//Declare which record to start with
	if (isset($_GET['s']) && is_numeric($_GET['s'])) {
		$start = $_GET['s'];
		//$i = $start;
	} else {
		$start = 0;
		//$i = $start;
	}
	
if ( isset($_POST['btn_filter']) ) {

	$post_at = "";
	$post_at_to_date = "";
	
	$queryCondition = "";
	if(!empty($_POST["search"]["post_at"])) {			
		$post_at = $_POST["search"]["post_at"];
		list($fid,$fim,$fiy) = explode("/",$post_at);
		
		$post_at_todate = date('Y-m-d');
		if(!empty($_POST["search"]["post_at_to_date"])) {
			$post_at_to_date = $_POST["search"]["post_at_to_date"];
			list($tid,$tim,$tiy) = explode("/",$_POST["search"]["post_at_to_date"]);
			$post_at_todate = "$tiy-$tim-$tid";
		}
		
		$queryCondition .= "WHERE my_date BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";
	}

	$sql = "SELECT * FROM {$prefix}account_general_transaction_new " . $queryCondition . " ORDER BY date_of_payment desc";
	$post_filter_set = mysqli_query($dbcon,$sql);
} 


if (isset($_GET['d1']) && isset($_GET['d2'])) {
	$post_at = "";
	$post_at_to_date = "";
	$queryCondition = "";
	
	$post_at = $_GET["d1"];
	list($fid,$fim,$fiy) = explode("/",$post_at);
	
	$post_at_todate = date('Y-m-d');
	
	$post_at_to_date = $_GET["d2"];
	list($tid,$tim,$tiy) = explode("/",$post_at_to_date);
	
	$post_at_todate = "$tiy-$tim-$tid";
		
	$queryCondition .= "WHERE date_of_payment BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";

	$sql = "SELECT * FROM {$prefix}account_general_transaction_new " . $queryCondition . " ORDER BY date_of_payment desc";
	$post_filter_set = mysqli_query($dbcon,$sql);	
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $page_title; ?></title>
		<meta name="description" content="Woobs Resources ERP Management System">
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/formValidation.min.css">

		<link rel="stylesheet" type="text/css" href="../../css/datepicker.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/datepicker3.min.css">
		<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../../js/formValidation.min.js"></script>
		<script type="text/javascript" src="../../js/framework/bootstrap.min.js"></script>
		<script type='text/javascript' src="../../js/bootstrap-datepicker.min.js"></script>
		<script type='text/javascript' src="../../js/fv.js"></script>
		<!--
		Date Options didnt work, form validation worked, menu worked
		<script type="text/javascript" src="../../js/bootstrap.js"></script> -->
		<script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>

		<link rel="stylesheet" type="text/css" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrapValidator.min.css">
		<script type="text/javascript" src="../../js/jquery.min.js"></script>
		<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
		<script src="../../js/sub_menu.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">
		
		<link rel="stylesheet" href="../../scripts/swal2/sweetalert2.min.css" type="text/css" />
		<script src="../../scripts/swal2/sweetalert2.min.js"></script>


<script>
$(document).ready(function(){
		$(document).on('click', '#approve_record', function(e){
			
			var recordId = $(this).data('id');
			SwalApprove(recordId);
			e.preventDefault();
		});
		
	});
	
	function SwalApprove(recordId){
		
		swal({
			title: 'Are you sure?',
			text: "This record will hit the Financials!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, approve!',
			showLoaderOnConfirm: true,
			  
			preConfirm: function() {
			  return new Promise(function(resolve) {
			       
			     $.ajax({
			   		url: 'fc_approve_func.php',
			    	type: 'POST',
			       	data: 'approve='+recordId,
			       	dataType: 'json'
			     })
			     .done(function(response){
			     	swal('Approved!', response.message, response.status);
					reloadPage();
			     })
				 
			     .fail(function(){
			     	swal('Oops...', 'Something went wrong, record not approved!', 'error');
			     });
			  });
		    },
			allowOutsideClick: false			  
		});	
		
		function reloadPage(){
			window.location.reload();
		}
	}	
</script>

<script>
$(document).ready(function(){
		$(document).on('click', '#decline_record', function(e){
			
			var declineId = $(this).data('id');
			SwalDecline(declineId);
			e.preventDefault();
		});
		
	});
	
	function SwalDecline(declineId){
		
		swal({
			title: 'Are you sure?',
			text: "This record will NOT hit the Financials!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, decline!',
			showLoaderOnConfirm: true,
			  
			preConfirm: function() {
			  return new Promise(function(resolve) {
			       
			     $.ajax({
			   		url: 'fc_decline_func.php',
			    	type: 'POST',
			       	data: 'decline='+declineId,
			       	dataType: 'json'
			     })
			     .done(function(response){
			     	swal('Declined!', response.message, response.status);
					reloadPage();
			     })
				 
			     .fail(function(){
			     	swal('Oops...', 'Something went wrong, unable to decline record!', 'error');
			     });
			  });
		    },
			allowOutsideClick: false			  
		});
		
		function reloadPage(){
			window.location.reload();
		}
	}		
</script>

</head>
<body>
<?php 
include ('include/staff_navbar.php'); 

if ($menu['department'] == "Audit/Inspections"){
	"<script>
$(document).ready(function(){
		$(document).on('click', '#approve_record', function(e){
			
			var recordId = $(this).data('id');
			SwalApprove(recordId);
			e.preventDefault();
		});
		
	});
	
	function SwalApprove(recordId){
		
		swal({
			title: 'Are you sure?',
			text: 'This record will be verified!',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, verify!',
			showLoaderOnConfirm: true,
			  
			preConfirm: function() {
			  return new Promise(function(resolve) {
			       
			     $.ajax({
			   		url: 'audit_approve_func<?php echo $suffix; ?>.php',
			    	type: 'POST',
			       	data: 'approve='+recordId,
			       	dataType: 'json'
			     })
			     .done(function(response){
			     	swal('Verified!', response.message, response.status);
					reloadPage();
			     })
				 
			     .fail(function(){
			     	swal('Oops...', 'Something went wrong, record not verified!', 'error');
			     });
			  });
		    },
			allowOutsideClick: false			  
		});	
		
		function reloadPage(){
			window.location.reload();
		}
	}	
</script>

<script>
$(document).ready(function(){
		$(document).on('click', '#decline_record', function(e){
			
			var declineId = $(this).data('id');
			SwalDecline(declineId);
			e.preventDefault();
		});
		
	});
	
	function SwalDecline(declineId){
		
		swal({
			title: 'Are you sure?',
			text: 'This record will NOT be verified!',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, decline!',
			showLoaderOnConfirm: true,
			  
			preConfirm: function() {
			  return new Promise(function(resolve) {
			       
			     $.ajax({
			   		url: 'audit_decline_func<?php echo $suffix; ?>.php',
			    	type: 'POST',
			       	data: 'decline='+declineId,
			       	dataType: 'json'
			     })
			     .done(function(response){
			     	swal('Declined!', response.message, response.status);
					reloadPage();
			     })
				 
			     .fail(function(){
			     	swal('Oops...', 'Something went wrong, unable to decline record!', 'error');
			     });
			  });
		    },
			allowOutsideClick: false			  
		});
		
		function reloadPage(){
			window.location.reload();
		}
	}		
</script>";
} elseif ($menu['department'] == "Accounts"){
	"<script>
$(document).ready(function(){
		$(document).on('click', '#approve_record', function(e){
			
			var recordId = $(this).data('id');
			SwalApprove(recordId);
			e.preventDefault();
		});
		
	});
	
	function SwalApprove(recordId){
		
		swal({
			title: 'Are you sure?',
			text: 'This record will hit the Financials!',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, approve!',
			showLoaderOnConfirm: true,
			  
			preConfirm: function() {
			  return new Promise(function(resolve) {
			       
			     $.ajax({
			   		url: 'fc_approve_func.php',
			    	type: 'POST',
			       	data: 'approve='+recordId,
			       	dataType: 'json'
			     })
			     .done(function(response){
			     	swal('Approved!', response.message, response.status);
					reloadPage();
			     })
				 
			     .fail(function(){
			     	swal('Oops...', 'Something went wrong, record not approved!', 'error');
			     });
			  });
		    },
			allowOutsideClick: false			  
		});	
		
		function reloadPage(){
			window.location.reload();
		}
	}	
</script>

<script>
$(document).ready(function(){
		$(document).on('click', '#decline_record', function(e){
			
			var declineId = $(this).data('id');
			SwalDecline(declineId);
			e.preventDefault();
		});
		
	});
	
	function SwalDecline(declineId){
		
		swal({
			title: 'Are you sure?',
			text: 'This record will NOT hit the Financials!',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, decline!',
			showLoaderOnConfirm: true,
			  
			preConfirm: function() {
			  return new Promise(function(resolve) {
			       
			     $.ajax({
			   		url: 'fc_decline_func.php',
			    	type: 'POST',
			       	data: 'decline='+declineId,
			       	dataType: 'json'
			     })
			     .done(function(response){
			     	swal('Declined!', response.message, response.status);
					reloadPage();
			     })
				 
			     .fail(function(){
			     	swal('Oops...', 'Something went wrong, unable to decline record!', 'error');
			     });
			  });
		    },
			allowOutsideClick: false			  
		});
		
		function reloadPage(){
			window.location.reload();
		}
	}		
</script>"; 
} else {
	echo '';
}

			
			$vp_user_id = $menu["user_id"];
			$vp_staff_name = $menu["full_name"];
			$sessionID = session_id();
			
			$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$url = htmlspecialchars(strip_tags($url));
			
			date_default_timezone_set('Africa/Lagos');
			$now = date('Y-m-d H:i:s');
			
			$vp_query = "INSERT IGNORE INTO visited_pages (id, user_id, staff_name, session_id, uri, time) VALUES ('', '$vp_user_id', '$vp_staff_name', '$sessionID', '$url', '$now')";
			$vp_result = mysqli_query($dbcon,$vp_query); 
?>
<div class="well"></div>
<div class="container-fluid">
	<div class="col-sm-12">
	<div class="page-header">
		<h2><strong><?php echo $project; ?> General Transaction Dashboard</strong></h2>
		
		<h5><strong>Wealth Creation: </strong>
		<?php
			$current_date = date('d-m-Y');
			$current_date = str_replace('-', '/', $current_date);
			
			
			$tquery = "SELECT * ";
			$tquery .= "FROM staffs ";
			$tquery .= "WHERE department = 'Wealth Creation' ";
			$tquery .= "AND level = 'leasing officer' ";
			$tquery .= "ORDER BY first_name ASC";
			
			$tresult = mysqli_query($dbcon, $tquery);

			if (!$tresult) {
			die ("Database search query failed");
			}
			while ($tstaff = mysqli_fetch_array($tresult, MYSQLI_ASSOC)) {
			$staff_id = $tstaff["user_id"];
			
			echo '<a href="../leasing/view_trans.php?staff_id='.$staff_id.'" class="btn btn-sm btn-default">'.$tstaff["first_name"].'</a> ';
			}
		?>
		</h5>
		<h5><strong>Account Dept: </strong>
		<?php
			$current_date = date('d-m-Y');
			$current_date = str_replace('-', '/', $current_date);
			
			$tquery = "SELECT user_id, first_name, department, level ";
			$tquery .= "FROM staffs ";
			$tquery .= "WHERE department = 'Accounts' ";
			$tquery .= "ORDER BY first_name ASC";
			
			$tresult = mysqli_query($dbcon, $tquery);

			if (!$tresult) {
			die ("Database search query failed");
			}
			while ($tstaff = mysqli_fetch_array($tresult, MYSQLI_ASSOC)) {
			$staff_id = $tstaff["user_id"];
			
			
			echo '<a href="acct_view_trans.php?staff_id='.$staff_id.'" class="btn btn-sm btn-default">'.$tstaff["first_name"].'</a> ';
			}
		?>
		</h5>
		<?php
			$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
			$query .= "WHERE leasing_post_status = 'Pending' ";
			//$query .= "OR approval_status = 'Pending'";
			$result = mysqli_query($dbcon, $query);
			$leasing_post = mysqli_fetch_array($result);
			$no_of_leasing_post = $leasing_post[0];
			
			$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
			$query .= "WHERE approval_status = 'Pending'";
			$result = mysqli_query($dbcon, $query);
			$fc_approvals = mysqli_fetch_array($result);
			$fc_pending_approvals = $fc_approvals[0];
			
			
			$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
			$query .= "WHERE approval_status = 'Declined' ";
			$query .= "OR verification_status = 'Declined' ";
			$query .= "OR leasing_post_status = 'Declined'";
			$result = mysqli_query($dbcon, $query);
			$declined_trans = mysqli_fetch_array($result);
			$all_declined_trans = $declined_trans[0];
			
		?>
		
		<h3 style="line-height: 30px;">
			<div class="row">
				<div class="col-sm-9">
					<?php echo '<a href="view_trans.php" class="btn btn-danger btn-sm">'.$no_of_leasing_post.': Pending Posts</a>'; ?> 
					<?php echo '<a href="pending_fc_approvals'.$suffix.'.php" class="btn btn-warning btn-sm">'.$fc_pending_approvals.': Pending FC Approvals</a>'; ?>
					<?php echo '<a href="../../mod/audit/pending_audit_approvals'.$suffix.'.php" class="btn btn-success btn-sm">Pending Auditor\'s Account Verifications</a>'; ?>
					<?php echo '<a href="view_trans_'.$prefix.'declined.php" class="btn btn-danger btn-sm">'.$all_declined_trans.': Declined Transactions</a>'; ?>
					<?php //echo '<a href="flagged_record'.$suffix.'.php" class="btn btn-warning btn-sm">'.$all_flagged_trans.': Flagged Records</a>'; ?>
				</div>
				<div class="col-sm-3">
				<?php
				if(@$page_department=="Audit") {
					echo '<form method="POST" action="../account/search'.$suffix.'_processing.php?sr='; if (isset($_POST["btn-search"])) {$search = $_POST["search"];}  echo '" autocomplete="off" >';
				} else {
					echo '<form method="POST" action="search'.$suffix.'_processing.php?sr='; if (isset($_POST["btn-search"])) {$search = $_POST["search"];}  echo '" autocomplete="off" >';
				}
				echo '
						<div class="input-group col-sm-12">
							<input type="text" class="search-query form-control input-sm" name="search" placeholder="Search" value="'; ?><?php if (isset($_POST["btn-search"])) { echo $search; } echo '" required />
							<span class="input-group-btn">
								<button class="btn btn-danger btn-sm" type="submit" name="btn-search">
									<span class=" glyphicon glyphicon-search"></span>
								</button>
							</span>
						</div>
					</form>';
				?>
				</div>
			</div>
		</h3>
		
		<?php include ('include/countdown_script.php'); ?>
		
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
		
		$ac_dcount_query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
		$ac_dcount_query .= "WHERE leasing_post_approving_officer_id = '$session_id' ";
		$ac_dcount_query .= "AND leasing_post_status = 'Approved' ";
		$ac_dcount_query .= "AND (approval_status = 'Declined' OR verification_status = 'Declined')";
		$ac_result = @mysqli_query($dbcon, $ac_dcount_query);
		$account_post = @mysqli_fetch_array($ac_result);
		$no_of_declined_acct_post = $account_post[0];
		?>
		
			$fcquery = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
			$fcquery .= "WHERE verification_status = 'Declined' ";
			$fcquery .= "AND approval_status = 'Approved' ";
			$fcresult = mysqli_query($dbcon, $fcquery);
			$fc_declined_trans = mysqli_fetch_array($fcresult);
			$all_fc_declined_trans = $fc_declined_trans[0];
			
			
			if ($menu["level"] == "fc" && $all_fc_declined_trans > 0) {
				header("Location: view_trans_{$prefix}declined.php");
			} 
		?>
	</div>
	
		<div class="row">
		<div id="stats">
		<div class="table-responsive">
			  <table id="simple-table" class="table table-bordered table-hover">
				<thead>
				<tr>
				<td colspan="13">
				<div class="row">
					<form name="frmSearch" method="post" action="view_trans<?php echo $suffix; ?>_processing.php?d1=<?php if ( isset($_POST['btn_filter']) ) {$post_at = $_POST["search"]["post_at"]; echo $post_at;} ?>&d2=<?php if ( isset($_POST['btn_filter']) ) {$post_at_to_date = $_POST["search"]["post_at_to_date"]; echo $post_at_to_date;} ?>&" autocomplete="off">
						<div class="col-md-8">
							<div class="col-md-3">
								<div class="form-group form-group-sm">
									<div class="date">
										<div class="input-group input-append date" id="start_date">
										<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
											<input type="text" class="form-control input-sm" placeholder="From Date" id="post_at" name="search[post_at]"  value="<?php if ( isset($_POST['btn_filter']) ) { echo $post_at;} if (isset($_GET['d1'])) {echo $post_at;}?>" />
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-1"><strong>-</strong></div>
							<div class="col-md-3">
								<div class="form-group form-group-sm">
									<div class="date">
										<div class="input-group input-append date" id="end_date">
											<input type="text" class="form-control input-sm" placeholder="To Date" id="post_at_to_date" name="search[post_at_to_date]" style="margin-left:10px"  value="<?php if ( isset($_POST['btn_filter']) ) {echo $post_at_to_date;} if (isset($_GET['d2'])) {echo $post_at_to_date;}?>" /> 
											<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<input type="submit" class="btn btn-success btn-sm" name="btn_filter" value="Show Range" />
								<a href="view_trans<?php echo $suffix; ?>.php" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-signal"></span> View ALL Records</a>
							</div>
						</div>
					</form>
					<div class="col-md-4 text-right"></div>
				</div>
				</td>
				</tr>

				<tr>
					<?php						
					echo '
					<th colspan="13">'; 
						if (isset($_GET["d1"]) && isset($_GET["d2"]) ) {
							echo '<span style="font-size: 18px;"><span style="color:#ec7063;">Showing search result of transactions between </span>'.$post_at.' <span style="color:#ec7063;">and</span> '.$post_at_to_date.'</span>';
						}
					echo '
					</th>';
					?>
				</tr>
				</thead>
				
				<thead>
				<tr class="info text-info">
					<th colspan="2" width="5%">ACTION</th>
					<th class="danger" width="3%">S/N</th>
					<th></th>
					<th width="8%">DATE</th>
					<th>SHOP NO</th>
					<th width="28%">TRANSACTION DESCRIPTION</th>
					<th width="10%" class="text-right">AMOUNT</th>
					<th width="8%" class="text-right">RECEIPT NO</th>
					<th class="text-center">REVIEW</th>
					<th class="text-center">FC</th>
					<th class="text-center">AUDIT</th>
					<th class="text-center" width="10%">AUTHORIZATION</th>
				</tr>
				</thead>
				
					 <?php
					 $caamount_paid = "";
					 $capayment_month = "";
					 
					 if (isset($_GET['s'])) {
							$i = $_GET['s'];
						} else {
							$i=0;
						} 
					 
					 $query = "SELECT * ";
					 $query .= "FROM {$prefix}account_general_transaction_new ";
					 $query .= "WHERE leasing_post_status = 'Pending' ";
					 //$query .= "OR approval_status = 'Pending' ";
					 $query .= "ORDER BY date_of_payment DESC ";
					 $query .= "LIMIT $start, $pagerows";
					 $post_all_set = mysqli_query($dbcon,$query);
					 $post_no = mysqli_num_rows($post_all_set);
					 
					 if(!empty($post_filter_set)){
						$post_set = $post_filter_set;
					 } else {
						 $post_set = $post_all_set;
					 }
						while ($post = mysqli_fetch_array($post_set, MYSQLI_ASSOC)) {
					
						 $post_shop_no = $post['shop_no'] ;
						 $amount = $post['amount_paid'];
						 $amount_paid = number_format((float)$amount, 0);
						 $posting_time = $post['posting_time'];
						 $leasing_approval_time = $post['leasing_post_approval_time'];
						 $approval_time = $post['approval_time'];
						 $verification_time = $post['verification_time'];
						 $receipt_no = $post["receipt_no"];
						 $date_of_payment = $post["date_of_payment"];
					?>
					
					<?php
					 $query = "SELECT * ";
					 $query .= "FROM customers ";
					 $query .= "WHERE shop_no = '$post_shop_no'";
					 $result = mysqli_query($dbcon, $query);
					 $shop = mysqli_fetch_array($result, MYSQLI_ASSOC);
					?>
					<?php
						$shop_id = $shop["id"];
						$txref = $post["id"];
					?>
					
					<tr>
						<td colspan="2">
						   <?php
							//For Pending Transactions/Declined Transactions 
							date_default_timezone_set('Africa/Lagos');
							$today_date = date('Y-m-d');

							if(($menu['department'] == "Accounts") && ($post["leasing_post_status"] == "" && $post["approval_status"] != "Approved" && $menu['level'] != "fc")) {
								if ($post["entry_status"] != "Journal") {
									echo '<a href="javascript:edit_id('.'\''.$txref.'\''.')" title="Edit"><img src="images/edit.png" /></a> - ';		
								} else {
									echo '<p><a href="journal_entry'.$suffix.'.php?repost_id='.$txref.'" class="btn btn-success btn-xs">Repost - Multi Credit</a></p>';
									echo '<p><a href="journal_entry'.$suffix.'_md.php?repost_id='.$txref.'" class="btn btn-danger btn-xs">Repost - Multi Debit</a></p>';
								}
							}

							if($menu["department"] == "Leasing" || $menu["department"] == "Wealth Creation") {
								if ($post["leasing_post_status"] != "Approved" && $post["entry_status"] != "Journal" && $menu['level'] != "fc") {
									echo '<a href="javascript:edit_id('.'\''.$txref.'\''.')" title="Edit"><img src="images/edit.png" /></a> - ';
								}
							}


							//Delete Current Day Transactions
							if($today_date == $date_of_payment || $date_of_payment == "0000-00-00" || $staff['level'] == "ce") {
								if (($post["approval_status"] != "Approved" && $staff['user_id'] == $post['posting_officer_id']) || $staff['level'] == "ce" ) {
									echo '<a href="javascript:delete_id('.'\''.$txref.'\''.')" title="Delete"><img src="images/delete.png" /></a>';
								}
							}
							?>
						</td>
						
						<th class="danger"><?php echo ++$i.'.'; ?></th>
							<td>
								<a href="javascript:txdetails('<?php echo $txref; ?>')" class="btn btn-primary btn-xs">Details</a>
							</td>

							<td>
								<?php 
									list($tid,$tim,$tiy) = explode("-",$post["date_of_payment"]);
									$date_of_payment = "$tiy/$tim/$tid";
									echo $date_of_payment; 
								?>
							</td>

							<td>
								<?php 
									if ($post["shop_no"]=="") {
										echo "";
									} else {
									echo 
										'<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-primary btn-sm"><span style="color:yellow;"><strong>'.$post["shop_no"].'</strong></span></a>';
									}
								?>
							</td> 

							<td>
								<?php
									$plate_no = $post["plate_no"];
									if ($plate_no == "") {
										echo strtoupper(strtolower($post["transaction_desc"]));
									} else {
										echo strtoupper($plate_no).' - '.strtoupper(strtolower($post["transaction_desc"]));
									}
								?>
							</td>

							<th class="text-right">
								<?php 
									if (($post["leasing_post_status"] != "Pending")) {
										echo "&#8358 {$amount_paid}"; 
									}
								?>
							</th>

							<th class="text-right">
								<?php 
									if (($post["leasing_post_status"] != "Pending")) {
										echo '<p>'.$post["receipt_no"].'</p>'; 
									}
									
									if(@$post["entry_status"] == "Journal"){
										echo '<p><span style="color:blue;">'.$post["cheque_no"].'</span></p>'; 
									}
								?>
							</th>
						
						<td>
							<?php
								if ($post["leasing_post_status"] == "Approved") {
									echo '<a href="#" class="btn btn-success btn-xs">Approved</a>';	
								} elseif ($post["leasing_post_status"] == "Declined") {
									echo '<a href="#" class="btn btn-danger btn-xs">Declined '.time_elapsed_string($leasing_approval_time).'</a>';	
								} elseif ($post["leasing_post_status"] == "Pending") {
									echo '<a href="#" class="btn btn-warning btn-xs">Pending '.time_elapsed_string($posting_time).'</a>';
								} else {
									echo '';
								}
							?> 
						</td>

						<td>
							<?php 
								if ($post["approval_status"] == "Approved") {
									echo '<a href="#" class="btn btn-success btn-xs">Approved</a>';	
								} elseif ($post["approval_status"] == "Declined") {
									echo '<a href="#" class="btn btn-danger btn-xs">Declined '.time_elapsed_string($approval_time).'</a>';	
								} elseif ($post["approval_status"] == "Pending" && $post["leasing_post_status"] == "Approved") {
									echo '<a href="#" class="btn btn-warning btn-xs">Pending '.time_elapsed_string($leasing_approval_time).'</a>';
								} elseif ($post["approval_status"] == "Pending" && $post["leasing_post_status"] == "") {
									echo '<a href="#" class="btn btn-warning btn-xs">Pending '.time_elapsed_string($posting_time).'</a>';
								} else {
									echo ' ';
								}
							?> 
						</td>
						<td>
							<?php
								if (($post["approval_status"] == "Pending") || ($post["approval_status"] == "Declined") || ($post["approval_status"] == ""))  {
									echo '';
								} elseif ($post["verification_status"] == "Verified") {
									echo '<a href="#" class="btn btn-success btn-xs">Verified</a>';
								} elseif ($post["verification_status"] == "Declined") {
									echo '<a href="#" class="btn btn-danger btn-xs">Declined '.time_elapsed_string($verification_time).'</a>';
								} elseif ($post["verification_status"] == "Flagged") {
									echo '<a href="#" class="btn btn-xs btn-danger">FLAGGED '.time_elapsed_string($verification_time).'</a>';
								} else {
									echo '<a href="#" class="btn btn-warning btn-xs">Pending '.time_elapsed_string($approval_time).'</a>';	
								};
							?>
						</td>
												

						<?php 
						if ($post["shop_no"]=="") {
							echo '<td rowspan="2">';
						} else {
							echo '<td rowspan="3">';
						}
						?>
							<?php
							if ($menu['department'] == "Audit/Inspections"){
								
								if (($post["approval_status"] == "Approved") && ($post['verification_status'] == "Verified") && ($menu["level"] == "Head, Audit & Inspection")) {
									echo '<a href="javascript:txdetails('.$txref.')" class="btn btn-xs btn-danger">Flag</a>';
									
								} elseif (($post["approval_status"] == "Approved") && ($post['verification_status'] == "Pending")) {
									if ($post['flag_status'] != "Flagged") {
										echo '<a class="btn btn-xs btn-success" id="approve_record" data-id="'.$txref.'" href="javascript:void(0)">Verify</a> ';
										echo '<a href="javascript:txdetails('.$txref.')" class="btn btn-xs btn-danger">Flag</a>';
									} else {
										echo '<p><a href="#" class="btn btn-xs btn-primary">CURRENTLY FLAGGED</a></p>';
										echo '<p><a class="btn btn-xs btn-success" id="approve_record" data-id="'.$txref.'" href="javascript:void(0)">Re-verify</a></p>';
									}
								} elseif (($post["approval_status"] == "Approved") && ($post['verification_status'] == "Flagged")) {
									echo '<a href="#" class="btn btn-xs btn-warning">FLAGGED</a>';
								} elseif (($post["approval_status"] == "Declined") && ($post['flag_status'] == "Flagged")) {
									echo '<a href="#" class="btn btn-xs btn-warning">FLAGGED</a>';
								} else {}
								
							} elseif ($menu['level'] == "fc"){
								if ($post['approval_status'] == "Approved" && $post['verification_status'] == "Verified") {
									echo '<a href="javascript:txdetails('.$txref.')" class="btn btn-xs btn-danger">Flag</a>';
									
								} elseif ($post['approval_status'] == "Approved" && $post['verification_status'] == "Pending") {
									echo '<a class="btn btn-xs btn-danger" id="decline_record" data-id="'.$txref.'" href="javascript:void(0)">Decline</a>';
									
								} elseif (($post["approval_status"] == "Approved") && ($post['verification_status'] == "Flagged")) {
									echo '<a class="btn btn-xs btn-danger" id="decline_record" data-id="'.$txref.'" href="javascript:void(0)">Decline</a>';
									
								} elseif ($post['approval_status'] == "Pending") {
									//if ($all_long_declined_trans == 0) {
										echo '<a class="btn btn-xs btn-success" id="approve_record" data-id="'.$txref.'" href="javascript:void(0)">Approve</a> ';
									//}
										echo '<a class="btn btn-xs btn-danger" id="decline_record" data-id="'.$txref.'" href="javascript:void(0)">Decline</a>';
								} elseif ($post['approval_status'] == "Declined" && $post['leasing_post_status'] != "Declined" && $post['flag_status'] != "Flagged") {
									//if ($all_long_declined_trans == 0) {
										echo '<a class="btn btn-xs btn-success" id="approve_record" data-id="'.$txref.'" href="javascript:void(0)">Approve</a> ';
									//}
								} elseif (($post["approval_status"] == "Declined") && ($post['flag_status'] == "Flagged")) {
									echo '<a href="#" class="btn btn-xs btn-warning">FLAGGED</a>';
								} else {}
							} elseif (($menu['department'] == "Accounts" && $role['acct_approve_payment'] != "Yes" && $post['leasing_post_status'] != "" && $post['approval_status']!="Approved") || ($menu['level'] == "ce")) {
								echo '<a href="review_trans'.$suffix.'.php?txref='.$txref.'" class="btn btn-primary btn-xs">Review</a>';
							} else {}
							?>
						</td>
						
					</tr>
					
					<?php 
						if ($post["shop_no"]!="") {
						?>
						<tr>
							<td colspan="2"></td>
							<td class="danger"></td>
							<td colspan="9">Payment Break Down:
								<?php
									$caquery = "SELECT * ";
									
									if($post["payment_category"] == "Rent") {
										$caquery .= "FROM collection_analysis ";
									} else {
										$caquery .= "FROM collection_analysis_arena ";
									}
										$caquery .= "WHERE shop_id = '$shop_id' ";
										$caquery .= "AND receipt_no = '$receipt_no'";
										$caresult = mysqli_query($dbcon, $caquery);
										while ($cashop = mysqli_fetch_array($caresult, MYSQLI_ASSOC)){
										$capayment_month = $cashop["payment_month"];
										$caamount_paid = $cashop["amount_paid"];
										$caamount_paid = number_format((float)$caamount_paid, 0);
										
										
										//if (($post["leasing_post_status"] != "Pending") && ($post["leasing_post_status"] != "Declined")) {
											echo ' <span style="color:#ec7063;">'.$capayment_month.'</span>'; echo ": &#8358 {$caamount_paid}"; echo ' |';
										//}
									}
								?>
							</td>
						</tr>
						<?php
						}
						?>

						<?php
							$debit_account = $post["debit_account"];
							$credit_account = $post["credit_account"];
							$posting_officer_id = $post["posting_officer_id"];
							$leasing_post_approving_officer_id = $post["leasing_post_approving_officer_id"];
							$leasing_post_approving_officer_name = $post["leasing_post_approving_officer_name"];
							$post_acct_staff = $post["posting_officer_name"];
							$post_fc_acct_staff = $post["approving_acct_officer_name"];
							
							
							$query_acct1 = "SELECT * ";
							$query_acct1 .= "FROM {$prefix}accounts ";
							$query_acct1 .= "WHERE acct_id = $debit_account";
							$acct_debit_table_set = mysqli_query($dbcon, $query_acct1);
							$acct_debit_table = mysqli_fetch_array($acct_debit_table_set, MYSQLI_ASSOC);
							
							$db_debit_desc = $acct_debit_table["acct_desc"];
							
							$query_acct2 = "SELECT * ";
							$query_acct2 .= "FROM {$prefix}accounts ";
							$query_acct2 .= "WHERE acct_id = $credit_account";
							$acct_credit_table_set = mysqli_query($dbcon, $query_acct2);
							$acct_credit_table = mysqli_fetch_array($acct_credit_table_set, MYSQLI_ASSOC);
							
							$db_credit_desc = $acct_credit_table["acct_desc"];
						?>
						<tr>	
							<td colspan="2"></td>
							<td class="danger"></td>
							<td colspan="5">
							<?php
								//$credit_account1 = $post['credit_account_jrn1'];
								$credit_account2 = @$post['credit_account_jrn2'];
								$credit_account3 = @$post['credit_account_jrn3'];
								$credit_account4 = @$post['credit_account_jrn4'];
								$credit_account5 = @$post['credit_account_jrn5'];
								$credit_account6 = @$post['credit_account_jrn6'];
								$credit_account7 = @$post['credit_account_jrn7'];
								
								
								//$debit_account1 = $post['debit_account_jrn1'];
								$debit_account2 = @$post['debit_account_jrn2'];
								$debit_account3 = @$post['debit_account_jrn3'];
								$debit_account4 = @$post['debit_account_jrn4'];
								$debit_account5 = @$post['debit_account_jrn5'];
								$debit_account6 = @$post['debit_account_jrn6'];
								$debit_account7 = @$post['debit_account_jrn7'];
								
								
								//Debit Account 2 Input Query 
								$dquery_acct2 = "SELECT * ";
								$dquery_acct2 .= "FROM {$prefix}accounts ";
								$dquery_acct2 .= "WHERE acct_id = $debit_account2";
								$acct_debit_table_set2 = @mysqli_query($dbcon, $dquery_acct2);
								$acct_debit_table2 = @mysqli_fetch_array($acct_debit_table_set2, MYSQLI_ASSOC);
								
								$db_debit_acct_desc2 = $acct_debit_table2["acct_desc"];
								
								//Debit Account 3 Input Query 
								$dquery_acct3 = "SELECT * ";
								$dquery_acct3 .= "FROM {$prefix}accounts ";
								$dquery_acct3 .= "WHERE acct_id = $debit_account3";
								$acct_debit_table_set3 = @mysqli_query($dbcon, $dquery_acct3);
								$acct_debit_table3 = @mysqli_fetch_array($acct_debit_table_set3, MYSQLI_ASSOC);
								
								$db_debit_acct_desc3 = $acct_debit_table3["acct_desc"];
								
								//Debit Account 4 Input Query 
								$dquery_acct4 = "SELECT * ";
								$dquery_acct4 .= "FROM {$prefix}accounts ";
								$dquery_acct4 .= "WHERE acct_id = $debit_account4";
								$acct_debit_table_set4 = @mysqli_query($dbcon, $dquery_acct4);
								$acct_debit_table4 = @mysqli_fetch_array($acct_debit_table_set4, MYSQLI_ASSOC);
								
								$db_debit_acct_desc4 = $acct_debit_table4["acct_desc"];
								
								//Debit Account 5 Input Query 
								$dquery_acct5 = "SELECT * ";
								$dquery_acct5 .= "FROM {$prefix}accounts ";
								$dquery_acct5 .= "WHERE acct_id = $debit_account5";
								$acct_debit_table_set5 = @mysqli_query($dbcon, $dquery_acct5);
								$acct_debit_table5 = @mysqli_fetch_array($acct_debit_table_set5, MYSQLI_ASSOC);
								
								$db_debit_acct_desc5 = $acct_debit_table5["acct_desc"];
								
								//Debit Account 6 Input Query 
								$dquery_acct6 = "SELECT * ";
								$dquery_acct6 .= "FROM {$prefix}accounts ";
								$dquery_acct6 .= "WHERE acct_id = $debit_account6";
								$acct_debit_table_set6 = @mysqli_query($dbcon, $dquery_acct6);
								$acct_debit_table6 = @mysqli_fetch_array($acct_debit_table_set6, MYSQLI_ASSOC);
								
								$db_debit_acct_desc6 = $acct_debit_table6["acct_desc"];

								//Debit Account 7 Input Query 
								$dquery_acct7 = "SELECT * ";
								$dquery_acct7 .= "FROM {$prefix}accounts ";
								$dquery_acct7 .= "WHERE acct_id = $debit_account7";
								$acct_debit_table_set7 = @mysqli_query($dbcon, $dquery_acct7);
								$acct_debit_table7 = @mysqli_fetch_array($acct_debit_table_set7, MYSQLI_ASSOC);
								
								$db_debit_acct_desc7 = $acct_debit_table7["acct_desc"];
								
								
								
								//Credit Account 2 Input Query 
								$cquery_acct2 = "SELECT * ";
								$cquery_acct2 .= "FROM {$prefix}accounts ";
								$cquery_acct2 .= "WHERE acct_id = $credit_account2";
								$acct_credit_table_set2 = @mysqli_query($dbcon, $cquery_acct2);
								$acct_credit_table2 = @mysqli_fetch_array($acct_credit_table_set2, MYSQLI_ASSOC);
								
								$db_credit_acct_desc2 = $acct_credit_table2["acct_desc"];
								
								//Credit Account 3 Input Query 
								$cquery_acct3 = "SELECT * ";
								$cquery_acct3 .= "FROM {$prefix}accounts ";
								$cquery_acct3 .= "WHERE acct_id = $credit_account3";
								$acct_credit_table_set3 = @mysqli_query($dbcon, $cquery_acct3);
								$acct_credit_table3 = @mysqli_fetch_array($acct_credit_table_set3, MYSQLI_ASSOC);
								
								$db_credit_acct_desc3 = $acct_credit_table3["acct_desc"];
								
								
								//Credit Account 4 Input Query 
								$cquery_acct4 = "SELECT * ";
								$cquery_acct4 .= "FROM {$prefix}accounts ";
								$cquery_acct4 .= "WHERE acct_id = $credit_account4";
								$acct_credit_table_set4 = @mysqli_query($dbcon, $cquery_acct4);
								$acct_credit_table4 = @mysqli_fetch_array($acct_credit_table_set4, MYSQLI_ASSOC);
								
								$db_credit_acct_desc4 = $acct_credit_table4["acct_desc"];
								
								
								//Credit Account 5 Input Query 
								$cquery_acct5 = "SELECT * ";
								$cquery_acct5 .= "FROM {$prefix}accounts ";
								$cquery_acct5 .= "WHERE acct_id = $credit_account5";
								$acct_credit_table_set5 = @mysqli_query($dbcon, $cquery_acct5);
								$acct_credit_table5 = @mysqli_fetch_array($acct_credit_table_set5, MYSQLI_ASSOC);
								
								$db_credit_acct_desc5 = $acct_credit_table5["acct_desc"];
								
								
								//Credit Account 6 Input Query 
								$cquery_acct6 = "SELECT * ";
								$cquery_acct6 .= "FROM {$prefix}accounts ";
								$cquery_acct6 .= "WHERE acct_id = $credit_account6";
								$acct_credit_table_set6 = @mysqli_query($dbcon, $cquery_acct6);
								$acct_credit_table6 = @mysqli_fetch_array($acct_credit_table_set6, MYSQLI_ASSOC);
								
								$db_credit_acct_desc6 = $acct_credit_table6["acct_desc"];
								
								
								//Credit Account 7 Input Query 
								$cquery_acct7 = "SELECT * ";
								$cquery_acct7 .= "FROM {$prefix}accounts ";
								$cquery_acct7 .= "WHERE acct_id = $credit_account7";
								$acct_credit_table_set7 = @mysqli_query($dbcon, $cquery_acct7);
								$acct_credit_table7 = @mysqli_fetch_array($acct_credit_table_set7, MYSQLI_ASSOC);
								
								$db_credit_acct_desc7 = $acct_credit_table7["acct_desc"];

								
								if(@$post["entry_status"] == "Journal"){	
									if($post["credit_account_jrn2"] != ""){	
										echo '
											<span style="color:red;"><strong>Multi Credit Journal Entry:</strong></span> <strong>'.$db_credit_acct_desc2.' | '.$db_credit_acct_desc3.' | '.$db_credit_acct_desc4.' | '.$db_credit_acct_desc5.' | '.$db_credit_acct_desc6.' | '.$db_credit_acct_desc7.'</strong>';
									} elseif($post["debit_account_jrn2"] != "")  {
										echo '
											<span style="color:red;"><strong>Multi Debit Journal Entry:</strong></span> <strong>'.$db_debit_acct_desc2.' | '.$db_debit_acct_desc3.' | '.$db_debit_acct_desc4.' | '.$db_debit_acct_desc5.' | '.$db_debit_acct_desc6.' | '.$db_debit_acct_desc7.'</strong>';
									} else {
										echo '';
									}
								}
							?>
							</td>
							<td colspan="4">
								<span style="color:red;"><strong>Debit:</strong></span> <strong><?php echo $db_debit_desc; ?></strong></br> 
								<span style="color:red;"><strong>Credit:</strong></span> <strong><?php echo $db_credit_desc; ?></strong>
							</td>
						</tr>

						<?php
							$start_date = $post["start_date"];
							$end_date = $post["end_date"];

							@list($tiy,$tim,$tid) = explode("-",@$shop["lease_start_date"]);
							$lease_start_date = "$tid/$tim/$tiy";

							@list($tiy,$tim,$tid) = explode("-",@$shop["lease_end_date"]);
							$lease_end_date = "$tid/$tim/$tiy";

							@list($tiy,$tim,$tid) = explode("-",@$shop["service_charge_start_date"]);
							$service_charge_start_date = "$tid/$tim/$tiy";

							@list($tiy,$tim,$tid) = explode("-",@$shop["service_charge_end_date"]);
							$service_charge_end_date = "$tid/$tim/$tiy";

						if($post["shop_no"] != ""){	
							echo '
							<tr>
								<td colspan="2"></td>
								<td class="danger"></td>
								<td colspan="4">';
									echo 'Current Tenancy Duration: '.$lease_start_date.' to '.$lease_end_date;
								echo '		
								</td>
								<td colspan="6">';
									if ($start_date != "" || $end_date != "") {
										echo 'Tenure Paid for: '.$start_date.' to '.$end_date;
									}
								echo '
								</td>
							</tr>';
							}
						?>

						<tr>
							<td colspan="2"></td>
							<td class="danger"></td>
							
							<td colspan="10" class="text-right">
								<?php
								if($leasing_post_approving_officer_name == ""){
									echo 'Posted by: <a href="acct_view_trans.php?staff_id='.$posting_officer_id.'"><strong>'.$post_acct_staff.'</strong></a>';
								}else{
									echo 'Posted by: <a href="../leasing/view_trans.php?staff_id='.$posting_officer_id.'"><strong>'.$post_acct_staff.'</strong></a> | Reviewed by: <a href="acct_view_trans'.$sub_suffix.'.php?staff_id='.$leasing_post_approving_officer_id.'"><strong>'.$leasing_post_approving_officer_name.'</strong></a>';
								}
								
								if($post["approving_acct_officer_name"] != ""){
									echo ' | Approved by: <a href="#"><strong>'.$post["approving_acct_officer_name"].'</strong></a>';
								}
								
								if($post["verifying_auditor_name"] != ""){
									echo ' | Verified by: <a href="#"><strong>'.$post["verifying_auditor_name"].'</strong></a>';
								}
								?>
							</td>
						</tr>

						<tr>
							<td colspan="13" class="info"></td>
						</tr>
						<tr>
							<td colspan="13" class="info"></td>
						</tr>
						<tr>
							<td colspan="13" class="info"></td>
						</tr>
					<?php	 
					 }
					?>
					<tr class="text-info">
						<td colspan="11"></td>
						<td class="text-right" colspan="2">
						<?php
							if ($pages > 1){
								echo '<p>';
								
								//What number is the current page?
								$current_page = ($start/$pagerows) + 1;
								
								//If the page is not the first page then create a Previous link
								if ($current_page != 1){
									echo '<a href="view_trans'.$suffix.'.php?s=' .  ($start - $pagerows) . '&p=' . $pages . '" class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Previous</a> ';
								}
								
								//Create a Next link
								if ($current_page != $pages){
									echo '<a href="view_trans'.$suffix.'.php?s=' .  ($start + $pagerows) . '&p=' . $pages . '" class="btn btn-primary">Next <span class="glyphicon glyphicon-forward"></span></a> ';
								}
								echo '</p>';
							}
						?>
						</td>
					</tr>
			  </table>
			  </div>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
function edit_id(id)
{
	if(confirm('Are you sure you want to EDIT this transaction record?'))
	{
		window.location.href='edit_posting<?php echo $suffix; ?>.php?edit_id='+id;
	}
}
function delete_id(id)
{
	if(confirm('Are you sure you want to COMPLETELY DELETE details?'))
	{
		window.location.href='view_trans<?php echo $suffix; ?>.php?delete_id='+id;
	}
}
function cdetails_id(id)
{
	if(confirm)
	{
		window.location.href='../leasing/customer_details.php?cdetails_id='+id;
	}
}

function txref(id)
{
	if(confirm)
	{
		window.location.href='review_trans<?php echo $suffix; ?>.php?txref='+id;
	}
}
function txdetails(id)
{
	if(confirm)
	{
		window.location.href='transaction_details<?php echo $suffix; ?>.php?txref='+id;
	}
}
$('#stats').ready 
	(function statRefresh() {
    var $statboard = $("#stats");
	setInterval(function statRefresh() {
    $statboard.load("view_trans<?php echo $suffix; ?>.php #stats");
	}, 600000);
})
</script>
</html>