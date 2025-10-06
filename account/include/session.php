<?php
ob_start();
session_start();
require_once 'include/dbconfig.php';

date_default_timezone_set('Africa/Lagos');
$current_time = new DateTime();
$begin_time = new DateTime('12:00');
$end_time = new DateTime('22:00'); 

$wc_begin_time = new DateTime('20:00');
$wc_begin_time_exception = new DateTime('19:00');
$wc_end_time = new DateTime('23:59');

if ($current_time >= $begin_time && $current_time <= $end_time){
	//include ('include/delete_pending_posts.php');
} 

// if session is not set this will redirect to login page
if(isset($_SESSION['staff']) ) {

// select loggedin users detail
$query = "SELECT * FROM staffs WHERE user_id=".$_SESSION['staff'];
$result = @mysqli_query($dbcon, $query); 
$staff = mysqli_fetch_array($result, MYSQLI_ASSOC);

//Check token
$tresult = mysqli_query($dbcon, "SELECT token FROM users_logs WHERE user_id='".$_SESSION['staff']."'");
 
  if (mysqli_num_rows($tresult) > 0)  {
 
   $row = mysqli_fetch_array($tresult); 
   $token = $row['token']; 

   if($_SESSION['token'] != $token){
    session_destroy();
    header("Location: ../../index.php");
   }
  } else{
	  	unset($_SESSION['staff']);
		unset($_SESSION['admin']);
		session_unset();
		session_destroy();
		header("Location: index.php");
		exit;
  }
} 

elseif (isset($_SESSION['admin']) ) {

// select loggedin users detail
$query = "SELECT * FROM staffs WHERE user_id=".$_SESSION['admin'];
$result = @mysqli_query($dbcon, $query); 
$staff = mysqli_fetch_array($result, MYSQLI_ASSOC);

//Check token
$tresult = mysqli_query($dbcon, "SELECT token FROM users_logs WHERE user_id='".$_SESSION['admin']."'");
 
  if (mysqli_num_rows($tresult) > 0)  {
 
   $row = mysqli_fetch_array($tresult); 
   $token = $row['token']; 

   if($_SESSION['token'] != $token){
    session_destroy();
    header("Location: ../../index.php");
   }
  } else{
	  	unset($_SESSION['staff']);
		unset($_SESSION['admin']);
		session_unset();
		session_destroy();
		header("Location: index.php");
		exit;
  }
}

else {
header("Location: ../../login.php");
exit;	
}