<?php 
if ($menu['level']=="ce") {
	echo ' 
			<div class="well"></div>
			<div class="panel-group" id="accordion1">
				
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a href="#cla2" data-toggle="collapse" data-parent="#accordion1">HR Department</a>
						</h4>
					</div>
					<div class="collapse panel-collapse" id="cla2">
						<div class="panel-body">
							<a href="../hr/manage_staff.php" class="list-group-item">Manage Staff Information</a>
							<a href="../hr/staff_register.php" class="list-group-item">Register New Employee</a>
							<a href="../hr/leave_form_approval.php" class="list-group-item">Leave Applications</a>
							<a href="#" class="list-group-item">Performance Appraisal</a>
						</div>
					</div>
				</div>
				
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a href="#cla4" data-toggle="collapse" data-parent="#accordion1">Leasing Department</a>
						</h4>
					</div>
					<div class="collapse panel-collapse" id="cla4">
						<div class="panel-body">
							<a href="../leasing/officers.php" class="list-group-item">Leasing Officers</a>
							<a href="../leasing/manage_customer.php" class="list-group-item">Manage ALL Customers</a>
							<a href="../leasing/register_customer.php" class="list-group-item">Create New Customer</a>
							<a href="../leasing/lease_application.php" class="list-group-item">Lease Application Dashboard</a>
							<a href="../leasing/vacant_shops.php" class="list-group-item">Vacant Shops</a>
							<a href="../leasing/expired_shop_lease.php" class="list-group-item">Expired Shop Lease</a>
							<a href="../leasing/verified_shop_records.php" class="list-group-item">Verified Shop Records</a>
							<a href="#" class="list-group-item">Performance Report</a>
						</div>
					</div>
				</div>
				
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a href="#cla3" data-toggle="collapse" data-parent="#accordion1">WRL Account Records</a>
						</h4>
					</div>
					<div class="collapse panel-collapse" id="cla3">
						<div class="panel-body">
							<a href="view_trans.php" class="list-group-item">View WRL Transactions</a>
							<a href="ledgers.php" class="list-group-item">WRL General Ledgers</a>
							<a href="trial_balance.php" class="list-group-item">WRL Trial Balance</a>
							<a href="balance_sheet.php" class="list-group-item">WRL Balance Sheet</a>
							<a href="profit_loss.php" class="list-group-item">WRL Profit and Loss</a>
						</div>
					</div>
				</div>
				
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a href="#cla6" data-toggle="collapse" data-parent="#accordion1">ARENA Account Records</a>
						</h4>
					</div>
					<div class="collapse panel-collapse" id="cla6">
						<div class="panel-body">
							<a href="view_trans_arena.php" class="list-group-item">View ARENA Transactions</a>
							<a href="ledgers_arena.php" class="list-group-item">ARENA General Ledgers</a>
							<a href="trial_balance_arena.php" class="list-group-item">ARENA Trial Balance</a>
							<a href="balance_sheet_arena.php" class="list-group-item">ARENA Balance Sheet</a>
							<a href="profit_loss_arena.php" class="list-group-item">ARENA Profit and Loss</a>
						</div>
					</div>
				</div>
				
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a href="#cla5" data-toggle="collapse" data-parent="#accordion1">Roles Management</a>
						</h4>
					</div>
					<div class="collapse panel-collapse" id="cla5">
						<div class="panel-body">
							<a href="../hr/manage_roles.php" class="list-group-item">View All User Roles</a>
							<a href="../hr/manage_roles.php" class="list-group-item">Update User Roles</a>
							<a href="../hr/manage_staff.php" class="list-group-item">Assign New Roles</a>
						</div>
					</div>
				</div>
			</div>';
			
} else {
/*
echo '
<div class="well"></div>
<div class="panel-group" id="accordion1">	
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a href="#cla1" data-toggle="collapse" data-parent="#accordion1">Activity Panel</a>
			</h4>
		</div>
		<div class="collapse in panel-collapse" id="cla1">
			<div class="panel-body">
				<div class="list-group">
					<a href="../../update_password.php" class="list-group-item">Change Password</a>
					<a href="../../update_record.php" class="list-group-item">Update Bio-data</a>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a href="#cla2" data-toggle="collapse" data-parent="#accordion1">Forms</a>
			</h4>
		</div>
		<div class="collapse panel-collapse" id="cla2">
			<div class="panel-body">
				<div class="list-group">
					<a href="#" class="list-group-item">Requisition Form</a>
					<a href="../hr/leave_form.php" class="list-group-item">Leave Application Form</a>
					<a href="#" class="list-group-item">Performance Appraisal</a>
				</div>
			</div>
		</div>
	</div>
</div>';
*/
}
?>
