<form  method="post" id="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
	
	<fieldset>
		<input type="hidden" name="posting_officer_id" class="form-control" placeholder=" " value="<?php echo $staff['user_id']; ?>" maxlength="50" />
		<input type="hidden" name="posting_officer_name" class="form-control" placeholder=" " value="<?php echo $staff['full_name']; ?>" maxlength="50">
		<input type="hidden" name="income_line" value="<?php echo $income_line; ?>" maxlength="50">
		<input type="hidden" name="posting_officer_dept" value="<?php echo $menu['department']; ?>">
		
		
		<table class="table table-bordered">
			<tr> 
			<td colspan="3">
				<?php include 'payments_past/remittance_form_inc.php'; ?>
				
				<div class="form-group form-group-sm"> 
				  <label for="category" class="col-md-4 control-label">Category:</label>
					<div class="col-md-7 selectContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
							<select name="category" class="form-control selectpicker" id="category" onBlur="loadCalc()" required >
								<option value="">Select a category</option>
								<option value="Goods (Offloading) - N7000">Goods (Offloading) - N7000</option>
								<option value="Goods (Offloading) - N15000">Goods (Offloading) - N15000</option>
								<option value="Goods (Offloading) - N20000">Goods (Offloading) - N20000</option>
								<option value="Goods (Offloading) - N30000">Goods (Offloading) - N30000</option>
								<option value="Goods (Loading) - N20000">Goods (Loading) - N20000</option>
								<option value="Fruits (Offloading) - N2500">Fruits (Offloading) - N2500</option>
								<option value="Fruits (Offloading) - N3500">Fruits (Offloading) - N3500</option>
								<option value="Fruits (Offloading) - N7000">Fruits (Offloading) - N7000</option>
								<option value="Fruits (Offloading) - N15000">Fruits (Offloading) - N15000</option>
								<option value="Apple Bus (Loading) - N3500">Apple Bus (Loading) - N3500</option>
								<option value="Cargo Truck (Loading) - N7000">Cargo Truck (Loading) - N7000</option>
								<option value="Cargo Truck 1 (Offloading) - N15000">Cargo Truck 1 (Offloading) - N15000</option>
								<option value="Cargo Truck 2 (Offloading) - N20000">Cargo Truck 2 (Offloading) - N20000</option>
								<option value="OK Truck (Offloading) - N20000">OK Truck (Offloading) - N20000</option>
								<option value="20 feet container - (Loading) - N15000">20 feet container - (Loading) - N15000</option>
								<option value="20 feet container - (Offloading) - N15000">20 feet container - (Offloading) - N15000</option>
								<option value="40 feet container - (Offloading) N30000">40 feet container - (Offloading) N30000</option>
								<option value="40 feet container - (Abassa Offloading - Weekend) - N30000">40 feet container - (Abassa Offloading - Weekend) - N30000</option>
								<option value="40 feet container - (Shoe Offloading - Weekend) - N60000">40 feet container - (Shoe Offloading - Weekend) - N60000</option>
								<option value="40 feet container - (Apple Offloading) - N30000">40 feet container - (Apple Offloading) - N30000</option>
								<option value="40 feet container - (Apple Offloading - Sunday) - N60000">40 feet container - (Apple Offloading - Sunday) - N60000</option>
								<option value="40 feet container - (Ok, Curtain Offloading) - N30000">40 feet container - (Ok, Curtain Offloading) - N30000</option>
								<option value="LT Buses (Offloading) - N4000">LT Buses (Offloading) - N4000</option>
								<option value="LT Buses (Offloading - Sunday) - N7000">LT Buses (Offloading - Sunday) - N7000</option>
								<option value="LT Buses (Loading) - N4000">LT Buses (Loading) - N4000</option>
								<option value="Mini LT Buses (Loading) - N3000">Mini LT Buses (Loading) - N3000</option>
								<option value="Mini LT Buses (Offloading) - N3000">Mini LT Buses (Offloading) - N3000</option>
								<option value="LT Buses Army Staff (Loading) - N1000">LT Buses Army Staff (Loading) - N1000</option>
								<option value="LT Buses Army Staff (Loading) - N2000">LT Buses Army Staff (Loading) - N2000</option>
								<option value="Mini Van (Loading) - N5000">Mini Van (Loading) - N5000</option>
								<option value="Mini Van (Offloading) - N5000">Mini Van (Offloading) - N5000</option>
								<option value="OK Mini Van (Loading) - N6000">OK Mini Van (Loading) - N6000</option>
								<option value="OK Mini Van (Offloading) - N6000">OK Mini Van (Offloading) - N6000</option>
								<option value="Sienna Buses (Loading) - N2000">Sienna Buses (Loading) - N2000</option>
								<option value="Oil Tanker (Offloading) - N30000">Oil Tanker (Offloading) - N30000</option>
							</select>
						</div>
					</div>
				</div>		
				
				<div class="form-group form-group-sm">
					<label for="no_of_days" class="control-label col-md-4">No of Days:</label>
					<div class="col-md-3 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
							<input type="number" id="no_of_days" name="no_of_days" class="form-control input-sm" min="1" max="365" value="<?php if (isset($_POST['no_of_days'])) echo @$no_of_days; ?>" onBlur="loadCalc()" required />
						</div>
					</div>
				</div>
				
				<div class="form-group form-group-sm">
					<label for="plate_no" class="control-label col-md-4">Plate No:</label>
					<div class="col-md-4 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
							<input type="text" id="plate_no" name="plate_no" class="form-control input-sm" placeholder="" value="<?php if (isset($_POST['plate_no'])) echo @$plate_no; ?>" onBlur="loadCalc()" maxlength="8" required />
						</div>
					</div>
				</div>
	
				<div class="form-group form-group-sm">
					<label for="receipt_no" class="control-label col-md-4">Receipt No:</label>
					<div class="col-md-4 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
							<input type="text" name="receipt_no" class="form-control input-sm" placeholder="Receipt No" pattern="^\d{7}$" value="<?php if (isset($_POST['receipt_no'])) echo @$receipt_no; ?>" maxlength="7" onBlur="loadCalc()" required />
						</div>
					</div>
				</div>
				
				
						
				<div class="form-group form-group-sm">
					<label for="amount_paid" class="control-label col-md-4">Amount Remitted:</label>
					<div class="col-md-4 inputGroupContainer">
						<div class="input-group">
							<span class="input-group-addon">&#8358;</span>
							<input type="text" id="amount_paid" name="amount_paid" class="form-control input-sm" placeholder="Amount Remitted" value="<?php if (isset($_POST['amount_paid'])) echo @$amount_paid; ?>" maxlength="20" onBlur="loadCalc()" readonly />
						</div>
					</div>
				</div>
				
						   
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
				<tr>
					<td colspan="3">
					<input type="hidden" name="debit_alias" value="till" maxlength="50">
					<input type="hidden" name="credit_alias" value="'.$alias.'" maxlength="50">
					
					
					<div class="form-group form-group-sm">
						<label for="credit_account" class="col-md-4 control-label">Income Line:</label>
						<div class="col-md-5 selectContainer">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
								<select name="credit_account" class="form-control selectpicker" id="credit_account" required>
								  <option value="">Select an income line</option>';
									
									$query = "SELECT * FROM accounts ";
									$query .= "WHERE page_visibility = 'Loading' ";
									$query .= "ORDER BY acct_desc ASC";
									$account_set = @mysqli_query($dbcon, $query); 
									
									while ($account = mysqli_fetch_array($account_set, MYSQLI_ASSOC)) {
										echo '<option value="'.$account['acct_id'].'">'.ucwords(strtolower($account['acct_desc'])).'</option>'; 
									} 
									
									echo '
								</select>
							</div>
						</div>
					</div>
					</td>
				</tr>';
				} 
			 
			 
				if ($menu["department"] == "Accounts"){ 
			?>
				<tr>
					<td colspan="3">
						<!-- Select Basic -->			   
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
									  <option value="">Select an income line</option>
										<?php
											$query = "SELECT * FROM accounts ";
											$query .= "WHERE page_visibility = 'Loading' ";
											$query .= "ORDER BY acct_desc ASC";
											$account_set = @mysqli_query($dbcon, $query); 
											
											while ($account = mysqli_fetch_array($account_set, MYSQLI_ASSOC)) {

											echo '<option value="'; ?><?php echo $account['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($account['acct_desc'])); ?><?php echo '</option>'; } 
										
										?>
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
								?>
							</div>
						</div>
					</td>
				</tr>
		</table>
	
	</fieldset>
</form>