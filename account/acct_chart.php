<?php
include 'include/session.php';

$project = "WRL";
$prefix = "";
$suffix = "";
$page_title = "{$project} General Ledger - Woobs Resources ERP";

if(isset($_SESSION['staff']) ) {
$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['staff'];
}
if(isset($_SESSION['admin']) ) {
$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['admin'];
}
$role_set = mysqli_query($dbcon, $query);
$role = mysqli_fetch_array($role_set, MYSQLI_ASSOC);

if ($role['acct_view_record'] != "Yes") {
	header("Location: ../../../index.php");
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Woobs Resources ERP</title>
		<meta name="description" content="Woobs Resources ERP Management System">
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<script src="../../js/jquery.js"></script>
		<script src="../../js/bootstrap.js"></script>
		<script src="../../js/sub_menu.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">
		
		<script src="js/exportxls.js"></script>
</head>
<body>
<?php 
	include ('include/staff_navbar.php');
	$vp_user_id = $menu["user_id"];
	$vp_staff_name = $menu["full_name"];
	$sessionID = session_id();
	
	$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$url = htmlspecialchars(strip_tags($url));
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	$vp_query = "INSERT IGNORE INTO visited_pages (id, user_id, staff_name, session_id, uri, time) VALUES ('', '$vp_user_id', '$vp_staff_name', '$sessionID', '$url', '$now')";
	$vp_result = mysqli_query($dbcon,$vp_query); 
?>
<div class="well"></div>
<div class="container-fluid">
	<div class="col-md-2"></div>

	<div class="col-md-6">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo $project; ?> Chart of Accounts<small></small></h1>
			</div>
		</div>
	</div>

		<div class="row">
		<div class="table-responsive">
				<p><button id="btnExport" onclick="javascript:xport.toCSV('<?php echo $project; ?> Chart of Account');"> Export to CSV</button></p>
			  <table id="<?php echo $project; ?> Chart of Account" class="table table-hover">
				<thead>
				<tr class="info">
					<th colspan="10"class="danger">ACCOUNT DESCRIPTION</th>
					<th colspan="2" class="danger"></th>
				</tr>
				</thead>
					<?php

					 $query = "SELECT * ";
					 $query .= "FROM {$prefix}account_class";
					 $chart_menu_set = @mysqli_query($dbcon,$query);
					 
					 while ($chart_menu = @mysqli_fetch_array($chart_menu_set, MYSQLI_ASSOC)) {
						$acct_class = $chart_menu["acct_class_desc"];
					?>
					
					<tr>
						<th colspan="12" class="info"><?php echo strtoupper($acct_class); ?></th> 
					</tr>
					<?php
						$query = "SELECT * ";
						$query .= "FROM {$prefix}accounts ";
						$query .= "WHERE acct_class = '$acct_class'";
						$chart_set = @mysqli_query($dbcon,$query);

						while ($chart = @mysqli_fetch_array($chart_set, MYSQLI_ASSOC)) {
						$acct_id = $chart['acct_id'];
						$acct_desc = $chart['acct_desc'];
					?>
					
					<tr>
						<td colspan="10">
							<ul><li><?php echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_id.'&">'.$acct_desc.'</a>'; ?></li></ul>
						</td> 
						<td align="center">
							<a href="javascript:edit_id('<?php echo $acct_id; ?>')"><img src="images/edit.png" alt="EDIT" title="Edit <?php echo $chart["acct_desc"]; ?> account" /></a>
						</td>
						<td align="center">
							<a href="javascript:delete_id('<?php echo $acct_id; ?>')"><img src="images/delete.png" alt="DELETE" title="Delete <?php echo $chart["acct_desc"]; ?> account" /></a>
						</td>
					<?php	 
					 }
					?>
					</tr>
					<?php
					}
					?>
			  </table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

</script>
</body>
</html>