<?php 
include_once ('include/session.php'); 
include ('include/functions.php');

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$page_title = "{$project} Transaction Details - Wealth Creation ERP";

if(isset($_GET['txref']))
{
	$query="SELECT * FROM {$prefix}account_general_transaction_new WHERE id=".$_GET['txref'];
	$post_set = mysqli_query($dbcon,$query);
	$post_no = mysqli_num_rows($post_set);
	
	if($post_no == 0) {
		$query="SELECT * FROM {$prefix}account_general_transaction WHERE id=".$_GET['txref'];
		$post_set = mysqli_query($dbcon,$query);
		$post_no = mysqli_num_rows($post_set);
	}

	$post = mysqli_fetch_array($post_set, MYSQLI_ASSOC);
	 
	 $post_shop_no = $post['shop_no'] ;
	 $amount = $post['amount_paid'];
	 $amount_paid = number_format((float)$amount, 2);
		
	$query = "SELECT * ";
	$query .= "FROM customers ";
	$query .= "WHERE shop_no = '$post_shop_no'";
	$result = mysqli_query($dbcon, $query);
	$shop = mysqli_fetch_array($result, MYSQLI_ASSOC);

	$shop_id = $shop["id"];
	$txref = $post["id"];
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
		<title><?php echo $page_title; ?></title>
		<meta name="description" content="Woobs Resources ERP Management System">
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../style.css" type="text/css" />
		<script src="../../js/jquery.js"></script>
		<script src="../../js/bootstrap.js"></script>
		<script src="../../js/sub_menu.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">
</head>

<body>
	<?php 
//Located within hr module
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

	<div class="col-md-12">
	<div class="page-header">
		<h2><strong><?php echo $project; ?> Transaction Details</strong></h2>
		<?php
			$fquery = "SELECT * ";
			$fquery .= "FROM {$prefix}account_flagged_record ";
			$fquery .= "WHERE id='$txref' ";
			$fpost_all_set = @mysqli_query($dbcon,$fquery);
			$fpost = @mysqli_fetch_array($fpost_all_set, MYSQLI_ASSOC);

			$flag_officer_name = $fpost['flag_officer_name'];
			
			if($post['flag_status'] == "Flagged") {
				echo '
				<div class="row">
					<div class="col-sm-6">
						<a href="#" class="btn btn-sm btn-danger">FLAGGED by '.$flag_officer_name.'</a> <a href="#" class="btn btn-warning btn-sm">Flagged on '.$fpost["flag_time"].' ['.time_elapsed_string($fpost["flag_time"]).']</a>
						<p><h4><strong><span style="color: indigo";>'.$fpost['comment'].'</span></strong></h4></p>
					</div>
				</div>';
			}
		?>
	</div>
	
		<div class="row">
			<div class="col-md-6">
				<table class="table table-bordered">
					<thead>
						<th class="success" colspan="5">Posted Record</th>
					</thead>
					<tr>
						<td class="text-right" width="28%">Customer's Name:</td>
						<th colspan="4"><?php echo ucwords(strtolower($post["customer_name"])); ?></th>
					</tr>
					
					<tr>
						<td class="text-right">Current Occupant:</td>
						<th colspan="4"><?php echo ucwords(strtolower($shop["off_takers_name"])); ?></th>
					</tr>
					
					<tr>
						<td class="text-right">Space No:</td>
						<th colspan="4">
							<?php
								if ($post["shop_no"] != "") {
									if((@$post["payment_category"] == "Power Consumption") || (@$post["payment_category"] == "New Meter")){
										$shop_id = $post['shop_id'];
										echo '<a href="../technical/power_consumption_details.php?cdetails_id='.$shop_id.'" class="btn btn-success btn-md" title="Get more information about Shop '.$post["shop_no"].', '.$post["customer_name"].'">'.$post["shop_no"].'</a>';
									} else {
										echo '<a href="javascript:cdetails_id(\''.$shop_id.'\')" class="btn btn-success btn-md" title="Get more information about Shop '.$post["shop_no"].', '.$post["customer_name"].'">'.$post["shop_no"].'</a>';
									}
								} else {
									echo "";
								}
							?>
						</th>
					</tr>
					
					<tr>
						<td class="text-right">Space Size</td>
						<th colspan="4"><?php echo $post["shop_size"]; ?></th>
					</tr>
					
					<tr>
						<td class="text-right">Date of Payment:</td>
						<th colspan="4">
							<?php 
								list($tid,$tim,$tiy) = explode("-",$post["date_of_payment"]);
								$date_of_payment = "$tiy/$tim/$tid";
								echo $date_of_payment; 
							?>
						</th>
					</tr>
					
					<tr>
						<td class="text-right">Date on Receipt:</td>
						<th colspan="4">
							<?php 
								if(isset($post["date_on_receipt"]) && $post["date_on_receipt"] !="0000-00-00") {
									list($tiy,$tim,$tid) = explode("-",@$post["date_on_receipt"]);
									@$date_on_receipt = "$tid/$tim/$tiy";
									echo @$date_on_receipt;
								}
							?>
						</th>
					</tr>
					
					<tr>
						<td class="text-right">Period Covered:</td>
						<th colspan="4"><?php echo $post["start_date"].' - '.$post["end_date"]; ?></th>
					</tr>
					
					<tr>
						<td class="text-right">Payment Type:</td>
						<th colspan="4"><?php echo $post["payment_type"]; ?></th>
					</tr>
					<tr>
						<td class="text-right">Transaction Description:</td>
						<th colspan="4"><?php echo strtoupper(strtolower($post["transaction_desc"])); ?></th>
					</tr>
					
					<tr>
						<td class="text-right">No of Tickets:</td>
						<th colspan="4"><?php echo $post["no_of_tickets"]; ?></th>
					</tr>
					
					<tr>
						<td class="text-right">Plate No:</td>
						<th colspan="4"><?php echo strtoupper(strtolower($post["plate_no"])); ?></th>
					</tr>
					
					<tr>
						<td class="text-right">Cheque No:</td>
						<th colspan="4"><?php echo $post["cheque_no"]; ?></th>
					</tr>
					
					<tr>
						<td class="text-right">Teller No:</td>
						<th colspan="4"><?php echo $post["teller_no"]; ?></th>
					</tr>
					
					<tr>
						<td class="text-right">Receipt No:</td>
						<th colspan="4"><?php echo $post["receipt_no"]; ?></th>
					</tr>
					
					<tr>
						<td class="text-right">Payment Category:</td>
						<th colspan="4"><?php echo $post["payment_category"]; ?></th>
					</tr>
					
					<tr>
						<td class="text-right">Amount Paid:</td>
						<th colspan="4">
							<?php 
								$amount_paid = $post['amount_paid'];
								$amount_paid = number_format((int)$amount_paid, 2);
								echo "&#8358 {$amount_paid}"; 
							?>
						</th>
					</tr>
					
					<tr>
						<td class="text-right">Payment Breakdown:</td>
						<th colspan="4">
							<?php
								$receipt_no = $post["receipt_no"];
								
								if ($post["payment_category"] == "Rent") {
									$caquery = "SELECT * ";
									$caquery .= "FROM collection_analysis ";
									$caquery .= "WHERE shop_id = '$shop_id' ";
									$caquery .= "AND receipt_no = '$receipt_no'";
									$caresult = mysqli_query($dbcon, $caquery);
									
									while ($cashop = mysqli_fetch_array($caresult, MYSQLI_ASSOC)){
									$capayment_month = $cashop["payment_month"];
									$caamount_paid = $cashop["amount_paid"];
									$caamount_paid = number_format((float)$caamount_paid, 0);

									echo ' <span style="color:#ec7063;">'.$capayment_month.'</span>'; echo ": &#8358 {$caamount_paid}"; echo ' |';
									}
								} elseif ($post["payment_category"] == "Service Charge") {
									$caquery = "SELECT * ";
									$caquery .= "FROM collection_analysis_arena ";
									$caquery .= "WHERE shop_id = '$shop_id' ";
									$caquery .= "AND receipt_no = '$receipt_no'";
									$caresult = mysqli_query($dbcon, $caquery);
									
									while ($cashop = mysqli_fetch_array($caresult, MYSQLI_ASSOC)){
									$capayment_month = $cashop["payment_month"];
									$caamount_paid = $cashop["amount_paid"];
									$caamount_paid = number_format((float)$caamount_paid, 0);

									echo ' <span style="color:#ec7063;">'.$capayment_month.'</span>'; echo ": &#8358 {$caamount_paid}"; echo ' |';
									}
								} else {
									
								}
							?>
						</th>
					</tr>
					
					<tr class="info">
						<td class="text-right danger"><strong>Debit Account:</strong></td>
						<th colspan="2">
							<?php
								//Debit Accounts 1
								$debit_account1 = $post["debit_account"];
								
								$dquery1 = "SELECT * ";
								$dquery1 .= "FROM {$prefix}accounts ";
								$dquery1 .= "WHERE acct_id = '$debit_account1'";
								$dresult1 = mysqli_query($dbcon, $dquery1);
								$dacct_ledger1 = mysqli_fetch_array($dresult1, MYSQLI_ASSOC);
								
								$debit_acct_id1 = $dacct_ledger1["acct_id"];
								$debit_account_desc1 = $dacct_ledger1["acct_desc"];
								
								if ($staff['department']=="Leasing") {
									echo '<a href="#" title="Call over '.ucwords(strtolower($debit_account_desc1)).'">'.ucwords(strtolower($debit_account_desc1)).'</a></br>';
								} else {
									echo '<a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$debit_acct_id1.'#txref" title="Call over '.ucwords(strtolower($debit_account_desc1)).'">'.ucwords(strtolower($debit_account_desc1)).'</a></br>';
								}
								
								
								//Debit Accounts 2
								$debit_account2 = $post["debit_account_jrn2"];
								
								$dquery2 = "SELECT * ";
								$dquery2 .= "FROM {$prefix}accounts ";
								$dquery2 .= "WHERE acct_id = '$debit_account2'";
								$dresult2 = mysqli_query($dbcon, $dquery2);
								$dacct_ledger2 = mysqli_fetch_array($dresult2, MYSQLI_ASSOC);
								
								$debit_acct_id2 = $dacct_ledger2["acct_id"];
								$debit_account_desc2 = $dacct_ledger2["acct_desc"];
								
								if ($debit_account2 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($debit_account_desc2)).'">'.ucwords(strtolower($debit_account_desc2)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$debit_acct_id2.'#txref" title="Call over '.ucwords(strtolower($debit_account_desc2)).'">'.ucwords(strtolower($debit_account_desc2)).'</a></br>';
									}
								}
								
								
								//Debit Accounts 3
								$debit_account3 = $post["debit_account_jrn3"];
								
								$dquery3 = "SELECT * ";
								$dquery3 .= "FROM {$prefix}accounts ";
								$dquery3 .= "WHERE acct_id = '$debit_account3'";
								$dresult3 = mysqli_query($dbcon, $dquery3);
								$dacct_ledger3 = mysqli_fetch_array($dresult3, MYSQLI_ASSOC);
								
								$debit_acct_id3 = $dacct_ledger3["acct_id"];
								$debit_account_desc3 = $dacct_ledger3["acct_desc"];
								
								if ($debit_account3 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($debit_account_desc3)).'">'.ucwords(strtolower($debit_account_desc3)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$debit_acct_id3.'#txref" title="Call over '.ucwords(strtolower($debit_account_desc3)).'">'.ucwords(strtolower($debit_account_desc3)).'</a></br>';
									}
								}
								
								
								//Debit Accounts 4
								$debit_account4 = $post["debit_account_jrn4"];
								
								$dquery4 = "SELECT * ";
								$dquery4 .= "FROM {$prefix}accounts ";
								$dquery4 .= "WHERE acct_id = '$debit_account4'";
								$dresult4 = mysqli_query($dbcon, $dquery4);
								$dacct_ledger4 = mysqli_fetch_array($dresult4, MYSQLI_ASSOC);
								
								$debit_acct_id4 = $dacct_ledger4["acct_id"];
								$debit_account_desc4 = $dacct_ledger4["acct_desc"];
								
								if ($debit_account4 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($debit_account_desc4)).'">'.ucwords(strtolower($debit_account_desc4)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$debit_acct_id4.'#txref" title="Call over '.ucwords(strtolower($debit_account_desc4)).'">'.ucwords(strtolower($debit_account_desc4)).'</a></br>';
									}
								}
								
								
								//Debit Accounts 5
								$debit_account5 = $post["debit_account_jrn5"];
								
								$dquery5 = "SELECT * ";
								$dquery5 .= "FROM {$prefix}accounts ";
								$dquery5 .= "WHERE acct_id = '$debit_account5'";
								$dresult5 = mysqli_query($dbcon, $dquery5);
								$dacct_ledger5 = mysqli_fetch_array($dresult5, MYSQLI_ASSOC);
								
								$debit_acct_id5 = $dacct_ledger5["acct_id"];
								$debit_account_desc5 = $dacct_ledger5["acct_desc"];
								
								if ($debit_account5 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($debit_account_desc5)).'">'.ucwords(strtolower($debit_account_desc5)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$debit_acct_id5.'#txref" title="Call over '.ucwords(strtolower($debit_account_desc5)).'">'.ucwords(strtolower($debit_account_desc5)).'</a></br>';
									}
								}
								
								
								//Debit Accounts 6
								$debit_account6 = $post["debit_account_jrn6"];
								
								$dquery6 = "SELECT * ";
								$dquery6 .= "FROM {$prefix}accounts ";
								$dquery6 .= "WHERE acct_id = '$debit_account6'";
								$dresult6 = mysqli_query($dbcon, $dquery6);
								$dacct_ledger6 = mysqli_fetch_array($dresult6, MYSQLI_ASSOC);
								
								$debit_acct_id6 = $dacct_ledger6["acct_id"];
								$debit_account_desc6 = $dacct_ledger6["acct_desc"];
								
								if ($debit_account6 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($debit_account_desc6)).'">'.ucwords(strtolower($debit_account_desc6)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$debit_acct_id6.'#txref" title="Call over '.ucwords(strtolower($debit_account_desc6)).'">'.ucwords(strtolower($debit_account_desc6)).'</a></br>';
									}
								}
								
								
								//Debit Accounts 7
								$debit_account7 = $post["debit_account_jrn7"];
								
								$dquery7 = "SELECT * ";
								$dquery7 .= "FROM {$prefix}accounts ";
								$dquery7 .= "WHERE acct_id = '$debit_account7'";
								$dresult7 = mysqli_query($dbcon, $dquery7);
								$dacct_ledger7 = mysqli_fetch_array($dresult7, MYSQLI_ASSOC);
								
								$debit_acct_id7 = $dacct_ledger7["acct_id"];
								$debit_account_desc7 = $dacct_ledger7["acct_desc"];
								
								if ($debit_account7 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($debit_account_desc7)).'">'.ucwords(strtolower($debit_account_desc7)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$debit_acct_id7.'#txref" title="Call over '.ucwords(strtolower($debit_account_desc7)).'">'.ucwords(strtolower($debit_account_desc7)).'</a></br>';
									}
								}
							?>
						</th>
						
						<th colspan="2">
						<?php
							if ($post["debit_amount_jrn1"] != "") {
								echo "&#8358 " . number_format((int)$post["debit_amount_jrn1"], 2) .'</br>';
							}else {
								echo "&#8358 " . number_format((int)$post["amount_paid"], 2) .'</br>';
							}
							
							if ($debit_account2 != "") {
								echo "&#8358 " . number_format((int)$post["debit_amount_jrn2"], 2) .'</br>';
							}
							if ($debit_account3 != "") {
								echo "&#8358 " . number_format((int)$post["debit_amount_jrn3"], 2) .'</br>';
							}
							if ($debit_account4 != "") {
								echo "&#8358 " . number_format((int)$post["debit_amount_jrn4"], 2) .'</br>';
							}
							if ($debit_account5 != "") {
								echo "&#8358 " . number_format((int)$post["debit_amount_jrn5"], 2) .'</br>';
							}
							if ($debit_account6 != "") {
								echo "&#8358 " . number_format((int)$post["debit_amount_jrn6"], 2) .'</br>';
							}
							if ($debit_account7 != "") {
								echo "&#8358 " . number_format((int)$post["debit_amount_jrn7"], 2) .'</br>';
							}
						?>
						
						</th>
					</tr>
					
					
					
					<tr class="info">
						<td class="text-right danger"><strong>Credit Account:</strong></td>
						<th colspan="2">
							<?php
								//Credit Accounts 1
								$credit_account1 = $post["credit_account"];
								
								$cquery1 = "SELECT * ";
								$cquery1 .= "FROM {$prefix}accounts ";
								$cquery1 .= "WHERE acct_id = '$credit_account1'";
								$cresult1 = mysqli_query($dbcon, $cquery1);
								$cacct_ledger1 = mysqli_fetch_array($cresult1, MYSQLI_ASSOC);
								
								$credit_acct_id1 = $cacct_ledger1["acct_id"];
								$credit_account_desc1 = $cacct_ledger1["acct_desc"];
								
								if ($staff['department']=="Leasing") {
									echo '<a href="#" title="Call over '.ucwords(strtolower($credit_account_desc1)).'">'.ucwords(strtolower($credit_account_desc1)).'</a></br>';
								} else {
									echo '<a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$credit_acct_id1.'#txref" title="Call over '.ucwords(strtolower($credit_account_desc1)).'">'.ucwords(strtolower($credit_account_desc1)).'</a></br>';
								}
								
								
								//Credit Accounts 2
								$credit_account2 = $post["credit_account_jrn2"];
								
								$cquery2 = "SELECT * ";
								$cquery2 .= "FROM {$prefix}accounts ";
								$cquery2 .= "WHERE acct_id = '$credit_account2'";
								$cresult2 = mysqli_query($dbcon, $cquery2);
								$cacct_ledger2 = mysqli_fetch_array($cresult2, MYSQLI_ASSOC);
								
								$credit_acct_id2 = $cacct_ledger2["acct_id"];
								$credit_account_desc2 = $cacct_ledger2["acct_desc"];
								
								if ($credit_account2 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($credit_account_desc2)).'">'.ucwords(strtolower($credit_account_desc2)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$credit_acct_id2.'#txref" title="Call over '.ucwords(strtolower($credit_account_desc2)).'">'.ucwords(strtolower($credit_account_desc2)).'</a></br>';
									}
								}
								
								//Credit Accounts 3
								$credit_account3 = $post["credit_account_jrn3"];
								
								$cquery3 = "SELECT * ";
								$cquery3 .= "FROM {$prefix}accounts ";
								$cquery3 .= "WHERE acct_id = '$credit_account3'";
								$cresult3 = mysqli_query($dbcon, $cquery3);
								$cacct_ledger3 = mysqli_fetch_array($cresult3, MYSQLI_ASSOC);
								
								$credit_acct_id3 = $cacct_ledger3["acct_id"];
								$credit_account_desc3 = $cacct_ledger3["acct_desc"];
								
								if ($credit_account3 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($credit_account_desc3)).'">'.ucwords(strtolower($credit_account_desc3)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$credit_acct_id3.'#txref" title="Call over '.ucwords(strtolower($credit_account_desc3)).'">'.ucwords(strtolower($credit_account_desc3)).'</a></br>';
									}
								}
								
								
								//Credit Accounts 4
								$credit_account4 = $post["credit_account_jrn4"];
								
								$cquery4 = "SELECT * ";
								$cquery4 .= "FROM {$prefix}accounts ";
								$cquery4 .= "WHERE acct_id = '$credit_account4'";
								$cresult4 = mysqli_query($dbcon, $cquery4);
								$cacct_ledger4 = mysqli_fetch_array($cresult4, MYSQLI_ASSOC);
								
								$credit_acct_id4 = $cacct_ledger4["acct_id"];
								$credit_account_desc4 = $cacct_ledger4["acct_desc"];
								
								if ($credit_account4 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($credit_account_desc4)).'">'.ucwords(strtolower($credit_account_desc4)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$credit_acct_id4.'#txref" title="Call over '.ucwords(strtolower($credit_account_desc4)).'">'.ucwords(strtolower($credit_account_desc4)).'</a></br>';
									}
								}
								
								
								//Credit Accounts 5
								$credit_account5 = $post["credit_account_jrn5"];
								
								$cquery5 = "SELECT * ";
								$cquery5 .= "FROM {$prefix}accounts ";
								$cquery5 .= "WHERE acct_id = '$credit_account5'";
								$cresult5 = mysqli_query($dbcon, $cquery5);
								$cacct_ledger5 = mysqli_fetch_array($cresult5, MYSQLI_ASSOC);
								
								$credit_acct_id5 = $cacct_ledger5["acct_id"];
								$credit_account_desc5 = $cacct_ledger5["acct_desc"];
								
								if ($credit_account5 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($credit_account_desc5)).'">'.ucwords(strtolower($credit_account_desc5)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$credit_acct_id5.'#txref" title="Call over '.ucwords(strtolower($credit_account_desc5)).'">'.ucwords(strtolower($credit_account_desc5)).'</a></br>';
									}
								}
								
								
								//Credit Accounts 6
								$credit_account6 = $post["credit_account_jrn6"];
								
								$cquery6 = "SELECT * ";
								$cquery6 .= "FROM {$prefix}accounts ";
								$cquery6 .= "WHERE acct_id = '$credit_account6'";
								$cresult6 = mysqli_query($dbcon, $cquery6);
								$cacct_ledger6 = mysqli_fetch_array($cresult6, MYSQLI_ASSOC);
								
								$credit_acct_id6 = $cacct_ledger6["acct_id"];
								$credit_account_desc6 = $cacct_ledger6["acct_desc"];
								
								if ($credit_account6 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($credit_account_desc6)).'">'.ucwords(strtolower($credit_account_desc6)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$credit_acct_id6.'#txref" title="Call over '.ucwords(strtolower($credit_account_desc6)).'">'.ucwords(strtolower($credit_account_desc6)).'</a></br>';
									}
								}	
								
								
								//Credit Accounts 7
								$credit_account7 = $post["credit_account_jrn7"];
								
								$cquery7 = "SELECT * ";
								$cquery7 .= "FROM {$prefix}accounts ";
								$cquery7 .= "WHERE acct_id = '$credit_account7'";
								$cresult7 = mysqli_query($dbcon, $cquery7);
								$cacct_ledger7 = mysqli_fetch_array($cresult7, MYSQLI_ASSOC);
								
								$credit_acct_id7 = $cacct_ledger7["acct_id"];
								$credit_account_desc7 = $cacct_ledger7["acct_desc"];
								
								if ($credit_account7 != "") {
									if ($staff['department']=="Leasing") {
										echo ' <a href="#" title="Call over '.ucwords(strtolower($credit_account_desc7)).'">'.ucwords(strtolower($credit_account_desc7)).'</a></br>';
									} else {
										echo ' <a href="ledgers'.$suffix.'.php?ref='.$txref.'&acct_id='.$credit_acct_id7.'#txref" title="Call over '.ucwords(strtolower($credit_account_desc7)).'">'.ucwords(strtolower($credit_account_desc7)).'</a></br>';
									}
								}
							?>
						</th>
						
						<th colspan="2">
						<?php
							if ($post["credit_amount_jrn1"] != "") {
								echo "&#8358 " . number_format((int)$post["credit_amount_jrn1"], 2) .'</br>';
							}else {
								echo "&#8358 " . number_format((int)$post["amount_paid"], 2) .'</br>';
							}
							if ($credit_account2 != "") {
								echo "&#8358 " . number_format((int)$post["credit_amount_jrn2"], 2) .'</br>';
							}
							if ($credit_account3 != "") {
								echo "&#8358 " . number_format((int)$post["credit_amount_jrn3"], 2) .'</br>';
							}
							if ($credit_account4 != "") {
								echo "&#8358 " . number_format((int)$post["credit_amount_jrn4"], 2) .'</br>';
							}
							if ($credit_account5 != "") {
								echo "&#8358 " . number_format((int)$post["credit_amount_jrn5"], 2) .'</br>';
							}
							if ($credit_account6 != "") {
								echo "&#8358 " . number_format((int)$post["credit_amount_jrn6"], 2) .'</br>';
							}
							if ($credit_account7 != "") {
								echo "&#8358 " . number_format((int)$post["credit_amount_jrn7"], 2) .'</br>';
							}
						?>
						
						</th>
					</tr>
					
				</table>
			</div>
			
			<div class="col-md-6">
				<div class="row">
					<table class="table table-bordered">
						<thead>
							<th class="success" colspan="5">Transaction Trail</th>
						</thead>
						
						<tr>
							<td class="text-right">This transaction was REMITTED by:</td>
							<th colspan="4"><span style="color: red";><?php echo $post["remitting_staff"]; ?></span></th>
						</tr>
						
						<tr>
							<td class="text-right">Posted by:</td>
							<th colspan="4"><?php echo ucwords(strtolower($post["posting_officer_name"])); ?> on <span style="color: red";><?php echo date($post["posting_time"]); ?></span></th>
						</tr>
						
						<tr class="danger">
							<td class="text-right">Post Approval Status:</td>
							<th colspan="4"><?php echo $post['leasing_post_status']; ?></th>
						</tr>
						
						<tr class="danger">
							<td class="text-right">Post Approval Time:</td>
							<th colspan="4"><?php echo $post['leasing_post_approval_time']; ?></th>
						</tr>
						
						<tr class="danger">
							<td class="text-right">Payment Approved by:</td>
							<th colspan="4"><?php echo $post['leasing_post_approving_officer_name']; ?></th>
						</tr>
						
						<tr class="warning">
							<td class="text-right">FC Approval Status:</td>
							<th colspan="4"><?php echo $post['approval_status']; ?></th>
						</tr>
						
						<tr class="warning">
							<td class="text-right">FC Approval Time:</td>
							<th colspan="4"><?php echo $post['approval_time']; ?></th>
						</tr>
						<tr class="warning">
							<td class="text-right">Approving FC Name:</td>
							<th colspan="4"><?php echo $post['approving_acct_officer_name']; ?></th>
						</tr>
						<tr class="success">
							<td class="text-right">Audit Verification Status:</td>
							<th colspan="4"><?php echo $post['verification_status']; ?></th>
						</tr>
						
						<tr class="success">
							<td class="text-right">Payment Verified by:</td>
							<th colspan="4"><?php echo $post['verifying_auditor_name']; ?></th>
						</tr>
						
						<tr class="success">
							<td class="text-right">Audit Verification Time:</td>
							<th colspan="4"><?php echo $post['verification_time']; ?></th>
						</tr>
					</table>
				</div>
				
				
				<?php
					if($post['flag_status'] == "" && ($menu["level"] == "fc" || $menu["level"] == "Head, Audit & Inspection")) {
				?>
					<div class="row">
						<form  method="post" id="form" enctype="multipart/form-data" class="form-horizontal" action="flagged_record_processing<?php echo $suffix; ?>.php" autocomplete="off">
							<fieldset>	
								<h4><strong>Exception Report</strong></h4><hr/>
										
								<div class="form-group form-group-sm">
									<label for="comment1" class="control-label col-md-3">Comment:</label>
									<div class="col-md-8 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-signal"></i></span>
											<textarea name="comment" id="comment" class="form-control input-sm" placeholder="Add comment" value="" rows="7" maxlength="250" data-minLength="100" style="font-size: 12pt" required></textarea>
										</div>
										<div class="col-md-7">Min count: <span id="mincount">100</span></div>
										<div class="col-md-5">Max character left: <span id="maxcount">250</span></div>
									</div>
								</div>
								
								<input type="hidden" name="txref" value="<?php echo $txref; ?>">
								<input type="hidden" name="posting_officer_id" class="form-control" placeholder=" " value="<?php echo $staff['user_id']; ?>"  />
								<input type="hidden" name="posting_officer_name" class="form-control" placeholder=" " value="<?php echo $staff['full_name']; ?>" />
								<input type="hidden" name="verification_status" class="form-control" placeholder=" " value="<?php echo $post['verification_status']; ?>" />
								<input type="hidden" name="posting_officer_level" class="form-control" placeholder=" " value="<?php echo $menu['level']; ?>" />
								
								<div class="text-center">
									<button name="btn_comment" id="btn_comment" type="submit" class="btn btn-sm btn-danger" disabled>Flag Record</button>
								</div>
							</fieldset>
						</form>
					</div>
				<?php
					} 
				?>
			</div>
		</div>
		<div class="well"></div>
	</div>
</body>
<script type="text/javascript">
$(document).ready(function() {
 $("#comment").on("keyup", function(){
     var minLength = $(this).attr("data-minlength");
     var currentLength = $(this).val().length;
     var remaining = parseInt(minLength) - parseInt(currentLength)
     $("#mincount").text((remaining < 0 ? 0: remaining));
     $("#maxcount").text((250 - $(this).val().length));
	 
     if (parseInt(currentLength) < parseInt(minLength))
     {
         $("#btn_comment").prop("disabled", true);
     } else{
         $("#btn_comment").prop("disabled", false);
     }
 });
});


function cdetails_id(id)
{
	if(confirm)
	{
		window.location.href='../leasing/customer_details.php?cdetails_id='+id;
	}
}
</script>
</html>
