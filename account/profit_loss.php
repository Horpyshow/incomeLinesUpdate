<?php
include 'include/session.php';

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$page_title = "{$project} Profit or Loss - Wealth Creation ERP";

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

	<div class="col-sm-12">
	<div class="row">
		<div class="col-sm-12">
			<div class="page-header">
				<h2><span style="color:#ec7063;"><strong><?php echo strtoupper($project); ?></strong></span></br>
				<strong>Statement of Comprehensive Income</strong></h2>
			</div>
		</div>	
	</div>
		<?php
			if (isset($_GET['d1']) && isset($_GET['d2'])){

			$post_at = "";
			$post_at_to_date = "";
			$queryCondition = "";
			
			$post_at = $_GET["d1"];
			list($fid,$fim,$fiy) = explode("/",@$post_at);
			
			$post_at_todate = date('Y-m-d');
			$post_at_to_date = $_GET["d2"];
			list($tid,$tim,$tiy) = explode("/",@$post_at_to_date);
			
			$post_at_todate = "$tiy-$tim-$tid";
			
			$date_range1 = "$fiy-$fim-$fid";
		}
		?>

		<div class="row col-sm-10">
		<div class="table-responsive">
			<table class="display" style="width:100%">
				<thead>
				<tr>
					<td colspan="5">
					<div class="row">
						<form name="frmSearch" method="post" action="profit_loss<?php echo $suffix; ?>_processing.php?d1=<?php if ( isset($_POST['btn_filter']) ) {$post_at = $_POST["search"]["post_at"]; echo $post_at;} ?>&d2=<?php if ( isset($_POST['btn_filter']) ) {$post_at_to_date = $_POST["search"]["post_at_to_date"]; echo $post_at_to_date;} ?>" autocomplete="off">
							<div class="col-sm-10">
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
								<div class="col-sm-5">
									<input type="submit" class="btn btn-success btn-sm" name="btn_filter" value="Show Range" />
									<a href="profit_loss<?php echo $suffix; ?>.php" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-signal"></span> View Current Month</a>
								</div>
							</div>
						</form>
					</div>
					</td>
				</tr>
				
				<tr>
					<th colspan="5">
						<?php
							date_default_timezone_set('Africa/Lagos');
							$today = date('Y-m-d');
							
							$first_day_of_current_year = date('Y-m-d', strtotime('first day of january this year'));
							list($fyocy,$fmocy,$fdocy) = explode("-",$first_day_of_current_year);
							$first_day_of_current_year = "$fdocy/$fmocy/$fyocy";							
							
							$first_day_of_month = date('Y-m-01');
							list($fdid,$fdim,$fdiy) = explode("-",$first_day_of_month);
							$first_day_of_month = "$fdiy/$fdim/$fdid";
							$date_of_current_year="$fdiy-01-01";
							
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
			
			<table id="profit_loss" class="display" style="width:80%">
				<thead>
					<tr class="info">
						<th class="danger">ACCOUNT CODE</th>
						<th class="danger">ACCOUNT DESCRIPTION</th>
						<th class="danger text-right">SELECTED PERIOD </br>LEDGER BALANCE (&#8358;)</th>
						<th></th>
						<th class="danger text-right">CURRENT YEAR </br>LEDGER BALANCE (&#8358;)</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
						$total_revenue = 0;
						
						$sqlquery = "SELECT * ";
						$sqlquery .= "FROM {$prefix}account_gl_code ";
						$sqlquery .= "WHERE gl_category = 'Revenues' ";
						$sqlaccount_query = mysqli_query($dbcon,$sqlquery);
						
						while ($account_class = mysqli_fetch_array($sqlaccount_query, MYSQLI_ASSOC)) {
						
						$gl_code = $account_class['gl_code'];	
						$acct_class_headings = $account_class['gl_code_name'];

					echo'
						<tr>
							<th></th>
							<td><strong>'.strtoupper($acct_class_headings).'</strong></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
					?>
					<?php
						//Initialize the variables
						$var = 0;
						$debits = 0;
						$credits = 0;
						$overall_debits = 0;
						$overall_credits = 0;
						$ytotal_balances = 0;
						
						$sql_query = "SELECT * ";
						$sql_query .= "FROM {$prefix}accounts ";
						$sql_query .= "WHERE gl_code = '$gl_code' ";
						$sql_query .= "AND profit_loss_report = 'Yes' ";
						$sql_query .= "ORDER BY acct_code ASC";
						$account_query = mysqli_query($dbcon,$sql_query);
						
						while ($all_account = mysqli_fetch_array($account_query, MYSQLI_ASSOC)) {
						
						$acct_query_id = $all_account["acct_id"];
						$all_account_desc = $all_account["acct_desc"];
						$pl_ledger = $all_account["acct_table_name"];
						$acct_code = $all_account["acct_code"];
						
					//Inner table for revenues begin here	
					echo
						'<tr>
							<td>'.$acct_code.'</td>
							<td><a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$all_account_desc.'</a></td>
							
							<td class="info text-right">';
							?>
							
							<?php
								if (isset($_GET['d1']) && isset($_GET['d2'])) {

								$post_at = "";
								$post_at_to_date = "";
								$queryCondition = "";
								$queryCondition2 = "";

								$post_at = $_GET["d1"];
								list($fid,$fim,$fiy) = explode("/",$post_at);
								$post_at_todate = date('Y-m-d');

								$post_at_to_date = $_GET["d2"];
								list($tid,$tim,$tiy) = explode("/",$post_at_to_date);
								$post_at_todate = "$tiy-$tim-$tid";
							  
								$queryCondition .= "WHERE date BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";
								
								//Condition to filter from first day of the year to current date.                 
								$queryCondition2 .= "WHERE date BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";

								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "$queryCondition ";
								$query .= "AND approval_status = 'Approved'";
								$sum = @mysqli_query($dbcon,$query);
								$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);

								//Get the debit balance position
								$debits = $total['debits'];
								$new_debits = $debits;
								$overall_debits += $debits;
								
								//Get the credit balance position
								$credits = $total['credits'];
								$new_credits = $credits;
								$overall_credits += $credits;

								$new_balance = ($new_credits - $new_debits);
								$total_balances += $new_balance;
								$new_balance = number_format((float)$new_balance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$new_balance.'</a>';
								
							} else {
								 
								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "WHERE approval_status = 'Approved' ";
								$query .= "AND (YEAR(date) = YEAR(NOW()) AND MONTH(date) = MONTH(NOW())) ";
								$sum = @mysqli_query($dbcon,$query);
								while ($total = @mysqli_fetch_array($sum, MYSQLI_ASSOC)) {

								$debits = $total['debits'];
								$credits = $total['credits'];

								//Credit minus debit incase there is a debit entry on any Revenue Ledger as all Revenue Ledger must carry +ve values on income statement.
								$balance = ($credits - $debits);
								$total_balances += $balance;
								$balance = number_format((float)$balance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$balance.'</a>';
								}
							}
							
							echo '
							</td>
							
							<td></td>
							
							<td class="info text-right">';
							?>
							<?php
								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "WHERE approval_status = 'Approved' ";
								
								//filter ONLY records in the current year.
								$query .= "AND (YEAR(date) = YEAR(NOW()))";
								$sum = @mysqli_query($dbcon,$query);
								while ($ytotal = @mysqli_fetch_array($sum, MYSQLI_ASSOC)) {

								$ydebits = $ytotal['debits'];
								$ycredits = $ytotal['credits'];	
								$ybalance = ($ycredits - $ydebits);

								$ytotal_balances += $ybalance;
								$ybalance = number_format((float)$ybalance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='.$first_day_of_current_year.'&d2='.$last_day_of_month.'" target="_blank">'.$ybalance.'</a>';
								}
							?>
							<?php 
							echo '
							</td>
						</tr>
						';
						}
						
						//Determine the each sub revenue here
						echo 
						'<tr>
							<th></th>
							<th class="danger text-danger">TOTAL '.strtoupper($acct_class_headings).'</th>
							<th class="danger text-danger text-right">';
								$total_revenues = $total_balances;
								$total_balances = number_format((float)$total_balances, 2);
								echo $total_balances;	//declare current month or filtered month total revenues
							echo '
							</th>
							
							<th></th>
							<th class="danger text-danger text-right">';
								$ytotal_revenues = $ytotal_balances;
								$ytotal_balances = number_format((float)$ytotal_balances, 2);
								echo $ytotal_balances; 		//declare year to date (current year) total revenues
							echo '
							</th>
						</tr>';
						
						echo'
						<tr>
							<th></th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
						
						$total_balances=0;
						$lump_sum=0;
						$total_revenue = 0;
						$total_expenses = 0;
						}
						//Inner table for revenues ends here
					?>
					
					<?php
						//Query to determine the Expense lines
						$total_balances=0;
						$ytotal_balances = 0;
						$lump_sum=0;
						$total_expenses = 0;
						$ytotal_expenses = 0;
						
						$sqlquery = "SELECT * ";
						$sqlquery .= "FROM {$prefix}account_gl_code ";
						$sqlquery .= "WHERE gl_category = 'Expenses' ";
						$sql_query .= "ORDER BY gl_code ASC";
						$sqlaccount_query = mysqli_query($dbcon,$sqlquery);
						
						while ($account_class = mysqli_fetch_array($sqlaccount_query, MYSQLI_ASSOC)) {
						
						$gl_code = $account_class['gl_code'];						
						$acct_class_headings = $account_class['gl_code_name'];

					echo'
						<tr>
							<th></th>
							<td><strong>'.strtoupper($acct_class_headings).'</strong></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
					?>
					<?php
						//Initialize the variables
						$var = 0;
						$debits = 0;
						$credits = 0;
						$overall_debits = 0;
						$overall_credits = 0;
						$ytotal_balances = 0;
						$total_ledger_balances = 0;
						
						$sql_query = "SELECT * ";
						$sql_query .= "FROM {$prefix}accounts ";
						$sql_query .= "WHERE gl_code = '$gl_code' ";
						$sql_query .= "AND profit_loss_report = 'Yes' ";
						$sql_query .= "ORDER BY acct_code ASC";
						$account_query = mysqli_query($dbcon,$sql_query);
						
						while ($all_account = mysqli_fetch_array($account_query, MYSQLI_ASSOC)) {
						
						$acct_query_id = $all_account["acct_id"];
						$all_account_desc = $all_account["acct_desc"];
						$pl_ledger = $all_account["acct_table_name"];
						$acct_code = $all_account["acct_code"];
						
					echo
						'<tr>
							<td>'.$acct_code.'</td>
							<td><a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$all_account_desc.'</a></td>
							
							<td class="info text-right">';
							?>
							
							<?php
								if (isset($_GET['d1']) && isset($_GET['d2'])) {

								$post_at = "";
								$post_at_to_date = "";
								$queryCondition = "";
								$queryCondition2 = "";

								$post_at = $_GET["d1"];
								list($fid,$fim,$fiy) = explode("/",$post_at);

								$post_at_todate = date('Y-m-d');
								$post_at_to_date = $_GET["d2"];
								list($tid,$tim,$tiy) = explode("/",$post_at_to_date);
								$post_at_todate = "$tiy-$tim-$tid";
							  
								$queryCondition .= "WHERE date BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";
								
								//Condition to filter from first day of the year to current date.                 
								$queryCondition2 .= "WHERE date BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";

								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "$queryCondition ";
								$query .= "AND approval_status = 'Approved'";
								$sum = @mysqli_query($dbcon,$query);
								$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);

								//Get the debit balance position
								$debits = $total['debits'];
								
								$new_debits = $debits;
								$overall_debits += $debits;
								
								//Get the credit balance position
								$credits = $total['credits'];
								$new_credits = $credits;
								$overall_credits += $credits;	

								$new_balance = ($new_debits - $new_credits);
								$total_balances += $new_balance;
								$new_balance = number_format((float)$new_balance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$new_balance.'</a>';
								
							} else {
								 
								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "WHERE approval_status = 'Approved' ";
								$query .= "AND (YEAR(date) = YEAR(NOW()) AND MONTH(date) = MONTH(NOW())) ";
								$sum = @mysqli_query($dbcon,$query);
								while ($total = @mysqli_fetch_array($sum, MYSQLI_ASSOC)) {

								$debits = $total['debits'];
								$credits = $total['credits'];

								//Credit minus debit incase there is a debit entry on any Revenue Ledger as all Revenue Ledger must carry +ve values on income statement.
								$balance = ($debits - $credits);
								$total_balances += $balance;
								$balance = number_format((float)$balance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$balance.'</a>';
								}
							}
							?>
							
							<?php
							echo '
							</td>
							<td></td>
							<td class="info text-right">';
							?>
							<?php
							
								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "WHERE approval_status = 'Approved' ";
								
								//filter ONLY records in the current year.
								$query .= "AND (YEAR(date) = YEAR(NOW()))";
								$sum = @mysqli_query($dbcon,$query);
								while ($ytotal = @mysqli_fetch_array($sum, MYSQLI_ASSOC)) {

								$ydebits = $ytotal['debits'];
								$ycredits = $ytotal['credits'];	
								$ybalance = ($ydebits - $ycredits);

								$ytotal_balances += $ybalance;
								$ybalance = number_format((float)$ybalance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='.$first_day_of_current_year.'&d2='.$last_day_of_month.'" target="_blank">'.$ybalance.'</a>';
								}
							?>
							<?php 
							echo '
							</td>
						</tr>';
						}
						
						//Determine the each sub expenses here
						echo 
						'<tr>
							<th></th>
							<th class="danger text-danger">TOTAL '.strtoupper($acct_class_headings).'</th>
							<th class="danger text-danger text-right">';
								$total_expenses += $total_balances;
								$total_balances = number_format((float)$total_balances, 2);
								echo $total_balances;	//declare current month or filtered month total expenses
							echo '
							</th>
							
							<th></th>
							<th class="danger text-danger text-right">';
								$ytotal_expenses += $ytotal_balances;
								$ytotal_balances = number_format((float)$ytotal_balances, 2);
								echo $ytotal_balances; 		//declare year to date (current year) total expenses
							echo '
							</th>
						</tr>';
						
						echo'
						<tr>
							<th></th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
						
						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
						}
					?>
				</tbody>
				
				<tfoot>
					<?php
					echo 
					'<tr>
						<th></th>
						<th>PROFIT FOR THE PERIOD</th>
						<th class="danger text-danger text-right">';
							//declare the net income for the current/filtered month
							$net_income = $total_revenues - $total_expenses;
							$net_income = number_format((float)$net_income, 2);
							echo $net_income;
						echo '
						</th>
						
						<th></th>
						<th class="danger text-danger text-right">';
							//declare the net income for the current year
							$ynet_income = $ytotal_revenues - $ytotal_expenses;
							$ynet_income = number_format((float)$ynet_income, 2);
							echo $ynet_income;
						echo '
						</th>
					</tr>';					
					?>
				</tfoot>
			  </table>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
$(document).ready(function() {
    $('#profit_loss').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
			{ extend: 'pdfHtml5', footer: true },
			{ extend: 'print', footer: true }
        ],
		pageLength: 200,
		order: [],
    } );
} );
</script>
</html>