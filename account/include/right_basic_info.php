<table class="table table-hover table-bordered">
					<thead>
						<tr class="success">
							<th colspan="2" class="text-right">Basic Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="col-md-5 info"><span class="glyphicon glyphicon-user"></span> Name:</td>
							<td><?php echo $staff['last_name']; ?>, <?php echo $staff['first_name']; ?> <?php echo $staff['other_name']; ?></td>
						</tr>
						
						<tr>
							<td class="col-md-5 info"><span class="glyphicon glyphicon-signal"></span> Grade:</td>
							<td><?php echo $staff['present_grade']; ?></td>
						</tr>
						<tr>
							<td class="info"><span class="glyphicon glyphicon-list"></span> Department:</td>
							<td><?php echo $staff['department']; ?></td>
						</tr>
						<tr>
							<td class="info"><span class="glyphicon glyphicon-user"></span> Supervisor:</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2"><span class="glyphicon glyphicon-envelope"></span> Email: <?php echo $staff['email']; ?></td>
						</tr>
						<tr>
						<tr>
							<td class="info"><span class="glyphicon glyphicon-phone"></span> CUG Phone:</td>
							<td><?php echo $staff['cug_phone']; ?></td>
						</tr>
						
						<tr>
							<td class="info" colspan="2"><span class="glyphicon glyphicon-comment"></span> Last Perfomance Appraisal Report:</td>
						</tr>
						<tr>
							<td colspan="2">No content yet...</td>
						</tr>
						<tr>
						<td></td>
						<td class="text-right"><a href="profile.php" class="btn btn-success btn-sm" title="Get more information about <?php echo $staff["full_name"]; ?>">View more details</a></td>
						</tr>
					</tbody>
				</table>