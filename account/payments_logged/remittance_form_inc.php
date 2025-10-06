<?php
	//Remittance input field begins here
	
	//if($session_department == "Wealth Creation") {
		
		date_default_timezone_set('Africa/Lagos');
		$today = date('Y-m-d');
		list($tiy,$tim,$tid) = explode("-", $today); 
		$today = "$tid/$tim/$tiy";
		
		
?>

<div class="form-group form-group-sm">
	<label class="col-sm-4 control-label">Date of Payment:</label>
	<div class="col-sm-3">
		<div class="input-group input-append">
			<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date of Payment" value="<?php 
			if (isset($remit_id)) {
				list($tiy,$tim,$tid) = explode("-", $current_date); 
				$date_of_payment = "$tid/$tim/$tiy";
				echo @$date_of_payment;
			}
			?>" readonly />
			<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		</div>
	</div>
</div>

<?php


if($session_department == "Wealth Creation") {
	$unposted = number_format((float)$unposted, 0);
?>
<div class="form-group form-group-sm"> 
  <label for="remit_id" class="col-sm-4 control-label">Remittances:</label>
	<div class="col-sm-5 selectContainer">
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-signal"></i></span>
			<select name="remit_id" class="form-control selectpicker" id="remit_id" required >
				<option value="">Select...</option>
				<option value="<?php echo $remit_id; ?>"><?php echo $date.': Remittance - N'.$unposted; ?></option>
			</select>
		</div>
	</div>
</div>

<?php
	} 
	//else {
		/*
?>

<div class="form-group form-group-sm">
	<label class="col-md-4 control-label">Date of Payment:</label>
	<div class="col-md-3 date">
		<div class="input-group input-append date" id="date_of_payment">
			<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date of Payment" value="<?php  if (isset($_POST['date_of_payment'])) echo @$date_of_payment; ?>" required />
			<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		</div>
	</div>
</div>

<?php
*/
	//}
	//Remittance input field ends here
?>