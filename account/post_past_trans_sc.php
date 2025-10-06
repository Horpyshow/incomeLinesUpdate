<?php
include 'include/session.php';

$project = "Service Charge";
$prefix = "";
$suffix = "_arena";
$sub_prefix = "sc_";
$sub_suffix = "_sc";
$page_title = "Post {$project} Transactions - Wealth Creation ERP";

include ('controllers/post_past_trans_form_submit_inc_sc.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $project; ?></title>
		<meta http-equiv="Content-Type" name="description" content="Woobs Resources ERP Management System; text/html; charset=utf-8" />
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../../css/datepicker.min.css">
		<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../../js/framework/bootstrap.min.js"></script>
		<script type='text/javascript' src="../../js/bootstrap-datepicker.min.js"></script>
		<script type='text/javascript' src="../../js/fv3.js"></script>
		<script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../../css/bootstrapValidator.min.css">
		<script type="text/javascript" src="../../js/jquery.min.js"></script>
		<script type="text/javascript" src="../../js/bootstrap.min.js"></script>

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
					
					$("#shop_id").val(customerData.id);
					$("#shopid").val(customerData.id);
					$("#shopno").val(customerData.shop_no);
					$("#customername").val(customerData.customer_name);
					$("#shopsize").val(customerData.shop_size);
					$("#location").val(customerData.location);
					$("#facility_type").val(customerData.facility_type);
					
					//Lookup variables for WRL or ARENA
					$("#monthly_expected").val(customerData.expected_service_charge);
					$("#yearly_expected").val(customerData.expected_service_charge_yearly);
					
					$('#shop').html(customerData.shop_no);
					
					//Assign shop ID to the modal for Payment History
					$("#historybtn").val(customerData.id);
				}   	
			} 
		});
 	});
});

function refresh_box() 
{
    $("#postcount").load('get_posting_count<?php echo $sub_suffix; ?>.php');
    setTimeout(refresh_box, 30000);
}

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
							<strong>Past <?php echo $project; ?> Payment</strong>
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
			<?php include ('controllers/post_past_trans_form_inc_sc.php'); ?>
		</div>
	</div>			
</div>

<!-- Javascript function -->
<?php include ('controllers/leasing_bulk_post_function_inc_sc.php'); ?>


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
							url: "get_collection_analysis_arena.php",
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