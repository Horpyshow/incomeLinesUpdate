<?php
	//Remittance input field begins here
		date_default_timezone_set('Africa/Lagos');
		$yesterday = date('Y-m-d',strtotime("-1 days"));
		$today = $yesterday;
		list($tiy,$tim,$tid) = explode("-", $today); 
		$today = "$tid/$tim/$tiy";
?>

<div class="form-group form-group-sm">
	<label class="col-sm-4 control-label"><span style="color:#ec7063; font-weight:bold;">Sunday Market Date:</span></label>
	<div class="col-sm-3">
		<div class="input-group input-append">
			<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date of Payment" value="<?php 
			if (isset($_POST['date_of_payment'])) {
				list($tiy,$tim,$tid) = explode("-", $date_of_payment); 
				$date_of_payment = "$tid/$tim/$tiy";
				echo @$date_of_payment;
			} else {
				echo @$today;
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
?>