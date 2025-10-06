<?php
ob_start();
session_start();

// This file provides the information for accessing the database.and connecting to MySQL. It also sets the language coding to utf-8
// First we define the constants:
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', '');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'wealth_creation');
// Next we assign the database connection to a variable that we will call $dbcon:
$dbcon = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
OR die ('Could not connect to MySQL: ' . mysqli_connect_error () ); 
// Finally, we set the language encoding.as utf-8
mysqli_set_charset($dbcon, 'utf8');

// if session is not set this will redirect to login page
if( !isset($_SESSION['staff']) ) {
header("Location: ../../../../../login.php");
exit;
} else {
	
header("Location: ../../../../../login.php");
exit;
	
}
