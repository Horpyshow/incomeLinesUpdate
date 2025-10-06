<?php
include 'include/session.php';

$project = "Wealth Creation";
$prefix = "";
$suffix = "";
$page_title = "{$project} General Ledger - Wealth Creation ERP";

?>
<?php
$ledger = NULL;

//Check if there is a ledger has been queried
if (isset($_POST['btn_view_ledger'])) {
	
	$account_id = @$_POST['ledger_account'];
	
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM {$prefix}accounts ";
	$query_acct1 .= "WHERE acct_id = $account_id";
	$acct_table_set = @mysqli_query($dbcon, $query_acct1);
	$acct_table = @mysqli_fetch_array($acct_table_set, MYSQLI_ASSOC);
	
	$ledger = $acct_table["acct_table_name"];
	$ledger_desc = $acct_table["acct_desc"];
}
//Check if a ledger id is already set in the browser
elseif (isset($_GET['acct_id'])) {
	
	$account_id = @$_GET['acct_id'];
	
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM {$prefix}accounts ";
	$query_acct1 .= "WHERE acct_id = $account_id";
	$acct_table_set = @mysqli_query($dbcon, $query_acct1);
	$acct_table = @mysqli_fetch_array($acct_table_set, MYSQLI_ASSOC);
	
	$ledger = $acct_table["acct_table_name"];
	$ledger_desc = $acct_table["acct_desc"];
} else {
	
}

@$page_title = "{$project} {$ledger_desc} Ledger - Woobs Resources ERP";

//Check if there is a filter request.
if (isset($_GET['d1']) && isset($_GET['d2']) && isset($_GET['acct_id'])) {
	$account_id = @$_GET['acct_id'];
	
	$query_acct1 = "SELECT * ";
	$query_acct1 .= "FROM {$prefix}accounts ";
	$query_acct1 .= "WHERE acct_id = $account_id";
	$acct_table_set = @mysqli_query($dbcon, $query_acct1);
	$acct_table = @mysqli_fetch_array($acct_table_set, MYSQLI_ASSOC);
	
	$ledger = $acct_table["acct_table_name"];

	$post_at = "";
	$post_at_to_date = "";
	$queryCondition = "";
	
	$post_at = $_GET["d1"];
	list($fid,$fim,$fiy) = explode("/",$post_at);
	
	$post_at_todate = date('Y-m-d');
	
	$post_at_to_date = $_GET["d2"];
	list($tid,$tim,$tiy) = explode("/",$post_at_to_date);
	
	$post_at_todate = "$tiy-$tim-$tid";
		
	$queryCondition .= "WHERE approval_status='Approved' AND date BETWEEN '$fiy-$fim-$fid' AND '" . $post_at_todate . "'";

	$sql = "SELECT * FROM " . $ledger . " " . $queryCondition . " ORDER BY date ASC";
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
		<link rel="stylesheet" href="../../css/mystyles.css">
		
		<link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/buttons.dataTables.min.css">
		
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
		<div class="col-sm-7">
			<?php
				// Get the Account ID and lookup the details of the selected ledger
				if (isset($_GET['acct_id'])) {
					
					$account_id = @$_GET['acct_id'];
					
					$query = "SELECT * ";
					$query .= "FROM {$prefix}accounts ";
					$query .= "WHERE acct_id = '$account_id' ";			
					$query .= "LIMIT 1";
					$account_set = mysqli_query($dbcon, $query);
					$account = mysqli_fetch_array($account_set, MYSQLI_ASSOC);

					$profit_loss_report = $account["profit_loss_report"];
					$acct_code = $account["acct_code"];
					$gl_code = $account["gl_code"];
					
					$gl_query = "SELECT * ";
					$gl_query .= "FROM account_gl_code ";
					$gl_query .= "WHERE gl_code = '$gl_code'";
					$gl_code_set = mysqli_query($dbcon, $gl_query);
					$gl_account = mysqli_fetch_array($gl_code_set, MYSQLI_ASSOC);
					
					$gl_code_name = $gl_account["gl_code_name"];
					$gl_category = $gl_account["gl_category"];
				}
			?>
			<div class="page-header">
				<h2><?php echo $project.' <span style="color:#ec7063; font-weight:bold;">'.@$account['acct_desc']; ?></span> Ledger</h2>
				<h3><?php echo @$gl_category; ?> Account | <span style="color:#ec7063;"><?php echo @$gl_code_name; ?></span> | Code: <strong><?php echo @$acct_code; ?></strong></h3>
			</div>
		
		</div>
		
		<?php
		
		if($menu["level"] != "leasing officer") {
		?>
		<div class="col-sm-5 noprint">
			<form  method="post" id="form" class="form-horizontal" action="ledgers<?php echo $suffix; ?>_processing.php" autocomplete="off">
				<table class="table table-hover table-bordered">
					<thead>
						<tr class="success">
							<th colspan="3" class="text-right"><?php echo strtoupper($project); ?> Account Ledgers</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th colspan="2">
								<!-- Select Basic -->			   
								<div class="form-group form-group-sm"> 
									<div class="col-sm-11 selectContainer">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></span>
											<select name="ledger_account" class="form-control selectpicker" id="ledger_account">
											  <option value="">Choose a ledger to view</option>
												<?php
												$query = "SELECT * FROM {$prefix}accounts ";
												$query .= "WHERE active = 'Yes' ";
												$query .= "ORDER BY acct_desc ASC";
												$account_set = @mysqli_query($dbcon, $query); 
												
												while ($account = mysqli_fetch_array($account_set, MYSQLI_ASSOC)) {
												?>

												<option value="<?php echo $account['acct_id']; ?>"><?php echo $account['acct_desc']; ?></option>
												<?php
												} 
												
												?>
											</select>
										</div>
									</div>
								</div>
							</th>
							<td align="center">
								<div class="form-group form-group-sm">
									<div>
										<button type="submit" name="btn_view_ledger" class="btn btn-danger btn-sm">View Ledger <span class="glyphicon glyphicon-send"></span></button>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<?php
		}
		?>
	</div>
	
	<?php 
	if ($ledger == NULL) {
		echo 
		'<div class="row">
			</table>
				<tr>
					<td colspan="2" align="center">
						<div class="form-group form-group-sm">
							<div class="alert alert-danger fade in">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<span class="glyphicon glyphicon-info-sign"></span> ATTENTION: '.$staff["first_name"].', Please choose the ledger account to display!
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>';
	}

	?>

		<div class="row">
		<div class="table-responsive">
			  <table class="table table-hover">
				<thead>
				<tr>
					<th colspan="9">
						<?php
							date_default_timezone_set('Africa/Lagos');
							$today = date('Y-m-d');
							
							$first_day_of_month = date('Y-m-01');
							list($fdid,$fdim,$fdiy) = explode("-",$first_day_of_month);
							$first_day_of_month = "$fdiy/$fdim/$fdid";
							
							$last_day_of_month = $today;
							list($ldid,$ldim,$ldiy) = explode("-",$last_day_of_month);
							$last_day_of_month = "$ldiy/$ldim/$ldid";
							
							if (isset($_GET['d1']) && isset($_GET['d2']) && isset($_GET['acct_id'])) {
								echo '<span style="font-size: 18px;"><span style="color:#ec7063;">Showing search result of entries between </span>'.$post_at.' <span style="color:#ec7063;">and</span> '.$post_at_to_date.'</span>';
							} elseif (isset($_GET['acct_id'])) {
								echo '<span style="font-size: 18px;"><span style="color:#ec7063;">Currently showing entries from </span>'.$first_day_of_month.' <span style="color:#ec7063;">to</span> '.$last_day_of_month.'</span>';
							} else {
								"";
							}
						?>
					</th>
				</tr>
				<tr>
					<div class="row">
						<form name="frmSearch" method="post" action="ledgers<?php echo $suffix; ?>_processing.php?d1=<?php if ( isset($_POST['btn_filter']) ) {$post_at = $_POST["search"]["post_at"]; echo $post_at;} ?>&d2=<?php if ( isset($_POST['btn_filter']) ) {$post_at_to_date = $_POST["search"]["post_at_to_date"]; echo $post_at_to_date;} ?>&acct_id=<?php if ( isset($_GET['acct_id']) ) {$acct_id = $_GET["acct_id"]; echo $acct_id;} ?>&" autocomplete="off">
							<div class="col-sm-8">
								<div class="col-sm-3">
									<div class="form-group form-group-sm">
										<div class="date">
											<div class="input-group input-append date" id="start_date">
											<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
												<input type="text" class="form-control input-sm" placeholder="From Date" id="post_at" name="search[post_at]"  value="<?php if ( isset($_POST['btn_filter']) ) { echo @$post_at;} if (isset($_GET['d1'])) {echo @$post_at;}?>" />
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-1"><strong>-</strong></div>
								<div class="col-sm-3">
									<div class="form-group form-group-sm">
										<div class="date">
											<div class="input-group input-append date" id="end_date">
												<input type="text" class="form-control input-sm" placeholder="To Date" id="post_at_to_date" name="search[post_at_to_date]" style="margin-left:10px"  value="<?php if ( isset($_POST['btn_filter']) ) {echo @$post_at_to_date;} if (isset($_GET['d2'])) {echo @$post_at_to_date;}?>" /> 
												<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-5">
									<input type="submit" class="btn btn-success btn-sm" name="btn_filter" value="Show Range" />
									<a href="ledgers.php?acct_id=<?php if ( isset($_GET['acct_id']) ) {$acct_id = $_GET["acct_id"]; echo $acct_id;} ?>&" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-signal"></span> View Current Month</a>
								</div>
							</div>
						</form>
					</div>
				</tr>
				</thead>
			</table>
			
			<table id="ledger" class="display" style="width:100%">
				<thead>
				<tr class="info">
					<th class="danger">S/N</th>
					<th class="danger">VALUE DATE</th>
					<th class="danger">DATE ON </br>RECEIPT</th>
					<th class="danger">REF/CHEQUE NO</th>
					<th class="danger">JOURNAL NO</th>
					<th class="danger">RECEIPT NO</th>
					<th class="danger">TRANSACTION DESCRIPTION</th>
					<th class="danger text-right">DEBIT AMOUNT </br>(&#8358;)</th>
					<th class="danger text-right">CREDIT AMOUNT </br>(&#8358;)</th>
					<th class="danger text-right">BALANCE </br>(&#8358;)</th>
				</tr>
				</thead>
				
				<?php
					$i = 1;
					
					if (isset($_GET['d1']) && isset($_GET['d2']) && isset($_GET['acct_id'])) {
						//Check if a date range has been selected and determine the start date of the range.
						$filtered_query_year = $_GET["d1"];
						list($fild,$film,$fily) = explode("/",$filtered_query_year);
						$filtered_query_date = "$fily-$film-$fild";
						
						//Query to sum all the credit and debit balances of the previous year
						$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
						$query .= "FROM $ledger ";
						$query .= "WHERE approval_status = 'Approved' ";
						$query .= "AND date < '$filtered_query_date'";
						$bf_sum = @mysqli_query($dbcon,$query);
						$bf_total = @mysqli_fetch_array($bf_sum, MYSQLI_ASSOC);

						$bf_debits = $bf_total['debits'];
						$bf_credits = $bf_total['credits'];	
						$bf_balance = ($bf_debits - $bf_credits);
					}
					
					else {
						//Query to sum all the credit and debit balances of the periods before the current month.
						$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
						$query .= "FROM $ledger ";
						$query .= "WHERE approval_status = 'Approved' ";
						$query .= "AND date < DATE_FORMAT(NOW() ,'%Y-%m-01') ";
						$bf_sum = @mysqli_query($dbcon,$query);
						$bf_total = @mysqli_fetch_array($bf_sum, MYSQLI_ASSOC);

						$bf_debits = $bf_total['debits'];
						$bf_credits = $bf_total['credits'];	
						$bf_balance = ($bf_debits - $bf_credits);
					}
				?>
				<tbody>	
					<tr>
						<td><?php echo $i.'.'; ?></td>
						<td></td>
						<td></td>
						<th></th> 
						<td></td>
						<td></td>
						
						<td><?php echo 'B/F'; ?></td>
						
						<td class="info"></td>
						<td class="info"></td>
						
						<th class="info text-right">
							<?php
								$bf_total_balance = number_format((float)$bf_balance, 2);	
								echo $bf_total_balance;
							?>
						</th>
					</tr>
				
					<?php
						$query = "SELECT * ";
						$query .= "FROM $ledger ";
						$query .= "WHERE approval_status = 'Approved' ";
						$query .= "AND (YEAR(date) = YEAR(NOW()) AND MONTH(date) = MONTH(NOW())) ";
						$query .= "ORDER BY date DESC";
						$post_all_set = @mysqli_query($dbcon,$query);
						$post_no = @mysqli_num_rows($post_all_set);

						if(!empty($post_filter_set)){
							$selected_ledger_set = $post_filter_set;
						} else {
							$selected_ledger_set = $post_all_set;
						}

						while ($selected_ledger = @mysqli_fetch_array($selected_ledger_set, MYSQLI_ASSOC)) {
							$txref = $selected_ledger["id"];	
					?>
					
					<tr>
						<td><?php if(isset($_GET["ref"]) && $txref==$_GET["ref"]) {echo '<a class="anchor" name="txref"></a>';} ?><?php echo ++$i.'.'; ?></td>
						<td <?php if(isset($_GET["ref"]) && $txref==$_GET["ref"]) {echo 'class="danger"';} ?>>
							<?php 
								list($tiy,$tim,$tid) = explode("-",$selected_ledger["date"]);
								$date = "$tid/$tim/$tiy";
								echo $date;
							?>
						</td>
						<td>
							<?php 
								if(isset($selected_ledger["date_on_receipt"]) && $selected_ledger["date_on_receipt"] !="0000-00-00") {
									list($tiy,$tim,$tid) = explode("-",@$selected_ledger["date_on_receipt"]);
									@$date_on_receipt = "$tid/$tim/$tiy";
									echo @$date_on_receipt;
								}
							?>
						</td>
						<th <?php if(isset($_GET["ref"]) && $txref==$_GET["ref"]) {echo 'class="danger"';} ?>><?php echo $selected_ledger["ref_cheque_no"]; ?></th> 
						<td <?php if(isset($_GET["ref"]) && $txref==$_GET["ref"]) {echo 'class="danger"';} ?>><?php echo $selected_ledger["journal_no"]; ?></td>
						<td <?php if(isset($_GET["ref"]) && $txref==$_GET["ref"]) {echo 'class="danger"';} ?>><?php echo $selected_ledger["receipt_no"]; ?></td>
						<td <?php if(isset($_GET["ref"]) && $txref==$_GET["ref"]) {echo 'class="danger"';} ?>><a href="javascript:txdetails('<?php echo $txref; ?>')"><?php echo strtoupper($selected_ledger["trans_desc"]); ?></a></td>
						
						
						<td <?php if(isset($_GET["ref"]) && $txref==$_GET["ref"]) {echo 'class="danger text-right"';} else {echo 'class="info text-right"';} ?>>
							<?php
								if (empty($selected_ledger['debit_amount'])) {
								$debit_amount = "";
								} else {  
								$debitamount = $selected_ledger['debit_amount'];
								$debit_amount = number_format((float)$debitamount, 2);
								echo $debit_amount;
								}
							?>
						</td>
						<td <?php if(isset($_GET["ref"]) && $txref==$_GET["ref"]) {echo 'class="danger text-right"';} else {echo 'class="info text-right"';} ?>>
							<?php
								if (empty($selected_ledger['credit_amount'])) {
								$credit_amount = "";
								} else {
								$creditamount = $selected_ledger['credit_amount'];
								$credit_amount = number_format((float)$creditamount, 2);
								echo $credit_amount;
								}
							?>
						</td>
							
						<td <?php if(isset($_GET["ref"]) && $txref==$_GET["ref"]) {echo 'class="danger text-right"';} else {echo 'class="info"';} ?>>
							<?php
								if (empty($selected_ledger['balance'])) {
								$balance = "";
								} else {
								$bal = $selected_ledger['balance'];
								$balance = number_format((float)$bal, 2);
								echo $balance;
								}
							?>
						</td>
						
					<?php	 
					 }
					?>
					</tr>
					</tbody>
					
					<?php
						if (isset($_GET['d1']) && isset($_GET['d2']) && isset($_GET['acct_id'])) {
							
							//Check if a date range has been selected and determine the start date of the range.
							$filtered_query_year = $_GET["d1"];
							list($fild,$film,$fily) = explode("/",$filtered_query_year);
							$filtered_query_date = "$fily-$film-$fild";
							

							//Query to sum all the credit and debit balances of the previous year
							$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
							$query .= "FROM $ledger ";
							$query .= "WHERE approval_status = 'Approved' ";
							$query .= "AND date < '$filtered_query_date'";
							$bf_sum = @mysqli_query($dbcon,$query);
							$bf_total = @mysqli_fetch_array($bf_sum, MYSQLI_ASSOC);

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
							$sum = @mysqli_query($dbcon,$query);
							$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);

							$debits = $total['debits'];
							if (($bf_balance >= 0)) {
								$new_debits = $debits + $bf_balance;	
							}else {
								$new_debits = $debits;
							}
							
							$credits = $total['credits'];
							if (($bf_balance < 0)) {
								$new_credits = $credits + -$bf_balance;	
							}else {
								$new_credits = $credits;
							}
							 
							$new_balance = ($new_debits - $new_credits);
						} else {
							
							$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
							$query .= "FROM $ledger ";
							$query .= "WHERE approval_status = 'Approved' ";
							$query .= "AND date < DATE_FORMAT(NOW() ,'%Y-%m-01') ";
							$bf_sum = @mysqli_query($dbcon,$query);
							$bf_total = @mysqli_fetch_array($bf_sum, MYSQLI_ASSOC);

							$bf_debits = $bf_total['debits'];
							$bf_credits = $bf_total['credits'];	
							$bf_balance = ($bf_debits - $bf_credits);						
							
							$query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
							$query .= "FROM $ledger ";
							$query .= "WHERE approval_status = 'Approved' ";
							$query .= "AND (YEAR(date) = YEAR(NOW()) AND MONTH(date) = MONTH(NOW())) ";
							$sum = @mysqli_query($dbcon,$query);
							$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);

							$debits = $total['debits'];
							if (($bf_balance >= 0)) {
								$new_debits = $debits + $bf_balance;	
							}else {
								$new_debits = $debits;
							}
							
							$credits = $total['credits'];
							if (($bf_balance < 0)) {
								$new_credits = $credits + -$bf_balance;	
							}else {
								$new_credits = $credits;
							}
							
							$new_balance = ($new_debits - $new_credits);
						}
					?>
					<tfoot>
					<tr>
						<td colspan="7"></td>
						<th class="danger text-danger text-right">
							<?php
								$total_debit = number_format((float)$debits, 2);
								echo $total_debit;
							?>
						</th>
						
						<th class="danger text-danger text-right">
							<?php 
								$total_credit = number_format((float)$credits, 2);
								echo $total_credit;
							?>
						</th>
							
						<th class="danger text-danger text-right">
							<?php 
								$total_balance = number_format((float)$new_balance, 2);
								echo $total_balance;
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
</html>
<script type="text/javascript">
function txdetails(id)
{
	if(confirm)
	{
		window.location.href='transaction_details<?php echo $suffix; ?>.php?txref='+id;
	}
}

$(document).ready(function() {
    $('#ledger').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            <?php
			if ($menu["level"] == "ce" || $menu["user_id"] == "40" || $menu["user_id"] == "170") {
			?>
				{ extend: 'copyHtml5', footer: true },
				{ extend: 'excelHtml5', footer: true },
				{ extend: 'pdfHtml5', footer: true },
			<?php
			}
			?>
			{ extend: 'print', footer: true }
        ],
		pageLength: 500000,
    } );
} );
</script>