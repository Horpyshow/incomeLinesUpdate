<?php
if (isset($_POST["btn_view_ledger"])) {
	$account_id = $_POST['ledger_account'];
	$account_id = htmlspecialchars($account_id);
	$account_id = urlencode($account_id);
	
	header ("Location: ledgers.php?acct_id={$account_id}&");
}


if ( isset($_POST['btn_filter'])) {
	$d1 = $_POST["search"]["post_at"];
	$d1 = htmlspecialchars($d1);
	$d1 = urlencode($d1);
	
	$d2 = $_POST["search"]["post_at_to_date"];
	$d2 = htmlspecialchars($d2);
	$d2 = urlencode($d2);
	
	header ("Location: profit_loss.php?d1={$d1}&d2={$d2}&");
}
?>