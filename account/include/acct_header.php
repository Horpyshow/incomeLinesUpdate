<h5><strong>Wealth Creation: </strong>
		<?php
			$current_date = date('d-m-Y');
			$current_date = str_replace('-', '/', $current_date);
			
			//echo $current_date.' '.'</br>';
			
			$tquery = "SELECT * ";
			$tquery .= "FROM staffs ";
			$tquery .= "WHERE department = 'Wealth Creation' ";
			$tquery .= "AND level = 'leasing officer' ";
			$tquery .= "ORDER BY first_name ASC";
			
			$tresult = mysqli_query($dbcon, $tquery);

			if (!$tresult) {
			die ("Database search query failed");
			}
			while ($tstaff = mysqli_fetch_array($tresult, MYSQLI_ASSOC)) {
			$staff_id = $tstaff["user_id"];
			
			/*
			$query = "SELECT SUM(amount_paid) as amount_posted ";
			$query .= "FROM {$prefix}account_general_transaction_new ";
			$query .= "WHERE posting_officer_id = '$staff_id' " ;
			$query .= "AND leasing_post_status = 'Pending'";
			$sum = @mysqli_query($dbcon,$query);
			$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);
			
			$till = $total['amount_posted'];
			
			
			$declined_query = "SELECT SUM(amount_paid) as amount_posted ";
			$declined_query .= "FROM {$prefix}account_general_transaction_new ";
			$declined_query .= "WHERE posting_officer_id = '$staff_id' ";
			$declined_query .= "AND leasing_post_status = 'Declined'";
			$dsum = @mysqli_query($dbcon,$declined_query);
			$dtotal = @mysqli_fetch_array($dsum, MYSQLI_ASSOC);
			
			$till_declined = $dtotal['amount_posted'];
			
			$total_till = ($till + $till_declined);
			$total_till = number_format((float)$total_till, 2);
			*/
			echo '<a href="../leasing/view_trans.php?staff_id='.$staff_id.'" class="btn btn-sm btn-default">'.$tstaff["first_name"].'</a> ';
			}
		?>
		</h5>
		<h5><strong>Account Dept: </strong>
		<?php
			$current_date = date('d-m-Y');
			$current_date = str_replace('-', '/', $current_date);
			
			$tquery = "SELECT user_id, first_name, department, level ";
			$tquery .= "FROM staffs ";
			$tquery .= "WHERE department = 'Accounts' ";
			$tquery .= "ORDER BY first_name ASC";
			
			$tresult = mysqli_query($dbcon, $tquery);

			if (!$tresult) {
			die ("Database search query failed");
			}
			while ($tstaff = mysqli_fetch_array($tresult, MYSQLI_ASSOC)) {
			$staff_id = $tstaff["user_id"];
			
			/*
			$query = "SELECT SUM(amount_paid) as amount_posted ";
			$query .= "FROM {$prefix}account_general_transaction_new ";
			$query .= "WHERE posting_officer_id = '$staff_id' " ;
			$query .= "AND approval_status = 'Pending'";
			$sum = @mysqli_query($dbcon,$query);
			$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);
			
			$till = $total['amount_posted'];
			
			
			$declined_query = "SELECT SUM(amount_paid) as amount_posted ";
			$declined_query .= "FROM {$prefix}account_general_transaction_new ";
			$declined_query .= "WHERE posting_officer_id = '$staff_id' ";
			$declined_query .= "AND approval_status = 'Declined'";
			$dsum = @mysqli_query($dbcon,$declined_query);
			$dtotal = @mysqli_fetch_array($dsum, MYSQLI_ASSOC);
			
			$till_declined = $dtotal['amount_posted'];
			
			$total_till = ($till + $till_declined);
			$total_till = number_format((float)$total_till, 2);
			*/
			
			echo '<a href="acct_view_trans.php?staff_id='.$staff_id.'" class="btn btn-sm btn-default">'.$tstaff["first_name"].'</a> ';
			}
		?>
		</h5>
		<?php
			$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
			$query .= "WHERE leasing_post_status = 'Pending' ";
			//$query .= "OR approval_status = 'Pending'";
			$result = mysqli_query($dbcon, $query);
			$leasing_post = mysqli_fetch_array($result);
			$no_of_leasing_post = $leasing_post[0];
			
			$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
			$query .= "WHERE approval_status = 'Pending'";
			$result = mysqli_query($dbcon, $query);
			$fc_approvals = mysqli_fetch_array($result);
			$fc_pending_approvals = $fc_approvals[0];
			
			//$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
			//$query .= "WHERE approval_status = 'Approved' ";
			//$query .= "AND verification_status = 'Pending'";
			//$result = mysqli_query($dbcon, $query);
			//$auditor_approvals = mysqli_fetch_array($result);
			//$auditor_pending_approvals = $auditor_approvals[0];
			
			$query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
			$query .= "WHERE approval_status = 'Declined' ";
			$query .= "OR verification_status = 'Declined' ";
			$query .= "OR leasing_post_status = 'Declined'";
			$result = mysqli_query($dbcon, $query);
			$declined_trans = mysqli_fetch_array($result);
			$all_declined_trans = $declined_trans[0];
			
			/*
			$fquery = "SELECT COUNT(id) FROM {$prefix}account_flagged_record ";
			$fquery .= "WHERE flag_status != 'Resolution Confirmed' ";
			$fresult = mysqli_query($dbcon, $fquery);
			$flag_trans = @mysqli_fetch_array($fresult);
			$all_flagged_trans = $flag_trans[0];
			*/
		?>
		
		<h3 style="line-height: 30px;">
			<div class="row">
				<div class="col-sm-9">
					<?php echo '<a href="view_trans.php" class="btn btn-danger btn-sm">'.$no_of_leasing_post.': Pending Posts</a>'; ?> 
					<?php echo '<a href="pending_fc_approvals'.$suffix.'.php" class="btn btn-warning btn-sm">'.$fc_pending_approvals.': Pending FC Approvals</a>'; ?>
					<?php echo '<a href="../../mod/audit/pending_audit_approvals'.$suffix.'.php" class="btn btn-success btn-sm">Pending Auditor\'s Account Verifications</a>'; ?>
					<?php echo '<a href="view_trans_'.$prefix.'declined.php" class="btn btn-danger btn-sm">'.$all_declined_trans.': Declined Transactions</a>'; ?>
					<?php //echo '<a href="flagged_record'.$suffix.'.php" class="btn btn-warning btn-sm">'.$all_flagged_trans.': Flagged Records</a>'; ?>
				</div>
				<div class="col-sm-3">
				<?php
				if(@$page_department=="Audit") {
					echo '<form method="POST" action="../account/search'.$suffix.'_processing.php?sr='; if (isset($_POST["btn-search"])) {$search = $_POST["search"];}  echo '" autocomplete="off" >';
				} else {
					echo '<form method="POST" action="search'.$suffix.'_processing.php?sr='; if (isset($_POST["btn-search"])) {$search = $_POST["search"];}  echo '" autocomplete="off" >';
				}
				echo '
						<div class="input-group col-sm-12">
							<input type="text" class="search-query form-control input-sm" name="search" placeholder="Search" value="'; ?><?php if (isset($_POST["btn-search"])) { echo $search; } echo '" required />
							<span class="input-group-btn">
								<button class="btn btn-danger btn-sm" type="submit" name="btn-search">
									<span class=" glyphicon glyphicon-search"></span>
								</button>
							</span>
						</div>
					</form>';
				?>
				</div>
			</div>
		</h3>
		
		<?php include ('include/countdown_script.php'); ?>
		
		<?php
		/*		
			$queryd = "SELECT * FROM {$prefix}account_general_transaction_new ";
			$queryd .= "WHERE leasing_post_status = 'Declined' AND HOUR(TIMEDIFF(NOW(), leasing_post_approval_time))>24 ";
			$queryd .= "OR (approval_status = 'Declined' AND HOUR(TIMEDIFF(NOW(), approval_time))>24) ";
			$queryd .= "OR (verification_status = 'Declined' AND HOUR(TIMEDIFF(NOW(), verification_time))>24) ";
			$queryd .= "ORDER BY date_of_payment DESC ";   
			$resultd = mysqli_query($dbcon, $queryd);
			
			$all_long_declined_trans = mysqli_num_rows($resultd);
			
			if ($all_long_declined_trans != 0) {
				echo '<h4 style="line-height: 30px; color:#ec7063;"><strong>FC approval right has been automatically DISABLED due to '.$all_long_declined_trans.' declined transactions in the system for more than 24hrs!</strong></h4>';
			}
		*/
		
		if(isset($_SESSION['staff']) ) {
			$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['staff'];
		}
		if(isset($_SESSION['admin']) ) {
			$staffquery = "SELECT * FROM staffs WHERE user_id=".$_SESSION['admin'];
		}
		$staffresult = mysqli_query($dbcon, $staffquery);
		$session_staff = mysqli_fetch_array($staffresult, MYSQLI_ASSOC);
		$session_id = $session_staff['user_id'];
		
		$ac_dcount_query = "SELECT COUNT(id) FROM {$prefix}account_general_transaction_new ";
		$ac_dcount_query .= "WHERE leasing_post_approving_officer_id = '$session_id' ";
		$ac_dcount_query .= "AND leasing_post_status = 'Approved' ";
		$ac_dcount_query .= "AND (approval_status = 'Declined' OR verification_status = 'Declined')";
		$ac_result = @mysqli_query($dbcon, $ac_dcount_query);
		$account_post = @mysqli_fetch_array($ac_result);
		$no_of_declined_acct_post = $account_post[0];
		?>