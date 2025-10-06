<?php
include 'include/session.php';

$project = "Rent";
$prefix = "";
$suffix = "";
$sub_prefix = "";
$sub_suffix = "";
$page_title = "Post {$project} Transactions - Wealth Creation ERP";

include ('controllers/post_trans_form_submit_inc.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $project; ?></title>
		<meta http-equiv="Content-Type" name="description" content="Wealth Creation Management System; text/html; charset=utf-8" />
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
		
		<link rel="stylesheet" type="text/css" href="../../css/datepicker.min.css">
		<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../../js/framework/bootstrap.min.js"></script>
		<script type='text/javascript' src="../../js/bootstrap-datepicker.min.js"></script>
		<script type='text/javascript' src="../../js/fv2.js"></script>
		
		<script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>
		
		<link rel="stylesheet" type="text/css" href="../../css/bootstrapValidator.min.css">
		<script type="text/javascript" src="../../js/jquery.min.js"></script>
		<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
		
		<script src="../../js/sub_menu.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">

<style type="text/css">
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>

		
<script type="text/javascript">
$(document).ready(function(){
	$("#form").ready(function(){
		$("#row_month2").hide();
		$("#row_month3").hide();
		$("#row_month4").hide();
		$("#row_month5").hide();
		$("#row_month6").hide();
		$("#row_month7").hide();
		$("#row_month8").hide();
		$("#row_month9").hide();
		$("#row_month10").hide();
		$("#row_month11").hide();
		$("#row_month12").hide();
	});
	
	
	$("#shop_id").change(function() {    
		var id = $(this).find(":selected").val();
		var dataString = 'shopid='+ id;    
		$.ajax({
			url: 'get_customer_info_r.php',
			dataType: "json",
			data: dataString,  
			cache: false,
			success: function(customerData) {
			   if(customerData) {
					$("#form")[0].reset();
					var lease_start_date = customerData.lease_start_date;
					var symd = lease_start_date.split('-');
					var sy = symd[0];
					var sm = symd[1];
					var sd = symd[2];
					
					var new_start_date = sd + '/' + sm + '/' + sy;
					$('#start_date').attr('placeholder',new_start_date);
					
					
					var lease_end_date = customerData.lease_end_date;
					var eymd = lease_end_date.split('-');
					var ey = eymd[0];
					var em = eymd[1];
					var ed = eymd[2];
					
					var new_end_date = ed + '/' + em + '/' + ey;
					$('#end_date').attr('placeholder',new_end_date);
					
					
					var fields = lease_start_date.split('-');

					var year = fields[0];
					var month = fields[1];
					var day = fields[2];
					
					if(month == 01){
						month = "January";
					} 
					else if (month == 02){
						month = "February";
					}
					else if(month == 03){
						month = "March";
					}
					else if(month == 04){
						month = "April";
					}
					else if(month == 05){
						month = "May";
					}
					else if(month == 06){
						month = "June";
					}
					else if(month == 07){
						month = "July";
					}
					else if(month == 08){
						month = "August";
					}
					else if(month == 09){
						month = "September";
					}
					else if(month == 10){
						month = "October";
					}
					else if(month == 11){
						month = "November";
					}
					else if(month == 12){
						month = "December";
					} else {
						month = "";
					}
					
					var i = 1;
					
					var ptenure4 = month + ' ' + (parseInt(year) - (4*i));
					var ptenure3 = month + ' ' + (parseInt(year) - (3*i));
					var ptenure2 = month + ' ' + (parseInt(year) - (2*i));
					var ptenure1 = month + ' ' + (parseInt(year) - i);
					var ctenure = month + ' ' + year;
					var ntenure1 = month + ' ' + (parseInt(year) + 1);
					var ntenure2 = month + ' ' + (parseInt(year) + 2);
					var ntenure3 = month + ' ' + (parseInt(year) + 3);
					var ntenure4 = month + ' ' + (parseInt(year) + 4);
					var ntenure5 = month + ' ' + (parseInt(year) + 5);
					var ntenure6 = month + ' ' + (parseInt(year) + 6);
					var ntenure7 = month + ' ' + (parseInt(year) + 7);
					var ntenure8 = month + ' ' + (parseInt(year) + 8);
					var ntenure9 = month + ' ' + (parseInt(year) + 9);
					var ntenure10 = month + ' ' + (parseInt(year) + 10);
					
					
					var lduration = customerData.lease_tenure;
					var duration_char = lduration.split(' ');
					var duration = duration_char[0];
					
					if (duration == 1){
						var option1 = [ctenure, ptenure1, ptenure2, ptenure3];
						var options = ["", ptenure3, ptenure2, ptenure1, ctenure];
					} 
					else if(duration == 2){
						var option1 = [ctenure, ntenure1, ptenure1, ptenure2, ptenure3];
						var options = ["", ptenure1, ptenure2, ptenure3, ctenure, ntenure1];
					} 
					else if(duration == 3){
						var option1 = [ctenure, ntenure1, ntenure2, ptenure1, ptenure2, ptenure3];
						var options = ["", ptenure1, ptenure2, ptenure3, ctenure, ntenure1, ntenure2];
					}
					else if(duration == 4){
						var option1 = [ctenure, ntenure1, ntenure2, ntenure3, ptenure1, ptenure2, ptenure3];
						var options = ["", ptenure1, ptenure2, ptenure3, ctenure, ntenure1, ntenure2, ntenure3];
					}
					else if(duration == 5){
						var option1 = [ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ptenure1, ptenure2, ptenure3];
						var options = ["", ptenure1, ptenure2, ptenure3, ctenure, ntenure1, ntenure2, ntenure3, ntenure4];
					}
					else if(duration == 6){
						var option1 = [ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ntenure5, ptenure1, ptenure2, ptenure3];
						var options = ["", ptenure1, ptenure2, ptenure3, ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ntenure5];
					}
					else if(duration == 7){
						var option1 = [ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ntenure5, ntenure6, ptenure1, ptenure2, ptenure3];
						var options = ["", ptenure1, ptenure2, ptenure3, ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ntenure5, ntenure6];
					}
					else if(duration == 8){
						var option1 = [ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ntenure5, ntenure6, ntenure7, ptenure1, ptenure2, ptenure3];
						var options = ["", ptenure1, ptenure2, ptenure3, ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ntenure5, ntenure6, ntenure7];
					}
					else if(duration == 9){
						var option1 = [ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ntenure5, ntenure6, ntenure7, ntenure8, ptenure1, ptenure2, ptenure3];
						var options = ["", ptenure1, ptenure2, ptenure3, ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ntenure5, ntenure6, ntenure7, ntenure8];
					}
					else if(duration == 10){
						var option1 = [ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ntenure5, ntenure6, ntenure7, ntenure8, ntenure9, ptenure1, ptenure2, ptenure3];
						var options = ["", ptenure1, ptenure2, ptenure3, ctenure, ntenure1, ntenure2, ntenure3, ntenure4, ntenure5, ntenure6, ntenure7, ntenure8, ntenure9];
					}
					else {}
					
					//Populate Option year1
					$('#year1').empty();
					$.each(option1, function(i, p) {
						$('#year1').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year2
					$('#year2').empty();
					$.each(options, function(i, p) {
						$('#year2').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year3
					$('#year3').empty();
					$.each(options, function(i, p) {
						$('#year3').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year4
					$('#year4').empty();
					$.each(options, function(i, p) {
						$('#year4').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year5
					$('#year5').empty();
					$.each(options, function(i, p) {
						$('#year5').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year6
					$('#year6').empty();
					$.each(options, function(i, p) {
						$('#year6').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year7
					$('#year7').empty();
					$.each(options, function(i, p) {
						$('#year7').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year8
					$('#year8').empty();
					$.each(options, function(i, p) {
						$('#year8').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year9
					$('#year9').empty();
					$.each(options, function(i, p) {
						$('#year9').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year10
					$('#year10').empty();
					$.each(options, function(i, p) {
						$('#year10').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year11
					$('#year11').empty();
					$.each(options, function(i, p) {
						$('#year11').append($('<option></option>').val(p).html(p));
					});
					
					//Populate Option year12
					$('#year12').empty();
					$.each(options, function(i, p) {
						$('#year12').append($('<option></option>').val(p).html(p));
					});
					
					$("#shop_id").val(customerData.id);
					$("#shopid").val(customerData.id);
					$("#shopno").val(customerData.shop_no);
					$("#customername").val(customerData.customer_name);
					$("#location").val(customerData.location);
					$("#shopsize").val(customerData.shop_size);
					$("#facility_type").val(customerData.facility_type);
					
					//Lookup variables for WRL or ARENA
					$("#monthly_expected").val(customerData.expected_rent);
					$("#yearly_expected").val(customerData.total_expected_rent);
					
					$('#shop').html(customerData.shop_no);
					
					//Assign shop ID to the modal for Payment History
					$("#historybtn").val(customerData.id);
					
					var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];;
					var date = new Date();
	
					document.getElementById('payment_month').value = months[date.getMonth()] + ' ' + date.getFullYear();
				}   	
			} 
		});
 	});
});

$(document).ready(function(){
   refresh_box();
   
});
</script>
</head>
<body>
<div class="well"></div>
	<?php include ('include/staff_navbar.php');
				
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

<div class="container-fluid">
	<div class="col-md-12">
		<div class="row">
			<div class="page-header">
				<div class="container-fluid">
					<div class="col-md-7">
						<h2>
							<strong><?php echo $project; ?> Payment</strong>
							<a href="post_new_lease.php" class="btn btn-sm btn-success">New Lease (Rent)</a>
							<a href="post_past_trans.php" class="btn btn-sm btn-danger">Past Postings (Rent)</a>
						</h2>
					</div>
				
					<div class="col-md-5">
					 
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
							
							$till_query = "SELECT SUM(amount_paid) as amount_posted ";
							$till_query .= "FROM {$prefix}account_general_transaction_new ";
							$till_query .= "WHERE posting_officer_id = '$session_id' ";
							$till_query .= "AND approval_status = 'Pending'";
							$sum = @mysqli_query($dbcon,$till_query);
							$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);
							
							$till = $total['amount_posted'];
														
							$declined_query = "SELECT SUM(amount_paid) as amount_posted ";
							$declined_query .= "FROM {$prefix}account_general_transaction_new ";
							$declined_query .= "WHERE posting_officer_id = '$session_id' ";
							$declined_query .= "AND approval_status = 'Declined'";
							$dsum = @mysqli_query($dbcon,$declined_query);
							$dtotal = @mysqli_fetch_array($dsum, MYSQLI_ASSOC);
							
							$till_declined = $dtotal['amount_posted'];
							
							$total_till = ($till + $till_declined);
							$total_till = number_format((float)$total_till, 2);
							echo '<h4><a href="acct_view_trans.php?staff_id='.$session_id.'"><span style="color:#ec7063; font-weight:bold;">&#8358 '.$total_till.'</span> Till Balance | ';
						?>
						
						<?php
							$dcount_query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
							$dcount_query .= "WHERE posting_officer_id = '$session_id' ";
							$dcount_query .= "AND (approval_status = 'Declined' OR verification_status = 'Declined')";
							$result = mysqli_query($dbcon, $dcount_query);
							$leasing_post = mysqli_fetch_array($result);
							$no_of_declined_post = $leasing_post[0];
							
							$ac_dcount_query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
							$ac_dcount_query .= "WHERE leasing_post_approving_officer_id = '$session_id' ";
							$ac_dcount_query .= "AND leasing_post_status = 'Approved' ";
							$ac_dcount_query .= "AND (approval_status = 'Declined' OR verification_status = 'Declined')";
							$ac_result = mysqli_query($dbcon, $ac_dcount_query);
							$account_post = mysqli_fetch_array($ac_result);
							$no_of_declined_acct_post = $account_post[0];
							
							$pcount_query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
							$pcount_query .= "WHERE posting_officer_id = '$session_id' ";
							$pcount_query .= "AND approval_status = 'Pending'";
							$result = mysqli_query($dbcon, $pcount_query);
							$leasing_post = mysqli_fetch_array($result);
							$no_of_pending_post = $leasing_post[0];
							
							echo '<span style="color:#ec7063; font-weight:bold;">'.$no_of_declined_post.'</span> Declined | <span style="color:#ec7063; font-weight:bold;">'.$no_of_pending_post.'</span> Pending</a></h4>';
							echo '<h6><strong><span style="color:#ec7063;" class="glyphicon glyphicon-arrow-up"></span> Click up to view details</strong></h6>';
						?>
						
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<?php include ('controllers/post_trans_form_inc.php'); ?>
		</div>
	</div>			
</div>

<!-- Javascript function -->
<?php include ('controllers/leasing_bulk_post_function_inc.php'); ?>


<!-- Shop Report Modal begins here -->
<div class="modal fade" id="modal2" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" style="background: lightblue;">
				<button class="close" data-dismiss="modal">&times;</button>
				<h4>
					Shop Payment History
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<table class="table table-bordered">
						<tr>
							<th colspan="5">	
								<div class="form-group form-group-sm">
									<label class="control-label col-md-4">Shop No:</label>
									<div class="col-md-5 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-signal"></i></span>
											<input type="text" id="col_shopno" class="form-control input-sm" value="" />
										</div>
									</div>
								</div>
							
								<div class="form-group form-group-sm">
									<label class="control-label col-md-4">Customer Name:</label>
									<div class="col-md-6 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
											<input type="text" id="col_customername" class="form-control input-sm" value="" />
										</div>
									</div>
								</div>
								
								<div class="form-group form-group-sm">
									<label class="control-label col-md-4">Last Payment Date (Y-m-d):</label>
									<div class="col-md-6 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
											<input type="text" id="col_date_of_payment" class="form-control input-sm" value="" />
										</div>
									</div>
								</div>
							
								<div class="form-group form-group-sm">
									<label class="control-label col-md-4">Last Payment:</label>
									<div class="col-md-6 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
											<input type="text" id="col_payment_month" class="form-control input-sm" value="" />
										</div>
									</div>
								</div>
							
								<div class="form-group form-group-sm">
									<label class="control-label col-md-4">Amount Paid:</label>
									<div class="col-sm-6 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon">&#8358;</span>
											<input type="text" id="col_amount_paid" class="form-control input-sm" value="" />
										</div>
									</div>
								</div>
							</th>
						</tr>
					</table>
				</form>
			</div>
			
			<div class="modal-footer">
				<strong><span style="color:#ec7063;">Click on Shop ID to load details <i class="glyphicon glyphicon-arrow-right"></i></span></strong> <input type="button" id="historybtn" class="btn btn-danger" value="">
				<button class="btn btn-primary" data-dismiss="modal">Close</button>
			</div>
			<script type="text/javascript">
					$('#historybtn').click(function(e) {
						e.preventDefault();
						$.ajax({
							url: "get_collection_analysis.php",
							dataType: "json",
							data: { 
								id: $(this).val(),
							},
							cache: false,
							success: function(collectionData) {
							   if(collectionData) {
									$("#col_shopno").val(collectionData.shop_no);
									$("#col_customername").val(collectionData.customer_name);
									$("#col_date_of_payment").val(collectionData.date_of_payment);
									$("#col_payment_month").val(collectionData.payment_month);
									$("#col_amount_paid").val(collectionData.amount_paid);
								}   	
							} 
						});
					});
				</script>
			
			<script type="text/javascript">
				$('#modal2').on('hidden.bs.modal', function () {
					$('.modal-body').find('table,input,textarea').val('');
				});
			</script>
		</div>
	</div>
</div>
<!-- Shop Reports Modal ends here -->

</body>
</html>