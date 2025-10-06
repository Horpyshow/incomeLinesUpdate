<?php

include 'include/session.php';

if($_REQUEST['ref_id']) {
	$sql = "SELECT * FROM customers_new_registration WHERE ref_no='".$_REQUEST['ref_id']."'";
	$resultset = mysqli_query($dbcon, $sql) or die("database error:". mysqli_error($dbcon));
	
	$data = array();
	while( $rows = mysqli_fetch_assoc($resultset) ) {
		$data = $rows;
	}
	echo json_encode($data);
} else {
	echo 0;	
}
?>