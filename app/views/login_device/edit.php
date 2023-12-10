<?php build('content') ?>
	
	<div class="col-md-6 mx-auto">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Login Device</h4>
				<a href="/LoginDevice/">Login Devices</a>
				<?php Flash::show()?>
			</div>

			<div class="card-body">
				<?php
					Form::open([
						'method' => 'post',
						'action' => '/loginDevice/update'
					]);

					Form::hidden('id' , $device->id);
				?>
				<div class="form-group">
					<?php
						Form::label('User');
						Form::select('user_id' , $users , $device->user_id ,['class' => 'form-control' , 'readonly' => '' , 'disabled' => '']);
					?>
				</div>

				<div class="form-group">
					<?php
						Form::label('Device');
						Form::select('type' , [
							'rfid', 'web','biometrics',
							'qr'
						],$device->type ,['class' => 'form-control', 'readonly' => '' , 'disabled' => '']);
					?>
				</div>
				<div class="form-group">
					<?php
						Form::label('Login Key');
						Form::text('login_key' , $device->login_key, [
							'class' => 'form-control'
						]);
					?>
				</div>

				<div class="form-group">
					<?php Form::submit('' , 'Save Changes' , [
						'class' => 'btn btn-primary'
					])?>
				</div>
				<?php Form::close()?>
			</div>
		</div>
	</div>
<?php endbuild()?>
<?php loadTo('tmp/layout')?>