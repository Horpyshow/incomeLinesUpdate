<?php
if ( isset($_POST['btn_filter']) ) {
	$d1 = $_POST["date_of_remittance"];
	$d1 = htmlspecialchars($d1);
	$d1 = urlencode($d1);
	
	header ("Location: account_dashboard.php?d1={$d1}&");
}
?>