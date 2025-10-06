<?php
include 'include/session.php';

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$page_title = "{$project} Balance Sheet - Wealth Creation ERP";

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
				<strong>Statement of Financial Position</strong></h2>
			</div>
		
		</div>
			
	</div>
		<?php
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
		}
		?>

		<div class="row col-sm-11">
		<div class="table-responsive">
			<table class="display" style="width:100%">
				<thead>
				<tr>
					<td colspan="4">
					<div class="row">
						<form name="frmSearch" method="post" action="balance_sheet<?php echo $suffix; ?>_processing.php?d1=<?php if ( isset($_POST['btn_filter']) ) {$post_at = $_POST["search"]["post_at"]; echo $post_at;} ?>&d2=<?php if ( isset($_POST['btn_filter']) ) {$post_at_to_date = $_POST["search"]["post_at_to_date"]; echo $post_at_to_date;} ?>" autocomplete="off">
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
									<a href="balance_sheet<?php echo $suffix; ?>.php" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-signal"></span> View Current Month</a>
								</div>
							</div>
						</form>
					</div>
					</td>
				</tr>
				
				<tr>
					<th colspan="4">
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
			
			<table id="balance_sheet" class="display" style="width:80%">
				<thead>
					<tr class="info">
						<th class="danger">ACCOUNT CODE</th>
						<th class="danger">ACCOUNT DESCRIPTION</th>
						<th class="danger text-right">AMOUNT (&#8358;)</th>
						<th></th>
						<th class="danger text-right"></th>
					</tr>
				</thead>
				<tbody>
					<?php
						//Non Current Assets begins here
						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
						$total_ppe = 0;
						$total_non_current_assets = 0;
						
						echo'
						<tr>
							<th style="border-bottom:3pt solid black;"></th>
							<td style="font-size:14pt; border-bottom:3pt solid black;"><strong>ASSETS</strong></td>
							<td style="border-bottom:3pt solid black;"></td>
							<td style="border-bottom:3pt solid black;"></td>
							<td style="border-bottom:3pt solid black;"></td>
						</tr>';
						
						$sqlquery = "SELECT * ";
						$sqlquery .= "FROM {$prefix}account_gl_code ";
						$sqlquery .= "WHERE gl_category = 'Non Current Assets' ";
						$sqlquery .= "ORDER BY gl_code ASC";
						$sqlaccount_query = mysqli_query($dbcon,$sqlquery);
						
						while ($account_class = mysqli_fetch_array($sqlaccount_query, MYSQLI_ASSOC)) {
						
						$gl_code = $account_class['gl_code'];	
						$acct_class_headings = $account_class['gl_code_name'];

						echo'
						<tr>
							<th>'.$gl_code.'</th>
							<td><strong>'.strtoupper($acct_class_headings).'</strong></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
					?>
					<?php
						$sql_query = "SELECT * ";
						$sql_query .= "FROM {$prefix}accounts ";
						$sql_query .= "WHERE gl_code = '$gl_code' ";
						$sql_query .= "AND balance_sheet_report = 'Yes' ";
						$sql_query .= "ORDER BY acct_code ASC";
						$account_query = mysqli_query($dbcon,$sql_query);
						
						while ($all_account = mysqli_fetch_array($account_query, MYSQLI_ASSOC)) {
						
						$acct_query_id = $all_account["acct_id"];
						$all_account_desc = $all_account["acct_desc"];
						$negation_status = $all_account["negative_acct"];
						$pl_ledger = $all_account["acct_table_name"];
						$acct_code = $all_account["acct_code"];
					
					echo
						'<tr>
							<td>'.$acct_code.'</td>
							<td>'.ucwords(strtolower($all_account_desc)).'</td>
							<td class="info text-right">'; 
							?>
							<?php
								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "WHERE approval_status = 'Approved'";
								$sum = @mysqli_query($dbcon,$query);
								while ($total = @mysqli_fetch_array($sum, MYSQLI_ASSOC)) {

								$debits = $total['debits'];
								$credits = $total['credits'];	
								$balance = ($debits - $credits);
								$total_balances += $balance;
								
								$balance = number_format((float)$balance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$balance.'</a>';
								}
							?>		
							<?php
							echo '
							</td>
							<td></td>
							<td></td>
						</tr>';
						}
					?>
					<?php
						//Determine total non current assets by suming all total ledger balances
						$total_non_current_assets += $total_balances;
						$non_current_assets = $total_non_current_assets;						
						
						//Create an exception for PPE and Accum Deprec. to total on the 2nd column
						if($acct_class_headings == "Accumulated Depreciation" || $acct_class_headings == "Plant Property & Equipment"){
							//Determine the total PPE at this point.
							$total_ppe += $total_balances;
							
							//Additional of all ledger balances
							$total_balances = number_format((float)$total_balances, 2);  
							
							echo 
							'<tr>
								<th></th>
								<th class="danger text-danger">TOTAL '.strtoupper($acct_class_headings).'</th>
								<td style="border-top:2pt solid black;"></td>
								<th class="danger text-danger text-right">'.$total_balances.'</th>
								<td></td>
							</tr>';
						} else {
							//Additional of all ledger balances
							$total_balances = number_format((float)$total_balances, 2);
							echo 
							'<tr>
								<th></th>
								<th class="danger text-danger">TOTAL '.strtoupper($acct_class_headings).'</th>
								<td style="border-top:2pt solid black;"></td>
								<td></td>
								<th class="danger text-danger text-right">'.$total_balances.'</th>
							</tr>';
						}
						
						if($acct_class_headings == "Accumulated Depreciation"){
							$total_ppe = number_format((float)$total_ppe, 2);
							echo 
							'<tr>
								<th></th>
								<th class="danger text-danger">NET PLANT PROPERTIES & EQUIPMENT</th>
								<td ></td>
								<td style="border-top:2pt solid black;"></td>
								<th class="danger text-danger text-right">'.$total_ppe.'</th>
							</tr>';
							}

						echo '
						<tr>
							<th></th>
							<th></th>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
						
						//Re-Initialize the variables of the while loop
						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
						$total_assets = 0;
						}
						
						$total_non_current_assets = number_format((float)$total_non_current_assets, 2);
						echo					
							'<tr>
								<th></th>
								<th class="danger text-danger">TOTAL NON-CURRENT ASSETS</th>
								<td ></td>
								<td ></td>
								<th class="danger text-danger text-right" style="border-top:2pt solid black;">'.$total_non_current_assets.'</th>
							</tr>';
						?>
						<tr>
							<th></th>
							<th></th>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						
						<?php
						//Current Assets begins here
						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
						$total_current_assets = 0;
						
						$sqlquery = "SELECT * ";
						$sqlquery .= "FROM {$prefix}account_gl_code ";
						$sqlquery .= "WHERE gl_category = 'Current Assets' ";
						$sqlquery .= "ORDER BY gl_code ASC";
						$sqlaccount_query = mysqli_query($dbcon,$sqlquery);
						
						while ($account_class = mysqli_fetch_array($sqlaccount_query, MYSQLI_ASSOC)) {
						
						$gl_code = $account_class['gl_code'];	
						$acct_class_headings = $account_class['gl_code_name'];

						echo'
						<tr>
							<th>'.$gl_code.'</th>
							<td><strong>'.strtoupper($acct_class_headings).'</strong></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
					?>
					<?php
						$sql_query = "SELECT * ";
						$sql_query .= "FROM {$prefix}accounts ";
						$sql_query .= "WHERE gl_code = '$gl_code' ";
						$sql_query .= "AND balance_sheet_report = 'Yes' ";
						$sql_query .= "ORDER BY acct_code ASC";
						$account_query = mysqli_query($dbcon,$sql_query);
						
						while ($all_account = mysqli_fetch_array($account_query, MYSQLI_ASSOC)) {
						
						$acct_query_id = $all_account["acct_id"];
						$all_account_desc = $all_account["acct_desc"];
						$negation_status = $all_account["negative_acct"];
						$pl_ledger = $all_account["acct_table_name"];
						$acct_code = $all_account["acct_code"];
					
					echo
						'<tr>
							<td>'.$acct_code.'</td>
							<td>'.ucwords(strtolower($all_account_desc)).'</td>
							<td class="info text-right">'; 
							?>
							<?php
								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "WHERE approval_status = 'Approved'";
								$sum = @mysqli_query($dbcon,$query);
								while ($total = @mysqli_fetch_array($sum, MYSQLI_ASSOC)) {

								$debits = $total['debits'];
								$credits = $total['credits'];	
								$balance = ($debits - $credits);

								$total_balances += $balance;
								$balance = number_format((float)$balance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$balance.'</a>';
								
								}
							?>		
							<?php
							echo '
							</td>
							<td></td>
							<td></td>
						</tr>';
						}
					?>
					<?php
						$total_current_assets += $total_balances;
						$current_assets = $total_current_assets;
						$total_balances = number_format((float)$total_balances, 2);
						echo 
						'<tr>
							<th></th>
							<th class="danger text-danger">TOTAL '.strtoupper($acct_class_headings).'</th>
							<td style="border-top:2pt solid black;"></td>
							<th class="danger text-danger text-right">'.$total_balances.'</th>
							<td></td>
						</tr>';
						
						echo '
						<tr>
							<th></th>
							<th></th>
							<td></td>
							<td></td>
							<td></td>
						</tr>';

						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
						}
					
					$total_assets = ($non_current_assets + $current_assets);					
					$total_current_assets = number_format((float)$total_current_assets, 2);
					$total_assets = number_format((float)$total_assets, 2);
					echo					
						'<tr>
							<th></th>
							<th class="danger text-danger">TOTAL CURRENT ASSETS</th>
							<td></td>
							<td style="border-top:2pt solid black;"></td>
							<th class="danger text-danger text-right">'.$total_current_assets.'</th>
						</tr>';
					echo					
						'<tr>
							<th style="border-top:2pt solid black; border-bottom:2pt solid black;"></th>
							<th class="danger text-danger" style="border-top:2pt solid black; border-bottom:2pt solid black;">TOTAL ASSETS</th>
							<td style="border-top:2pt solid black; border-bottom:2pt solid black;"></td>
							<td style="border-top:2pt solid black; border-bottom:2pt solid black;"></td>
							<th class="danger text-danger text-right" style="border-top:2pt solid black; border-bottom:2pt solid black;">'.$total_assets.'</th>
						</tr>';
					?>
						
					<tr>
						<th></th>
						<th></th>
						<td></td>
						<td></td>
						<td></td>
					</tr>	
					
					<?php
						//Current Liabilities begins here
						$total_current_liabilities = 0;
						$non_current_liabilities = 0;
						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
						
						echo'
						<tr>
							<th style="border-bottom:3pt solid black;"></th>
							<td style="font-size:14pt; border-bottom:3pt solid black;"><strong>LIABILITIES</strong></td>
							<td style="border-bottom:3pt solid black;"></td>
							<td style="border-bottom:3pt solid black;"></td>
							<td style="border-bottom:3pt solid black;"></td>
						</tr>';
						
						$sqlquery = "SELECT * ";
						$sqlquery .= "FROM {$prefix}account_gl_code ";
						$sqlquery .= "WHERE gl_category = 'Current Liabilities' ";
						$sqlquery .= "ORDER BY gl_code ASC";
						$sqlaccount_query = mysqli_query($dbcon,$sqlquery);
						
						while ($account_class = mysqli_fetch_array($sqlaccount_query, MYSQLI_ASSOC)) {
						
						$gl_code = $account_class['gl_code'];	
						$acct_class_headings = $account_class['gl_code_name'];

						echo'
						<tr>
							<th>'.$gl_code.'</th>
							<td><strong>'.strtoupper($acct_class_headings).'</strong></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
					?>
					<?php
						$sql_query = "SELECT * ";
						$sql_query .= "FROM {$prefix}accounts ";
						$sql_query .= "WHERE gl_code = '$gl_code' ";
						$sql_query .= "AND balance_sheet_report = 'Yes' ";
						$sql_query .= "ORDER BY acct_code ASC";
						$account_query = mysqli_query($dbcon,$sql_query);
						
						while ($all_account = mysqli_fetch_array($account_query, MYSQLI_ASSOC)) {
						
						$acct_query_id = $all_account["acct_id"];
						$all_account_desc = $all_account["acct_desc"];
						$negation_status = $all_account["negative_acct"];
						$pl_ledger = $all_account["acct_table_name"];
						$acct_code = $all_account["acct_code"];
					
					echo
						'<tr>
							<td>'.$acct_code.'</td>
							<td>'.ucwords(strtolower($all_account_desc)).'</td>
							<td class="info text-right">'; 
							?>
							<?php
								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "WHERE approval_status = 'Approved'";
								$sum = @mysqli_query($dbcon,$query);
								while ($total = @mysqli_fetch_array($sum, MYSQLI_ASSOC)) {

								$debits = $total['debits'];
								$credits = $total['credits'];	
								$balance = ($debits - $credits);

								$total_balances += $balance;
								$total_current_assets = $total_balances;
								$balance = number_format((float)$balance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$balance.'</a>';
								
								}
							?>		
							<?php
							echo '
							</td>
							<td></td>
							<td></td>
						</tr>';
						}
					?>
					<?php
						$total_current_liabilities += $total_balances;
						$current_liabilities = $total_current_liabilities;
						$total_balances = number_format((float)$total_balances, 2);
						echo 
						'<tr>
							<th></th>
							<th class="danger text-danger">TOTAL '.strtoupper($acct_class_headings).'</th>
							<td style="border-top:2pt solid black;"></td>
							<th class="danger text-danger text-right">'.$total_balances.'</th>
							<td></td>
						</tr>';
						
						echo '
						<tr>
							<th></th>
							<th></th>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
						
					$total_balances=0;
					$ytotal_balances=0;
					$lump_sum=0;
					}
						
					$total_current_liabilities = number_format((float)$total_current_liabilities, 2);
					echo					
						'<tr>
							<th></th>
							<th class="danger text-danger">TOTAL CURRENT LIABILITIES</th>
							<td></td>
							<td style="border-top:2pt solid black;"></td>
							<th class="danger text-danger text-right">'.$total_current_liabilities.'</th>
						</tr>';
					?>
						
					<tr>
						<th></th>
						<th></th>
						<td></td>
						<td></td>
						<td></td>
					</tr>
						
					<?php
						//Non Current Liabilities begins here
						$total_non_current_liabilities = 0;
						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
						
						$sqlquery = "SELECT * ";
						$sqlquery .= "FROM {$prefix}account_gl_code ";
						$sqlquery .= "WHERE gl_category = 'Non Current Liabilities' ";
						$sqlquery .= "ORDER BY gl_code ASC";
						$sqlaccount_query = mysqli_query($dbcon,$sqlquery);
						
						while ($account_class = mysqli_fetch_array($sqlaccount_query, MYSQLI_ASSOC)) {
						
						$gl_code = $account_class['gl_code'];	
						$acct_class_headings = $account_class['gl_code_name'];

						echo'
						<tr>
							<th>'.$gl_code.'</th>
							<td><strong>'.strtoupper($acct_class_headings).'</strong></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
					?>
					<?php
						$sql_query = "SELECT * ";
						$sql_query .= "FROM {$prefix}accounts ";
						$sql_query .= "WHERE gl_code = '$gl_code' ";
						$sql_query .= "AND balance_sheet_report = 'Yes' ";
						$sql_query .= "ORDER BY acct_code ASC";
						$account_query = mysqli_query($dbcon,$sql_query);
						
						while ($all_account = mysqli_fetch_array($account_query, MYSQLI_ASSOC)) {
						
						$acct_query_id = $all_account["acct_id"];
						$all_account_desc = $all_account["acct_desc"];
						$negation_status = $all_account["negative_acct"];
						$pl_ledger = $all_account["acct_table_name"];
						$acct_code = $all_account["acct_code"];
					
					echo
						'<tr>
							<td>'.$acct_code.'</td>
							<td>'.ucwords(strtolower($all_account_desc)).'</td>
							<td class="info text-right">'; 
							?>
							<?php
								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "WHERE approval_status = 'Approved'";
								$sum = @mysqli_query($dbcon,$query);
								while ($total = @mysqli_fetch_array($sum, MYSQLI_ASSOC)) {

								$debits = $total['debits'];
								$credits = $total['credits'];	
								$balance = ($debits - $credits);
								
								$total_balances += $balance;
								$total_current_assets = $total_balances;
								$balance = number_format((float)$balance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$balance.'</a>';
								
								}
							?>		
							<?php
							echo '
							</td>
							<td></td>
							<td></td>
						</tr>';
						}
					?>
					<?php
						$total_non_current_liabilities += $total_balances;
						$non_current_liabilities = $total_non_current_liabilities;
						$total_balances = number_format((float)$total_balances, 2);
						echo 
						'<tr>
							<th></th>
							<th class="danger text-danger">TOTAL '.strtoupper($acct_class_headings).'</th>
							<td style="border-top:2pt solid black;"></td>
							<th class="danger text-danger text-right">'.$total_balances.'</th>
							<td></td>
						</tr>';
						
						echo '
						<tr>
							<th></th>
							<th></th>
							<td></td>
							<td></td>
							<td></td>
						</tr>';

						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
					}
					
					$total_liabilities = ($current_liabilities + $non_current_liabilities);	
					$liability = $total_liabilities;					
					$total_non_current_liabilities = number_format((float)$total_non_current_liabilities, 2);
					$total_liabilities = number_format((float)$total_liabilities, 2);
					echo					
						'<tr>
							<th></th>
							<th class="danger text-danger">TOTAL NON CURRENT LIABILITIES</th>
							<td></td>
							<td style="border-top:2pt solid black;"></td>
							<th class="danger text-danger text-right">'.$total_non_current_liabilities.'</th>
						</tr>';
						
					echo					
						'<tr>
							<th style="border-top:2pt solid black;"></th>
							<th class="danger text-danger" style="border-top:2pt solid black;">TOTAL LIABILITIES</th>
							<td style="border-top:2pt solid black;"></td>
							<td style="border-top:2pt solid black;"></td>
							<th class="danger text-danger text-right" style="border-top:2pt solid black;">'.$total_liabilities.'</th>
						</tr>';
					?>
						
					<tr>
						<th></th>
						<th></th>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					

					<?php
						//Equity begins here
						$total_equity = 0;
						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
						
						
						$sqlquery = "SELECT * ";
						$sqlquery .= "FROM {$prefix}account_gl_code ";
						$sqlquery .= "WHERE gl_category = 'Equity' ";
						$sqlquery .= "ORDER BY gl_code ASC";
						$sqlaccount_query = mysqli_query($dbcon,$sqlquery);
						
						while ($account_class = mysqli_fetch_array($sqlaccount_query, MYSQLI_ASSOC)) {
						
						$gl_code = $account_class['gl_code'];	
						$acct_class_headings = $account_class['gl_code_name'];

						echo'
						<tr>
							<th>'.$gl_code.'</th>
							<td><strong>'.strtoupper($acct_class_headings).'</strong></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
					?>
					<?php
						$sql_query = "SELECT * ";
						$sql_query .= "FROM {$prefix}accounts ";
						$sql_query .= "WHERE gl_code = '$gl_code' ";
						$sql_query .= "AND balance_sheet_report = 'Yes' ";
						$sql_query .= "ORDER BY acct_code ASC";
						$account_query = mysqli_query($dbcon,$sql_query);
						
						while ($all_account = mysqli_fetch_array($account_query, MYSQLI_ASSOC)) {
						
						$acct_query_id = $all_account["acct_id"];
						$all_account_desc = $all_account["acct_desc"];
						$negation_status = $all_account["negative_acct"];
						$pl_ledger = $all_account["acct_table_name"];
						$acct_code = $all_account["acct_code"];
					
					echo
						'<tr>
							<td>'.$acct_code.'</td>
							<td>'.ucwords(strtolower($all_account_desc)).'</td>
							<td class="info text-right">'; 
							?>
							<?php
								$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
								$query .= "FROM $pl_ledger ";
								$query .= "WHERE approval_status = 'Approved'";
								$sum = @mysqli_query($dbcon,$query);
								while ($total = @mysqli_fetch_array($sum, MYSQLI_ASSOC)) {

								$debits = $total['debits'];
								$credits = $total['credits'];	
								$balance = ($debits - $credits);

								$total_balances += $balance;
								$total_current_assets = $total_balances;
								$balance = number_format((float)$balance, 2);
								
								echo '<a href="ledgers'.$suffix.'.php?acct_id='.$acct_query_id.'&d1='; ?><?php if (isset($_GET["d1"])) {$d1 = $_GET["d1"]; $d1 = htmlspecialchars($d1); $d1 = urlencode($d1); echo $d1.'&d2=';} ?><?php if (isset($_GET["d2"])) {$d2 = $_GET["d2"]; $d2 = htmlspecialchars($d2); $d2 = urlencode($d2); echo $d2;} ?><?php echo '" target="_blank">'.$balance.'</a>';								
								}
							?>		
							<?php
							echo '
							</td>
							<td></td>
							<td></td>
						</tr>';
						}
					?>
					<?php
						$total_equity += $total_balances;
						$equity = $total_equity;
						$total_balances = number_format((float)$total_balances, 2);
						echo 
						'<tr>
							<th></th>
							<th class="danger text-danger">TOTAL '.strtoupper($acct_class_headings).'</th>
							<td style="border-top:2pt solid black;"></td>
							<th class="danger text-danger text-right">'.$total_balances.'</th>
							<th class="danger text-danger text-right">'.$total_equity.'</th>
						</tr>';
						
						echo '
						<tr>
							<th></th>
							<th></th>
							<td></td>
							<td></td>
							<td></td>
						</tr>';

						$total_balances=0;
						$ytotal_balances=0;
						$lump_sum=0;
						}
						
						$total_capital = ($liability + $equity);					
						$total_capital = number_format((float)$total_capital, 2);
						echo					
						'<tr>
							<th style="border-top:2pt solid black; border-bottom:2pt solid black;"></th>
							<th class="danger text-danger" style="border-top:2pt solid black; border-bottom:2pt solid black;">TOTAL CAPITAL (LIABILITY + EQUITY)</th>
							<td style="border-top:2pt solid black; border-bottom:2pt solid black;"></td>
							<td style="border-top:2pt solid black; border-bottom:2pt solid black;"></td>
							<th class="danger text-danger text-right" style="border-top:2pt solid black; border-bottom:2pt solid black;">'.$total_capital.'</th>
						</tr>';
						?>
				</tbody>
				
				<tfoot>

				</tfoot>
			  </table>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
$(document).ready(function() {
    $('#balance_sheet').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            <?php include 'include/copy_restriction.php'; ?>
			{ extend: 'print', footer: true }
        ],
		pageLength: 500000,
		order: [],
    } );
} );
</script>
</html>