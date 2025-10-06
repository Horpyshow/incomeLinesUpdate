<?php

include 'include/session.php';

if($_REQUEST['id']) {
	$sql = "SELECT * FROM collection_analysis WHERE shop_id='".$_REQUEST['id']."'";
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