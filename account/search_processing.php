<?php
if (isset($_POST["btn-search"])) {
	$search = $_POST["search"];
	$search = htmlspecialchars($search);
	$search = urlencode($search);
}

header ("Location: search.php?sr={$search}&");


if (isset($_POST["btn-rsearch"])) {
	$search = $_POST["rsearch"];
	$search = htmlspecialchars($search);
	$search = urlencode($search);
}

header ("Location: search.php?sr={$search}&");
?>