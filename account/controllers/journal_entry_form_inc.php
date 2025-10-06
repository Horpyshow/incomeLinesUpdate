<?php 
if (isset($_GET['repost_id'])) {
	$repost_id = $_GET['repost_id'];
	
	echo '<form  method="post" id="form" class="form-horizontal" action="journal_entry'.$suffix.'.php?repost_id='.$repost_id.'" autocomplete="off">';
} else {
	echo '<form  method="post" id="form" class="form-horizontal" action="journal_entry'.$suffix.'.php" autocomplete="off">';
}
?>
				<fieldset>
					<?php include ('controllers/error_messages_inc.php'); ?>
					<table class="table table-bordered">
						<tr >
							<td colspan="2">
								<input type="hidden" name="posting_officer_id" class="form-control" placeholder=" " value="<?php echo $staff['user_id']; ?>" maxlength="50" />
								<input type="hidden" name="posting_officer_name" class="form-control" placeholder=" " value="<?php echo $staff['full_name']; ?>" maxlength="50" />
								
								<div class="form-group form-group-sm">
									<label class="col-md-2 control-label">Date:</label>
									<div class="col-md-4 date">
										<div class="input-group input-append date" id="date_of_payment">
											<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date" value="<?php 
											if (isset($_POST['date_of_payment'])) {
												list($tiy,$tim,$tid) = explode("-", $date_of_payment); 
												$date_of_payment = "$tid/$tim/$tiy";
												echo @$date_of_payment;
											}
											?>" required />
											<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										</div>
									</div>
								</div>
								
								
								<div class="form-group form-group-sm">
									<label for="reference_no" class="col-md-2 control-label">Reference No:</label>
									<div class="col-md-4 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
											<input type="text" class="form-control input-sm" name="reference_no" placeholder="Reference No" maxlength="10" value="<?php  if (isset($_POST['reference_no'])) echo @$reference_no; ?>" />
										</div>
									</div>
								</div>
								
								
								<div class="form-group form-group-sm">
									<label for="receipt_no" class="col-md-2 control-label">Receipt No:</label>
									<div class="col-md-4 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
											<input type="text" name="receipt_no" class="form-control input-sm" placeholder="Receipt No" pattern="^\d{7}$" value="<?php if (isset($_POST['receipt_no'])) echo @$receipt_no; ?>" maxlength="7" required />
										</div>
									</div>
								</div>
								
								
								<div class="form-group form-group-sm">
									<label for="reference_no" class="col-md-2 control-label">Plate No:</label>
									<div class="col-md-4 inputGroupContainer">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
											<input type="text" class="form-control input-sm" name="plate_no" placeholder="Reference No" value="<?php  if (isset($_POST['plate_no'])) echo @$plate_no; ?>" />
										</div>
									</div>
								</div>
							</td>
							
							<td colspan="2" class="text-right">
								<?php
								if (isset($_GET['repost_id'])) {
									echo '
										<button type="submit" id="btn_post_journal_edit" name="btn_post_journal_edit" class="btn btn-danger btn-sm">Post Entry <span class="glyphicon glyphicon-send"></span></button>
										<button type="reset" name="btn_clear" class="btn btn-primary btn-sm">Clear</button>';
								} else {
									if($no_of_declined_post == 0 && $no_of_declined_acct_post == 0) {
										echo '
										<button type="submit" id="btn_post_journal" name="btn_post_journal" class="btn btn-danger btn-sm">Post Entry <span class="glyphicon glyphicon-send"></span></button>
										<button type="reset" name="btn_clear" class="btn btn-primary btn-sm">Clear</button>';
									} else {
										if($no_of_declined_post > 0) {
											echo '
											<h4><span style="color:#ec7063; font-weight:bold;">You have '.$no_of_declined_post.' DECLINED POSTS from previous transactions. Kindly treat before proceeding to post fresh transactions. Thanks.</span>
											</h4>';
										}
										if($no_of_declined_acct_post > 0) {
											echo '
											<h4><span style="color:#ec7063; font-weight:bold;">You have '.$no_of_declined_acct_post.' DECLINED APPROVALS from previous transactions. Kindly treat before proceeding to post fresh transactions. Thanks.</span>
											</h4>';
										}
									}
								}
								?>
							</td>
						</tr>
					</table>
					
					
					<table class="table table-bordered">
						<thead>
							<tr class="info text-info">
								<th width="24%">ACCOUNT TYPE</th>
								<th width="36%">TRANSACTION DESCRIPTION</th>
								<th width="20%" class="text-center">&#8358; </br>DEBIT AMOUNT</th>
								<th width="20%" class="text-center">&#8358; </br>CREDIT AMOUNT</th>
							</tr>
						</thead>
							<tr>
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 selectContainer">
											<div class="input-group">
												<span class="input-group-addon"></span>
												<select name="debit_account1" class="form-control selectpicker" id="debit_account1" required>
												  <option value="">Select Debit Account</option>
													<?php
													$dquery1 = "SELECT * FROM {$prefix}accounts ";
													$dquery1 .= "WHERE active = 'Yes' ";
													$dquery1 .= "AND income_line = '' ";
													$dquery1 .= "ORDER BY acct_desc ASC";
													$daccount_set1 = @mysqli_query($dbcon, $dquery1); 
													
													while ($daccount1 = mysqli_fetch_array($daccount_set1, MYSQLI_ASSOC)) {

													echo '<option value="'; ?><?php echo $daccount1['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($daccount1['acct_desc'])); ?><?php echo '</option>'; } 
													
													?>
												</select>
											</div>
										</div>
									</div>
								</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
												<input type="text" name="dtransaction_desc1" class="form-control input-sm" placeholder="Debit transaction description 1" value="<?php if (isset($_POST['dtransaction_desc1'])) echo @$dtransaction_desc1; ?>" maxlength="100" required />
											</div>
										</div>
									</div>
								</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="debit_amount1" id="debit_amount1" onkeyup="balCalc()" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['debit_amount1'])) echo @$debit_amount1; ?>" maxlength="20" />
											</div>
										</div>
									</div>
								</th>
								
								<th></th>
							</tr>
							
							
							<tr>
								<th>
									<div class="form-group form-group-sm"> 
										<div class="col-md-12 selectContainer">
											<div class="input-group">
												<span class="input-group-addon"></span>
												<select name="credit_account1" class="form-control selectpicker" id="credit_account1" required>
												  <option value="">Select Credit Account</option>
													<?php
													$cquery1 = "SELECT * FROM {$prefix}accounts ";
													$cquery1 .= "WHERE active = 'Yes' ";
													$cquery1 .= "AND income_line = '' ";
													$cquery1 .= "ORDER BY acct_desc ASC";
													$caccount_set1 = @mysqli_query($dbcon, $cquery1); 
													
													while ($caccount1 = mysqli_fetch_array($caccount_set1, MYSQLI_ASSOC)) {

													echo '<option value="'; ?><?php echo $caccount1['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($caccount1['acct_desc'])); ?><?php echo '</option>'; } 
													
													?>
												</select>
											</div>
										</div>
									</div>
								</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
												<input type="text" name="ctransaction_desc1" class="form-control input-sm" placeholder="Credit transaction description 1" value="<?php if (isset($_POST['ctransaction_desc1'])) echo @$ctransaction_desc1; ?>" maxlength="100" required />
											</div>
										</div>
									</div>
								</th>
								
								<th></th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="credit_amount1" id="credit_amount1" onkeyup="balCalc()" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['credit_amount1'])) echo @$credit_amount1; ?>" maxlength="20" />
											</div>
										</div>
									</div>
								</th>
							</tr>
							
							
							
							<tr>
								<th>
									<div class="form-group form-group-sm"> 
										<div class="col-md-12 selectContainer">
											<div class="input-group">
												<span class="input-group-addon"></span>
												<select name="credit_account2" class="form-control selectpicker" id="credit_account2" >
												  <option value="">Select Credit Account</option>
													<?php
													$cquery2 = "SELECT * FROM {$prefix}accounts ";
													$cquery2 .= "WHERE active = 'Yes' ";
													$cquery2 .= "AND income_line = '' ";
													$cquery2 .= "ORDER BY acct_desc ASC";
													$caccount_set2 = @mysqli_query($dbcon, $cquery2); 
													
													while ($caccount2 = mysqli_fetch_array($caccount_set2, MYSQLI_ASSOC)) {

													echo '<option value="'; ?><?php echo $caccount2['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($caccount2['acct_desc'])); ?><?php echo '</option>'; } 
													
													?>
												</select>
											</div>
										</div>
									</div>
								</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
												<input type="text" name="ctransaction_desc2" class="form-control input-sm" placeholder="Credit transaction description 2" value="<?php if (isset($_POST['ctransaction_desc2'])) echo @$ctransaction_desc2; ?>" maxlength="100" />
											</div>
										</div>
									</div>
								</th>
								
								<th></th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="credit_amount2" id="credit_amount2" onkeyup="balCalc()" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['credit_amount2'])) echo @$credit_amount2; ?>" maxlength="20" />
											</div>
										</div>
									</div>
								</th>
							</tr>
							
							
							
							<tr>
								<th>
									<div class="form-group form-group-sm"> 
										<div class="col-md-12 selectContainer">
											<div class="input-group">
												<span class="input-group-addon"></span>
												<select name="credit_account3" class="form-control selectpicker" id="credit_account3" >
												  <option value="">Select Credit Account</option>
													<?php
													$cquery3 = "SELECT * FROM {$prefix}accounts ";
													$cquery3 .= "WHERE active = 'Yes' ";
													$cquery3 .= "AND income_line = '' ";
													$cquery3 .= "ORDER BY acct_desc ASC";
													$caccount_set3 = @mysqli_query($dbcon, $cquery3); 
													
													while ($caccount3 = mysqli_fetch_array($caccount_set3, MYSQLI_ASSOC)) {

													echo '<option value="'; ?><?php echo $caccount3['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($caccount3['acct_desc'])); ?><?php echo '</option>'; } 
													
													?>
												</select>
											</div>
										</div>
									</div>
								</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
												<input type="text" name="ctransaction_desc3" class="form-control input-sm" placeholder="Credit transaction description 3" value="<?php if (isset($_POST['ctransaction_desc3'])) echo @$ctransaction_desc3; ?>" maxlength="100" />
											</div>
										</div>
									</div>
								</th>
								
								<th></th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="credit_amount3" id="credit_amount3" onkeyup="balCalc()" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['credit_amount3'])) echo @$credit_amount3; ?>" maxlength="20" />
											</div>
										</div>
									</div>
								</th>
							</tr>
							
							
							
							<tr>
								<th>
									<div class="form-group form-group-sm"> 
										<div class="col-md-12 selectContainer">
											<div class="input-group">
												<span class="input-group-addon"></span>
												<select name="credit_account4" class="form-control selectpicker" id="credit_account4" >
												  <option value="">Select Credit Account</option>
													<?php
													$cquery4 = "SELECT * FROM {$prefix}accounts ";
													$cquery4 .= "WHERE active = 'Yes' ";
													$cquery4 .= "AND income_line = '' ";
													$cquery4 .= "ORDER BY acct_desc ASC";
													$caccount_set4 = @mysqli_query($dbcon, $cquery4); 
													
													while ($caccount4 = mysqli_fetch_array($caccount_set4, MYSQLI_ASSOC)) {

													echo '<option value="'; ?><?php echo $caccount4['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($caccount4['acct_desc'])); ?><?php echo '</option>'; } 
													
													?>
												</select>
											</div>
										</div>
									</div>
								</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
												<input type="text" name="ctransaction_desc4" class="form-control input-sm" placeholder="Credit transaction description 4" value="<?php if (isset($_POST['ctransaction_desc4'])) echo @$ctransaction_desc4; ?>" maxlength="100" />
											</div>
										</div>
									</div>
								</th>
								
								<th></th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="credit_amount4" id="credit_amount4" onkeyup="balCalc()" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['credit_amount4'])) echo @$credit_amount4; ?>" maxlength="20" />
											</div>
										</div>
									</div>
								</th>
							</tr>
							
							
							
							<tr>
								<th>
									<div class="form-group form-group-sm"> 
										<div class="col-md-12 selectContainer">
											<div class="input-group">
												<span class="input-group-addon"></span>
												<select name="credit_account5" class="form-control selectpicker" id="credit_account5">
												  <option value="">Select Credit Account</option>
													<?php
													$cquery5 = "SELECT * FROM {$prefix}accounts ";
													$cquery5 .= "WHERE active = 'Yes' ";
													$cquery5 .= "AND income_line = '' ";
													$cquery5 .= "ORDER BY acct_desc ASC";
													$caccount_set5 = @mysqli_query($dbcon, $cquery5); 
													
													while ($caccount5 = mysqli_fetch_array($caccount_set5, MYSQLI_ASSOC)) {

													echo '<option value="'; ?><?php echo $caccount5['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($caccount5['acct_desc'])); ?><?php echo '</option>'; } 
													
													?>
												</select>
											</div>
										</div>
									</div>
								</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
												<input type="text" name="ctransaction_desc5" class="form-control input-sm" placeholder="Credit transaction description 5" value="<?php if (isset($_POST['ctransaction_desc5'])) echo @$ctransaction_desc5; ?>" maxlength="100" />
											</div>
										</div>
									</div>
								</th>
								
								<th></th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="credit_amount5" id="credit_amount5" onkeyup="balCalc()" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['credit_amount5'])) echo @$credit_amount5; ?>" maxlength="20" />
											</div>
										</div>
									</div>
								</th>
							</tr>
							
							
							
							<tr>
								<th>
									<div class="form-group form-group-sm"> 
										<div class="col-md-12 selectContainer">
											<div class="input-group">
												<span class="input-group-addon"></span>
												<select name="credit_account6" class="form-control selectpicker" id="credit_account6" >
												  <option value="">Select Credit Account</option>
													<?php
													$cquery6 = "SELECT * FROM {$prefix}accounts ";
													$cquery6 .= "WHERE active = 'Yes' ";
													$cquery6 .= "AND income_line = '' ";
													$cquery6 .= "ORDER BY acct_desc ASC";
													$caccount_set6 = @mysqli_query($dbcon, $cquery6); 
													
													while ($caccount6 = mysqli_fetch_array($caccount_set6, MYSQLI_ASSOC)) {

													echo '<option value="'; ?><?php echo $caccount6['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($caccount6['acct_desc'])); ?><?php echo '</option>'; } 
													
													?>
												</select>
											</div>
										</div>
									</div>
								</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
												<input type="text" name="ctransaction_desc6" class="form-control input-sm" placeholder="Credit transaction description 6" value="<?php if (isset($_POST['ctransaction_desc6'])) echo @$ctransaction_desc6; ?>" maxlength="100" />
											</div>
										</div>
									</div>
								</th>
								
								<th></th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="credit_amount6" id="credit_amount6" onkeyup="balCalc()" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['credit_amount6'])) echo @$credit_amount6; ?>" maxlength="20" />
											</div>
										</div>
									</div>
								</th>
							</tr>
							
							
							
							<tr>
								<th>
									<div class="form-group form-group-sm"> 
										<div class="col-md-12 selectContainer">
											<div class="input-group">
												<span class="input-group-addon"></span>
												<select name="credit_account7" class="form-control selectpicker" id="credit_account7" >
												  <option value="">Select Credit Account</option>
													<?php
													$cquery7 = "SELECT * FROM {$prefix}accounts ";
													$cquery7 .= "WHERE active = 'Yes' ";
													$cquery7 .= "AND income_line = '' ";
													$cquery7 .= "ORDER BY acct_desc ASC";
													$caccount_set7 = @mysqli_query($dbcon, $cquery7); 
													
													while ($caccount7 = mysqli_fetch_array($caccount_set7, MYSQLI_ASSOC)) {

													echo '<option value="'; ?><?php echo $caccount7['acct_id']; ?><?php echo '">'; ?><?php echo ucwords(strtolower($caccount7['acct_desc'])); ?><?php echo '</option>'; } 
													
													?>
												</select>
											</div>
										</div>
									</div>
								</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
												<input type="text" name="ctransaction_desc7" class="form-control input-sm" placeholder="Credit transaction description 7" value="<?php if (isset($_POST['ctransaction_desc7'])) echo @$ctransaction_desc7; ?>" maxlength="100" />
											</div>
										</div>
									</div>
								</th>
								
								<th></th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="credit_amount7" id="credit_amount7" onkeyup="balCalc()" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['credit_amount7'])) echo @$credit_amount7; ?>" maxlength="20" />
											</div>
										</div>
									</div>
								</th>
							</tr>
							
							

							<tr class="danger">
								<th class="text-right" colspan="2">TOTAL</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="total_debit_amount" id="total_debit_amount" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['total_debit_amount'])) echo @$total_debit_amount; ?>" maxlength="20" readonly />
											</div>
										</div>
									</div>
								</th>
								
								<th>
									<div class="form-group form-group-sm">
										<div class="col-md-12 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="total_credit_amount" id="total_credit_amount" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['total_credit_amount'])) echo @$total_credit_amount; ?>" maxlength="20" readonly />
											</div>
										</div>
									</div>
								</th>
							</tr>
							
							<tr class="danger">
								<th class="text-right" colspan="2">OUT OF BALANCE</th>
								<th colspan="2">
									<div class="form-group form-group-sm">
										<div class="col-md-6 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">&#8358;</span>
												<input type="tel" name="balance" id="balance" class="form-control input-sm" placeholder="0.00" value="<?php if (isset($_POST['total_credit_amount'])) echo @$total_credit_amount; ?>" maxlength="20" readonly />
											</div>
										</div>
									</div>
								</th>
							</tr>
					</table>
				</fieldset>
			</form>