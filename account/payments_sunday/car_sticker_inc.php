<form  method="post" id="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
	<fieldset>
		<input type="hidden" name="posting_officer_id" class="form-control" placeholder="" value="<?php echo $staff['user_id']; ?>" maxlength="50" />
		<input type="hidden" name="posting_officer_name" class="form-control" placeholder="" value="<?php echo $staff['full_name']; ?>" maxlength="50">
		<input type="hidden" name="income_line" value="<?php echo $income_line; ?>" maxlength="50">
		<input type="hidden" name="posting_officer_dept" value="<?php echo $menu['department']; ?>">
		
		
		<table class="table table-bordered">
			<tr> 
			<td colspan="3">
				<?php include 'payments_sunday/remittance_form_inc.php'; ?>
				
				<?php
					DEFINE ('WRL_USER', 'root');
					DEFINE ('WRL_PASSWORD', '');
					DEFINE ('WRL_HOST', 'localhost');
					DEFINE ('WRL_NAME', 'woobsres_woobserp');

					$dblink = @mysqli_connect (WRL_HOST, WRL_USER, WRL_PASSWORD, WRL_NAME)
					OR die ('Could not connect to MySQL: ' . mysqli_connect_error () ); 
					mysqli_set_charset($dblink, 'utf8');
						
					$query = "SELECT * ";
					$query .= "FROM customers ";
					$query .= " ORDER BY shop_no ASC";
					$result_set = @mysqli_query($dblink, $query); 
				?>
				
				<?php
					$staff_name = $staff['full_name'];
						
					$wcquery = "SELECT * ";
					$wcquery .= "FROM customers ";
					$wcquery .= "WHERE (facility_type = 'Coldroom' OR facility_type = 'Container' OR facility_type = 'Kclamp') ";
					$wcquery .= "AND shop_no != '' ";
					$wcquery .= "ORDER BY shop_no ASC";
					$wcresult_set = @mysqli_query($dbcon, $wcquery); 
				?>
				
				<div class="form-group form-group-sm"> 
				  <label for="shop_no" class="col-sm-4 control-label">Shop No:</label>
					<div class="col-sm-5 selectContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<select name="shop_no" class="form-control selectpicker" id="shop_no" required>
							  <option value="">Select...</option>
								<?php
									while($wcshop = mysqli_fetch_array($wcresult_set, MYSQLI_ASSOC))
									{
										?>
										<option value="<?php echo $wcshop['shop_no'].'-'.$wcshop['customer_name']; ?>"><?php echo $wcshop['shop_no'].' - '.$wcshop['customer_name']; ?></option>
										<?php
									} 
								?>
								<?php
									while($shop = mysqli_fetch_array($result_set, MYSQLI_ASSOC))
									{
										//if($shop['facility_type'] = 'Container') {
											
										?>
										<option value="<?php echo $shop['shop_no'].'-'.$shop['customer_name']; ?>"><?php echo $shop['shop_no'].' - '.$shop['customer_name']; ?></option>
										<?php
									} 
								?>
							</select>
						</div>
					</div>
				</div>
				

				<div class="form-group form-group-sm"> 
				  <label for="sticker_no" class="col-md-4 control-label">Sticker No:</label>
					<div class="col-md-3 selectContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
							<select name="sticker_no" class="form-control selectpicker" id="sticker_no" onBlur="loadCalc()" required >
								<option value="">Select sticker</option>
								<?php
									$query3 = "SELECT * FROM car_sticker ";
									$query3 .= "WHERE status = '' ";
									$query3 .= "ORDER BY sticker_no ASC ";
									$sticker_set = @mysqli_query($dbcon, $query3); 
									
									while ($sticker = mysqli_fetch_array($sticker_set, MYSQLI_ASSOC)) {

									echo '<option value="'.$sticker['sticker_no'].'">'.$sticker['sticker_no'].'</option>'; 
									}
								?>
							</select>
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
							<input type="text" id="amount_paid" name="amount_paid" class="form-control input-sm" placeholder="Amount Remitted" value="50000" maxlength="20" onBlur="loadCalc()" readonly />
						</div>
					</div>
				</div>
			</td>
			</tr>
			
			<?php include 'payments_sunday/submit_button_inc.php'; ?>	
		</table>
	
	</fieldset>
</form>