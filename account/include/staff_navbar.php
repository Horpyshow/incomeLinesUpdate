<div>
	<nav class="navbar navbar-inverse navbar-fixed-top">
	 <!--<nav class="navbar navbar-default navbar-fixed-top"> -->
			<div class="navbar-header">
			    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="../../index.php">Dashboard</a></li>
				</ul>
				
				<?php 
				
				$query = "SELECT * ";
				$query .= "FROM staffs ";
				
				if(isset($_SESSION['admin']) ) {
				$query .= "WHERE user_id =".$_SESSION['admin'];
				}
				if(isset($_SESSION['staff']) ) {
				$query .= "WHERE user_id =".$_SESSION['staff'];	
				}
				$menu_set = mysqli_query($dbcon, $query);
				$menu = mysqli_fetch_array($menu_set, MYSQLI_ASSOC);
				

/*****************************************************
Chief Executive Exclusive Navigation begins here	
*****************************************************/
			
				if ($menu['level'] == "ce") {
				
				echo 
					'
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-transfer"></span> Transactions <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="view_trans.php"><span class="glyphicon glyphicon-tasks"></span> View Transactions</a></li>
								<li><a href="../leasing/trans_analysis.php"><span class="glyphicon glyphicon-th-list"></span> Print Analysis</a></li>
								<li><a href="#"><span class="glyphicon glyphicon-tasks"></span> Kclamp Rent</a></li>
								<li><a href="#"><span class="glyphicon glyphicon-tasks"></span> Kclamp Service Charge</a></li>
								<li><a href="payments.php"><span class="glyphicon glyphicon-tasks"></span> Payments</a></li>
								<li><a href="journal_entry.php"><span class="glyphicon glyphicon-tasks"></span> Journal Entry</a></li>
							</ul>
						</li>
					</ul>
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-list-alt"></span> Financial Reports <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="ledgers.php"><span class="glyphicon glyphicon-th-list"></span> General Ledgers</a></li>
								<li><a href="trial_balance.php"><span class="glyphicon glyphicon-th-list"></span> Trial Balance</a></li>
								<li><a href="profit_loss.php"><span class="glyphicon glyphicon-th-list"></span> Income Statement</a></li>
								<li><a href="account/balance_sheet.php"><span class="glyphicon glyphicon-th-list"></span> Financial Position</a></li>
							</ul>
						</li>
					</ul>
					
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-stats"></span> Chart of Accounts <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="acct_chart.php"><span class="glyphicon glyphicon-stats"></span> Account Chart</a></li>
							</ul>
						</li>
					</ul>
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-transfer"></span> Customers <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="../leasing/manage_customer.php"><span class="glyphicon glyphicon-tasks"></span> Manage Customers</a></li>
							</ul>
						</li>
					</ul>
					';
					}  

				
/*****************************************************
Account Department Exclusive Navigation begins here	
*****************************************************/
			

				if ($menu['department'] == "Accounts") {
				
				echo 
					'<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-transfer"></span> Transactions <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="view_trans.php"><span class="glyphicon glyphicon-tasks"></span> View Transactions</a></li>
								<li><a href="post_trans.php"><span class="glyphicon glyphicon-tasks"></span> Kclamp/Coldroom/Container Rent</a></li>
								<li><a href="post_trans_sc.php"><span class="glyphicon glyphicon-tasks"></span> Kclamp/Coldroom/Container Service Charge</a></li>
								<li><a href="payments.php"><span class="glyphicon glyphicon-tasks"></span> Payments</a></li>
								<li><a href="journal_entry.php"><span class="glyphicon glyphicon-tasks"></span> Journal Entry</a></li>
							</ul>
						</li>
					</ul>
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-list-alt"></span> Financial Reports <span class="caret"></span></a>
							<ul class="dropdown-menu">
								
									<li><a href="ledgers.php"><span class="glyphicon glyphicon-th-list"></span> General Ledgers</a></li>';
							?>
							<?php 
								if ($menu['department'] == "Accounts" && ($menu['level'] == "fc" || $menu['level'] == "senior accountant")) {
								echo '
									<li><a href="trial_balance.php"><span class="glyphicon glyphicon-th-list"></span> Trial Balance</a></li>
									<li><a href="profit_loss.php"><span class="glyphicon glyphicon-th-list"></span> Income Statement</a></li>
									<li><a href="balance_sheet.php"><span class="glyphicon glyphicon-th-list"></span> Financial Position</a></li>
									';
								}
							?>
							<?php 
								echo '
									
							</ul>
						</li>
					</ul>
					
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-stats"></span> Chart of Accounts <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="acct_chart.php"><span class="glyphicon glyphicon-stats"></span> Account Chart</a></li>
							</ul>
						</li>
					</ul>';
				} 
				
				
/*****************************************************
Audit/Inspection Exclusive Navigation begins here	
*****************************************************/
				if ($menu['department'] == "Audit/Inspections") {
					echo '
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-transfer"></span> Account Dept <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="view_trans.php"><span class="glyphicon glyphicon-tasks"></span> View Transactions</a></li>
								<li><a href="../leasing/trans_analysis.php"><span class="glyphicon glyphicon-th-list"></span> Print Analysis</a></li>
								<li><a href="ledgers.php"><span class="glyphicon glyphicon-th-list"></span> General Ledgers</a></li>';
								?>
							<?php 
								if ($menu['department'] == "Audit/Inspections" && $menu['level'] == "Head, Audit & Inspection") {
								echo '
								<li><a href="trial_balance.php"><span class="glyphicon glyphicon-th-list"></span> Trial Balance</a></li>
								<li><a href="profit_loss.php"><span class="glyphicon glyphicon-th-list"></span> Income Statement</a></li>
								<li><a href="balance_sheet.php"><span class="glyphicon glyphicon-th-list"></span> Financial Position</a></li>';
								}
							?>
							<?php 
								echo '
							</ul>
						</li>
					</ul>
					
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-transfer"></span> Customers <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="../leasing/manage_customer.php"><span class="glyphicon glyphicon-tasks"></span> Manage Customers</a></li>
							</ul>
						</li>
					</ul>';
				
				}				

/*****************************************************
Leasing Department Exclusive Navigation begins here	
*****************************************************/
				if ($menu['department'] == "Wealth Creation") {
				
				echo 
					'<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" oncontextmenu="return false;">
							<span class="glyphicon glyphicon-user"></span> Wealth Department <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="../leasing/officers.php" oncontextmenu="return false;"><span class="glyphicon glyphicon-user"></span> Officers</a></li>
							</ul>
						</li>
					</ul>
					
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" oncontextmenu="return false;">
							<span class="glyphicon glyphicon-transfer"></span> Collections <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="../leasing/view_trans.php" oncontextmenu="return false;"><span class="glyphicon glyphicon-tasks"></span> View Transactions</a></li>
								
								<li><a href="../leasing/trans_analysis.php" oncontextmenu="return false;"><span class="glyphicon glyphicon-th-list"></span> Print Analysis</a></li>
								
								<li><a href="payments.php" oncontextmenu="return false;"><span class="glyphicon glyphicon-tasks"></span> Payments</a></li>
								
								<li><a href="../leasing/post_trans.php" oncontextmenu="return false;"><span class="glyphicon glyphicon-tasks"></span> Kclamp/Coldroom/Container Rent</a></li>
								
								<li><a href="../leasing/post_trans_sc.php" oncontextmenu="return false;"><span class="glyphicon glyphicon-tasks"></span> Kclamp/Coldroom/Container Service Charge</a></li>
							</ul>
						</li>
					</ul>
					
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" oncontextmenu="return false;">
							<span class="glyphicon glyphicon-transfer"></span> Customers <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="../leasing/manage_customer.php"><span class="glyphicon glyphicon-tasks"></span> Manage Customers</a></li>
							</ul>
						</li>
					</ul>';
				} 
				
/*****************************************************
DGM Exclusive Navigation begins here	
*****************************************************/
				if ($menu['level'] == "dgm") {
				
				echo 
					'
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-transfer"></span> Account Dept <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="../account/view_trans.php"><span class="glyphicon glyphicon-tasks"></span> View Transactions</a></li>
								<li><a href="../leasing/trans_analysis.php"><span class="glyphicon glyphicon-th-list"></span> Print Analysis</a></li>
								<li><a href="../account/ledgers.php"><span class="glyphicon glyphicon-th-list"></span> General Ledgers</a></li>
								<li><a href="../account/trial_balance.php"><span class="glyphicon glyphicon-th-list"></span> Trial Balance</a></li>
								<li><a href="../account/profit_loss.php"><span class="glyphicon glyphicon-th-list"></span> Income Statement</a></li>
								<li><a href="../account/balance_sheet.php"><span class="glyphicon glyphicon-th-list"></span> Financial Position</a></li>
							</ul>
						</li>
					</ul>';
					
				}
				
				
/*****************************************************
Risk Management Exclusive Navigation begins here	
*****************************************************/
				if ($menu['department'] == "Risk/Asset Management") {
				
				echo 
					'
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-home"></span> Shop Management <span class="caret"></span></a>
							<ul class="dropdown-menu">	
								<li><a href="../../mod/leasing/vacant_shops.php"><span class="glyphicon glyphicon-th-list"></span> Vacant Shops</a></li>
								<li><a href="../../mod/leasing/expired_shop_lease.php"><span class="glyphicon glyphicon-th-list"></span> Expired Shop Lease</a></li>
							</ul>
						</li>
					</ul>
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-user"></span> Customer Management <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="../../mod/leasing/manage_customer.php"><span class="glyphicon glyphicon-user"></span> Manage ALL Customers</a></li>
								<li><a href="../../mod/leasing/lease_application.php"><span class="glyphicon glyphicon-th-list"></span> Lease Application Dashboard</a></li>
							</ul>
						</li>
					</ul>
					
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-transfer"></span> WRL Accounts <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="view_trans.php"><span class="glyphicon glyphicon-th-list"></span> View WRL Transactions</a></li>
								<li><a href="ledgers.php"><span class="glyphicon glyphicon-th-list"></span> WRL General Ledgers</a></li>
								<li><a href="trial_balance.php"><span class="glyphicon glyphicon-th-list"></span> WRL Trial Balance</a></li>
								<li><a href="profit_loss.php"><span class="glyphicon glyphicon-th-list"></span> WRL Income Statement</a></li>
								<li><a href="balance_sheet.php"><span class="glyphicon glyphicon-th-list"></span> WRL Financial Position</a></li>
								<li><a href="acct_chart.php"><span class="glyphicon glyphicon-stats"></span> WRL Account Chart</a></li>
							</ul>
						</li>
					</ul>
					
					
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-transfer"></span> ARENA Accounts <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="view_trans_arena.php"><span class="glyphicon glyphicon-user"></span> View ARENA Transactions</a></li>
								<li><a href="ledgers_arena.php"><span class="glyphicon glyphicon-th-list"></span> ARENA General Ledgers</a></li>
								<li><a href="trial_balance_arena.php"><span class="glyphicon glyphicon-th-list"></span> ARENA Trial Balance</a></li>
								<li><a href="profit_loss_arena.php"><span class="glyphicon glyphicon-th-list"></span> ARENA Income Statement</a></li>
								<li><a href="balance_sheet_arena.php"><span class="glyphicon glyphicon-th-list"></span> ARENA Financial Position</a></li>
								<li><a href="acct_chart_arena.php"><span class="glyphicon glyphicon-stats"></span> ARENA Account Chart</a></li>
							</ul>
						</li>
					</ul>';
				}
				

/*****************************************************
IT/E-Business Exclusive Navigation begins here	
*****************************************************/
/*
				if ($menu['department'] == "IT/E-Business") {
				
				echo 
					'		
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-user"></span> Customer Management <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="../staff/mod/leasing/manage_customer.php"><span class="glyphicon glyphicon-user"></span> Manage ALL Customers</a></li>
								<li><a href="../staff/mod/leasing/lease_application.php"><span class="glyphicon glyphicon-th-list"></span> Lease Application Dashboard</a></li>
								<li><a href="../staff/mod/leasing/register_customer.php"><span class="glyphicon glyphicon-user"></span> Create New Customer</a></li>
							</ul>
						</li>
					</ul>';
				}
*/			
				?>


				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<span class="glyphicon glyphicon-user"></span>&nbsp; &nbsp;</a>
					  
					</li>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						&nbsp;Hello <?php echo $staff['full_name']; ?>!&nbsp;<span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="../../profile.php"><span class="glyphicon glyphicon-th-list"></span>&nbsp;Profile</a></li>
						<li><a href="../../update_password.php"><span class="glyphicon glyphicon-lock"></span>&nbsp;Change Password</a></li>
						<li><a href="../../logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
					  </ul>
					</li>
				</ul>
			</div>
	</nav>
<div>