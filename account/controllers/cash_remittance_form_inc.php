<form  method="post" id="form" class="form-horizontal" action="cash_remittance_processing.php" autocomplete="off">
	<input type="hidden" name="posting_officer_id" id="officer_id" class="form-control" value="<?php echo $staff['user_id']; ?>" maxlength="50" />
	<input type="hidden" name="posting_officer_name" class="form-control" value="<?php echo $staff['full_name']; ?>" maxlength="50" />

	<div class="form-group form-group-sm">
		<label class="col-sm-4 control-label">Date of Payment:</label>
		<div class="col-sm-6">
			<div class="input-group input-append">
				<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date of Payment" value="<?php 
				if (isset($_POST['date_of_payment'])) {
					list($tiy,$tim,$tid) = explode("-", @$date_of_payment); 
					@$date_of_payment = "$tid/$tim/$tiy";
					echo @$date_of_payment;
				} else {
					echo @$today;
				}
				?>" readonly />
				<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
		</div>
	</div>
	
	<div class="form-group form-group-sm"> 
	  <label for="leasing_officer_in_charge" class="col-sm-4 control-label">Officer:</label>
		<div class="col-sm-7 selectContainer">
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<select name="officer" class="form-control selectpicker" id="officer" required>
				  <option value=" ">Select...</option>
					<?php
						$lo_query = "SELECT * FROM staffs WHERE department='Wealth Creation' AND (level = 'leasing officer' AND inputter_status = 'inputter')";
						$lo_leasing_set = @mysqli_query($dbcon, $lo_query); 
						
						while ($lo_leasing_officer = mysqli_fetch_array($lo_leasing_set, MYSQLI_ASSOC)) {
							$officer_id = $lo_leasing_officer["user_id"];
							$officer_name = $lo_leasing_officer["full_name"];

							echo '<option value="'.$officer_id.'">'.$officer_name.'</option>'; 
						} 
					?>
				</select>
			</div>
		</div>
	</div>
	
	
	<!-- Text input-->			
	<div class="form-group form-group-sm">
		<label for="amount_paid" class="control-label col-md-4">Amount Remitted:</label>
		<div class="col-sm-6 inputGroupContainer">
			<div class="input-group">
				<span class="input-group-addon">&#8358;</span>
				<input type="password" name="amount_paid" id="amount_paid" class="form-control input-sm" placeholder="Amount Paid" value="<?php if (isset($_POST['amount_paid'])) echo @$amount_paid; ?>" maxlength="25" required />
			</div>
		</div>
	</div>
	
	
	<!-- Text input-->			
	<div class="form-group form-group-sm">
		<label for="confirm_amount_paid" class="control-label col-md-4">Confirm Amount Remitted:</label>
		<div class="col-sm-6 inputGroupContainer">
			<div class="input-group">
				<span class="input-group-addon">&#8358;</span>
				<input type="text" name="confirm_amount_paid" id="confirm_amount_paid" class="form-control input-sm" placeholder="Confirm Amount Paid" value="" maxlength="25" required />
			</div>
			<span id="message"></span>
		</div>
	</div>
	
	
	<!-- Text input-->			
	<div class="form-group form-group-sm">
		<label for="no_of_receipts" class="control-label col-md-4">No of Receipts:</label>
		<div class="col-sm-4 inputGroupContainer">
			<div class="input-group">
				<span class="input-group-addon"></span>
				<input type="text" name="no_of_receipts" id="no_of_receipts" class="form-control input-sm" value="<?php if (isset($_POST['no_of_receipts'])) echo @$no_of_receipts; ?>" maxlength="3" required />
			</div>
		</div>
	</div>
	
	
	<div class="form-group form-group-sm"> 
		<label for="category" class="col-sm-4 control-label">Category:</label>
		<div class="col-sm-8 selectContainer">
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<select name="category" class="form-control selectpicker" id="category" required>
					<option value="">Select...</option>
					<option value="Rent">Rent Collection</option>
					<option value="Service Charge">Service Charge Collection</option>
					<option value="Other Collection">Other Collection</option>
				</select>
			</div>
		</div>
	</div>
	
	
	<div class="text-center">
		<div>
			<button type="submit" id="btn_post" name="btn_post" class="btn btn-sm btn-danger">Post Remittance <span class="glyphicon glyphicon-send"></span></button>
			<button type="reset" name="btn_clear" class="btn btn-sm btn-primary">Clear</button>
		</div>
	</div>
</form>