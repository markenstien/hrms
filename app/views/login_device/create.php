<?php build('content') ?>

	

	<div class="col-md-6 mx-auto">

		<div class="card">

			<div class="card-header">

				<h4 class="card-title">Login Device</h4>

			</div>



			<div class="card-body">

				<?php

					Form::open([

						'method' => 'post',

						'action' => '/loginDevice/store'

					]);

				?>

				<div class="form-group">

					<?php

						Form::label('User');

						Form::select('user_id' , $users ,'',['class' => 'form-control']);

					?>

				</div>



				<div class="form-group">

					<?php

						Form::label('Device');

						Form::select('type' , [

							'rfid', 'web','biometrics',

							'qr'

						],'rfid',['class' => 'form-control']);

					?>

				</div>

				<div class="form-group">

					<?php

						Form::label('Login Key');

						Form::text('login_key' , '', [

							'class' => 'form-control'

						]);

					?>

				</div>



				<div class="form-group">

					<?php Form::submit('' , 'Register Login' , [

						'class' => 'btn btn-primary'

					])?>

				</div>

				<?php Form::close()?>

			</div>

		</div>

	</div>

<?php endbuild()?>

<?php loadTo('tmp/layout')?>