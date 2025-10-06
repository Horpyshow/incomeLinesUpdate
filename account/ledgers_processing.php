<?php
if (isset($_POST["btn_view_ledger"])) {
	$account_id = $_POST['ledger_account'];
	$account_id = htmlspecialchars($account_id);
	$account_id = urlencode($account_id);
	
	header ("Location: ledgers.php?acct_id={$account_id}&");
}


if ( isset($_POST['btn_filter']) && isset($_GET['acct_id']) ) {
	$account_id = $_GET['acct_id'];
	$account_id = htmlspecialchars($account_id);
	$account_id = urlencode($account_id);
	
	$d1 = $_POST["search"]["post_at"];
	$d1 = htmlspecialchars($d1);
	$d1 = urlencode($d1);
	
	$d2 = $_POST["search"]["post_at_to_date"];
	$d2 = htmlspecialchars($d2);
	$d2 = urlencode($d2);
	
	header ("Location: ledgers.php?acct_id={$account_id}&d1={$d1}&d2={$d2}&");
}
?>