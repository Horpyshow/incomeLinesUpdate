<?php
	if (isset($debiterror) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-warning fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$debiterror.'
		</div>
	</div>';
   }
   
   if (isset($crediterror) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-warning fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$crediterror.'
		</div>
	</div>';
   }
	
	if (isset($duplicate_Error) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-warning fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$duplicate_Error.'
		</div>
	</div>';
   }
   
   if (isset($date_Error) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-warning fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$date_Error.'
		</div>
	</div>';
   }
   
   if (isset($receipt_Error) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-success fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$receipt_Error.'
		</div>
	</div>';
   }
   
   if (isset($teller_Error) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-success fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$teller_Error.'
		</div>
	</div>';
   }
   
   if (isset($cheque_Error) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-success fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$cheque_Error.'
		</div>
	</div>';
   }
   
   if (isset($reference_Error) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-success fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$reference_Error.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error1) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error1.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error2) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error2.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error3) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error3.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error4) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error4.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error5) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error5.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error6) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error6.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error7) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error7.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error8) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error8.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error9) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error9.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error10) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error10.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error11) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error11.'
		</div>
	</div>';
   }
   
   if (isset($payment_Error12) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$payment_Error12.'
		</div>
	</div>';
   }
   
   if (isset($loggable_payment) ) {
	echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$loggable_payment.'
		</div>
	</div>';
   }
   
   if (isset($amount_remitted_Error) ) {
	   echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$amount_remitted_Error.'
		</div>
	</div>';
   }
   
   
   if (isset($remittance_bal_Error) ) {
	   echo '
	<div class="form-group form-group-sm">
		<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.@$remittance_bal_Error.'
		</div>
	</div>';
   }
?>