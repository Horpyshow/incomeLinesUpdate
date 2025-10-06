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
			<!-- Debit Account -->
			<div class="mb-4 flex justify-center">
				<div class="w-full max-w-md">
					<label for="debit_account" class="block text-sm font-medium text-gray-700">DR:</label>
					<div class="flex mt-1">
						<span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">DR</span>
						<select 
							name="debit_account" 
							id="debit_account" 
							class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" 
							required
						>
							<option value="">Select debit account</option>
							<option value="10103">Account Till</option> 
							<option value="10150">Wealth Creation Funds Account</option> 
						</select>
					</div>
				</div>
			</div>

			<!-- Credit Account -->
			<div class="mb-4 flex justify-center">
				<div class="w-full max-w-md">
					<label for="credit_account" class="block text-sm font-medium text-gray-700">CR:</label>
					<div class="flex mt-1">
						<span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">CR</span>
						<select 
							name="credit_account" 
							id="credit_account" 
							class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" 
							required
						>
							<option value="">Select credit account</option>
							<?php
								$cquery = "SELECT * FROM accounts WHERE active = 'Yes' AND acct_alias = '$alias' ";
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
	<td colspan="3" class="text-center pt-6">
		<div>
			<?php
				if (@$amt_remitted <= 0 && $menu["department"] == "Wealth Creation") {
					echo '<h4 class="text-red-500 font-semibold">You do not have any unposted remittances for today.</h4>';
				} else {
					if($no_of_declined_post == 0 && $it_status == 0) {
						echo '
						<button 
							type="submit" 
							name="btn_post_'.$income_line.'" 
							class="inline-flex items-center px-5 py-2 bg-red-600 text-white text-sm font-medium rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition"
						>
							Post '.$income_line_desc.'
							<span class="ml-2 glyphicon glyphicon-send"></span>
						</button>

						<button 
							type="reset" 
							name="btn_clear" 
							class="ml-3 inline-flex items-center px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition"
						>
							Clear
						</button>';
					} else {
						if($no_of_declined_post > 0) {
							echo '<h4 class="text-red-500 font-semibold">You have '.$no_of_declined_post.' DECLINED POSTS from previous transactions.</h4>';
						}
						if ($it_status > 0) {
							echo '<h4 class="text-red-500 font-semibold">You have '.$it_status.' WRONG postings with errors from previous transactions.</h4>';
						}
						echo '<h4 class="text-red-500">Kindly treat before proceeding to post fresh transactions. Thanks.</h4>';
					}
				}
			?>
		</div>
	</td>
</tr>
