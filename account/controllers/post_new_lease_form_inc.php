<form  method="post" id="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
<fieldset>
<div class="col-sm-7">
	<?php include ('controllers/error_messages_inc.php'); ?>
		<table>
			<tr>
				<input type="hidden" name="posting_officer_id" id="officer_id" class="form-control" value="<?php echo $staff['user_id']; ?>" maxlength="50" />
				<input type="hidden" name="posting_officer_name" class="form-control" value="<?php echo $staff['full_name']; ?>" maxlength="50" />
				<input type="hidden" name="shopid" id="shopid" class="form-control input-sm" value="<?php if (isset($_POST["shopid"])) echo @$shop_id; ?>"/>
				<input type="hidden" name="ref_no" id="ref_no" class="form-control" placeholder=" " value="<?php if (isset($_POST["ref_no"])) echo $ref_no; ?>" />

				<?php
					$staff_name = $staff['full_name'];
						
					$query = "SELECT * ";
					$query .= "FROM customers_new_registration ";
					$query .= "WHERE rent_paid = '' ";
					$query .= "AND record_creation_status  = 'Approved' ";
					$query .= "ORDER BY shop_no ASC";
					$result_set = @mysqli_query($dbcon, $query); 
				?>
				
				<div class="form-group form-group-sm"> 
				  <label for="shop_id" class="col-sm-2 control-label">Shop:</label>
					<div class="col-sm-5 selectContainer">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<select name="shop_id" class="form-control selectpicker" id="shop_id">
							  <option value="">Select...</option>
								<?php
									while($shop = mysqli_fetch_array($result_set, MYSQLI_ASSOC))
									{
										?>
										<option value="<?php echo $shop['id']; ?>"><?php echo $shop['shop_no']; ?></option>
										<?php
									} 
								?>
							</select>
						</div>
					</div>
					
					<h5><strong><span style="color:#ec7063;">Last payment <span class="glyphicon glyphicon-arrow-right"></span></span></strong> <a data-toggle="modal" data-target="#modal2"><span id="shop"></span></a></h5> 
				</div>
			
			</tr>
		
		
		
			<tr>
			<div id="row_month1">
			<div class="form-group form-group-sm">
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month1" class="form-control selectpicker" id="month1" onChange="loadCalc()" required><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year1" class="form-control selectpicker" id="year1" onBlur="loadCalc()" required><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year1" id="month_year1" value="" onChange="loadCalc()" />
					</div>
				</div>
				

				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount1" id="amount1" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" required />
					</div>
				</div>
			</div>
			</div><!--row_month1-->
			
			
			<!-- Row 2 ---->
			<div id="row_month2">
			<div class="form-group form-group-sm">
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month2" class="form-control selectpicker" id="month2" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year2" class="form-control selectpicker" id="year2" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year2" id="month_year2" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount2" id="amount2" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month2-->
			
			
			
			<!-- Row 3 ---->
			<div id="row_month3">
			<div class="form-group form-group-sm">
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month3" class="form-control selectpicker" id="month3" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year3" class="form-control selectpicker" id="year3" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year3" id="month_year3" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount3" id="amount3" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month3-->
			
			
			
			<!-- Row 4 ---->
			<div id="row_month4">
			<div class="form-group form-group-sm">
				
				
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month4" class="form-control selectpicker" id="month4" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year4" class="form-control selectpicker" id="year4" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year4" id="month_year4" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount4" id="amount4" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month4-->
			
			
			
			<!-- Row 5 ---->
			<div id="row_month5">
			<div class="form-group form-group-sm">
				
				
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month5" class="form-control selectpicker" id="month5" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year5" class="form-control selectpicker" id="year5" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year5" id="month_year5" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount5" id="amount5" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month5-->
			
			
			
			<!-- Row 6 ---->
			<div id="row_month6">
			<div class="form-group form-group-sm">
				
				
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month6" class="form-control selectpicker" id="month6" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year6" class="form-control selectpicker" id="year6" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year6" id="month_year6" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount6" id="amount6" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month6-->
			
			
			
			<!-- Row 7 ---->
			<div id="row_month7">
			<div class="form-group form-group-sm">
				
				
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month7" class="form-control selectpicker" id="month7" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year7" class="form-control selectpicker" id="year7" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year7" id="month_year7" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount7" id="amount7" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month7-->
			
			
			
			<!-- Row 8 ---->
			<div id="row_month8">
			<div class="form-group form-group-sm">
				
				
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month8" class="form-control selectpicker" id="month8" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year8" class="form-control selectpicker" id="year8" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year8" id="month_year8" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount8" id="amount8" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month8-->
			
			
			<!-- Row 9 ---->
			<div id="row_month9">
			<div class="form-group form-group-sm">
				
				
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month9" class="form-control selectpicker" id="month9" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year9" class="form-control selectpicker" id="year9" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year9" id="month_year9" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount9" id="amount9" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month9-->
			
			
			
			<!-- Row 10 ---->
			<div id="row_month10">
			<div class="form-group form-group-sm">
				
				
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month10" class="form-control selectpicker" id="month10" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year10" class="form-control selectpicker" id="year10" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year10" id="month_year10" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount10" id="amount10" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month10-->
			
			
			
			<!-- Row 11 ---->
			<div id="row_month11">
			<div class="form-group form-group-sm">
				
				
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month11" class="form-control selectpicker" id="month11" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year11" class="form-control selectpicker" id="year11" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year11" id="month_year11" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount11" id="amount11" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month11-->
			
			
			<!-- Row 12 ---->
			<div id="row_month12">
			<div class="form-group form-group-sm">
				
				
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="month12" class="form-control selectpicker" id="month12" onChange="loadCalc()"><?php include ('controllers/month_select_option_inc.php'); ?></select>
					</div>
				</div>
			
				<div class="col-sm-3 selectContainer">
					<div class="input-group">
						<select name="year12" class="form-control selectpicker" id="year12" onBlur="loadCalc()"><?php include ('controllers/year_select_option_inc.php'); ?></select>
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<input type="text" readonly class="form-control input-sm" name="month_year12" id="month_year12" value="" onChange="loadCalc()" />
					</div>
				</div>
				
				<div class="col-sm-3 inputGroupContainer">
					<div class="input-group">
						<span class="input-group-addon">&#8358;</span>
						<input type="text" name="amount12" id="amount12" class="form-control input-sm" placeholder="Amount" onBlur="loadCalc()" value="" />
					</div>
				</div>
			</div>
			</div><!--row_month12-->
		</div>
		</tr>
	</table>	
	

	<table class="table table-bordered">	
		<tr>
			<div class="form-group form-group-sm">
				</br><span class="col-sm-12" style="font-weight:bold; text-align:center;">Transaction Account</span>
			</div>
		
			<!-- Select Basic -->			   
			<div class="form-group form-group-sm"> 
				<div class="col-sm-7 col-sm-offset-2 selectContainer">
					<div class="input-group">
						<span class="input-group-addon">DR:</span>
						<select name="debit_account" class="form-control selectpicker" id="debit_account" required>
						  <option value="">Select debit account</option>
							<?php
								$query = "SELECT * FROM {$prefix}accounts ";
								$query .= "WHERE active = 'Yes' ";
								$query .= "ORDER BY acct_desc ASC";
								$account_set = @mysqli_query($dbcon, $query); 
								
								while ($account = @mysqli_fetch_array($account_set, MYSQLI_ASSOC)) {
							?>
								<option value="<?php echo $account['acct_id']; ?>"><?php echo ucwords(strtolower($account['acct_desc'])); ?></option> 
							<?php
								} 
							?>
						</select>
					</div>
				</div>
			</div>

		
			<!-- Select Basic -->			   
			<div class="form-group form-group-sm"> 
				<div class="col-sm-7 col-sm-offset-2 selectContainer">
					<div class="input-group">
						<span class="input-group-addon">CR:</span>
						<select name="credit_account" class="form-control selectpicker" id="credit_account" required>
						  <option value="">Select credit account</option>
							<?php
								$query = "SELECT * FROM {$prefix}accounts ";
								$query .= "WHERE active = 'Yes' ";
								$query .= "ORDER BY acct_desc ASC";
								$account_set = @mysqli_query($dbcon, $query); 
								
								while ($account = @mysqli_fetch_array($account_set, MYSQLI_ASSOC)) {
							?>
								<option value="<?php echo $account['acct_id']; ?>"><?php echo ucwords(strtolower($account['acct_desc'])); ?></option> 
							<?php
								} 
							?>
						</select>
					</div>
				</div>
			</div>
		</tr>
	</table>
</div>

<div class="col-sm-5">
	<div class="row">
				<table class="table table-bordered">
				<tr>
					<td colspan="3">
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="shopno" class="control-label col-md-3">Shop No:</label>
							<div class="col-sm-5 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
									<input type="text" name="shopno" id="shopno" class="form-control input-sm" placeholder="" value="<?php if (isset($_POST["shopno"])) echo @$shop_no; ?>" maxlength="20" onBlur="loadCalc()" readonly />
								</div>
							</div>
						</div>					
					
					
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="customername" class="control-label col-md-3">Customer Name:</label>
							<div class="col-sm-8 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<input type="text" name="customername" id="customername" class="form-control input-sm" placeholder="" value="<?php if (isset($_POST["customername"])) echo @$customer; ?>" onBlur="loadCalc()" readonly />
								</div>
							</div>
						</div>
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="shopsize" class="control-label col-md-3">Shop Size:</label>
							<div class="col-sm-5 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
									<input type="text" name="shopsize" id="shopsize" class="form-control input-sm" placeholder="" value="<?php if (isset($_POST["shopsize"])) echo @$shop_size; ?>" maxlength="20" onBlur="loadCalc()" readonly />
								</div>
							</div>
						</div>
						
						
						<div class="form-group form-group-sm">
							<label class="control-label col-sm-3" for="monthly_expected">Expected Rent:</label>
							<div class="col-sm-5 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon">&#8358;</span>
									<input type="text" name="monthly_expected" id="monthly_expected" class="form-control input-sm" onBlur="loadCalc()" value="<?php if (isset($_POST["monthly_expected"])) echo @$expected_rent; ?>" readonly />
								</div>
							</div>
						</div>
						
						
						<div class="form-group form-group-sm">
							<label class="control-label col-sm-3" for="yearly_expected">Total Payable:</label>
							<div class="col-sm-5 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon">&#8358;</span>
									<input type="text" name="yearly_expected" id="yearly_expected" class="form-control input-sm" onBlur="loadCalc()" value="<?php if (isset($_POST["yearly_expected"])) echo @$total_expected_rent; ?>" readonly />
								</div>
							</div>
						</div>
						
						<!-- Select Basic -->			   
						<div class="form-group form-group-sm"> 
						  <label for="payment_type" class="col-md-4 control-label">Type of Payment:</label>
							<div class="col-md-5 selectContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-tasks"></i></span>
									<select name="payment_type" class="form-control selectpicker" id="payment_type" required>
									  <option value=" ">Select...</option>
									  <option <?php if (isset($_POST['payment_type']) && @$payment_type=="Cash") echo 'selected'; ?> value="Cash">Cash</option>
									  <option <?php if (isset($_POST['payment_type']) && @$payment_type=="Cheque") echo 'selected'; ?> value="Cheque">Cheque</option>
									  <option <?php if (isset($_POST['payment_type']) && @$payment_type=="Transfer") echo 'selected'; ?> value="Transfer">Transfer</option>
									</select>
								</div>
							</div>
						</div>
										
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="cheque_no" class="control-label col-md-4">Cheque No:</label>
							<div class="col-md-5 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
									<input type="text" name="cheque_no" class="form-control input-sm" placeholder="Cheque No" value="<?php if (isset($_POST['cheque_no'])) echo @$cheque_no; ?>" maxlength="20" />
								</div>
							</div>
						</div>
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="teller_no" class="control-label col-md-4">Teller No:</label>
							<div class="col-md-5 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
									<input type="text" name="teller_no" class="form-control input-sm" placeholder="Teller No" pattern="^\d{7}$" value="<?php if (isset($_POST['teller_no'])) echo @$teller_no; ?>" maxlength="7" />
								</div>
							</div>
						</div>
						
						<div class="form-group form-group-sm">
							<label class="col-sm-4 control-label">Date of Payment:</label>
							<div class="col-sm-5 date">
								<div class="input-group input-append date" id="date_of_payment">
									<input type="text" class="form-control input-sm" name="date_of_payment" placeholder="Date of Payment" value="<?php 
									if (isset($_POST['date_of_payment'])) {
										list($tiy,$tim,$tid) = explode("-", $date_of_payment); 
										$date_of_payment = "$tid/$tim/$tiy";
										echo @$date_of_payment;
									}
									?>" onBlur="loadCalc()" required />
									<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
						</div>
						
						
						<div class="form-group form-group-sm">
							<label class="col-sm-4 control-label">Date on Receipt:</label>
							<div class="col-sm-5 date">
								<div class="input-group input-append date" id="date_on_receipt">
									<input type="text" class="form-control input-sm" name="date_on_receipt" placeholder="Date on Receipt" value="<?php 
									if (isset($_POST['date_on_receipt'])) {
										list($tiy,$tim,$tid) = explode("-", $date_on_receipt); 
										$date_on_receipt = "$tid/$tim/$tiy";
										echo @$date_on_receipt;
									}
									?>" onBlur="loadCalc()" required />
									<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
						</div>
					</td>
				</tr>
				

				<tr>
					<td>
						<div class="form-group form-group-sm">
							<div class="col-sm-offset-2 col-md-10 date">
								<div class="input-group input-append date">
								<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type="text" class="form-control input-sm" name="start_date" id="start_date" placeholder="Period date" value="<?php  if (isset($_POST['start_date'])) echo @$start_date; ?>" onBlur="loadCalc()" required />
								</div>
							</div>
						</div>
					</td>
					<th class="text-center">to:</th>
					<td class="text-right">	
						<div class="form-group form-group-sm">
							<div class="col-sm-10 date">
								<div class="input-group input-append date">
									<input type="text" class="form-control input-sm" name="end_date" id="end_date" placeholder="Period covered" value="<?php  if (isset($_POST['end_date'])) echo @$end_date; ?>" onBlur="loadCalc()" required />
									<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td colspan="3">
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="transaction_desc" class="control-label col-md-3">Trans. Description:</label>
							<div class="col-sm-9 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
									<input type="text" name="transaction_desc" class="form-control input-sm" placeholder="Transaction description" value="<?php if (isset($_POST['transaction_desc'])) echo @$transaction_desc; ?>" maxlength="100" onBlur="loadCalc()" required />
								</div>
								
							</div>
						</div>
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="receipt_no" class="control-label col-md-3">Receipt No:</label>
							<div class="col-sm-5 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
									<input type="text" name="receipt_no" class="form-control input-sm" placeholder="Receipt No" pattern="^\d{7}$" value="<?php if (isset($_POST['receipt_no'])) echo @$receipt_no; ?>" maxlength="7" onBlur="loadCalc()" required />
								</div>
							</div>
						</div>
						
						
						<!-- Text input-->			
						<div class="form-group form-group-sm">
							<label for="amount_paid" class="control-label col-md-3">Total Amount Paid:</label>
							<div class="col-sm-5 inputGroupContainer">
								<div class="input-group">
									<span class="input-group-addon">&#8358;</span>
									<input type="text" name="amount_paid" id="amount_paid" class="form-control input-sm" placeholder="Amount Paid" value="<?php if (isset($_POST['amount_paid'])) echo @$amount_paid; ?>" maxlength="20" onBlur="loadCalc()" readonly />
								</div>
							</div>
						</div>
					</td>
				</tr>
				
				<tr>
					<td colspan="3">
						<div class="text-center">
							<div>
								<?php
								if($no_of_declined_post == 0 && $no_of_declined_acct_post == 0) {
									echo '
									<button type="submit" id="btn_post" name="btn_post" class="btn btn-sm btn-danger">Post Payment <span class="glyphicon glyphicon-send"></span></button>
									<button type="reset" name="btn_clear" class="btn btn-sm btn-primary">Clear</button>';
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
								?>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
</div>
</fieldset>
</form>