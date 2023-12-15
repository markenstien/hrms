<?php build('content') ?>
<div class="col-md-6 mx-auto">
	<div class="card">
		<h1 class="alert alert-primary text-center">FROM TIMESHEETS</h1>
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

			<?php if(is_null($payroll->release_date)) :?>
				<a href="/PayrollController/release/<?php echo seal(['id' => $payroll->id, 'token' => $csrf])?>" class="btn btn-primary btn-sm">Release Payroll</a>
			<?php else:?>
				<a href="/PayrollController/show_release/<?php echo $payroll->id?>" class="btn btn-primary btn-sm">Show Released</a>
			<?php endif?>

			<a href="/PayrollController/delete/<?php echo $payroll->id?>" class="btn btn-danger btn-sm">Delete</a>
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

			<h4>Salaries to Distribute</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<th>#</th>
						<th>Employee</th>
						<th>Rate</th>
						<th>Total Worked Hours</th>
						<th>Days of work</th>
						<th>Earning</th>
						<th>Deductions</th>
						<th>Salary</th>
					</thead>

					<tbody>
						<?php
							$counter = 0;
						?>
						<?php foreach($groupedByBranch as $groupKey => $groupRow) :?>
							<?php $groupTotalAmount = 0?>
							<?php $totalDeductions = 0?>
							<?php $totalSalaryRelease = 0?>
							<tr>
								<td colspan="8" style="background-color:blue; color:#fff;"><?php echo $groupRow['name']?></td>
							</tr>
							<?php $timesheetsGroupedByUser = $groupRow['users'];?>

							<?php foreach($timesheetsGroupedByUser as $key => $row):?>
								<?php $timesheets = $row['timesheets']?>
								<?php
									$totalWorkHours = 0;
									$daysOfWork = 0;
									$totalAmount = 0;
									$deduction = 0;
								?>

								<?php if(!empty($timesheets)) :?>
									<?php
										$CommonService::_timeSheetComputation($timesheets, $totalWorkHours, $daysOfWork, $totalAmount);
									?>
									<tr>
										<td><?php echo ++$counter?></td>
										<td><?php echo $row['fullname']?></td>
										<td><?php echo $row['rate_per_hour']?></td>
										<td><?php echo minutesToHours($totalWorkHours)?></td>
										<td><?php echo $daysOfWork?></td>
										<td><?php echo amountHTML($totalAmount)?></td>
										<td>
											<?php
												if(!empty($row['deductions'])) {
													foreach($row['deductions'] as $deductKey => $deductData) : ?>
														<?php $deduction = $deductData->deduction_amount?>
														<span class="badge badge-warning" title="<?php echo $deductData->deduction_name?>">
															<?php echo $deductData->deduction_amount?>
														</span>
													<?php endforeach;
												} else {
													echo 'NONE';
												}
											?>
										</td>
										<td><?php echo amountHTML($totalAmount - $deduction)?></td>
									</tr>
									
									<?php
										$groupTotalAmount += $totalAmount;
										$totalDeductions += $deduction;
										$totalSalaryRelease += ($totalAmount - $deduction);
									?>
								<?php endif?>
							<?php endforeach?>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><?php echo amountHTML($groupTotalAmount)?></td>
								<td><?php echo amountHTML($totalDeductions)?></td>
								<td><?php echo amountHTML($totalSalaryRelease)?></td>
							</tr>
						<?php endforeach?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>