<?php build('content') ?>
<div class="container-fluid">
	<?php echo wControlButtonLeft('Add Deductions & Contributions Management', [
		$navigationHelper->setNav('', 'Back', _route('deduction:index'))
	])?>
	<div class="col-md-6 mx-auto">
		<div class="card">
			<?php echo wCardHeader(wCardTitle('Add Deductions & Contributions'))?>
			<div class="card-body">
				<?php Flash::show()?>
				<?php
					Form::open([
						'method' => 'post'
					]);
					Form::hidden('deduction_id', $deduction->id);
				?>
					<div class="form-group">
						<?php
							Form::label('Deduction/Contribution Code');
							Form::text('', "{$deduction->deduction_name} ({$deduction->deduction_code})", [
								'readonly' => true,
								'class' => 'form-control'
							])
						?>
					</div>
					<div class="form-group">
						<?php
							Form::label('Employee ID');
							Form::text('uid','', [
								'class' => 'form-control',
								'required' => true,
								'placeholder' => 'ID of your employee'
							]);
						?>
					</div>

					<div class="form-group">
						<?php
							Form::label('Balance');
							Form::text('initial_balance','', [
								'class' => 'form-control',
								'required' => true,
								'placeholder' => '0 is not valid'
							]);
						?>
					</div>

					<h4>Payments</h4>

					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<?php
									Form::label('Amount to Pay');
									Form::number('payment_amount', '', [
										'class' => 'form-control',
										'required'=> true
									]);
								?>

								<label>
									<input type="radio" name="deduction_type" value="exact" checked>
									Exact Amount
								</label>
								<label>
									<input type="radio" name="deduction_type" value="percentage" disabled>
									Percentage
								</label>
							</div>

							<div class="col-md-6">
								<?php
									Form::label('Apply Deduction Every..');
									Form::select('deduction_cycle',[
										'every_cutoff',
										// 'every_first_week',
										// 'every_second_week',
										// 'every_third_week',
										// 'every_fourth_week',
										// 'twice_a_month',
										// 'once_a_month',
									],'every_cutoff', [
										'class' => 'form-control',
										'required'=> true
									]);
								?>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>
							<input type="checkbox" name="stop_if_zero">
							Stop Deduction once Balance is zero.
						</label>
					</div>

					<div class="form-group">
						<?php
							Form::label('Description');
							Form::textarea('description', '', [
								'class' => 'form-control',
								'rows' => 4
							])
						?>
					</div>

					<div class="form-group">
						<?php
							Form::submit('', 'Create Deduction');
						?>
					</div>
				<?php Form::close() ?>
			</div>
		</div>
	</div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>