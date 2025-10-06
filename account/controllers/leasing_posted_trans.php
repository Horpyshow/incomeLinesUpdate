<div class="container-fluid">
	<div class="col-md-12">		
		<div class="row">
			<form  method="post" id="form" class="form-horizontal" action="review_trans.php?txref=<?php echo $post['id']; ?>" autocomplete="off">
				<fieldset>

				<div class="col-md-12">
					<div class="row">
									
							<!-- Success message -->
							<table class="table table-bordered">
							<tr> 
							<!--Table Column 1st Begins Begins here -->
								<td colspan="3">
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="customername" class="control-label col-md-3">Customer Name:</label>
										<div class="col-md-8 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
												<input type="text" name="customername" class="form-control input-sm" placeholder="" value="<?php if (isset($_GET['txref'])) echo ucwords(strtolower($post['customer_name'])); ?>" maxlength="100" readonly />
											</div>
										</div>
									</div>

									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="customername" class="control-label col-md-3">Current Shop Occupant:</label>
										<div class="col-md-8 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
												<input type="text" name="customername" class="form-control input-sm" placeholder="" value="<?php 
												if (isset($_GET['txref'])) {
													$post_shop_no = $post['shop_no'];
													
													$cust_query = "SELECT * ";
													$cust_query .= "FROM customers ";
													$cust_query .= "WHERE shop_no = '$post_shop_no'";
													$cust_result = mysqli_query($dbcon, $cust_query);
													$cust_data = mysqli_fetch_array($cust_result, MYSQLI_ASSOC);
													
													$current_occupant_name = $cust_data['off_takers_name'];
													echo ucwords(strtolower($current_occupant_name));
												}  
												?>" maxlength="100" readonly />
											</div>
										</div>
									</div>
									
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="shopno" class="control-label col-md-3">Shop No:</label>
										<div class="col-md-8 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
												<input type="text" name="shopno" class="form-control input-sm" placeholder="" value="<?php if (isset($_GET['txref'])) echo $post['shop_no']; ?>" maxlength="20" readonly />
											</div>
										</div>
									</div>
									
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="shop_size" class="control-label col-md-3">Shop Size:</label>
										<div class="col-md-5 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
												<input type="text" name="shop_size" class="form-control input-sm" placeholder="" value="<?php if (isset($_GET['txref'])) echo $post['shop_size']; ?>" maxlength="20" readonly />
											</div>
										</div>
									</div>
									
									
									
								</td>
								</tr>


							<tr>
								<td colspan="3">
									<div class="form-group form-group-sm">
										<label for="date_of_payment" class="control-label col-md-3">Date of Payment:</label>
										<div class="col-md-5 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
												<input type="text" name="date_of_payment" class="form-control input-sm" placeholder="" value="<?php if (isset($_GET['txref'])) echo $post['date_of_payment']; ?>" maxlength="20" readonly />
											</div>
										</div>
									</div>
								</td>
							</tr>


							<tr>
								<td colspan="3">
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="transaction_desc" class="control-label col-md-4">Narration <span style="color:#ec7063;">(If wrong, correct it!):</span></label>
										<div class="col-md-7 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
												<input type="text" name="transaction_desc" class="form-control input-sm" placeholder="Transaction description" value="<?php if (isset($_GET['txref'])) echo $post['transaction_desc']; ?>" maxlength="100" />
											</div>
										</div>
									</div>
									
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="plate_no" class="control-label col-md-4">Plate No:</label>
										<div class="col-md-4 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
												<input type="text" name="plate_no" class="form-control input-sm" value="<?php if (isset($_GET['txref'])) echo strtoupper($post['plate_no']); ?>" maxlength="20" readonly />
											</div>
										</div>
									</div>
									
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="receipt_no" class="control-label col-md-4">Receipt No:</label>
										<div class="col-md-4 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
												<input type="text" name="receipt_no" id="receipt_no" class="form-control input-sm" placeholder="Receipt No" onkeyup="entryCheck()" value="<?php if (isset($_POST['receipt_no'])) echo @$receipt_no; ?>" maxlength="7" required />
											</div>
										</div>
									</div>
									
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="amount_paid" class="control-label col-md-4">Amount Paid:</label>
										<div class="col-md-4 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="text" name="amount_paid" id="amount_paid" class="form-control input-sm" placeholder="Amount Paid" onkeyup="entryCheck()" value="<?php if (isset($_POST['amount_paid'])) echo @$amount_paid; ?>" maxlength="20" required />
											</div>
										</div>
									</div>
									
									
									<!-- Select Basic -->			   
									<div class="form-group form-group-sm"> 
									  <label for="reason" class="col-md-4 control-label">Reason (Required for Decline) </label>
										<div class="col-md-5 selectContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-tasks"></i></span>
												<select name="reason" class="form-control selectpicker" id="reason" onchange="entryCheck()">
												  <option value="">Select...</option>
												  <option value="Wrong amount">Wrong Amount</option>
												  <option value="Wrong receipt no">Wrong Receipt No</option>
												  <option value="Inconsistent customer details">Inconsistent Customer Details</option>
												</select>
											</div>
										</div>
									</div>
								</td>
							</tr>					
								
							
							<tr>
							<td colspan="3" align="center">
							<div class="form-group form-group-sm">
								<div>
									<?php
									if($no_of_declined_post == 0) {
										echo '
										<span id="span_reject" style="display: none;"> 
											<input type="submit" name="mybutton" value="Decline Payment" alt="Decline" class="btn btn-sm btn-danger">
										</span>';
										
										echo '
										<span id="span_approve" style="display: none;"> 
											<input type="submit" name="mybutton" value="Approve Payment" alt="Approve" class="btn btn-sm btn-success">
										</span>';
									} else {
										echo '
										<span style="color:#ec7063; font-weight:bold;">You have '.$no_of_declined_post.' DECLINED POSTS from previous transactions.</br> Kindly treat before proceeding to post fresh transactions.</br> Thanks.</span>';
									}
									?>
								</div>
							</div>
							</td>
							</tr>
							</table>
						</div>
				</div>

				</fieldset>
			</form>
		</div>
			

	</div>
</div>