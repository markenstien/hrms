<?php build('content') ?>
<div class="container-fluid">
	<div class="col-md-6 mx-auto">
		<?php echo wCardHeader(wCardTitle('Payslip'))?>
		<div class="card">
			<div class="card-body">
				<div>
					<p style="margin:0px"><span class="h4"><?php echo $user->fullname?></span> <span class="badge badge-primary"><?php echo $user->position_name?></span></p>
					<table class="table table-bordered">
						<tr>
							<td>EE ID : <?php echo $user->uid?> </td>
							<td>Employed : <?php echo date('Y/m/d', strtotime($user->hire_date))?></td>
						</tr>
						<tr>
							<td>Dept/Position : <?php echo $user->department_name?>/<?php echo $user->position_name?></td>
							<td>Rate : <?php echo $user->salary_per_day?>/day</td>
						</tr>
						<tr>
							<td>Pay Period : <?php echo $payslip->start_date?> To <?php echo $payslip->end_date?>
								(<?php echo date_difference($payslip->start_date, $payslip->end_date)?>)
							</td>
							<td>Total Work Hours : <?php echo minutesToHours($payslip->reg_hours_total)?></td>
						</tr>
					</table>
				</div>

				<h3 style="margin-top:50px">PAYSLIP</h3>
				<div class="row">
					<div class="col-md-6">
						<h4>Earnings:</h4>
						<p>REG WRK HRS | <?php echo minutesToHours($payslip->reg_hours_total)?> | 
						<?php echo amountHTML($payslip->reg_amount_total)?> </p>

						<?php if($payslip->bonus_notes) :?>
							<?php foreach(json_decode($payslip->bonus_notes) as $key => $row) :?>
								<p><?php echo $row->code?> | <?php echo $row->name?> | <?php echo amountHTML($row->amount)?></p>
							<?php endforeach?>
						<?php endif?>
					</div>

					<?php if($payslip->deduction_notes) :?>
						<div class="col-md-6">
							<h4>Deductions:</h4>
							<?php foreach(json_decode($payslip->deduction_notes) as $key => $row) :?>
								<p><?php echo $row->code?> | <?php echo $row->name?> | <?php echo amountHTML($row->amount)?> </p>
							<?php endforeach?>
						</div>
					<?php endif?>
				</div>
				<h4 class="mt-4">Take Home Pay : 
					<?php echo amountHTML($payslip->take_home_pay)?>
					<?php
						if($payslip->take_home_pay < 0) {
							echo 'Negative Take Home Pay';
						}
					?>
				</h4>
			</div>
			<div class="card-footer">
				<p class="text-center">VALID PAYSLIP: <?php echo COMPANY_NAME?>.</p>
			</div>
		</div>
	</div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>