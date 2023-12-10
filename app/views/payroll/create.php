<?php build('content')?>
	<div class="container-fluid">
		<?php echo wControlButtonLeft('Payroll Module', [
			$navigationHelper->setNav('', 'Back', _route('payroll:index'))
		])?>
		<div class="col-md-6 mx-auto">
			<div class="card">
				<?php echo wCardHeader(wCardTitle('Payroll')) ?>
				<div class="card-body">
					<?php Flash::show()?>		
					<?php
						Form::open([
							'method' => 'post',
							'action' => ''
						]);
					?>
					<div class="row">
						<div class="form-group col">
							<?php
								Form::label('Start Date');
								Form::date('start_date',$payrollDates[0] ?? '', [
									'class' => 'form-control',
									'required' => true,
								]);
							?>
						</div>

						<div class="form-group col">
							<?php
								Form::label('End Date');
								Form::date('end_date',$payrollDates[1] ?? '', [
									'class' => 'form-control',
									'required' => true
								]);
							?>
						</div>
					</div>

					<div class="form-group">
						<?php
							Form::submit('', 'Create Payroll');
						?>
					</div>
					<?php Form::close()?>
				</div>
			</div>
		</div>
	</div>

<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>