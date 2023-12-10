<?php build('content') ?>

	

	<div class="col-md-6 mx-auto">

		<div class="card">

			<div class="card-header">

				<h4 class="card-title">Automatic logout setting</h4>

				<a href="/AutomaticLogoutSetting/">logout settings</a>

				<?php Flash::show()?>

			</div>



			<div class="card-body">

				<?php

					Form::open([

						'method' => 'post',

						'action' => '/AutomaticLogoutSetting/update'

					]);



					Form::hidden('id' , $setting->id);

				?>

				<div class='form-group'>

					<?php

						Form::label('Full Name');

						Form::text('' , $account->firstname . ' '.$account->lastname, [

							'class' => 'form-control' , 

							'readonly' => ''

						]);

					?>

				</div>

				<div class="form-group">

					<?php

						list($hours , $minutes) = convertMinutesToHours($setting->max_duration);

					?>

					

					<div class='row'>

						<div class='col'>

						<?php

							Form::label('Hours');

							Form::text('hours' ,  intval($hours), [

								'class' => 'form-control'

							]);

						?>

						</div>



						<div class='col'>

						<?php

							Form::label('Minutes');

							Form::text('minutes' ,  intval($minutes), [

								'class' => 'form-control'

							]);

						?>

						</div>

					</div>

					<small><?php echo minutesToHours($setting->max_duration)?></small>

				</div>



				<div class="form-group">

					<?php 

						Form::submit('' , 'Save Setting' , [

							'class' => 'btn btn-warning'

						]);

					?>

				</div>

				<?php Form::close()?>

			</div>

		</div>

	</div>

<?php endbuild()?>

<?php loadTo('tmp/layout')?>