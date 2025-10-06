<?php

echo '<table class="table table-bordered">
				<tr>
					<th colspan="5" class="text-right danger"> New Customer Record:</th>
				</tr>
				
				<tr>
					<td class="text-right info">Customer\'s Name:</td>
					<th colspan="4">'.$new_customer["title"].' '.$new_customer["customer_name"].'</th>
				</tr>

				<tr>
					<td class="text-right info">Phone No:</td>
					<th colspan="4">'.$new_customer["phone_no"].'</th>
				</tr>
				
				<tr>
					<td class="text-right info">Facility Type:</td>
					<th colspan="4">'.$new_customer["facility_type"].'</th>
				</tr>
				
				<tr>
					<td class="text-right info">Choosen Shop No:</td>
					<th colspan="4">'.$new_customer["shop_no"].'</th>
				</tr>
				
				<tr>
					<td class="text-right info">Shop Size:</td>
					<th colspan="4">'.$new_customer["shop_size"].'</th>
				</tr>
				
				<tr>
					<td class="text-right info">Lease Tenure:</td>
					<th colspan="4">'.$new_customer["lease_tenure"].'</th>
				</tr>
				<tr>
					<td class="text-right info">Rent Due:</td>
					<th colspan="4">'; ?><?php echo "&#8358 {$new_customer['total_expected_rent']}"; ?><?php echo '</th>
				</tr>'; ?>
				<?php
				
					$expected_rent = $new_customer["total_expected_rent"];
					$expected_rent = preg_replace('/[,]/', '', $expected_rent);
					$expected_rent = ($expected_rent + 0);
					if (!is_int($expected_rent)) {
					$expected_rent = (int)$expected_rent;
					}
					
					$discount = $new_customer["approved_discount"];
					if (!is_int($discount)) {
					$discount = (int)$discount;
					}
					
					$amount = $expected_rent;
					$discount_amount = (($discount/100) * $amount);
					$new_amount = ($amount - $discount_amount);
					
					$new_amount = number_format($new_amount, 0);
					
					
					if ($new_customer['discount']=="Yes, Discount Required" && $new_customer['discount_request_status']=="Discount Approved") {
					echo '<tr>
						<td class="text-right info">Approved Discount:</td>
						<th colspan="4">'.$new_customer["approved_discount"].'&#37</th>
					</tr>

					<tr>
						<td class="text-right info">Discounted Amount Payable:</td>
						<th colspan="4">&#8358 '.$new_amount.'</th>
					</tr>';
					} 
					
					if ($new_customer['discount']=="Yes, Discount Required" && $new_customer['discount_request_status']=="Discount Reviewed") {
					echo '<tr>
						<td class="text-right info">Approved Discount:</td>
						<th colspan="4">'.$new_customer["approved_discount"].'&#37</th>
					</tr>

					<tr>
						<td class="text-right info">Discounted Amount Payable:</td>
						<th colspan="4">&#8358 '.$new_amount.'</th>
					</tr>';
					}
				?>
				<?php echo '
				
			
			</table>





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
				</table>'; ?>