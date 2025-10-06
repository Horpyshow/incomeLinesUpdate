<div class="row">
	<table class="table table-hover table-bordered">
		<thead>
			<tr class="danger">
				<td colspan="2"><strong>Payment Breakdown</strong></td>
			</tr>
		</thead>
		<tbody>
			<?php
			if (isset($_GET['txref']) && $payment_category == "Rent") {
				$txref_id = $_GET["txref"];
				
				$query = "SELECT * FROM collection_analysis ";
				$query .= "WHERE trans_id = '$txref_id'";
				$collection_post_set = mysqli_query($dbcon,$query);

				while ($collection_post = mysqli_fetch_array($collection_post_set, MYSQLI_ASSOC)) {
			?>
			<tr>
				<td><strong><?php echo $collection_post['payment_month']; ?></strong></td>
				<td class="text-right"><strong><span style="color:#ec7063;"><?php echo $collection_post['amount_paid']; ?></span></strong></td>
			</tr>
			<?php
				}
			} elseif (isset($_GET['txref']) && $payment_category == "Service Charge") {
				$txref_id = $_GET["txref"];
				
				$query = "SELECT * FROM collection_analysis_arena ";
				$query .= "WHERE trans_id = '$txref_id'";
				$collection_post_set = mysqli_query($dbcon,$query);

				while ($collection_post = mysqli_fetch_array($collection_post_set, MYSQLI_ASSOC)) {
			?>
			<tr>
				<td><strong><?php echo $collection_post['payment_month']; ?></strong></td>
				<td class="text-right"><strong><span style="color:#ec7063;"><?php echo $collection_post['amount_paid']; ?></span></strong></td>
			</tr>
			<?php
				}
			} else {
				echo "";
			}
			?>					
		</tbody>
	</table>
</div>