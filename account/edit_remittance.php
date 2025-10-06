<?php
include 'include/session.php';
include ('include/functions.php');

$page_title = "Remittance Correction";
$unposted = 0;
$adjustment = 0;
$error = false;


if (isset($_POST['btn_post']) && isset($_GET['edit_id'])) {
	$edit_id = $_GET['edit_id'];
	
	$remitting_officer_id = $_POST['officer'];
	$squery = "SELECT * FROM staffs WHERE user_id = '$remitting_officer_id'";
	$sresult = @mysqli_query($dbcon, $squery);
	$so_officer = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);
	$remitting_officer_name = $so_officer['full_name'];
	
	
	$fetched_remitting_officer_id = $_POST['fetched_officer'];
	$fquery = "SELECT * FROM staffs WHERE user_id = '$fetched_remitting_officer_id'";
	$fresult = @mysqli_query($dbcon, $fquery);
	$fo_officer = @mysqli_fetch_array($fresult, MYSQLI_ASSOC);
	$fetched_remitting_officer_name = $fo_officer['full_name'];
	
	
	if ($remitting_officer_id != $fetched_remitting_officer_id) {
		$query_officer_id = $remitting_officer_id;
		$query_officer_name = $remitting_officer_name;
	} else {
		$query_officer_id = $fetched_remitting_officer_id;
		$query_officer_name = $fetched_remitting_officer_name;
	}
	
	$category = $_POST['category'];
	$fetched_category = $_POST['fetched_category'];
	
	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
	$current_date = $date_of_payment;
	
	
	//Determine the remit ID to use based on the category of payment
	$suquery = "SELECT date, category, remitting_officer_id ";
	$suquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$query_officer_id' AND date='$current_date' AND category = '$category') ";
	$suresult = mysqli_query($dbcon, $suquery);
	$rusql = mysqli_fetch_array($suresult, MYSQLI_ASSOC); 
	$count = mysqli_num_rows($suresult);
	
	if ($category == "Rent"){
		if($count == 0) {
			$remit_id = time().mt_rand(5101,5200);
		} else {
			$srquery = "SELECT remit_id, date, remitting_officer_id, category ";
			$srquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$query_officer_id' AND date='$current_date' AND category = '$category') ";
			$srquery .= "LIMIT 1 ";
			$srresult = mysqli_query($dbcon, $srquery);
			$rrsql = mysqli_fetch_array($srresult, MYSQLI_ASSOC); 
			
			$remit_id = $rrsql["remit_id"];;
		} 
	}elseif ($category == "Service Charge"){
		if($count == 0) {
			$remit_id = time().mt_rand(5000,5100);
		} else {
			$srquery = "SELECT remit_id, date, remitting_officer_id, category ";
			$srquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$query_officer_id' AND date='$current_date' AND category = '$category') ";
			$srquery .= "LIMIT 1 ";
			$srresult = mysqli_query($dbcon, $srquery);
			$rrsql = mysqli_fetch_array($srresult, MYSQLI_ASSOC); 
			
			$remit_id = $rrsql["remit_id"];;
		} 
	}elseif ($category == "Other Collection"){
		if($count == 0) {
			$remit_id = time().mt_rand(5000,5300);
		} else {
			$srquery = "SELECT remit_id, date, remitting_officer_id, category ";
			$srquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$query_officer_id' AND date='$current_date' AND category = '$category') ";
			$srquery .= "LIMIT 1 ";
			$srresult = mysqli_query($dbcon, $srquery);
			$rrsql = mysqli_fetch_array($srresult, MYSQLI_ASSOC); 
			
			$remit_id = $rrsql["remit_id"];;
		} 
	}else {
		$error = true;
	}
	
	
	//Compute balances and determine changes to the existing record before committing
	$oquery = "SELECT *, SUM(amount_paid) as amount_remitted ";
	$oquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$fetched_remitting_officer_id' AND category='$fetched_category' AND date='$current_date') ";
	$oresult = mysqli_query($dbcon, $oquery);
	$osum = mysqli_fetch_array($oresult, MYSQLI_ASSOC); 
	
	$total_amount_remitted = $osum['amount_remitted'];

		
	$co_query = "SELECT posting_officer_id, date_of_payment, payment_category, SUM(amount_paid) as amount_posted ";
	$co_query .= "FROM account_general_transaction_new ";
	$co_query .= "WHERE (posting_officer_id = '$fetched_remitting_officer_id' AND payment_category='$fetched_category' AND date_of_payment='$current_date') ";
	$co_sum = mysqli_query($dbcon,$co_query);
	$co_total = mysqli_fetch_array($co_sum, MYSQLI_ASSOC);
	
	$total_amount_posted = $co_total['amount_posted'];


	$uquery = "SELECT *, SUM(amount_paid) as amount_logged ";
	$uquery .= "FROM unposted_transactions WHERE (posting_officer_id = '$fetched_remitting_officer_id' AND category='$fetched_category' AND date_of_payment='$current_date' AND payment_status='unposted') ";
	$uresult = mysqli_query($dbcon, $uquery);
	$usum = mysqli_fetch_array($uresult, MYSQLI_ASSOC); 
	
	$total_amount_logged = $usum['amount_logged'];
	
	$total_amount_posted_logged = ($total_amount_posted + $total_amount_logged);
	
	
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$fetched_amount = $_POST['fetched_amount'];
	$unposted = $total_amount_remitted - ($total_amount_posted + $total_amount_logged);
	
	$adjustment = $fetched_amount - $amount_paid;
	
	if ($adjustment > 0 && ($remitting_officer_id == $fetched_remitting_officer_id)) {
		if ($unposted < $adjustment) {
			$error = true;
			$adjustment_Error = "<h4><strong>ATTENTION:</strong> A reduction of: <strong>&#8358 {$adjustment}</strong> for $remitting_officer_name remittance CANNOT be accomodated. Actual <strong>UNPOSTED /UNLOGGED</strong> amount left is <strong>&#8358 {$unposted}</strong>! This will make the total posting MORE THAN the actual remittance</h4>";
		} 
	} 
	
	if ($remitting_officer_id != $fetched_remitting_officer_id ) {
		if ($unposted < $amount_paid) {
			$error = true;
			$adjBalance_Error = "<h4><strong>ATTENTION:</strong> A reduction of:<strong> &#8358 {$amount_paid}</strong> for $fetched_remitting_officer_name CANNOT be accomodated. Actual <strong>UNPOSTED /UNLOGGED</strong> amount left is <strong>&#8358 {$unposted}</strong>. This is LESS THAN <strong>&#8358 {$amount_paid}</strong>  being moved to $remitting_officer_name!</h4>";
		}
	}

	$no_of_receipts = $_POST['no_of_receipts'];
	

	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	
	if(!$error) {
		$query = "UPDATE cash_remittance SET remit_id='$remit_id', date='$date_of_payment', amount_paid='$amount_paid', no_of_receipts='$no_of_receipts', category='$category', remitting_officer_id='$query_officer_id', remitting_officer_name='$query_officer_name', posting_officer_id='$posting_officer_id', posting_officer_name='$posting_officer_name', remitting_time='$now' WHERE id='$edit_id'";
		$post_payment = @mysqli_query($dbcon, $query);
		
		if ($post_payment)
		{
			?>
			<script type="text/javascript">
			alert('Cash remittance UPDATED successful!');
			window.location.href='account_dashboard.php';
			</script>
			<?php
		}
		else
		{
			?>
			<script type="text/javascript">
			alert('Error occured while posting');
			window.location.href='edit_remittance.php?edit_id=<?php echo $edit_id; ?>';
			</script>
			<?php
		}
	} 
}



if (isset($_GET['edit_id']) ) {
	$edit_id = $_GET["edit_id"];
	
	$suquery = "SELECT * FROM cash_remittance WHERE id = '$edit_id'";
	$suresult = mysqli_query($dbcon, $suquery);

	$qremit = mysqli_fetch_array($suresult, MYSQLI_ASSOC);
	$remit_id = $qremit['remit_id'];
	$remitting_officer_id = $qremit['remitting_officer_id'];
	
	$amount_remitted = $qremit['amount_paid'];

	$category = $qremit['category'];
	
	$remittance_date = $qremit['date'];
	$date_of_payment = $remittance_date;
	
	$no_of_receipts = $qremit['no_of_receipts'];
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $page_title; ?></title>
		<meta name="description" content="Woobs Resources ERP Management System">
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/formValidation.min.css">

		<link rel="stylesheet" type="text/css" href="../../css/datepicker.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/datepicker3.min.css">
		<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../../js/formValidation.min.js"></script>
		<script type="text/javascript" src="../../js/framework/bootstrap.min.js"></script>
		<script type='text/javascript' src="../../js/bootstrap-datepicker.min.js"></script>
		<script type='text/javascript' src="../../js/fv.js"></script>
		<!--
		Date Options didnt work, form validation worked, menu worked
		<script type="text/javascript" src="../../js/bootstrap.js"></script> -->
		<script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>

		<link rel="stylesheet" type="text/css" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrapValidator.min.css">
		<script type="text/javascript" src="../../js/jquery.min.js"></script>
		<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
		<script src="../../js/sub_menu.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">
		
		<link rel="stylesheet" href="../../scripts/swal2/sweetalert2.min.css" type="text/css" />
		<script src="../../scripts/swal2/sweetalert2.min.js"></script>
		
		
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
	<div class="col-sm-12">
			
		

		<!--Row begins here -->
		<div class="row">
			<div class="col-md-4"></div>
			
			<div class="col-md-4">
			<h3><strong><?php echo $page_title; ?></strong> <?php echo '<a href="account_dashboard.php" class="btn btn-success btn-sm">Remittance Dashboard</a>'; ?></h3>
			<?php
			//if (($staff['department']=="Accounts" || $menu['level']=="ce") && $staff['user_id']!="177"){
			if ($staff['department']=="Accounts" || $menu['level']=="ce"){		
			?>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title"><span class="glyphicon glyphicon-bookmark"></span> Remittance Correction Menu</h4>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<?php
								if (isset($adjustment_Error) ) {
								echo '
								<div class="form-group form-group-sm">
									<div class="alert alert-danger fade in">
										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$adjustment_Error.'
									</div>
								</div>';
							   }
							   
							   if (isset($adjBalance_Error) ) {
								echo '
								<div class="form-group form-group-sm">
									<div class="alert alert-success fade in">
										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$adjBalance_Error.'
									</div>
								</div>';
							   }
							?>
							<div class="col-md-12">
								<form  method="post" id="form" class="form-horizontal" action="edit_remittance.php?edit_id=<?php echo $edit_id; ?>" autocomplete="off">
								<input type="hidden" name="posting_officer_id" id="officer_id" class="form-control" value="<?php echo $staff['user_id']; ?>" maxlength="50" />
								<input type="hidden" name="posting_officer_name" class="form-control" value="<?php echo $staff['full_name']; ?>" maxlength="50" />
								<input type="hidden" name="fetched_amount" class="form-control" value="<?php echo $qremit['amount_paid']; ?>" maxlength="50" />
								<input type="hidden" name="fetched_officer" class="form-control" value="<?php echo $qremit['remitting_officer_id']; ?>" maxlength="50" />
								<input type="hidden" name="fetched_category" class="form-control" value="<?php echo $qremit['category']; ?>" maxlength="50" />

								<div class="form-group form-group-sm">
									<label class="col-sm-4 control-label">Date of Payment:</label>
									<div class="col-sm-6">
										<div class="input-group input-append">
											<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date of Payment" value="<?php 
											if (isset($_GET['edit_id'])) {
												list($tiy,$tim,$tid) = explode("-", @$date_of_payment); 
												@$date_of_payment = "$tid/$tim/$tiy";
												echo @$date_of_payment;
											} ?>" readonly />
											<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										</div>
									</div>
								</div>
								
								<div class="form-group form-group-sm"> 
								  <label for="leasing_officer_in_charge" class="col-sm-4 control-label">Officer:</label>
									<div class="col-sm-7 selectContainer">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
											<select name="officer" class="form-control selectpicker" id="officer" required>
											  <option value=" ">Select...</option>
												<?php
													$lo_query = "SELECT * FROM staffs WHERE department='Wealth Creation' AND (level = 'leasing officer' AND inputter_status = 'inputter')";
													$lo_leasing_set = @mysqli_query($dbcon, $lo_query); 
													
													while ($lo_leasing_officer = mysqli_fetch_array($lo_leasing_set, MYSQLI_ASSOC)) {
														$officer_id = $lo_leasing_officer["user_id"];
														$officer_name = $lo_leasing_officer["full_name"];
														
														echo '<option '; if ($remitting_officer_id == $officer_id) echo 'selected'; echo ' value="'.$officer_id.'">'.$officer_name.'</option>'; 
													} 
												?>

											</select>
										</div>
									</div>
								</div>
								
								
								<!-- Text input-->			
								<div class="form-group form-group-sm">
									<label for="amount_paid" class="control-label col-md-4">Amount Remitted:</label>
									<div class="col-sm-6 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon">&#8358;</span>
											<input type="password" name="amount_paid" id="amount_paid" class="form-control input-sm" placeholder="Amount Paid" value="<?php if (isset($_GET['edit_id'])) echo @$amount_remitted; ?>" maxlength="25" required />
										</div>
									</div>
								</div>
								
								
								<!-- Text input-->			
								<div class="form-group form-group-sm">
									<label for="confirm_amount_paid" class="control-label col-md-4">Confirm Amount Remitted:</label>
									<div class="col-sm-6 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon">&#8358;</span>
											<input type="text" name="confirm_amount_paid" id="confirm_amount_paid" class="form-control input-sm" placeholder="Confirm Amount Paid" value="<?php if (isset($_GET['edit_id'])) echo @$amount_remitted; ?>" maxlength="25" required />
										</div>
										<span id="message"></span>
									</div>
								</div>
								
								
								<!-- Text input-->			
								<div class="form-group form-group-sm">
									<label for="no_of_receipts" class="control-label col-md-4">No of Receipts:</label>
									<div class="col-sm-4 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon"></span>
											<input type="text" name="no_of_receipts" id="no_of_receipts" class="form-control input-sm" value="<?php if (isset($_GET['edit_id'])) echo @$no_of_receipts; ?>" maxlength="3" required />
										</div>
									</div>
								</div>
								
								
								<div class="form-group form-group-sm"> 
									<label for="category" class="col-sm-4 control-label">Category:</label>
									<div class="col-sm-8 selectContainer">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
											<select name="category" class="form-control selectpicker" id="category" required>
												<option <?php if ($category=="") echo 'selected'; ?>  value="">Select...</option>
												<option <?php if ($category=="Rent") echo 'selected'; ?> value="Rent">Rent Collection</option>
												<option <?php if ($category=="Service Charge") echo 'selected'; ?> value="Service Charge">Service Charge Collection</option>
												<option <?php if ($category=="Other Collection") echo 'selected'; ?> value="Other Collection">Other Collection</option>
											</select>
										</div>
									</div>
								</div>
								
								
								<div class="text-center">
									<div>
										<button type="submit" id="btn_post" name="btn_post" class="btn btn-sm btn-danger">Update Remittance <span class="glyphicon glyphicon-send"></span></button>
										<button type="reset" name="btn_clear" class="btn btn-sm btn-primary">Clear</button>
									</div>
								</div>
							</form>

							</div>
						</div>
					</div>
				</div>
				<?php
				}
				?>
			</div>
			
			
			
		</div>
		<!-- Row ends here -->
		
		
		
	
	</div>
</div>
</body>

</html>
<script type="text/javascript">
$('#amount_paid, #confirm_amount_paid').on('keyup', function () {
  if ($('#amount_paid').val() == $('#confirm_amount_paid').val()) {
    $('#message').html('Confirmed!').css('color', 'green');
  } else 
    $('#message').html('Amount mismatch!').css('color', 'red');
});
</script>