<?php
echo '
<table class="table table-hover table-bordered">
					<thead>
						<tr class="success">
							<th colspan="2" class="text-right">Rent Transaction Accounts</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th colspan="2">
								<!-- Select Basic -->			   
								<div class="form-group form-group-sm"> 
									<div class="col-md-12 selectContainer">
										<div class="input-group">
											<span class="input-group-addon">DR:</span>
											<select name="debit_account" class="form-control selectpicker" id="debit_account" required>
											  <option value="">Select debit account</option>'; ?>
												<?php
												$query = "SELECT * FROM accounts ";
												$query .= "WHERE active = 'Yes' ";
												$query .= "ORDER BY acct_desc ASC";
												$account_set = @mysqli_query($dbcon, $query); 
												
												while ($account = @mysqli_fetch_array($account_set, MYSQLI_ASSOC)) {

												echo '<option value="'; ?><?php echo $account['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($account['acct_desc'])); ?><?php echo '</option>'; } 
												
												?>
											<?php echo
											'</select>
										</div>
									</div>
								</div>
							</th>
						</tr>
						
						<tr>
							<td></td>
						</tr>
						
						
						<tr>
							<th colspan="2">
								<!-- Select Basic -->			   
								<div class="form-group form-group-sm"> 
									<div class="col-md-12 selectContainer">
										<div class="input-group">
											<span class="input-group-addon">CR:</span>
											<select name="credit_account" class="form-control selectpicker" id="credit_account" required>
											  <option value="">Select credit account</option>'; ?>
												<?php
												$query = "SELECT * FROM accounts ";
												$query .= "WHERE active = 'Yes' ";
												$query .= "ORDER BY acct_desc ASC";
												$account_set = @mysqli_query($dbcon, $query); 
												
												while ($account = mysqli_fetch_array($account_set, MYSQLI_ASSOC)) {

												echo '<option value="'; ?><?php echo $account['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($account['acct_desc'])); ?><?php echo '</option>'; } 
												
												?>
											<?php echo
											'</select>
										</div>
									</div>
								</div>
							</th>
						</tr>
					</tbody>
				</table>
				
<table class="table table-hover table-bordered">
					<thead>
						<tr class="danger">
							<th colspan="3" class="text-right">Rent Payment History</th>
						</tr>
						<tr>
							<td colspan="3" class="text-right"><strong>'.ucwords(strtolower($customer_name)).'</strong> </br>
							Current Occupant: <strong>'.ucwords(strtolower(@$selected_shop_data["off_takers_name"])).'</strong> </br>
							Current allocation date: <strong>'.strtoupper(strtolower(@$selected_shop_data["lease_start_date"])).'</strong></br>
							Expiry date: <strong>'.strtoupper(strtolower(@$selected_shop_data["lease_end_date"])).'</strong></br>
							</td>
						</tr>
					</thead>
					<tbody>';
						
							if ($customer_name == "") {
								$rent_payment["date_of_payment"] = "";
								$rent_payment["start_date"] = "";
								$rent_payment["end_date"] = "";
								$amount_paid = "";
								$expected = "";
								$paid = "";
								$balance = "";
							} else {
								$query = "SELECT * ";
								$query .= "FROM account_general_transaction ";
								$query .= "WHERE shop_id = '$shop_id' ";
								$query .= "AND approval_status = 'Approved'";
								$payment_result = mysqli_query($dbcon, $query);
							}
						
						echo '<tr>
							<td class="info text-center"><span class="glyphicon glyphicon-calendar"></span> Payment Date:</td>
							<td class="info text-center"><span class="glyphicon glyphicon-time"></span> Tenure Paid For</td>
							<td class="info text-right"><span class="glyphicon glyphicon-calendar"></span> Amount Paid</td>
						</tr>';
						
						while ($rent_payment = @mysqli_fetch_array($payment_result, MYSQLI_ASSOC)) {
						
						echo '
						<tr>
							<td class="text-center">'; echo $rent_payment["date_of_payment"]; echo '</td>
							<td class="text-center">'; echo $rent_payment["start_date"].' to '.$rent_payment["end_date"]; echo '</td>
							<td class="text-right">';
								  
									$amountpaid = $rent_payment["amount_paid"];
									$amount_paid = number_format((int)$amountpaid, 2);
									echo "&#8358 {$amount_paid}";
							echo '	
							</td>
						</tr>';
						}
					
						
							if ($customer_name == "") {
								$rent_payment["date_of_payment"] = "";
								$rent_payment["start_date"] = "";
								$rent_payment["end_date"] = "";
								$amount_paid = "";
								$expected = "";
								$paid = "";
								$balance = "";
							} else {
								$query = "SELECT * ";
								$query .= "FROM account_general_transaction_new ";
								$query .= "WHERE shop_id = '$shop_id'";
								//$query .= "AND approval_status = 'Approved'";
								$payment_result = mysqli_query($dbcon, $query);
							}
						
						while ($rent_payment = @mysqli_fetch_array($payment_result, MYSQLI_ASSOC)) {
						
						echo '
						<tr>
							<td class="text-center">'; echo $rent_payment["date_of_payment"]; echo '</td>
							<td class="text-center">'; echo $rent_payment["start_date"].' to '.$rent_payment["end_date"]; echo '</td>
							<td class="text-right">';
								  
									$amountpaid = $rent_payment["amount_paid"];
									$amount_paid = number_format((int)$amountpaid, 2);
									echo "&#8358 {$amount_paid}";
						echo '		
							</td>
						</tr>';
						
						}
						
						echo '<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>';
							
								if ($customer_name == "") {
									$rent_payment["date_of_payment"] = "";
									$rent_payment["start_date"] = "";
									$rent_payment["end_date"] = "";
									$amount_paid = "";
									$expected = "";
									$paid = "";
									$balance = "";
								} else {
									$query = "SELECT SUM(amount_paid) as amount_paid ";
									$query .= "FROM account_general_transaction ";
									$query .= "WHERE shop_id = '$shop_id' ";
									$query .= "AND approval_status = 'Approved'";
									$sum = @mysqli_query($dbcon,$query);
									$total = @mysqli_fetch_array($sum, MYSQLI_ASSOC);
								}
							
							
								if ($customer_name == "") {
									$rent_payment["date_of_payment"] = "";
									$rent_payment["start_date"] = "";
									$rent_payment["end_date"] = "";
									$amount_paid = "";
									$expected = "";
									$paid = "";
									$balance = "";
								} else {
									$query_n = "SELECT SUM(amount_paid) as amount_paid ";
									$query_n .= "FROM account_general_transaction_new ";
									$query_n .= "WHERE shop_id = '$shop_id'";
									//$query_n .= "AND approval_status = 'Approved'";
									$sum_n = @mysqli_query($dbcon,$query_n);
									$total_n = @mysqli_fetch_array($sum_n, MYSQLI_ASSOC);
								}
							 

							  
								$expected = $selected_shop_data["total_expected_rent"];
								$expected = preg_replace('/[,]/', '', $expected);
								$expected = ($expected + 0);
								if (!is_int($expected)) {
								$expected = (int)$expected;
								}
								
								$total_till1_payments = @$total['amount_paid'];
								$total_till2_payments = @$total_n['amount_paid'];
								$acct_ledger_paid = $total_till1_payments + $total_till2_payments;
								
								$cbal_query = "SELECT * FROM customers ";
								$cbal_query .= "WHERE id = '$shop_id'";
								$cbal_result = @mysqli_query($dbcon, $cbal_query);
								$customer_acct = @mysqli_fetch_array($cbal_result, MYSQLI_ASSOC);
								
								$record_amt_paid = $customer_acct['rent_paid'];
								
								$paid = $acct_ledger_paid + $record_amt_paid;
								
								$balance = ($expected - $paid);
							
						echo '
						<tr>
							<th colspan="2" class="text-right info">Expected Rent (Yearly):</th>
							<th class="text-right info">';
								 	$yearly_expected = $customer_acct["expected_rent"];
									$yearly_expected = number_format($yearly_expected, 2);
									echo "&#8358 {$yearly_expected}";
						echo '		
							</th>
							
						</tr>
						<tr>
							<th colspan="2" class="text-right info">Total Expected Rent Payable:</th>
							<th class="text-right info">';
									$expected = number_format($expected, 2);
									echo "&#8358 {$expected}";
						echo '		
							</th>
						</tr>
						<tr>
							<th colspan="2" class="text-right">
								Uploaded Record: Amount Paid Before ERP @ 31st April
							</th>
							<th class="text-right warning">';
								
									if ($record_amt_paid == "") {
										$record_amt_paid = 0;
									}
									$record_amt_paid = number_format($record_amt_paid, 2);
									echo "&#8358 {$record_amt_paid}";
								
							echo '</th>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<th class="text-right warning">Total Rent Paid:</th>
							<th class="text-right warning">';
								
									$paid = number_format($paid, 2);
									echo "&#8358 {$paid}";
						echo '		
							</th>
						</tr>
						<tr>
							<th colspan="2" class="text-right">Balance:</th>
							<th class="text-right danger text-danger">';
								
									$balance = number_format($balance, 2);
									echo "&#8358 {$balance}";
						echo '		
							</th>
						</tr>
					</tbody>
				</table>
				'; ?>