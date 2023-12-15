<?php build('content') ?>
<div class="col-md-6 mx-auto">
	<div class="card">
		<h1 class="alert alert-primary text-center">RELEASED AMOUNT</h1>
		<div class="card-header">
			<h4 class="card-title">Payroll Preview</h4>
			<a href="/PayrollController/create">Back</a>

			<?php Flash::show()?>
		</div>

		<div class="card-body">
			<?php
				csrfReload();
				$csrf = csrfGet();
			?>
			<a href="/PayrollController/delete/<?php echo $payroll->id?>" class="btn btn-danger btn-sm">Delete</a>
			<a href="/PayrollController/show/<?php echo $payroll->id?>" class="btn btn-primary btn-sm">Show Timesheet</a>
			<a href="/PayrollController/show_release/<?php echo $payroll->id?>?export=true" class="btn btn-primary btn-sm">Export</a>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<td>Cut-off dates :</td>
						<td>
							<?php echo $payroll->start_date?> (<?php echo date('l', strtotime($payroll->start_date))?>) To 
							<?php echo $payroll->end_date?> (<?php echo date('l', strtotime($payroll->end_date))?>) 
							<?php echo date_difference($payroll->start_date, $payroll->end_date)?>
						</td>
					</tr>
					<!-- <tr>
						<td>Approved : </td>
						<td><?php echo $payroll->approved_by?></td>
					</tr> -->
					<tr>
						<td>Total Salaries : </td>
						<td><?php echo amountHTML($totalSalaryAmount)?></td>
					</tr>
					<tr>
						<td>Total Employee : </td>
						<td><?php echo $totalUsers?></td>
					</tr>

					<?php if(is_null($payroll->release_date)) :?>
						<tr>
							<td>Release : </td>
							<td>Not Released</td>
						</tr>
					<?php else:?>
						<tr>
							<td>Release Date : </td>
							<td><?php echo $payroll->release_date?></td>
						</tr>

						<tr>
							<td>Release By : </td>
							<td><?php echo $payroll->release_by?></td>
						</tr>
					<?php endif?>
				</table>
			</div>

			<?php foreach($groupedByBranch as $branchID => $branchItem) :?>
				<?php 
					$users = $branchItem['users'];
					$totalAmount = 0;
				?>
				<h4 style="background-color:blue; color:#fff;padding: 5px;"><?php echo $branchItem['name']?></h4>
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<th>#</th>
							<th>User</th>
							<th>Hours Worked</th>
							<th>Salary</th>
							<th>Take home pay</th>
							<th>Number of days</th>
							<th>Action</th>
						</thead>

						<tbody>
							<?php foreach($users as $key => $row) :?>
								<?php $totalAmount += $row->take_home_pay;?>
								<tr>
									<td><?php echo ++$key?></td>
									<td><?php echo $row->fullname?></td>
									<td><?php echo minutesToHours($row->reg_hours_total)?></td>
									<td><?php echo amountHTML($row->reg_amount_total)?></td>
									<td><?php echo amountHTML($row->take_home_pay)?></td>
									<td><?php echo $row->no_of_days?></td>
									<td>
										<a href="/PayrollController/show_payslip/<?php echo $row->payroll_id?>&user_id=<?php echo seal($row->user_id)?>">Show</a>
									</td>
								</tr>
							<?php endforeach?>
						</tbody>
					</table>
				</div>
				<h3><?php echo amountHTML($totalAmount)?></h3>
			<?php endforeach?>
		</div>
	</div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>