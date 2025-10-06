<div class="form-group form-group-sm">
	<label class="col-md-4 control-label">Date of Payment:</label>
	<div class="col-md-3 date">
		<div class="input-group input-append date" id="date_of_payment">
			<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date of Payment" value="<?php  if (isset($_POST['date_of_payment'])) echo @$date_of_payment; ?>" required />
			<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		</div>
	</div>
</div>
