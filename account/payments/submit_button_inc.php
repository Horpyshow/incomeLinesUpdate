<?php 
	if ($menu["department"] == "Wealth Creation" || $menu["level"] == "ce"){ 
	echo '
		<input type="hidden" name="debit_alias" value="till" maxlength="50">
		<input type="hidden" name="credit_alias" value="'.$alias.'" maxlength="50">';
	} 
 
	if ($menu["department"] == "Accounts"){ 
?>
	<tr>
		<td colspan="3">		   
		<div class="form-group form-group-sm"> 
			<div class="col-sm-5 col-sm-offset-4 selectContainer">
				<div class="input-group">
					<span class="input-group-addon">DR:</span>
					<select name="debit_account" class="form-control selectpicker" id="debit_account" required>
						<option value="">Select debit account</option>
						<option value="10103">Account Till</option> 
						<option value="10150">Wealth Creation Funds Account</option> 
					</select>
				</div>
			</div>
		</div>


		<div class="form-group form-group-sm"> 
			<div class="col-sm-5 col-sm-offset-4 selectContainer">
				<div class="input-group">
					<span class="input-group-addon">CR:</span>
					<select name="credit_account" class="form-control selectpicker" id="credit_account" required>
						<option value="">Select credit account</option>
						<?php
							$cquery = "SELECT * FROM accounts ";
							$cquery .= "WHERE active = 'Yes' ";
							$cquery .= "AND acct_alias = '$alias' ";
							$caccount_set = mysqli_query($dbcon, $cquery); 
							
							$caccount = mysqli_fetch_array($caccount_set, MYSQLI_ASSOC);
						?>
						<option value="<?php echo $caccount['acct_id']; ?>"><?php echo ucwords(strtolower($caccount['acct_desc'])); ?></option> 
					</select>
				</div>
			</div>
		</div>
		</td>
	</tr>
	<?php
		}
	?>

	<tr>
		<td colspan="3" align="center">
			<div class="form-group form-group-sm">
				<div>
					<?php
						// if ($current_time >= $wc_begin_time && $current_time <= $wc_end_time && $menu["department"] == "Wealth Creation"){
						// 	echo '<h4><span style="color:#ec7063; font-weight:bold;">Posting automatically disabled till tomorrow!</span></h4>';
						// } elseif (@$amt_remitted <= 0 && $menu["department"] == "Wealth Creation") {
						// 	echo '<h4><span style="color:#ec7063; font-weight:bold;">You do not have any unposted remittances for today.</h4>';
						// } else {
						// 	if($no_of_declined_post == 0 && $it_status == 0) {
						// 		echo '
						// 		<button type="submit" name="btn_post_'.$income_line.'" class="btn btn-danger btn-sm">Post '.$income_line_desc.' <span class="glyphicon glyphicon-send"></span></button>
						// 		<button type="reset" name="btn_clear" class="btn btn-primary btn-sm">Clear</button>';
						// 	} else {
						// 		if($no_of_declined_post > 0) {
						// 			echo '<h4><span style="color:#ec7063; font-weight:bold;">You have '.$no_of_declined_post.' DECLINED POSTS from previous transactions.';
						// 		}
						// 		if ($it_status > 0) {
						// 			echo '<h4><span style="color:#ec7063; font-weight:bold;">You have '.$it_status.' WRONG postings with errors from previous transactions.';
						// 		}
						// 		echo 'Kindly treat before proceeding to post fresh transactions. Thanks.</span></h4>';
						// 	}
						// }
					?>
					<?php
						if (@$amt_remitted <= 0 && $menu["department"] == "Wealth Creation") {
							echo '<h4><span style="color:#ec7063; font-weight:bold;">You do not have any unposted remittances for today.</h4>';
						} else {
							if($no_of_declined_post == 0 && $it_status == 0) {
								echo '
								<button type="submit" name="btn_post_'.$income_line.'" class="btn btn-danger btn-sm">Post '.$income_line_desc.' <span class="glyphicon glyphicon-send"></span></button>
								<button type="reset" name="btn_clear" class="btn btn-primary btn-sm">Clear</button>';
							} else {
								if($no_of_declined_post > 0) {
									echo '<h4><span style="color:#ec7063; font-weight:bold;">You have '.$no_of_declined_post.' DECLINED POSTS from previous transactions.';
								}
								if ($it_status > 0) {
									echo '<h4><span style="color:#ec7063; font-weight:bold;">You have '.$it_status.' WRONG postings with errors from previous transactions.';
								}
								echo 'Kindly treat before proceeding to post fresh transactions. Thanks.</span></h4>';
							}
						}
					?>
				</div>
			</div>
		</td>
	</tr>