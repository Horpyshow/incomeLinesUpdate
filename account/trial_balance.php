<?php
include 'include/session.php';

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$page_title = "{$project} Trial Balance - Wealth Creation ERP";

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

//Get the account id and query the database for the associated records
if (isset($_GET['acct_id'])) {	
	$account_id = @$_GET['acct_id'];
	
	$query = "SELECT * ";
	$query .= "FROM {$prefix}account_general_transaction_new ";
	$query .= "WHERE debit_account = $account_id ";
	$query .= "OR credit_account = $account_id ";
	$query .= "AND approval_status = 'Approved' ";
	$query .= "ORDER BY date_of_payment DESC ";
	$query .= "LIMIT 1000";
	
	$post_all_set = mysqli_query($dbcon,$query);
	$post_no = mysqli_num_rows($post_all_set);
}

if (isset($_GET['d1']) && isset($_GET['d2'])){
	$post_at = "";
	$post_at_to_date = "";
	$queryCondition = "";
	
	$post_at = $_GET["d1"];
	list($fid,$fim,$fiy) = explode("/",$post_at);
	
	$post_at_todate = date('Y-m-d');
	$post_at_to_date = $_GET["d2"];
	list($tid,$tim,$tiy) = explode("/",$post_at_to_date);
	
	$post_at_todate = "$tiy-$tim-$tid";
	$date_range1 = "$fiy-$fim-$fid";
		
	$queryCondition .= "WHERE approval_status='Approved' AND date_of_payment BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";

	$sql = "SELECT * FROM {$prefix}account_general_transaction_new " . $queryCondition . " ORDER BY date_of_payment desc";
	$post_filter_set = mysqli_query($dbcon,$sql);	
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $page_title; ?></title>
		<meta name="description" content="Wealth Creation ERP Management System">
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
		
		
    <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/buttons.dataTables.min.css">
	
	<!--<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>-->
    <script type="text/javascript" src="../../js/jquery.dataTables_new.min.js"></script>
    <script type="text/javascript" src="../../js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="../../js/jszip.min.js"></script>
    <script type="text/javascript" src="../../js/pdfmake.min.js"></script>
    <script type="text/javascript" src="../../js/vfs_fonts.js"></script>
    <script type="text/javascript" src="../../js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="../../js/buttons.print.js"></script>

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

	<div class="col-sm-11">
	<div class="row">
		<div class="col-sm-12">
			<div class="page-header">
				<h2><strong><?php echo $project; ?> Trial Balance</strong></h2>
			</div>
		</div>
	</div>

		<div class="row col-sm-10">
		<div class="table-responsive">
			<table class="display" style="width:100%">
				<thead>
				<tr>
					<td colspan="7">
					<div class="row">
						<form name="frmSearch" method="post" action="trial_balance<?php echo $suffix; ?>_processing.php?d1=<?php if ( isset($_POST['btn_filter']) ) {$post_at = $_POST["search"]["post_at"]; echo $post_at;} ?>&d2=<?php if ( isset($_POST['btn_filter']) ) {$post_at_to_date = $_POST["search"]["post_at_to_date"]; echo $post_at_to_date;} ?>" autocomplete="off">
							<div class="col-sm-11">
								<div class="col-sm-3">
									<div class="form-group form-group-sm">
										<div class="date">
											<div class="input-group input-append date" id="start_date">
											<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
												<input type="text" class="form-control input-sm" placeholder="From Date" id="post_at" name="search[post_at]"  value="<?php if ( isset($_POST['btn_filter']) ) { echo $post_at;} if (isset($_GET['d1'])) {echo $post_at;}?>" />
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-1"><strong>-</strong></div>
								<div class="col-sm-3">
									<div class="form-group form-group-sm">
										<div class="date">
											<div class="input-group input-append date" id="end_date">
												<input type="text" class="form-control input-sm" placeholder="To Date" id="post_at_to_date" name="search[post_at_to_date]" style="margin-left:10px"  value="<?php if ( isset($_POST['btn_filter']) ) {echo $post_at_to_date;} if (isset($_GET['d2'])) {echo $post_at_to_date;}?>" /> 
												<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<input type="submit" class="btn btn-success btn-sm" name="btn_filter" value="Show Range" />
									<a href="trial_balance<?php echo $suffix; ?>.php" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-signal"></span> View Current Month</a>
								</div>
							</div>
						</form>
					</div>
					</td>
				</tr>
				
				<tr>
					<th colspan="7">
						<?php
							date_default_timezone_set('Africa/Lagos');
							$today = date('Y-m-d');
							
							$first_day_of_month = date('Y-m-01');
							list($fdid,$fdim,$fdiy) = explode("-",$first_day_of_month);
							$first_day_of_month = "$fdiy/$fdim/$fdid";
							
							$last_day_of_month = $today;
							list($ldid,$ldim,$ldiy) = explode("-",$last_day_of_month);
							$last_day_of_month = "$ldiy/$ldim/$ldid";
							
							if (isset($_GET['d1']) && isset($_GET['d2'])) {
								echo '<span style="font-size: 18px;"><span style="color:#ec7063;">Showing search result of entries between </span>'.$post_at.' <span style="color:#ec7063;">and</span> '.$post_at_to_date.'</span>';
							} else {
								echo '<span style="font-size: 18px;"><span style="color:#ec7063;">Currently showing entries from </span>'.$first_day_of_month.' <span style="color:#ec7063;">to</span> '.$last_day_of_month.'</span>';
							}
						?>
					</th>
				</tr>
				</thead>
			</table>
			
			<table id="trial_balance" class="display" style="width:90%">
				<thead>
					<tr>
						<th style="border-bottom:3pt solid black;" class="danger">ACCT CODE</th>
						<th style="border-bottom:3pt solid black;" class="danger">ACCOUNT DESCRIPTION</th>
						<th style="border-bottom:3pt solid black;" class="danger text-right">DEBIT AMOUNT </br>(&#8358;)</th>
						<th style="border-bottom:3pt solid black;" class="danger text-right">CREDIT AMOUNT </br>(&#8358;)</th>
					</tr>
				</thead>
				<tbody>
					
					<?php
						//Initialize the variables
						$var = 0;
						$new_debits = 0;
						$new_credits = 0;
						
						$ledger_balance = 0;
						
						$overall_debits = 0;
						$overall_credits = 0;
						$total_debit_ledger_balance = 0;
						$overall_debit_ledger_balance = 0;
						
						$total_credit_ledger_balance = 0;
						$overall_credit_ledger_balance = 0;
						
						$sqlquery = "SELECT * ";
						$sqlquery .= "FROM {$prefix}account_gl_code ";
						$sqlquery .= "ORDER BY gl_code ASC";
						$sqlaccount_query = mysqli_query($dbcon,$sqlquery);
						
						while ($account_class = mysqli_fetch_array($sqlaccount_query, MYSQLI_ASSOC)) {
						
						$gl_code = $account_class['gl_code'];	
						$acct_class_headings = $account_class['gl_code_name'];
						
						//Query accounts for all available ledgers
						$query = "SELECT * ";
						$query .= "FROM {$prefix}accounts ";
						$query .= "WHERE gl_code = '$gl_code' ";
						$query .= "AND active = 'Yes' ";
						$query .= "ORDER BY acct_code ASC";
						$account_query = mysqli_query($dbcon,$query);
						
						while ($all_account = mysqli_fetch_array($account_query, MYSQLI_ASSOC)) {
							
						$acct_id = $all_account["acct_id"];
						$acct_code = $all_account["acct_code"];
						$acct_desc = $all_account["acct_desc"];
						$ledger = $all_account["acct_table_name"];
						$balance_sheet_report = $all_account["balance_sheet_report"];
						$profit_loss_report = $all_account["profit_loss_report"];
												
						if (isset($_GET['d1']) && isset($_GET['d2'])) {		
							//Check if a date range has been selected and determine the start date of the range.
							$filtered_query_year = $_GET["d1"];
							list($fild,$film,$fily) = explode("/",$filtered_query_year);
							$filtered_query_date = "$fily-$film-$fild";
							
							//Query to sum all the credit and debit balances of the previous year
							$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
							$query .= "FROM $ledger ";
							$query .= "WHERE approval_status = 'Approved' ";
							$query .= "AND date < '$filtered_query_date'";
							$bf_sum = mysqli_query($dbcon,$query);
							$bf_total = mysqli_fetch_array($bf_sum, MYSQLI_ASSOC);

							$bf_debits = $bf_total['debits'];
							$bf_credits = $bf_total['credits'];	
							$bf_balance = ($bf_debits - $bf_credits);
							//Checks end here

							$post_at = "";
							$post_at_to_date = "";
							$queryCondition = "";

							$post_at = $_GET["d1"];
							list($fid,$fim,$fiy) = explode("/",$post_at);

							$post_at_todate = date('Y-m-d');
							$post_at_to_date = $_GET["d2"];
							list($tid,$tim,$tiy) = explode("/",$post_at_to_date);
							$post_at_todate = "$tiy-$tim-$tid";
							
							$queryCondition .= "WHERE date BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";

							$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
							$query .= "FROM $ledger ";
							$query .= "$queryCondition ";
							$query .= "AND approval_status = 'Approved'";
							$sum = mysqli_query($dbcon,$query);
							$total = mysqli_fetch_array($sum, MYSQLI_ASSOC);
							
							//Get the debit balance position
							$debits = $total['debits'];
							$credits = $total['credits'];
							
							if ($bf_balance > 0) {
								$ledger_balance = (($debits + $bf_balance) - $credits) ;
								if ($ledger_balance >= 0) {
									$debit_ledger_balance = $ledger_balance;
									$credit_ledger_balance = 0;
								} else {
									$credit_ledger_balance = $ledger_balance;
									$debit_ledger_balance = 0;
								} 								
							}else if ($bf_balance == 0) {
								$ledger_balance = (($debits + $bf_balance) - $credits) ;
								if ($ledger_balance >= 0) {
									$debit_ledger_balance = $ledger_balance;
									$credit_ledger_balance = 0;
								} else {
									$credit_ledger_balance = $ledger_balance;
									$debit_ledger_balance = 0;
								} 								
							} else if ($bf_balance < 0) {
								$ledger_balance = ((-$credits + $bf_balance) + $debits) ;
								if ($ledger_balance >= 0) {
									$debit_ledger_balance = $ledger_balance;
									$credit_ledger_balance = 0;
								} else {
									$credit_ledger_balance = $ledger_balance;
									$debit_ledger_balance = 0;
								}
							} else {
								$debit_ledger_balance = 0;
								$credit_ledger_balance = 0;
							}				
						} else {
							
							//Query to sum all the credit and debit balances of the previous year
							$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
							$query .= "FROM $ledger ";
							$query .= "WHERE approval_status = 'Approved' ";
							$query .= "AND date < DATE_FORMAT(NOW() ,'%Y-%m-01') ";
							$bf_sum = mysqli_query($dbcon,$query);
							$bf_total = mysqli_fetch_array($bf_sum, MYSQLI_ASSOC);

							$bf_debits = $bf_total['debits'];
							$bf_credits = $bf_total['credits'];	
							$bf_balance = ($bf_debits - $bf_credits);
							
							//
							$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
							$query .= "FROM $ledger ";
							$query .= "WHERE approval_status = 'Approved' ";
							$query .= "AND (YEAR(date) = YEAR(NOW()) AND MONTH(date) = MONTH(NOW())) ";
							$sum = @mysqli_query($dbcon,$query);
							$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);

							//Get the debit balance position
							$debits = $total['debits'];
							$credits = $total['credits'];
							
							if ($bf_balance > 0) {
								$ledger_balance = (($debits + $bf_balance) - $credits) ;
								if ($ledger_balance >= 0) {
									$debit_ledger_balance = $ledger_balance;
									$credit_ledger_balance = 0;
								} else {
									$credit_ledger_balance = $ledger_balance;
									$debit_ledger_balance = 0;
								} 								
							}else if ($bf_balance == 0) {
								$ledger_balance = (($debits + $bf_balance) - $credits) ;
								if ($ledger_balance >= 0) {
									$debit_ledger_balance = $ledger_balance;
									$credit_ledger_balance = 0;
								} else {
									$credit_ledger_balance = $ledger_balance;
									$debit_ledger_balance = 0;
								} 								
							} else if ($bf_balance < 0) {
								$ledger_balance = ((-$credits + $bf_balance) + $debits) ;
								if ($ledger_balance >= 0) {
									$debit_ledger_balance = $ledger_balance;
									$credit_ledger_balance = 0;
								} else {
									$credit_ledger_balance = $ledger_balance;
									$debit_ledger_balance = 0;
								}
							} else {
								$debit_ledger_balance = 0;
								$credit_ledger_balance = 0;
							}
						}
					?>
					<?php
					echo'
						<tr>
							<td>'.$acct_code.'</td>
							
							<td><a href="ledgers'.$suffix.'.php?acct_id='.$acct_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$acct_desc.'</a></td>'; 
								
							echo '	
							<td class="info text-right">';
								if ($bf_balance > 0 || $bf_balance == 0 || $bf_balance < 0) {	
									
									$total_debit_ledger_balance += $debit_ledger_balance;
									$overall_debit_ledger_balance += $debit_ledger_balance;
									$debit_ledger_balance = number_format((float)$debit_ledger_balance, 2);

									echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$debit_ledger_balance.'</a></td>';
								}
							echo '
							</td>
							<td class="info text-right">';
								if ($bf_balance > 0 || $bf_balance == 0 || $bf_balance < 0) {	
									$total_credit_ledger_balance += $credit_ledger_balance;
									$overall_credit_ledger_balance += $credit_ledger_balance;
									$credit_ledger_balance = number_format((float)$credit_ledger_balance, 2);

									echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$credit_ledger_balance.'</a></td>';
								}
							echo '
							
							</td>
						</tr>
						';
						}
						
						$ledger_balance = 0;
						$debit_ledger_balance = 0;
						$total_debit_ledger_balance = 0;
						
						$credit_ledger_balance = 0;
						$total_credit_ledger_balance = 0;
					}
					?>
					</tbody>
					<tfoot>
					<tr>
						<td colspan="2" class="text-right"></td>
						<th class="danger text-danger text-right">
							<?php
								$overall_debit_ledger_balance = number_format((float)$overall_debit_ledger_balance, 2);
								echo $overall_debit_ledger_balance; 
							?>
						</th>
						
						<th class="danger text-danger text-right">
							<?php
								$overall_credit_ledger_balance = number_format((float)$overall_credit_ledger_balance, 2);
								echo $overall_credit_ledger_balance; 
							?>
						</th>
					</tr>
					</tfoot>
			  </table>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
$(document).ready(function() {
    $('#trial_balance').DataTable( {
        dom: 'Bfrtip',
        buttons: [
			<?php include 'include/copy_restriction.php'; ?>
			{ extend: 'print', footer: true }
        ],
		pageLength: 200,
		order: [],
    } );
} );
</script>
</html>