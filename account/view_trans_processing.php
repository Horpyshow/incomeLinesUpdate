<?php
if ( isset($_POST['btn_filter']) ) {
	$d1 = $_POST["search"]["post_at"];
	$d1 = htmlspecialchars($d1);
	$d1 = urlencode($d1);
	
	$d2 = $_POST["search"]["post_at_to_date"];
	$d2 = htmlspecialchars($d2);
	$d2 = urlencode($d2);
}	
header ("Location: view_trans.php?d1={$d1}&d2={$d2}&");
?>