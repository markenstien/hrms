<?php build('content') ?>
	<div class="col-md-6 mx-auto">
		<div class="card">
			<?php echo wCardHeader(wCardTitle($pageMainTitle))?>
			<div class="card-body">
				<?php
					Form::open([
						'method' => 'post'
					]);

					Form::hidden('id' , $branch->id);
				?>

				<div class="form-group">
					<?php
						Form::label('Branch');
						Form::text('branch' , $branch->branch , [
							'class' => 'form-control'
						]);
					?>
				</div>

				<div class="form-group">
					<?php
						Form::submit('' , 'Save Branch');
					?>
				</div>
				<?php Form::close()?>
			</div>
		</div>
	</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>