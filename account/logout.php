<?php
include 'include/dbconfig.php';
session_start();

if (!isset($_SESSION['staff'])) {
	header("Location: ../../login.php");
} else if(isset($_SESSION['staff'])!="") {
	header("Location: ../../index.php");
}

if (!isset($_SESSION['admin'])) {
	header("Location: ../../login.php");
} else if(isset($_SESSION['admin'])!="") {
	header("Location: ../../index.php");
}

if (isset($_GET['logout'])) {
	if (isset($_SESSION['staff'])){
		$sqlquery = "SELECT * FROM users WHERE id=".$_SESSION['staff'];
	} 
	if (isset($_SESSION['admin'])){
		$sqlquery = "SELECT * FROM users WHERE id=".$_SESSION['admin'];
	}
	$result = mysqli_query($dbcon,$sqlquery);
	$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
	//$count = mysqli_num_rows($result); // if uname/pass correct it returns must be 1 row

	$staff_id = $user['id'];
	$logout_status = 'Logged Out';

	date_default_timezone_set('Africa/Lagos'); // your reference timezone here
	$now = date('Y-m-d H:i:s');

	$session_query = "UPDATE users_logs SET logout_status='$logout_status', logout_time='$now' WHERE user_id='$staff_id'";
	$session_result = mysqli_query($dbcon, $session_query);
	
	unset($_SESSION['staff']);
	unset($_SESSION['admin']);
	session_unset();
	session_destroy();
	header("Location: ../../index.php");
	exit;
}
?>