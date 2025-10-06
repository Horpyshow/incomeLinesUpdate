<form  method="post" id="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
	<fieldset>
		<input type="hidden" name="posting_officer_id" class="form-control" placeholder=" " value="<?php echo $staff['user_id']; ?>" maxlength="50" />
		<input type="hidden" name="posting_officer_name" class="form-control" placeholder=" " value="<?php echo $staff['full_name']; ?>" maxlength="50">
		<input type="hidden" name="income_line" value="<?= isset($_GET['income_line']) ? $_GET['income_line'] : 'scroll_board'; ?>" maxlength="50">
		<input type="hidden" name="posting_officer_dept" value="<?php echo $menu['department']; ?>">
		
		
		<table class="table table-bordered">
			<tr> 
			<td colspan="3">
				<?php include 'payments/remittance_form_inc.php'; ?>
	
				<div class="form-group form-group-sm">
					<label for="transaction_descr" class="control-label col-md-4">Transaction Description:</label>
					<div class="col-md-7 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
							<input type="text" name="transaction_descr" class="form-control input-sm" placeholder="Transaction description" value="<?= (isset($_POST['transaction_descr']) || isset($income_line_desc)) ? $income_line_desc : 'Scroll Board'; ?>" pattern=".{6,}" onBlur="loadCalc()" readonly />
						</div>
					</div>
				</div>
				
				<div class="form-group form-group-sm"> 
				  <label for="ticket_category" class="col-md-4 control-label">Scroll Board Location:</label>
				  <?php 
					// $queryboard = $dbcon->prepare("SELECT * FROM scroll_boards");
					// $queryboard->execute();
					// $scrollboards = $queryboard->get_result();

					$queryboard = $dbcon->prepare("SELECT * FROM scroll_boards");
					if ($queryboard->execute()) {
						$scrollboards = $queryboard->get_result();//$queryboard->fetchAll(PDO::FETCH_ASSOC);
					} else {
						$errorInfo = $queryboard->errorInfo();
						die("Query Error: " . $errorInfo[2]);
					}
				  ?>
				   <div class="col-md-4 selectContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
							<select name="board_name" class="form-control selectpicker" id="board_name" required >
							<option value="">Select Scrollboard Location</option>
							<?php foreach ($scrollboards as $scrollboard): ?>
							  <option value="<?php echo htmlspecialchars($scrollboard['board_name']);?>"><?php echo htmlspecialchars($scrollboard['board_location']);?></option>
							<?php endforeach; ?> 
							</select>
						</div>
					</div>
				</div>

				<div class="form-group form-group-sm">
					<label for="transaction_descr" class="control-label col-md-4">Allocated to</label>
					<div class="col-md-7 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input type="text" id="allocated_to" name="allocated_to" class="form-control input-sm" placeholder="Allocated Company" value="" readonly />
						</div>
					</div>
				</div>

				<div class="form-group form-group-sm">
					<label for="expected_rent_monthly" class="control-label col-md-4">Expected Monthly Rent:</label>
					<div class="col-md-4 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon">&#8358</span>
							<input type="text" name="expected_rent_monthly" id="expected_rent_monthly" class="form-control input-sm" placeholder="Expected Monthly Rent" value="" readonly />
						</div>
					</div>
				</div>

				<div class="form-group form-group-sm">
					<label for="expected_rent_yearly" class="control-label col-md-4">Expected Yearly Rent:</label>
					<div class="col-md-4 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon">&#8358</span>
							<input type="text" name="expected_rent_yearly" id="expected_rent_yearly" class="form-control input-sm" placeholder="Expected Yearly Rent" value="" readonly />
						</div>
					</div>
				</div>
	
				<div class="form-group form-group-sm">
					<label for="receipt_no" class="control-label col-md-4">Receipt No:</label>
					<div class="col-md-4 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
							<input type="text" name="receipt_no" class="form-control input-sm" placeholder="Receipt No" pattern="^\d{7}$" value="<?php if (isset($_POST['receipt_no'])) echo @$receipt_no; ?>" onBlur="loadCalc()" maxlength="7" required />
						</div>
					</div>
				</div>
						
						<!-- I have to concatinate month and year on submu=ittion e.g January 2025 -->
				<div class="form-group form-group-sm">
					<label for="amount_paid" class="control-label col-md-4">Amount Remitted:</label>
					<div class="col-md-4 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon">&#8358;</span>
							<input type="text" id="amount_paid" name="amount_paid" class="form-control input-sm" placeholder="Amount Remitted" value="<?php if (isset($_POST['amount_paid'])) echo @$amount_paid; ?>" onBlur="loadCalc()" maxlength="20" required />
						</div>
					</div>
				</div>

				<div class="form-group form-group-sm">
					<label for="receipt_no" class="control-label col-md-4">Date of Payment</label>
					<div class="col-md-4 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    		<input type="date" name="date_of_payment" class="form-control" required>
                		</div>
					</div>
				</div>

				<!-- Select Basic -->			   
				<div class="form-group form-group-sm"> 
					<label for="payment_type" class="col-md-4 control-label">Type of Payment:</label>
					<div class="col-md-5 selectContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-tasks"></i></span>
							<select name="payment_type" class="form-control selectpicker" id="payment_type" required>
								<option value=" ">Select...</option>
								<option <?php if (isset($_POST['payment_type']) && @$payment_type=="Cash") echo 'selected'; ?> value="Cash">Cash</option>
								<option <?php if (isset($_POST['payment_type']) && @$payment_type=="Cheque") echo 'selected'; ?> value="Cheque">Cheque</option>
								<option <?php if (isset($_POST['payment_type']) && @$payment_type=="Transfer") echo 'selected'; ?> value="Transfer">Transfer</option>
							</select>
						</div>
					</div>
				</div>

				<!-- Text input-->			
				<div class="form-group form-group-sm">
					<label for="add_transaction_desc" class="control-label col-md-3">Additional Narration (Optional):</label>
					<div class="col-md-7 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
							<input type="text" name="add_transaction_desc" class="form-control input-sm" placeholder="Transfer ONLY" value="" maxlength="100" />
						</div>
					</div>
				</div>
				
				<div class="form-group form-group-sm">
					<label for="receipt_no" class="control-label col-md-4">Rent Start Date</label>
					<div class="col-md-4 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    		<input type="date" name="start_date" class="form-control" required>
                		</div>
					</div>
				</div>

				<div class="form-group form-group-sm">
					<label for="receipt_no" class="control-label col-md-4">Rent End Date</label>
					<div class="col-md-4 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    		<input type="date" name="end_date" class="form-control" required>
                		</div>
					</div>
				</div>
                

				<!-- <div class="form-group form-group-sm">
					<label for="month" class="col-md-4 control-label">Payment for the Month:</label>
					<div class="col-md-5 selectContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<select id="payment_month" class="form-control selectpicker" name="payment_month">
								<?php
								// Define the selected month, defaulting to the current month if not set
								// $month = isset($_POST['payment_month']) ? $_POST['payment_month'] : date('F');

								// for ($i = 1; $i <= 12; $i++) {
								// 	$monthName = date('F', mktime(0, 0, 0, $i, 1));
								// 	echo "<option value='$monthName' " . ($month == $monthName ? "selected" : "") . ">$monthName</option>";
								// }
								?>
							</select>
						</div>
					</div>
				</div> -->

				
				<!-- <div class="form-group form-group-sm">
					<label for="year" class="col-md-4 control-label"> Payment Year: </label>
					<div class="col-md-5 selectContainer">
						<div class="input-group">
							<select id="payment_year" class="form-control selectpicker" name="payment_year">
								<?php
								// $currentYear = date('Y');
								// for ($i = $currentYear - 3; $i <= $currentYear + 3; $i++) {
								// 	echo "<option value='$i' " . ($i == $year ? "selected" : "") . ">$i</option>";
								// }
								?>
							</select>
						</div>
					</div>
				</div> -->

				<!-- <div class="form-group form-group-sm">
					<label for="month" class="col-md-4 control-label">Payment Due Month:</label>
					<div class="col-md-5 selectContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<select id="falling_due_month" class="form-control selectpicker" name="falling_due_month">
								<?php
								// Define the selected month, defaulting to the current month if not set
								// $month = isset($_POST['falling_due_month']) ? $_POST['falling_due_month'] : date('F');

								// for ($i = 1; $i <= 12; $i++) {
								// 	$monthName = date('F', mktime(0, 0, 0, $i, 1));
								// 	echo "<option value='$monthName' " . ($month == $monthName ? "selected" : "") . ">$monthName</option>";
								// }
								?>
							</select>
						</div>
					</div>
				</div> -->
				
				<!-- <div class="form-group form-group-sm">
					<label for="year" class="col-md-4 control-label"> Payment Due Year: </label>
					<div class="col-md-5 selectContainer">
						<div class="input-group">
							<select id="falling_due_year" class="form-control selectpicker" name="falling_due_year">
								<?php
								// $currentYear = date('Y');
								// for ($i = $currentYear - 3; $i <= $currentYear + 3; $i++) {
								// 	echo "<option value='$i' " . ($i == $year ? "selected" : "") . ">$i</option>";
								// }
								?>
							</select>
						</div>
					</div>
				</div> -->
				
						   
				<div class="form-group form-group-sm"> 
				  <label for="remitting_staff" class="col-md-4 control-label">Remitter's Name:</label>
					<div class="col-md-5 selectContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<select name="remitting_staff" class="form-control selectpicker" id="remitting_staff" required >
							  <option value="">Select...</option>
								<?php
								//if($menu["department"] == "Wealth Creation") {
									$query3 = "SELECT * FROM staffs ";
									$query3 .= "WHERE department = 'Wealth Creation' ";
									$query3 .= "ORDER BY full_name ASC ";
									$leasing_set = @mysqli_query($dbcon, $query3); 
									
									while ($leasing_officer = mysqli_fetch_array($leasing_set, MYSQLI_ASSOC)) {

									echo '<option value="'; ?><?php echo $leasing_officer['user_id']; ?><?php echo '-wc'; ?><?php echo '">'; ?><?php echo $leasing_officer['full_name']; ?><?php echo '</option>'; } 
								//}
								
									$query4 = "SELECT * FROM staffs_others ";
									$query4 .= "ORDER BY full_name ASC ";
									$leasing_set2 = @mysqli_query($dbcon, $query4); 
									
									while ($leasing_officer2 = mysqli_fetch_array($leasing_set2, MYSQLI_ASSOC)) {

									echo '<option value="'; ?><?php echo $leasing_officer2['id']; ?><?php echo '-so'; ?><?php echo '">'; ?><?php echo $leasing_officer2['full_name'].' - '.$leasing_officer2['department']; ?><?php echo '</option>'; }  
								?>
							</select>
						</div>
					</div>
				</div>
			
			</td>
			</tr>
			

			<?php 
				if ($menu["department"] == "Wealth Creation" || $menu["level"] == "ce"){ 
				echo '
					<input type="hidden" name="debit_alias" value="till" maxlength="50">
					<input type="hidden" name="credit_alias" value="'.$alias.'" maxlength="50">';
				} 
			 
				if ($menu["department"] == "Accounts"){ 
			?>
				<tr>
					<td colspan="3">		   
					<div class="form-group form-group-sm"> 
						<div class="col-sm-5 col-sm-offset-4 selectContainer">
							<div class="input-group">
								<span class="input-group-addon">DR:</span>
								<select name="debit_account" class="form-control selectpicker" id="debit_account" required>
									<option value="">Select debit account</option>
									<option value="10103">Account Till</option> 
									<option value="10150">Wealth Creation Funds Account</option> 
								</select>
							</div>
						</div>
					</div>


					<div class="form-group form-group-sm"> 
						<div class="col-sm-5 col-sm-offset-4 selectContainer">
							<div class="input-group">
								<span class="input-group-addon">CR:</span>
								<select name="credit_account" class="form-control selectpicker" id="credit_account" required>
									<option value="">Select credit account</option>
									<?php
										$cquery = "SELECT * FROM accounts ";
										$cquery .= "WHERE active = 'Yes' ";
										$cquery .= "AND acct_alias = '$alias' ";
										$caccount_set = mysqli_query($dbcon, $cquery); 
										
										$caccount = mysqli_fetch_array($caccount_set, MYSQLI_ASSOC);
									?> 
									<!-- <option value="101007">account_ledger_scroll_board</option>  -->
									<option value="<?php echo $caccount['acct_id']; ?>"><?php echo ucwords(strtolower($caccount['acct_desc'])); ?></option> 
								</select>
							</div>
						</div>
					</div>
					</td>
				</tr>
				<?php
					}
				?>

				<tr>
					<td colspan="3" align="center">
						<div class="form-group form-group-sm">
							<div>
								<?php
								/*
									if ($current_time >= $wc_begin_time && $current_time <= $wc_end_time && $menu["department"] == "Wealth Creation"){
										echo '<h4><span style="color:#ec7063; font-weight:bold;">Posting automatically disabled till tomorrow!</span></h4>';
									} elseif (@$amt_remitted <= 0 && $menu["department"] == "Wealth Creation") {
										echo '<h4><span style="color:#ec7063; font-weight:bold;">You do not have any unposted remittances for today.</h4>';
									} else {
										if($no_of_declined_post == 0 && $it_status == 0) {
											echo '
											<button type="submit" name="btn_post_'.$income_line.'" class="btn btn-danger btn-sm">Post '.$income_line_desc.' <span class="glyphicon glyphicon-send"></span></button>
											<button type="reset" name="btn_clear" class="btn btn-primary btn-sm">Clear</button>';
										} else {
											if($no_of_declined_post > 0) {
												echo '<h4><span style="color:#ec7063; font-weight:bold;">You have '.$no_of_declined_post.' DECLINED POSTS from previous transactions.';
											}
											if ($it_status > 0) {
												echo '<h4><span style="color:#ec7063; font-weight:bold;">You have '.$it_status.' WRONG postings with errors from previous transactions.';
											}
											echo 'Kindly treat before proceeding to post fresh transactions. Thanks.</span></h4>';
										}
									}
								*/
								?>
								
								<?php
									if (@$amt_remitted <= 0 && $menu["department"] == "Wealth Creation") {
										echo '<h4><span style="color:#ec7063; font-weight:bold;">You do not have any unposted remittances for today.</h4>';
									} else {
										if($no_of_declined_post == 0 && $it_status == 0) {
											echo '
											<button type="submit" name="btn_post_'.$income_line.'" class="btn btn-danger btn-sm">Post '.$income_line_desc.' <span class="glyphicon glyphicon-send"></span></button>
											<button type="reset" name="btn_clear" class="btn btn-primary btn-sm">Clear</button>';
										} else {
											if($no_of_declined_post > 0) {
												echo '<h4><span style="color:#ec7063; font-weight:bold;">You have '.$no_of_declined_post.' DECLINED POSTS from previous transactions.';
											}
											if ($it_status > 0) {
												echo '<h4><span style="color:#ec7063; font-weight:bold;">You have '.$it_status.' WRONG postings with errors from previous transactions.';
											}
											echo 'Kindly treat before proceeding to post fresh transactions. Thanks.</span></h4>';
										}
									}
								?>
							</div>
						</div>
					</td>
				</tr>
		</table>
	
	</fieldset>
</form>