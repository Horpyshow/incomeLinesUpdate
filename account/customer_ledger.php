<?php
include 'include/session.php';

if(isset($_SESSION['staff']) ) {
$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['staff'];
}
if(isset($_SESSION['admin']) ) {
$query = "SELECT * FROM roles WHERE user_id=".$_SESSION['admin'];
}
$role_set = mysqli_query($dbcon, $query);
$role = mysqli_fetch_array($role_set, MYSQLI_ASSOC);

if ($role['acct_view_record'] != "Yes") {
?>
	<script type="text/javascript">
		alert('You do not have permissions to view this page! Contact your HOD for authorization. Thanks');
		window.location.href='../../../index.php';
	</script>
<?php
}

?>

<?php
$ledger = NULL;

/*
	1001 = account_ledger_fidelity_1
	1002 = account_ledger_cash_on_hand
	4001 = account_ledger_turnover
*/

if (isset($_POST['btn_view_ledger'])) {
	
	$account_id = @$_POST['ledger_account'];
	
	If ($account_id == 10250) {
		$ledger = "account_ledger_fidelity";
	} 
	if ($account_id == 10100) {
		$ledger = "account_ledger_cash_on_hand";
	}
	if ($account_id == 40000) {
		$ledger = "account_ledger_turnover";
	}
}
elseif (isset($_GET['id'])) {
	
	$account_id = @$_GET['id'];
	
	If ($account_id == 10250) {
		$ledger = "account_ledger_fidelity";
	} 
	if ($account_id == 10100) {
		$ledger = "account_ledger_cash_on_hand";
	}
	if ($account_id == 40000) {
		$ledger = "account_ledger_turnover";
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Woobs Resources ERP</title>
		<meta name="description" content="Woobs Resources ERP Management System">
		<meta name="author" content="Woobs Resources Ltd">
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<script src="../../js/jquery.js"></script>
		<script src="../../js/bootstrap.js"></script>
		<script src="../../js/sub_menu.js"></script>
		<link rel="stylesheet" href="../../css/sub_menu.css">

<script type="text/javascript">
function edit_id(id)
{
	if(confirm('Are you sure you want to edit details?'))
	{
		window.location.href='update_staff.php?edit_id='+id;
	}
}
function delete_id(id)
{
	if(confirm('Are you sure you want to COMPLETELY DELETE details?'))
	{
		window.location.href='manage_staff.php?delete_id='+id;
	}
}
function details_id(id)
{
	if(confirm)
	{
		window.location.href='staff_details.php?details_id='+id;
	}
}
function assign_id(id)
{
	if(confirm)
	{
		window.location.href='../admin/assign_roles.php?assign_id='+id;
	}
}
</script>
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
	<div class="col-md-2"><?php include ('include/staff_side_navbar.php'); ?></div>

	<div class="col-md-10">
	<div class="row">
		<div class="col-md-6">
			<?php
				$query = "SELECT * ";
				$query .= "FROM $ledger ";
				$query .= "ORDER BY date ASC";
				$selected_account_set = mysqli_query($dbcon, $query);
				$selected_account = @mysqli_fetch_array($selected_account_set, MYSQLI_ASSOC);
				
				$acct_id = $selected_account["acct_id"]
			
			?>
			
			<?php
				$query = "SELECT * ";
				$query .= "FROM accounts ";
				$query .= "WHERE acct_id = '$acct_id'";
				$query .= "LIMIT 1";
				$account_set = mysqli_query($dbcon, $query);
				$account = mysqli_fetch_array($account_set, MYSQLI_ASSOC);
			
			?>
			<div class="page-header">
				<h1><?php echo $account['acct_type']; ?> Account Ledger<small></small></h1>
				<h3>Account Description: <?php echo $account['acct_desc']; ?> <small>Account ID: <?php echo $account['acct_id']; ?></small></h3>
				<a href="view_trans.php" class="btn btn-success"><span class="glyphicon glyphicon-eye-open"></span> View Posted Transactions</a></br>
			</div>
		
		</div>
		<div class="col-md-6">
			<form  method="post" id="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
				<table class="table table-hover table-bordered">
					<thead>
						<tr class="success">
							<th colspan="3" class="text-right">Woobs Resources Account Ledgers</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th colspan="2">
								<!-- Select Basic -->			   
								<div class="form-group form-group-sm"> 
									<div class="col-md-12 selectContainer">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></span>
											<select name="ledger_account" class="form-control selectpicker" id="ledger_account">
											  <option value="">Choose a ledger to view</option>
												<?php
												$query = "SELECT * FROM accounts ";
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
										<button type="submit" name="btn_view_ledger" class="btn btn-danger">View Ledger <span class="glyphicon glyphicon-send"></span></button>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
			
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
				<tr class="info text-info">
				<th colspan="6"></th>
				<th colspan="3">
				
				<form method="POST" action="search.php">
					<div class="input-group col-md-12">
						<input type="text" class="search-query form-control" name="search" placeholder="Search" value="<?php echo @$search; ?>" />
						<span class="input-group-btn">
							<button class="btn btn-primary" type="submit" name="btn-search">
								<span class=" glyphicon glyphicon-search"></span>
							</button>
						</span>
					</div>
				
				</form>
				</th>
				</tr>
				</thead>
				<thead>
				<tr class="info">
					<th class="danger">S/N</th>
					<th class="danger">DATE</th>
					<th class="danger">REF/CHEQUE NO</th>
					<th class="danger">JOURNAL NO</th>
					<th class="danger">RECEIPT NO</th>
					<th class="danger">TRANSACTION DESCRIPTION</th>
					<th class="danger">DEBIT AMOUNT</th>
					<th class="danger">CREDIT AMOUNT</th>
					<th class="danger">BALANCE</th>
				</tr>
				</thead>
					<?php
					//Set the number of rows per display
					$pagerows = 100;
					
					//Has the total number of pages already been calculated?
					if (isset($_GET['p']) && is_numeric($_GET['p'])) {
						$pages = $_GET['p'];
					} else {
					//First, check for the total number of records
						$q = "SELECT COUNT(user_id) FROM staffs";
						$result = mysqli_query($dbcon,$q);
						$row = mysqli_fetch_array($result);
						$records = $row[0];
						
					//Now calculate the number of pages
					if ($records > $pagerows) {
						$pages = ceil($records/$pagerows);
					} else {
						$pages = 1;
					}
					}
					
					
					//Declare which record to start with
					if (isset($_GET['s']) && is_numeric($_GET['s'])) {
						$start = $_GET['s'];
					} else {
						$start = 0;
					}
					?>
									
					<?php
					
					
					  $i = 0;
					 
					 $query = "SELECT * ";
					 $query .= "FROM $ledger ";
					 $query .= "WHERE approval_status = 'Approved' ";
					 $query .= "ORDER BY date ASC ";
					 $query .= "LIMIT $start, $pagerows";
					 $selected_ledger_set = @mysqli_query($dbcon,$query);
					 $post_no = @mysqli_num_rows($selected_ledger_set);
					 
					 while ($selected_ledger = @mysqli_fetch_array($selected_ledger_set, MYSQLI_ASSOC)) {
						
					?>
					
					<tr>
						<td><?php echo ++$i; ?></td>
						<td><?php echo $selected_ledger["date"]; ?></td>
						<th><?php echo $selected_ledger["ref_cheque_no"]; ?></th> 

						<td><?php echo $selected_ledger["journal_no"]; ?></td>
						<td><?php echo $selected_ledger["receipt_no"]; ?></td>
						<td><?php echo $selected_ledger["trans_desc"]; ?></td>
						
						<td class="info">
							<?php
								if (empty($selected_ledger['debit_amount'])) {
								$debit_amount = "";
								} else {  
								$debitamount = $selected_ledger['debit_amount'];
								$debit_amount = number_format((int)$debitamount, 0);
								echo "&#8358 {$debit_amount}";
								}
							?>
						</td>
						<td class="info">
							<?php
								if (empty($selected_ledger['credit_amount'])) {
								$credit_amount = "";
								} else {
								$creditamount = $selected_ledger['credit_amount'];
								$credit_amount = number_format((int)$creditamount, 0);
								echo "&#8358 {$credit_amount}";
								}
							?>
						</td>
							
						<td class="info">
							<?php
								if (empty($selected_ledger['balance'])) {
								$balance = "";
								} else {
								$bal = $selected_ledger['balance'];
								$balance = number_format((int)$bal, 0);
								echo "&#8358 {$balance}";
								}
							?>
						</td>
						
					
					<?php	 
					 }
					?>
					</tr>
					
					<?php
					  $i = 0;
					 
					 $query = "SELECT SUM(debit_amount) as debits, SUM(credit_amount) as credits, SUM(balance) as balances ";
					 $query .= "FROM $ledger ";
					 $query .= "WHERE approval_status = 'Approved'";
					 $sum = @mysqli_query($dbcon,$query);
					 $total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);
						
					?>
					<tr>
						<td colspan="6"></td>
						<th class="danger text-danger">
							<?php 
								$debits = $total['debits'];
								$total_debit = number_format((int)$debits, 0);
								echo "&#8358 {$total_debit}";
							?>
						</th>
						
						<th class="danger text-danger">
							<?php 
								$credits = $total['credits'];
								$total_credit = number_format((int)$credits, 0);
								echo "&#8358 {$total_credit}";
							?>
						</th>
						
						<th class="danger text-danger">
							<?php 
								$balances = $total['balances'];
								$total_balance = number_format((int)$balances, 0);
								echo "&#8358 {$total_balance}";
							?>
						</th>
					</tr>
					
					
					<tr class="text-info">
						<td colspan="6">
							
						</td>
						<td class="text-right" colspan="3">
							<?php
							$q = "SELECT COUNT(user_id) FROM staffs";
							$result = mysqli_query($dbcon,$q);
							$row = mysqli_fetch_array($result);
							$staff_no = $row[0];
							
							//echo "<p>Total number of registered staff: $staff_no</p>";
							if ($pages > 1){
								echo '<p>';
								
								//What number is the current page?
								$current_page = ($start/$pagerows) + 1;
								
								//If the page is not the first page then create a Previous link
								if ($current_page != 1){
									echo '<a href="manage_staff.php?s=' .  ($start - $pagerows) . '&p=' . $pages . '" class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Previous</a> ';
								}
								
								//Create a Next link
								if ($current_page != $pages){
									echo '<a href="manage_staff.php?s=' .  ($start + $pagerows) . '&p=' . $pages . '" class="btn btn-primary">Next <span class="glyphicon glyphicon-forward"></span></a> ';
								}
								echo '</p>';
							}
							
							?>
						</td>
					</tr>
			  </table>
			</div>
		</div>
			

	</div>
</div>
<script type="text/javascript">
function approve_id(id)
{
	if(confirm('Are you sure you want to APPROVE Payment?'))
	{
		window.location.href='view_postings.php?approve_id='+id;
	}
}
function reject_id(id)
{
	if(confirm('Are you sure you want to REJECT this Payment?'))
	{
		window.location.href='view_postings.php?reject_id='+id;
	}
}
</script>
</body>
</html>