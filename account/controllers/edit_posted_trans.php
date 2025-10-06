<div class="container-fluid">
	<div class="col-md-12">		
		<div class="row">
			<form  method="post" id="form" class="form-horizontal" action="edit_posting.php?edit_id=<?php echo $post['id']; ?>" autocomplete="off">
				<fieldset>
				<input type="hidden" name="debit_account" value="<?php if (isset($_GET['edit_id'])) echo $post['debit_account']; ?>" />
				<input type="hidden" name="credit_account" value="<?php if (isset($_GET['edit_id'])) echo $post['credit_account']; ?>" />

				
				<div class="col-md-12">
					<div class="row">
							<!-- Success message -->
							<table class="table table-bordered">
							<?php
							   if ( isset($errMSG) ) {
							?>
								<div class="form-group form-group-sm">
									<div class="alert alert-danger">
										<span class="glyphicon glyphicon-info-sign"></span> <?php echo @$errMSG; ?>
									</div>
								</div>
							<?php
							   }
							?>
							<tr> 
							<!--Table Column 1st Begins Begins here -->
								<td colspan="3">
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="customername" class="control-label col-md-3">Customer Name:</label>
										<div class="col-md-8 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
												<input type="text" name="customername" class="form-control input-sm" placeholder="" value="<?php if (isset($_GET['edit_id'])) echo ucwords(strtolower($post['customer_name'])); ?>" maxlength="100" readonly />
											</div>
										</div>
									</div>

									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="shopno" class="control-label col-md-3">Space No:</label>
										<div class="col-md-8 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
												<input type="text" name="shopno" class="form-control input-sm" placeholder="" value="<?php if (isset($_GET['edit_id'])) echo $post['shop_no']; ?>" readonly />
											</div>
										</div>
									</div>
									
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="location" class="control-label col-md-3">Location:</label>
										<div class="col-md-8 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
												<input type="text" name="location" class="form-control input-sm" placeholder="" value="<?php 
												if (isset($_GET['edit_id'])) {
													$post_shop_no = $post['shop_no'];
													
													$cust_query = "SELECT * ";
													$cust_query .= "FROM customers ";
													$cust_query .= "WHERE shop_no = '$post_shop_no'";
													$cust_result = mysqli_query($dbcon, $cust_query);
													$cust_data = mysqli_fetch_array($cust_result, MYSQLI_ASSOC);
													
													$location = $cust_data['location'];
													echo (strtoupper($location));
												}  
												?>" maxlength="100" readonly />
											</div>
										</div>
									</div>

									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="shopsize" class="control-label col-md-3">Space Size:</label>
										<div class="col-md-5 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
												<input type="text" name="shopsize" class="form-control input-sm" placeholder="" value="<?php if (isset($_GET['edit_id'])) echo $post['shop_size']; ?>" readonly />
											</div>
										</div>
									</div>
								</td>
							</tr>
							
							
							<tr>
								<td colspan="3">
									<div class="form-group form-group-sm">
										<label class="col-md-3 control-label">Date of Payment:</label>
										<div class="col-md-5 date">
											<div class="input-group input-append date">
												<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date of Payment" value="<?php if (isset($_GET['edit_id'])) echo $form_date_of_payment; ?>" readonly />
												<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
											</div>
										</div>
									</div>
									
								</td>
							</tr>


							<tr>
								<td colspan="3">
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="transaction_desc" class="control-label col-md-4">Transaction Description:</label>
										<div class="col-md-7 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
												<input type="text" name="transaction_desc" class="form-control input-sm" placeholder="Transaction description" value="<?php if (isset($_GET['edit_id'])) echo @$post['transaction_desc']; ?>" maxlength="100" />
											</div>
										</div>
									</div>
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="ref_cheque_no" class="control-label col-md-4">Ref/Cheque No:</label>
										<div class="col-md-4 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
												<input type="text" name="ref_cheque_no" class="form-control input-sm" value="<?php if (isset($_GET['edit_id'])) echo $post['cheque_no']; ?>" />
											</div>
										</div>
									</div>
									
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="teller_no" class="control-label col-md-4">Teller No:</label>
										<div class="col-md-4 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
												<input type="text" name="teller_no" class="form-control input-sm" value="<?php if (isset($_GET['edit_id'])) echo $post['teller_no']; ?>" />
											</div>
										</div>
									</div>
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="plate_no" class="control-label col-md-4">Plate No:</label>
										<div class="col-md-4 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
												<input type="text" name="plate_no" class="form-control input-sm" placeholder="Plate No" value="<?php if (isset($_GET['edit_id'])) echo $post['plate_no']; ?>"/>
											</div>
										</div>
									</div>
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="receipt_no" class="control-label col-md-4">Receipt No:</label>
										<div class="col-md-4 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
												<input type="text" name="receipt_no" class="form-control input-sm" value="<?php if (isset($_GET['edit_id'])) echo $post['receipt_no']; ?>" maxlength="7" />
											</div>
										</div>
									</div>
									
									
									<!-- Text input-->			
									<div class="form-group form-group-sm">
										<label for="amount_paid" class="control-label col-md-4">Amount Paid:</label>
										<div class="col-md-4 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="text" name="amount_paid" class="form-control input-sm" placeholder="Amount Paid" value="<?php if (isset($_GET['edit_id'])) echo $post['amount_paid']; ?>" maxlength="20" required readonly />
											</div>
										</div>
									</div>
								</td>
							</tr>					
								
							
							<tr>
							<td colspan="3" align="center">
							<div class="form-group form-group-sm">
								<div>
									<button type="submit" name="btn_update_post" class="btn btn-success">Update Payment Record <span class="glyphicon glyphicon-send"></span></button>
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