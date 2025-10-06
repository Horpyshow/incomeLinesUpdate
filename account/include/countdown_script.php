<style type="text/css">
.blinking{
	animation:blinkingText 1.0s infinite;
}
</style>
<h4 style="line-height: 30px; color:#ec7063;">
	<strong>
	<?php
		$del_query = "SELECT * ";
		$del_query .= "FROM {$prefix}account_general_transaction_new ";
		$del_query .= "WHERE (leasing_post_status = 'Pending' OR approval_status = 'Pending') ";
		
		$del_query .= "AND date_of_payment < DATE_FORMAT(NOW() ,'%Y-%m-%d') ";
		$del_result = @mysqli_query($dbcon, $del_query);
		$pending_delete_count = mysqli_num_rows($del_result);
		
		
		
		$pquery = "SELECT * ";
		$pquery .= "FROM pending_delete_report ";
		$pquery .= "WHERE date = DATE_FORMAT(NOW() ,'%Y-%m-%d') ";
		$presult = @mysqli_query($dbcon, $pquery);
		
		$del_report = @mysqli_fetch_array($presult, MYSQLI_ASSOC);
		$del_report_count = $del_report['count'];
		
		

		date_default_timezone_set('Africa/Lagos');
		$now = date('Y-m-d H:i:s');

		$wipe_date = strtotime($now);
		$wipe_date2 = strtotime($now);
		$wipe_date = date('M j, Y', $wipe_date);
		$wipe_date2 = date('jS M Y', $wipe_date2);
	?>

		<!-- Display the countdown timer in an element -->
		<span id="text_notice"></span>
		<span id="counter" class="blinking"></span>

		<script>
		// Set the date we're counting down to
		var countDownDate = new Date("<?php echo $wipe_date; ?> 12:00:00").getTime();

		// Update the count down every 1 second
		var x = setInterval(function() {

		  // Get today's date and time
		  var now = new Date().getTime();

		  // Find the distance between now and the count down date
		  var distance = countDownDate - now;

		  // Time calculations for days, hours, minutes and seconds
		  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		  // Display the result in the element with id="demo"
		  //document.getElementById("text_notice").innerHTML = "ATTENTION: All <?php echo $pending_delete_count; ?> PENDING RECORDS will be automatically wiped off by 12PM in: ";
		  //document.getElementById("counter").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

		  // If the count down is finished, write some text
		  if (distance < 0) {
			clearInterval(x);
			//document.getElementById("text_notice").innerHTML = "ATTENTION: <?php echo $del_report_count; ?> PENDING RECORDS wiped off today, <?php echo $wipe_date2; ?> at 12:00PM";
			//document.getElementById("counter").innerHTML = "";
		  }
		}, 1000);
		</script>
	</strong>
</h4>