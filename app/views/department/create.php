<?php build('content') ?>
	<div class="container-fluid">
		<?php echo wControlButtonLeft($pageMainTitle, [
			$navigationHelper->setNav('menu', 'Back', _route('department:index'))
		])?>

		<div class="col-md-6 mx-auto">
			<div class="card">
				<?php echo wCardHeader(wCardTitle('Department Form')) ?>
				<div class="card-body">
					<?php
						Form::open([
							'method' => 'post'
						]);
					?>

					<div class="form-group">
						<?php
							Form::label('Department Name');
							Form::text('branch' , '' , [
								'class' => 'form-control',
								'required' => true
							]);
						?>
					</div>

					<div class="form-group">
						<?php
							Form::submit('' , 'Save Entry');
						?>
					</div>
					<?php Form::close()?>
				</div>
			</div>
		</div>
	</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>