<form  method="post" id="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
<fieldset>

<div class="col-md-9">
	<div class="row">
				<?php
				   if ( isset($receipt_Error) ) {
				?>
					<div class="form-group form-group-sm">
						<div class="alert alert-success fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<?php echo @$receipt_Error; ?>
						</div>
					</div>
				<?php
				   }
				?>

				<?php 
				$shop_no = @$selected_shop_data['shop_no'];
				if ($shop_no == NULL){
					$customer_name = NULL;
					$no_of_users = NULL;
					$shop_id = NULL;
					$power_id = NULL;
					$oldcustomer_name = NULL;
					$old_shop_no = NULL;
				} else {
					$customer_name = @$selected_shop_data['customer_name'];
					$no_of_users = @$selected_shop_data['no_of_users'];
					$shop_id = @$selected_shop_data['id'];
					$power_id = @$selected_shop_data['power_id'];
					$old_customer_name = @$selected_shop_data['old_customer_name'];
					$old_shop_no = @$selected_shop_data['old_shop_no'];
				}
				?>
						
				<!-- Success message -->
				<table class="table table-bordered">
				<?php
						
					$query = "SELECT * ";
					$query .= "FROM customers_power_consumption ";
					$query .= "ORDER BY shop_no ASC";
					$result_set = @mysqli_query($dbcon, $query); 


				?>
				
				<tr> 
				<!--Table Column 1st Begins Begins here -->
					<td colspan="4">
						<input type="hidden" name="shop_id" class="form-control" placeholder=" " value="<?php if (isset($selected_shop_data)) echo $shop_id; ?>" />
						<input type="hidden" name="power_id" class="form-control" placeholder=" " value="<?php if (isset($selected_shop_data)) echo $power_id; ?>" />
						<input type="hidden" name="approval_status" class="form-control" placeholder=" " value="Pending" maxlength="50" />
						<input type="hidden" name="customer_name" class="form-control" placeholder=" " value="<?php if (isset($selected_shop_data)) echo $customer_name; ?>" />
						<input type="hidden" name="shop_no" class="form-control" placeholder=" " value="<?php if (isset($selected_shop_data)) echo $shop_no; ?>" />
						
						
						<input type="hidden" name="posting_officer_id" class="form-control" placeholder=" " value="<?php echo $staff['user_id']; ?>" maxlength="50" />
						<input type="hidden" name="posting_officer_name" class="form-control" placeholder=" " value="<?php echo $staff['full_name']; ?>" maxlength="50" />
						
						
						<!-- Select Basic -->			   
						<div class="form-group form-group-sm"> 
						  <label for="selected_shop_no" class="col-md-3 control-label">Select Shop No:</label>
							<div class="col-md-6 selectContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<select name="selected_shop_no" class="form-control selectpicker" id="selected_shop_no">
									  <option value=" ">Select...</option>
										<?php
										while ($shop = mysqli_fetch_array($result_set, MYSQLI_ASSOC)) {

											echo '<option value="'; ?><?php echo $shop['shop_no']; ?><?php echo '">'; ?><?php echo $shop['shop_no'].'......'.strtoupper(strtolower($shop['customer_name'])); ?><?php echo '</option>'; 
										} 
										?>
									</select>
								</div>
							</div>
						</div>
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="customername" class="control-label col-md-3">Customer Name (NEW):</label>
							<div class="col-md-6 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<input type="text" name="customername" class="form-control input-sm" placeholder="" value="<?php if (isset($selected_shop_data)) echo ucwords(strtolower($customer_name)); ?>" maxlength="100" disabled />
								</div>
							</div>
						</div>
						
						
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="shopno" class="control-label col-md-3">Shop No:</label>
							<div class="col-md-6 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
									<input type="text" name="shopno" class="form-control input-sm" placeholder="" value="<?php if (isset($selected_shop_data)) echo $shop_no; ?>" maxlength="20" disabled />
								</div>
							</div>
						</div>
						
						
						
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="no_of_users" class="control-label col-md-3">No of Associated Shops:</label>
							<div class="col-md-5 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
									<input type="text" name="no_of_users" class="form-control input-sm" placeholder="" value="<?php if (isset($selected_shop_data)) echo @$no_of_users; ?>" maxlength="20" disabled />
								</div>
							</div>
							<button type="submit" name="btn_load_customer_data" class="btn btn-danger">Load Shop Details</button>
						</div>
					</td>
					</tr>


				<tr>
				
					<td colspan="4">
					<?php if ($shop_no != NULL && $customer_name != NULL){
		
						echo '
		

						<!-- Date Picker Source Code from http://formvalidation.io/examples/bootstrap-datepicker/ -->
						<div class="form-group form-group-sm">
							<label class="col-md-4 control-label">Date of Payment:</label>
							<div class="col-md-4 date">
								<div class="input-group input-append date" id="date_of_payment">
									<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date of Payment" value="'; ?><?php  if (isset($_POST['date_of_payment'])) echo @$date_of_payment; ?><?php echo '" required />
									<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
						</div>
						
					</td>
				</tr>
				

				<tr>
					<td>
						<div class="form-group form-group-sm">
							<div class="col-md-offset-4 col-md-7 date">
								<div class="input-group input-append date" id="start_date">
								<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type="text" class="form-control input-sm" name="start_date" placeholder="Period date" value="'; ?><?php  if (isset($_POST['start_date'])) echo @$start_date; ?><?php echo '" />
									
								</div>
							</div>
						</div>
					</td>
					<th class="text-center">to:</th>
					<td class="text-right">	
						<div class="form-group form-group-sm">
							<div class="col-md-8 date">
								<div class="input-group input-append date" id="end_date">
									<input type="text" class="form-control input-sm" name="end_date" placeholder="Period covered" value="'; ?><?php  if (isset($_POST['end_date'])) echo @$end_date; ?><?php echo '" />
									<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
						</div>
					</td>
				</tr>

				<tr>
						<td colspan="4">
						<!-- Select Basic -->			   
						<div class="form-group form-group-sm"> 
						  <label for="payment_type" class="col-md-4 control-label">Type of Payment:</label>
							<div class="col-md-4 selectContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-tasks"></i></span>
									<select name="payment_type" class="form-control selectpicker" id="payment_type">
									  <option value=" ">Select...</option>
									  <option '; ?><?php if (isset($_POST['payment_type']) && @$payment_type=="Cash") echo 'selected'; ?><?php echo 'value="Cash">Cash</option>
									  <option '; ?><?php if (isset($_POST['payment_type']) && @$payment_type=="Cheque") echo 'selected'; ?> <?php echo 'value="Cheque">Cheque</option>
									  <option '; ?><?php if (isset($_POST['payment_type']) && @$payment_type=="Transfer") echo 'selected'; ?> <?php echo 'value="Transfer">Transfer</option>
									</select>
								</div>
							</div>
						</div>
						
						<!-- Select Basic -->			   
						<div class="form-group form-group-sm"> 
						  <label for="payment_category" class="col-md-4 control-label">Payment Category:</label>
							<div class="col-md-4 selectContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-tasks"></i></span>
									<select name="payment_category" class="form-control selectpicker" id="category">
									  <option value=" ">Select...</option>
									  <option '; ?><?php if (isset($_POST['payment_category']) && @$payment_type=="Power Consumption") echo 'selected'; ?><?php echo 'value="Power Consumption">Power Consumption</option>
									  <option '; ?><?php if (isset($_POST['payment_category']) && @$payment_type=="New Meter") echo 'selected'; ?> <?php echo 'value="New Meter">New Meter</option>
									  <option '; ?><?php if (isset($_POST['payment_category']) && @$payment_type=="Additional Meter User") echo 'selected'; ?> <?php echo 'value="Additional Meter User">Additional Meter User</option>
									</select>
								</div>
							</div>
						</div>
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="transaction_desc" class="control-label col-md-4">Transaction Description:</label>
							<div class="col-md-6 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
									<input type="text" name="transaction_desc" class="form-control input-sm" placeholder="Transaction description" value="'; ?><?php if (isset($_POST['transaction_desc'])) echo @$transaction_desc; ?><?php echo '" maxlength="100" />
								</div>
							</div>
						</div>
						
						
						<!-- Select Basic -->			   
						<div class="form-group form-group-sm"> 
						  <label for="bank_name" class="col-md-4 control-label">Name of bank:</label>
							<div class="col-md-4 selectContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
									<select name="bank_name" class="form-control selectpicker" id="bank_name">
										<option value=" ">Select your Bank</option>			
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Access Bank") echo 'selected'; ?> <?php echo 'value="Access Bank">Access Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Citibank") echo 'selected'; ?> <?php echo 'value="Citibank">Citibank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Diamond Bank") echo 'selected'; ?> <?php echo 'value="Diamond Bank">Diamond Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Ecobank Nigeria") echo 'selected'; ?> <?php echo 'value="Ecobank Nigeria">Ecobank Nigeria</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Enterprise Bank Limited") echo 'selected'; ?> <?php echo 'value="Enterprise Bank Limited">Enterprise Bank Limited</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Fidelity Bank Nigeria") echo 'selected'; ?> <?php echo 'value="Fidelity Bank Nigeria">Fidelity Bank Nigeria</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="First Bank of Nigeria") echo 'selected'; ?> <?php echo 'value="First Bank of Nigeria">First Bank of Nigeria</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="First City Monument Bank") echo 'selected'; ?> <?php echo 'value="First City Monument Bank">First City Monument Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="FSDH Merchant Bank") echo 'selected'; ?> <?php echo 'value="FSDH Merchant Bank">FSDH Merchant Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Guaranty Trust Bank") echo 'selected'; ?> <?php echo 'value="GTBank">Guaranty Trust Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Heritage Bank Plc") echo 'selected'; ?> <?php echo 'value="Heritage Bank Plc">Heritage Bank Plc</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Keystone Bank Limited") echo 'selected'; ?> <?php echo 'value="Keystone Bank Limited">Keystone Bank Limited</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Mainstreet Bank Limited") echo 'selected'; ?> <?php echo 'value="Mainstreet Bank Limited">Mainstreet Bank Limited</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Rand Merchant Bank") echo 'selected'; ?> <?php echo 'value="Rand Merchant Bank">Rand Merchant Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Savannah Bank") echo 'selected'; ?> <?php echo 'value="Savannah Bank">Savannah Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Skye Bank") echo 'selected'; ?> <?php echo 'value="Skye Bank">Skye Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Stanbic IBTC Bank Nigeria Limited") echo 'selected'; ?> <?php echo 'value="Stanbic IBTC Bank Nigeria Limited">Stanbic IBTC Bank Nigeria Limited</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Standard Chartered Bank") echo 'selected'; ?> <?php echo 'value="Standard Chartered Bank">Standard Chartered Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Sterling Bank") echo 'selected'; ?> <?php echo 'value="Sterling Bank">Sterling Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Union Bank of Nigeria") echo 'selected'; ?> <?php echo 'value="Union Bank of Nigeria">Union Bank of Nigeria</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="United Bank for Africa") echo 'selected'; ?> <?php echo 'value="United Bank for Africa">United Bank for Africa</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Unity Bank Plc") echo 'selected'; ?> <?php echo 'value="Unity Bank Plc">Unity Bank Plc</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Wema Bank") echo 'selected'; ?> <?php echo 'value="Wema Bank">Wema Bank</option>
										<option '; ?><?php if (isset($_POST['bank_name']) && @$bank_name=="Zenith Bank") echo 'selected'; ?> <?php echo 'value="Zenith Bank">Zenith Bank</option>
									</select>
								</div>
							</div>
						</div>
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="cheque_no" class="control-label col-md-4">Cheque No:</label>
							<div class="col-md-4 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
									<input type="text" name="cheque_no" class="form-control input-sm" placeholder="Cheque No" value="'; ?><?php if (isset($_POST['cheque_no'])) echo @$cheque_no; ?><?php echo '" maxlength="20" />
								</div>
								
							</div>
						</div>
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="teller_no" class="control-label col-md-4">Teller No:</label>
							<div class="col-md-4 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
									<input type="text" name="teller_no" class="form-control input-sm" placeholder="Teller No" pattern="^\d{7}$" value="'; ?><?php if (isset($_POST['teller_no'])) echo @$teller_no; ?><?php echo '" maxlength="7" />
								</div>
								
							</div>
						</div>
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="receipt_no" class="control-label col-md-4">Receipt No:</label>
							<div class="col-md-4 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
									<input type="text" name="receipt_no" class="form-control input-sm" placeholder="Receipt No" pattern="^\d{7}$" value="'; ?><?php if (isset($_POST['receipt_no'])) echo @$receipt_no; ?><?php echo '" maxlength="7" required />
								</div>
								
							</div>
						</div>
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="amount_paid" class="control-label col-md-4">Amount Paid:</label>
							<div class="col-md-4 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon">&#8358;</span>
									<input type="text" name="amount_paid" class="form-control input-sm" placeholder="Amount Paid" value="'; ?><?php if (isset($_POST['amount_paid'])) echo @$amount_paid; ?><?php echo '" maxlength="20" required />
								</div>
							</div>
						</div>
					</td>
				</tr>					
						
				
				<tr>
					<td colspan="2">
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="remitting_customer" class="control-label col-md-5">Name of Remitter</br> (In case of customer):</label>
							<div class="col-md-6 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<input type="text" name="remitting_customer" class="form-control input-sm" placeholder="Remitting customer" value="'; ?><?php if (isset($_POST['remitting_customer'])) echo @$remitting_customer; ?><?php echo '" maxlength="50" />
								</div>
							</div>
						</div>
						
						
						<!-- Select Basic -->			   
						<div class="form-group form-group-sm"> 
						  <label for="remitting_staff" class="col-md-5 control-label">Name of Remitter</br> (In case of Technical Dept. Staff):</label>
							<div class="col-md-6 selectContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<select name="remitting_staff" class="form-control selectpicker" id="remitting_staff">
									  <option value=" ">Select...</option>'; ?>
										<?php
										$query = "SELECT full_name FROM staffs ";
										$query .= "WHERE department = 'technical'";
										$leasing_set = @mysqli_query($dbcon, $query); 
										
										while ($leasing_officer = mysqli_fetch_array($leasing_set, MYSQLI_ASSOC)) {

										echo '<option value="'; ?><?php echo $leasing_officer['full_name']; ?><?php echo '">'; ?><?php echo $leasing_officer['full_name']; ?><?php echo '</option>'; } 
										
										?><?php echo '

									</select>
								</div>
							</div>
						</div>
					
					</td>
					<td colspan="2">
						<div class="form-group form-group-sm"> 
							<div class="col-md-10  selectContainer">
								<div class="input-group">
									<span class="input-group-addon">DR:</span>
									<select name="debit_account" class="form-control selectpicker" id="debit_account" required>
									  <option value="">Select debit account</option>'; ?>
										<?php
										$query = "SELECT * FROM arena_accounts ";
										$query .= "WHERE page_visibility = 'Power' ";
										$query .= "ORDER BY acct_desc ASC";
										$account_set = @mysqli_query($dbcon, $query); 
										
										while ($account = @mysqli_fetch_array($account_set, MYSQLI_ASSOC)) {

										echo '<option value="'; ?><?php echo $account['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($account['acct_desc'])); ?><?php echo '</option>'; } 
										
										?>
									<?php 
									echo '
									</select>
								</div>
							</div>
						</div>



						<!-- Select Basic -->			   
						<div class="form-group form-group-sm"> 
							<div class="col-md-10 selectContainer">
								<div class="input-group">
									<span class="input-group-addon">CR:</span>
									<select name="credit_account" class="form-control selectpicker" id="credit_account" required>
									  <option value="">Select credit account</option>'; ?>
										<?php
										$query = "SELECT * FROM arena_accounts ";
										$query .= "WHERE page_visibility = 'Power' ";
										$query .= "ORDER BY acct_desc ASC";
										$account_set = @mysqli_query($dbcon, $query); 
										
										while ($account = mysqli_fetch_array($account_set, MYSQLI_ASSOC)) {

										echo '<option value="'; ?><?php echo $account['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($account['acct_desc'])); ?><?php echo '</option>'; } 
										
										?>
									<?php echo '
									</select>
								</div>
							</div>
						</div>						
					
					</td>
				</tr>
				
				<tr>
				<td colspan="4" align="center">
				<div class="form-group form-group-sm">
					<div>';
						if($no_of_declined_post == 0) {
							echo '
							<button type="submit" name="btn_post_rent" class="btn btn-danger">Post Payment <span class="glyphicon glyphicon-send"></span></button>
							<button type="submit" name="btn_clear" class="btn btn-primary">Clear</button>';
						} else {
							echo '
							<h4><span style="color:#ec7063; font-weight:bold;">You have '.$no_of_declined_post.' DECLINED POSTS from previous transactions.</br> Kindly treat before proceeding to post fresh transactions. Thanks.</span>
							</h4>';
						}
					echo '		
					</div>
				</div>
				</td>
				
				</tr>
				</table>
		</div>
</div>


<div class="col-md-3">'; ?>
		<?php //include ('controllers/right_acct_menu.php'); ?>

<?php echo '</div>';
				
				} else {
					echo '
				</table>
					<tr>
						<td colspan="2" align="center">
							<div class="form-group form-group-sm">
								<div class="alert alert-danger fade in">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<span class="glyphicon glyphicon-info-sign"></span> Please load the shop details before you proceed!
								</div>
							</div>
						</td>
				</tr>
				</table>';
					
				}
				?>
				<input type="hidden" name="customer_bal" class="form-control" placeholder=" " value="<?php if (isset($selected_shop_data)) echo $balance; ?>" maxlength="20" />

</fieldset>
</form>