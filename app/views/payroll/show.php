<?php build('content') ?>
<div class="col-md-8 mx-auto">
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

			
					
			<?php if($holidays) :?>
				<section class="mb-2 mt-2">
					<h4>Holidays Found for this payrol</h4>
					<?php foreach($holidays as $key => $row) :?>
						<div style="border: 1px solid #000; padding:10px"><?php echo $row->holiday_name?> | <?php echo Module::get('holidays')['workTypeList'][$row->holiday_work_type]?>
						| <?php echo Module::get('holidays')['payTypeList'][$row->holiday_pay_type]?></div>
					<?php endforeach?>
				</section>
			<?php endif?>

			<h4>Salaries to Distribute</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<th>#</th>
						<th>Employee</th>
						<th>Rate</th>
						<th><span title="Total Worked Hours">TWH</span></th>
						<th><span title="Days of work">DOW</span></th>
						<th>Earning</th>
						<th>Bonus</th>
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
							<?php $totalSalaryRelease = 0?>
							<tr>
								<td colspan="9" style="background-color:blue; color:#fff;"><?php echo $groupRow['name']?></td>
							</tr>
							<?php $timesheetsGroupedByUser = $groupRow['users'];?>

							<?php foreach($timesheetsGroupedByUser as $key => $row):?>
								<?php 
									$timesheets = $row['timesheets'];
									$ratePerDay = $row['rate_per_day'];

									$totalWorkHours = 0;
									$daysOfWork = 0;
									$totalAmount = 0;
									$deduction = 0;
									$bonus = 0;
								?>

								<?php if(!empty($timesheets)) :?>
									<?php
										$CommonService::_timeSheetComputation($timesheets, $totalWorkHours, $daysOfWork, $totalAmount);
									?>
									<tr>
										<td><?php echo ++$counter?></td>
										<td><?php echo $row['fullname'] . '('.$row['uid'].')'?></td>
										<td><?php echo amountHTML($ratePerDay)?></td>
										<td><?php echo minutesToHours($totalWorkHours)?></td>
										<td><?php echo $daysOfWork?></td>
										<td><?php echo amountHTML($totalAmount)?></td>
										<td>
											<?php if(!empty($holidays)) :?>
												<?php foreach($holidays as $hlKey => $hlRow) :?>
													<?php if(isEqual($hlRow->holiday_pay_type, 'paid') 
														&& isEqual($hlRow->holiday_work_type, 'non_working')) :?>
														<?php $bonus += $ratePerDay?>
														<span class="badge badge-success" title="<?php echo $hlRow->holiday_name?>">PAID</span>
													<?php endif?>
												<?php endforeach?>
											<?php else:?>
												<?php echo 'NA'?>
											<?php endif?>
										</td>
										<td>
											<?php
												if(!empty($row['deductions'])) {
													foreach($row['deductions'] as $deductKey => $deductData) : ?>
														<?php $deduction += $deductData->deduction_amount?>
														<span class="badge badge-warning" title="<?php echo $deductData->deduction_name?>">
															<?php echo $deductData->deduction_amount?>
														</span>
													<?php endforeach;
												} else {
													echo 'NA';
												}
											?>
										</td>
										<td><?php echo amountHTML(($totalAmount + $bonus) - $deduction)?></td>
									</tr>
									
									<?php
										$groupTotalAmount += $totalAmount;
										$totalDeductions += $deduction;
										$totalSalaryRelease += (($totalAmount + $bonus) - $deduction);
									?>
								<?php endif?>
							<?php endforeach?>
							<tr>
								<td><!-- # --></td>
								<td><!-- EMPLOYEE --></td>
								<td><!-- Rate --></td>
								<td><!-- TWH --></td>
								<td><!-- DOW --></td>
								<td><!-- EARNING --><?php echo amountHTML($groupTotalAmount)?></td>
								<td><!-- BONUS --></td>
								<td><!-- DEDUCTIONS --><?php echo amountHTML($totalDeductions)?></td>
								<td><!-- TOTAL SALARY --><?php echo amountHTML($totalSalaryRelease)?></td>
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