<?php
include 'include/session.php';
$current_date = date('Y-m-d');
$error = false;


if ( isset($_POST['btn_post']) ) {
	print_r($_POST);
	exit;
	
	$remitting_officer_id = $_POST['officer'];
	$category = $_POST['category'];
	
	$squery = "SELECT * FROM staffs WHERE user_id = '$remitting_officer_id'";
	$sresult = @mysqli_query($dbcon, $squery);
	$so_officer = @mysqli_fetch_array($sresult, MYSQLI_ASSOC);

	$remitting_officer_name = $so_officer['full_name'];
	
	
	$suquery = "SELECT date, category, remitting_officer_id ";
	$suquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$remitting_officer_id' AND date='$current_date' AND category = '$category') ";
	$suresult = mysqli_query($dbcon, $suquery);
	$rusql = mysqli_fetch_array($suresult, MYSQLI_ASSOC); 
	$count = mysqli_num_rows($suresult);
	
	if ($category == "Rent"){
		if($count == 0) {
			$remit_id = time().mt_rand(5101,5200);
		} else {
			$srquery = "SELECT remit_id, date, remitting_officer_id, category ";
			$srquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$remitting_officer_id' AND date='$current_date' AND category = '$category') ";
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
			$srquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$remitting_officer_id' AND date='$current_date' AND category = '$category') ";
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
			$srquery .= "FROM cash_remittance WHERE (remitting_officer_id = '$remitting_officer_id' AND date='$current_date' AND category = '$category') ";
			$srquery .= "LIMIT 1 ";
			$srresult = mysqli_query($dbcon, $srquery);
			$rrsql = mysqli_fetch_array($srresult, MYSQLI_ASSOC); 
			
			$remit_id = $rrsql["remit_id"];;
		} 
	}else {
		$error = true;
	}

	$date_of_payment = $_POST['date_of_payment'];
	list($tid,$tim,$tiy) = explode("/",$date_of_payment);
	$date_of_payment = "$tiy-$tim-$tid";
		
	$amount = $_POST['amount_paid'];
	$amount_paid = preg_replace('/[,]/', '', $amount);
	
	$no_of_receipts = $_POST['no_of_receipts'];
	

	$posting_officer_id = $_POST['posting_officer_id'];
	$posting_officer_name = $_POST['posting_officer_name'];
	
	date_default_timezone_set('Africa/Lagos');
	$now = date('Y-m-d H:i:s');
	
	
	$query = "INSERT INTO cash_remittance (id,remit_id,date,amount_paid,no_of_receipts,category,remitting_officer_id,remitting_officer_name,posting_officer_id,posting_officer_name,remitting_time) VALUES('','$remit_id','$date_of_payment','$amount_paid','$no_of_receipts','$category','$remitting_officer_id','$remitting_officer_name','$posting_officer_id','$posting_officer_name','$now')";
	$post_payment = @mysqli_query($dbcon, $query);
	
	if ($post_payment)
		{
			?>
			<script type="text/javascript">
			alert('Cash remittance successful!');
			window.location.href='account_dashboard.php';
			</script>
			<?php
		}
		else
		{
			?>
			<script type="text/javascript">
			alert('Error occured while posting');
			window.location.href='account_dashboard.php';
			</script>
			<?php
		}
}
?>