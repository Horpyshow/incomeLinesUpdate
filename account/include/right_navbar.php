<div class="row">
	<?php $i=0; ?>
	<table class="table table-hover table-bordered">
		<tbody>
			<?php
			if (isset($_GET['txref'])) {
				$query = "SELECT * FROM account_general_transaction_new ";
				$query .= "WHERE leasing_post_status = 'Pending' ";
				$query .= "AND posting_officer_id = $leasing_officer_id";
				$leasing_post_set = @mysqli_query($dbcon,$query);

				while ($leasing_post = @mysqli_fetch_array($leasing_post_set, MYSQLI_ASSOC)) {
				
				$txref_id = $leasing_post['id'];
			?>
			<tr>
				<td><?php echo $leasing_post['date_of_payment']; ?></td>
				<td>
					<a href="javascript:txref('<?php echo $txref_id; ?>')" class="btn btn-sm btn-success"><?php echo 'View</a> '.$leasing_post['customer_name'].' - <strong>'.strtoupper($leasing_post['plate_no']).'</strong> - '.$leasing_post['transaction_desc']; ?>
				</td>
				<td>

				</td>
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