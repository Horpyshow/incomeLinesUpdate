<?php
include 'include/session.php';

include 'payment_processing_logged.php';

if(isset($_GET["repost_id"])) {
	$repost_id = $_GET["repost_id"];
	
	$uquery = "SELECT * ";
	$uquery .= "FROM unposted_transactions ";
	$uquery .= "WHERE id = '$repost_id' ";
	$uresult_set = @mysqli_query($dbcon, $uquery); 
	$ushop = mysqli_fetch_array($uresult_set, MYSQLI_ASSOC);


	$current_date = $ushop['date_of_payment'];
	$receipt_no = $ushop['receipt_no'];
	$amt_remitted = $ushop['amount_paid'];
	$transaction_descr = $ushop['transaction_desc'];
	$amount_paid = $ushop['amount_paid'];
	$income_line = $ushop['income_line'];
	
	echo $income_line;
	
	//$income_line = $income_line;

//General Income Line	
	if ($income_line == "general") {
		$income_line_desc = "General Income Lines";

//Car Sticker	
	} elseif ($income_line == "car_sticker") {
		$alias = "car_sticker";
		$income_line_desc = "Car Sticker";
		
//Car Loading	
	} elseif ($income_line == "car_loading") {
		$alias = "car_loading";
		$income_line_desc = "Car Loading";
		$transaction_descr = $income_line_desc;
?>
<script type="text/javascript">
	function loadCalc() {

	var no_of_ticket  = isNaN(parseFloat(document.getElementById('no_of_tickets').value))? 0 : parseFloat(document.getElementById('no_of_tickets').value);

	var total_ticket_amount = (no_of_ticket * 1000);
	document.getElementById('amount_paid').value = total_ticket_amount;
	}
</script>
	
<?php	
//Hawkers	
	} elseif ($income_line == "hawkers") {
		$alias = "hawkers";
		$income_line_desc = "Hawker Tickets";
		$transaction_descr = $income_line_desc;
?>
<script type="text/javascript">
	function loadCalc() {

	var no_of_ticket  = isNaN(parseFloat(document.getElementById('no_of_tickets').value))? 0 : parseFloat(document.getElementById('no_of_tickets').value);

	var total_ticket_amount = (no_of_ticket * 200);
	document.getElementById('amount_paid').value = total_ticket_amount;
	}
</script>

<?php	
//Car Park	
	} elseif ($income_line == "car_park") {
		$alias = "carpark";
		$income_line_desc = "Car Park Tickets";
		$transaction_descr = $income_line_desc;
?>
<script type="text/javascript">
	function loadCalc() {
		
	var ticket_category  = isNaN(parseFloat(document.getElementById('ticket_category').value))? 0 : parseFloat(document.getElementById('ticket_category').value);

	var no_of_ticket  = isNaN(parseFloat(document.getElementById('no_of_tickets').value))? 0 : parseFloat(document.getElementById('no_of_tickets').value);

	var total_ticket_amount = (no_of_ticket * ticket_category);
	document.getElementById('amount_paid').value = total_ticket_amount;
	}
</script>
	
	
<?php
//WheelBarrow	
	} elseif ($income_line == "wheelbarrow") {
		$alias = "wheelbarrow";
		$income_line_desc = "WheelBarrow Tickets";
		$transaction_descr = $income_line_desc;
?>
<script type="text/javascript">
	function loadCalc() {

	var no_of_ticket  = isNaN(parseFloat(document.getElementById('no_of_tickets').value))? 0 : parseFloat(document.getElementById('no_of_tickets').value);

	var total_ticket_amount = (no_of_ticket * 300);
	document.getElementById('amount_paid').value = total_ticket_amount;
	}
</script>

<?php
//Daily Trade 
	} elseif ($income_line == "daily_trade") {
		$alias = "daily_trade";
		$income_line_desc = "Daily Trade Tickets";
		$transaction_descr = $income_line_desc;
?>
<script type="text/javascript">
	function loadCalc() {
	
	var ticket_category  = isNaN(parseFloat(document.getElementById('ticket_category').value))? 0 : parseFloat(document.getElementById('ticket_category').value);
	
	var no_of_ticket  = isNaN(parseFloat(document.getElementById('no_of_tickets').value))? 0 : parseFloat(document.getElementById('no_of_tickets').value);

	var total_ticket_amount = (no_of_ticket * ticket_category);
	document.getElementById('amount_paid').value = total_ticket_amount;
	}
</script>


<?php
//Abattoir
	} elseif ($income_line == "abattoir") {
		$alias = "abattoir";
		$income_line_desc = "Abattoir";
		$transaction_descr = $income_line_desc;
?>
<script type="text/javascript">
	function loadCalc() {
		
	var category = document.getElementById('category').value;
	var unit_cost = 0;
	
	if (category == "Cows Killed"){
		unit_cost = 1800;
	} else if (category == "Cows Takeaway"){
		unit_cost = 1000;
	} else if (category == "Goats Killed"){
		unit_cost = 400;
	} else if (category == "Goats Takeaway"){
		unit_cost = 100;
	} else if (category == "Pots of Pomo"){
		unit_cost = 250;
	} else {
		unit_cost = 0;
	}

	var quantity  = isNaN(parseFloat(document.getElementById('quantity').value))? 0 : parseFloat(document.getElementById('quantity').value);

	var total_ticket_amount = (quantity * unit_cost);
	document.getElementById('amount_paid').value = total_ticket_amount;
	}
</script>

<?php
//Loading/Offloading
	} elseif ($income_line == "loading") {
		$income_line_desc = "Loading/Offloading";
?>
<script type="text/javascript">
	function loadCalc() {
		
	var category = document.getElementById('category').value;
	
	if (document.getElementById('no_of_days').value == 0 || document.getElementById('no_of_days').value == "") {
		no_of_days = 1;
		document.getElementById('no_of_days').value = 1;
	} else {
		var no_of_days = isNaN(parseFloat(document.getElementById('no_of_days').value))? 0 : parseFloat(document.getElementById('no_of_days').value);
	}
	
	var unit_cost = 0;
	
	
	if (category == "Goods (Offloading) - N7000"){
		unit_cost = 7000;
	} else if (category == "Goods (Offloading) - N15000"){
		unit_cost = 15000;
	} else if (category == "Goods (Offloading) - N20000"){
		unit_cost = 20000;
	} else if (category == "Goods (Offloading) - N30000"){
		unit_cost = 30000;
	} else if (category == "Goods (Loading) - N20000"){
		unit_cost = 20000;
	} else if (category == "Fruits (Offloading) - N2500"){
		unit_cost = 2500;
	} else if (category == "Fruits (Offloading) - N3500"){
		unit_cost = 3500;
	} else if (category == "Fruits (Offloading) - N7000"){
		unit_cost = 7000;
	} else if (category == "Fruits (Offloading) - N15000"){
		unit_cost = 15000;
	} else if (category == "Apple Bus (Loading) - N3500"){
		unit_cost = 3500;
	} else if (category == "Cargo Truck (Loading) - N7000"){
		unit_cost = 7000;
	} else if (category == "Cargo Truck 1 (Offloading) - N15000"){
		unit_cost = 15000;
	} else if (category == "Cargo Truck 2 (Offloading) - N20000"){
		unit_cost = 20000;
	} else if (category == "OK Truck (Offloading) - N20000"){
		unit_cost = 20000;
	} else if (category == "20 feet container - (Loading) - N15000"){
		unit_cost = 15000;
	} else if (category == "20 feet container - (Offloading) - N15000"){
		unit_cost = 15000;
	} else if (category == "40 feet container - (Offloading) N30000"){
		unit_cost = 30000;
	} else if (category == "40 feet container - (Abassa Offloading - Weekend) - N30000"){
		unit_cost = 30000;
	} else if (category == "40 feet container - (Shoe Offloading - Weekend) - N60000"){
		unit_cost = 60000;
	} else if (category == "40 feet container - (Apple Offloading) - N30000"){
		unit_cost = 30000;
	} else if (category == "40 feet container - (Apple Offloading - Sunday) - N60000"){
		unit_cost = 60000;
	} else if (category == "40 feet container - (Ok, Curtain Offloading) - N30000"){
		unit_cost = 30000;
	} else if (category == "LT Buses (Offloading) - N4000"){
		unit_cost = 4000;
	} else if (category == "LT Buses (Offloading - Sunday) - N7000"){
		unit_cost = 7000;
	} else if (category == "LT Buses (Loading) - N4000"){
		unit_cost = 4000;
	} else if (category == "Mini LT Buses (Loading) - N3000"){
		unit_cost = 3000;
	} else if (category == "Mini LT Buses (Offloading) - N3000"){
		unit_cost = 3000;
	} else if (category == "LT Buses Army Staff (Loading) - N1000"){
		unit_cost = 1000;
	} else if (category == "LT Buses Army Staff (Loading) - N2000"){
		unit_cost = 2000;
	} else if (category == "OK Mini Van (Loading) - N6000"){
		unit_cost = 6000;
	} else if (category == "OK Mini Van (Offloading) - N6000"){
		unit_cost = 6000;
	} else if (category == "Mini Van (Loading) - N5000"){
		unit_cost = 5000;
	} else if (category == "Mini Van (Offloading) - N5000"){
		unit_cost = 5000;
	} else if (category == "Sienna Buses (Loading) - N2000"){
		unit_cost = 2000;
	} else if (category == "Oil Tanker (Offloading) - N30000"){
		unit_cost = 30000;
	} else {
		unit_cost = 0;
	}
	
	
	document.getElementById('amount_paid').value = (unit_cost * no_of_days);
	
	
	}
</script>
<?php
//Toilet Collection 
	} elseif ($income_line == "toilet_collection") {
		$alias = "toilet_collection";
		$income_line_desc = "Toilet Collection";
		$transaction_descr = $income_line_desc;


//Overnight Parking
	} elseif ($income_line == "overnight_parking") {
		$alias = "overnight_parking";
		$income_line_desc = "Overnight Parking";
?>
<script type="text/javascript">
function loadCalc() {
	type_category = document.getElementById("type_category").value;
	
	var unit_cost = 0;
	
	if (document.getElementById('no_of_nights').value == 0 || document.getElementById('no_of_nights').value == "") {
		no_of_nights = 1;
		document.getElementById('no_of_nights').value = 1;
	} else {
		var no_of_nights = isNaN(parseFloat(document.getElementById('no_of_nights').value))? 0 : parseFloat(document.getElementById('no_of_nights').value);
	}
	
	if (type_category == "Vehicle"){
		document.getElementById("vehicle_div").style.display="block";
		document.getElementById("plate_no_div").style.display="block";
		document.getElementById("trans_desc_div").style.display="none";
		document.getElementById("artisan_div").style.display="none";
		
		var vehicle_category = document.getElementById('vehicle_category').value;
		if (vehicle_category == "Overnight Parking - 40 feet - N5000"){
			unit_cost = 5000;
		} else if (vehicle_category == "Overnight Parking - OK Trucks - N2000"){
			unit_cost = 2000;
		} else if (vehicle_category == "Overnight Parking - LT Buses - N1500"){
			unit_cost = 1500;
		} else if (vehicle_category == "Overnight Parking - Sienna - N1000"){
			unit_cost = 1000;
		} else if (vehicle_category == "Overnight Parking - Cars - N1000"){
			unit_cost = 1000;
		} else {
			unit_cost = 0;
		}
		
	} else if (type_category == "Forklift Operator"){
		document.getElementById("vehicle_div").style.display="none";
		document.getElementById("plate_no_div").style.display="none";
		document.getElementById("artisan_div").style.display="none";
		document.getElementById("trans_desc_div").style.display="block";
		
		unit_cost = 500;
		
	} else if (type_category == "Artisan"){
		document.getElementById("vehicle_div").style.display="none";
		document.getElementById("plate_no_div").style.display="none";
		document.getElementById("artisan_div").style.display="block";
		document.getElementById("trans_desc_div").style.display="block";
		
		var artisan_category = document.getElementById('artisan_category').value;
		if (artisan_category == "Welder/Welding Equipment"){
			unit_cost = 500;
		} else if (artisan_category == "Carpenter"){
			unit_cost = 500;
		} else if (artisan_category == "Bricklayer"){
			unit_cost = 500;
		} else if (artisan_category == "Others"){
			unit_cost = 500;
		} else {
			unit_cost = 0;
		} 
	} else {
		document.getElementById("vehicle_div").style.display="none";
		document.getElementById("plate_no_div").style.display="none";
		document.getElementById("artisan_div").style.display="none";
		document.getElementById("trans_desc_div").style.display="none";
	}

	document.getElementById('amount_paid').value = (unit_cost * no_of_nights);
	
	var amount = document.getElementById('amount_paid').value;
	if(amount == 0){
		document.getElementById("btn_post_overnight_parking").disabled = true;
	}
}
</script>
<?php		

	} else {
		
	}
}

?>
<style>
#hidden_div {
    display: none;
}
</style>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Welcome - <?php echo $staff['full_name']; ?> | Wealth Creation ERP</title>
		<meta http-equiv="Content-Type" name="description" content="Wealth Creation ERP Management System; text/html; charset=utf-8" />
		<meta name="author" content="Woobs Resources Ltd">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/formValidation.min.css">
		
		<link rel="stylesheet" type="text/css" href="../../css/datepicker.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/datepicker3.min.css">
		<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../../js/formValidation.min.js"></script>
		<script type="text/javascript" src="../../js/framework/bootstrap.min.js"></script>
		<script type='text/javascript' src="../../js/bootstrap-datepicker.min.js"></script>
		<script type='text/javascript' src="../../js/fv.js"></script>
		<script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>
		
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrapValidator.min.css">
		<!--<script type="text/javascript" src="../../js/jquery.min.js"></script>-->
		<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
		
		<script src="../../js/sub_menu.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">
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

<?php
	if(isset($_SESSION['staff']) ) {
		$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['staff'];
	}
	if(isset($_SESSION['admin']) ) {
		$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['admin'];
	}
	$staffresult = mysqli_query($dbcon, $staffquery);
	$session_staff = mysqli_fetch_array($staffresult, MYSQLI_ASSOC);
	$session_id = $session_staff['user_id'];
	
	$dcount_query = "SELECT COUNT(id) FROM account_general_transaction_new ";
	$dcount_query .= "WHERE posting_officer_id = '$session_id' ";
	$dcount_query .= "AND (approval_status = 'Declined' OR verification_status = 'Declined')";
	$result = mysqli_query($dbcon, $dcount_query);
	$acct_office_post = mysqli_fetch_array($result);
	$no_of_declined_post = $acct_office_post[0];
?>

<div class="well"></div>
<div class="container-fluid">
	<div class="col-md-2">
				
	</div>

	<div class="col-md-8">
		<div class="row">
				<div class="container-fluid">
					<div class="col-md-12">
						<h4>
						<?php
							if(isset($_SESSION['staff']) ) {
								$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['staff'];
							}
							if(isset($_SESSION['admin']) ) {
								$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['admin'];
							}
								$staffresult = mysqli_query($dbcon, $staffquery);
								$session_staff = mysqli_fetch_array($staffresult, MYSQLI_ASSOC);
								$session_id = $session_staff['user_id'];
								$session_department = $session_staff['department'];
								
								if($session_department == "Wealth Creation") {
									$ca_query = "SELECT posting_officer_id, date_of_payment, payment_category, SUM(amount_paid) as amount_posted ";
									$ca_query .= "FROM account_general_transaction_new ";
									$ca_query .= "WHERE (posting_officer_id = '$session_id' AND payment_category='Other Collection' AND date_of_payment='$current_date') ";
									$ca_sum = @mysqli_query($dbcon,$ca_query);
									$ca_total = @mysqli_fetch_array($ca_sum, MYSQLI_ASSOC);
									
									$amount_posted = $ca_total['amount_posted'];
								
									$rm_query = "SELECT *, SUM(amount_paid) as amount_remitted ";
									$rm_query .= "FROM cash_remittance ";
									$rm_query .= "WHERE (remitting_officer_id = '$session_id' AND category='Other Collection' AND date='$current_date') ";
									$rm_sum = @mysqli_query($dbcon,$rm_query);
									$rm_total = @mysqli_fetch_array($rm_sum, MYSQLI_ASSOC);
									
									$date = $rm_total["date"];
									$remit_id = $rm_total["remit_id"];
									$category = $rm_total["category"];
									
									$amount_remitted = $rm_total['amount_remitted'];
									
									$unposted = $amount_remitted - $amount_posted;

									
									echo 'Remitted: <span style="color:#ec7063; font-weight:bold;">&#8358 '.$amount_remitted.'</span> | Posted: <span style="color:#ec7063; font-weight:bold;">&#8358 '.$amount_posted.'</span> | Unposted: <span style="color:#ec7063; font-weight:bold;">&#8358 '.$unposted.'</span> ';
								}
								
						?>
						</h4>
						
						<h4>
						<?php							
							$till_query = "SELECT SUM(amount_paid) as amount_posted ";
							$till_query .= "FROM account_general_transaction_new ";
							$till_query .= "WHERE posting_officer_id = '$session_id' ";
							$till_query .= "AND leasing_post_status = 'Pending'";
							$sum = @mysqli_query($dbcon,$till_query);
							$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);
							
							$till = $total['amount_posted'];
														
							$declined_query = "SELECT SUM(amount_paid) as amount_posted ";
							$declined_query .= "FROM account_general_transaction_new ";
							$declined_query .= "WHERE posting_officer_id = '$session_id' ";
							$declined_query .= "AND leasing_post_status = 'Declined'";
							$dsum = @mysqli_query($dbcon,$declined_query);
							$dtotal = @mysqli_fetch_array($dsum, MYSQLI_ASSOC);
							
							$till_declined = $dtotal['amount_posted'];
							
							$total_till = ($till + $till_declined);
							$total_till = number_format((float)$total_till, 2);
							
							if ($menu["department"] == "Accounts") {
								echo '<a href="view_trans.php"><span style="color:#ec7063; font-weight:bold;">&#8358 '.$total_till.'</span> Till Balance | ';
							} else {
								echo '<a href="../leasing/view_trans.php"><span style="color:#ec7063; font-weight:bold;">&#8358 '.$total_till.'</span> Till Balance | ';
							}
						?>
						
						<?php
							$no_of_declined_post = 0;
							
							$ldcount_query = "SELECT COUNT(id) FROM account_general_transaction_new ";
							$ldcount_query .= "WHERE posting_officer_id = '$session_id' ";
							$ldcount_query .= "AND leasing_post_status = 'Declined'";
							$lresult = mysqli_query($dbcon, $ldcount_query);
							$leasing_post = mysqli_fetch_array($lresult);
							$no_of_declined_post_leasing = $leasing_post[0];
							
							$dcount_query = "SELECT COUNT(id) FROM account_general_transaction_new ";
							$dcount_query .= "WHERE posting_officer_id = '$session_id' ";
							$dcount_query .= "AND approval_status = 'Declined'";
							$result = mysqli_query($dbcon, $dcount_query);
							$account_post = mysqli_fetch_array($result);
							$no_of_declined_post_account = $account_post[0];
							
							if($menu["department"] == "Wealth Creation") {
								$no_of_declined_post = $no_of_declined_post_leasing;
							} else {
								$no_of_declined_post = $no_of_declined_post_account;
							}
							
							$icount_query = "SELECT COUNT(id) FROM account_general_transaction_new ";
							$icount_query .= "WHERE posting_officer_id = '$session_id' ";
							$icount_query .= "AND it_status != '' ";
							$iresult = @mysqli_query($dbcon, $icount_query);
							$it_status_post = @mysqli_fetch_array($iresult);
							$it_status = $it_status_post[0];
							
							$pcount_query = "SELECT COUNT(id) FROM account_general_transaction_new ";
							$pcount_query .= "WHERE posting_officer_id = '$session_id' ";
							
							if($menu["department"] == "Wealth Creation") {
								$pcount_query .= "AND leasing_post_status = 'Pending'";
							} else {
								$pcount_query .= "AND approval_status = 'Pending'";
							}
							
							$result = mysqli_query($dbcon, $pcount_query);
							$leasing_post = mysqli_fetch_array($result);
							$no_of_pending_post = $leasing_post[0];
							
							if($menu["department"] == "Accounts") {
								echo '<span style="color:#ec7063; font-weight:bold;">'.$no_of_declined_post.'</span> Declined | <span style="color:#ec7063; font-weight:bold;">'.$no_of_pending_post.'</span> Pending</a> | <a href="view_trans.php"><span style="color:#ec7063; font-weight:bold;">'.$it_status.'</span> Wrong entries</a>';
							} else {
								echo '<span style="color:#ec7063; font-weight:bold;">'.$no_of_declined_post.'</span> Declined | <span style="color:#ec7063; font-weight:bold;">'.$no_of_pending_post.'</span> Pending</a> | <a href="../leasing/view_trans.php"><span style="color:#ec7063; font-weight:bold;">'.$it_status.'</span> Wrong entries</a>';
							}
						?>
						</h4>
					</div>
				</div>
		</div>
	
		<div class="row">
			<div class="col-md-11">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#t1" data-toggle="tab">
					<?php
					//Remittance Portal
						//if(isset($_GET["income_line"])) {
							echo '<strong>'.@$income_line_desc.'</strong>';
						//}
					?>
					</a></li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade in active" id="t1">
						<h3>
							<?php 
								

								echo '<h5>';

								$gquery = "SELECT * FROM accounts ";
								$gquery .= "WHERE page_visibility = 'General' ";
								$gquery .= "ORDER BY acct_desc ASC";
								$gaccount_set = @mysqli_query($dbcon, $gquery); 
								
								while ($gaccount = mysqli_fetch_array($gaccount_set, MYSQLI_ASSOC)) {
									if(@$income_line == "general") {
										echo ucwords(strtolower($gaccount['acct_desc'])).' | '; 
									}
								} 
								echo '</h5>';
							?>
						</h3>
						<?php
							//if(isset($_GET["income_line"])) {
								if ($income_line == "general") {
									include 'payments_logged/general_form_inc.php';
								} elseif ($income_line == "car_sticker") {
									include 'payments_logged/car_sticker_inc.php';
								} elseif ($income_line == "abattoir") {
									include 'payments_logged/abattoir_form_inc.php';
								} elseif ($income_line == "car_loading") {
									include 'payments_logged/car_loading_form_inc.php';
								} elseif ($income_line == "car_park") {
									include 'payments_logged/car_park_form_inc.php';
								} elseif ($income_line == "hawkers") {
									include 'payments_logged/hawkers_form_inc.php';
								} elseif ($income_line == "wheelbarrow") {
									include 'payments_logged/wheelbarrow_form_inc.php';
								} elseif ($income_line == "daily_trade") {
									include 'payments_logged/daily_trade_form_inc.php';
								} elseif ($income_line == "toilet_collection") {
									include 'payments_logged/toilet_collection_form_inc.php';
								} elseif ($income_line == "loading") {
									include 'payments_logged/loading_form_inc.php';
								} elseif ($income_line == "overnight_parking") {
									include 'payments_logged/overnight_parking_form_inc.php';
								} else {
									echo "<h3><strong>Please select and income line</strong></h3>";
								}
							//} else {
							//	echo "<h3><strong>Please select an income line</strong></h3>";
							//}
						?>

						<?php  ?>
						
						<span><?php include ('controllers/error_messages_inc.php'); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div><!-- col-md-8 -->
	
	
	<div class="col-md-2">
			
	</div>
</div> <!--container-fluid -->
</body>
</html>
<script type="text/javascript">
$(document).ready(function() {
	document.getElementById("trans_desc_div").style.display="none";
	document.getElementById("vehicle_div").style.display="none";
	document.getElementById("plate_no_div").style.display="none";	
	document.getElementById("artisan_div").style.display="none";
});
</script>