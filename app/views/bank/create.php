<?php build('content') ?>

	<div class="container">

		<div class="col-md-5">



			<h5>Connect <?php echo $user->firstname . ' ' .$user->lastname?>'s Pera-E </h5>

			<?php Flash::show()?>

			<?php if(!$pera) :?>

			<?php

				Form::open([

					'method' => 'post',

					'action' => '/Bank/register'

				]);

				Form::hidden('userId' , $user->id);
			?>



			<div class="form-group">

				<?php

					Form::label('Key');

					Form::text('apiKey' , '' , [

						'class' => 'form-control',

						'required' => ''

					]);

				?>

			</div>



			<div class="form-group">

				<?php

					Form::label('Secret');

					Form::text('apiSecret' , '' , [

						'class' => 'form-control',

						'required' => ''

					]);

				?>

			</div>



			<?php

				Form::submit('', 'Connect' , [

					'class' => 'btn btn-primary btn-sm'

				]);

			?>



			<div>

				<a href="https://pera-e.com/" target="_blank">Dont have an account ? Create your <span>free account on pera-e</span></a>

			</div>

			<?php Form::close()?>

			<?php else:?>

				<h3>Pera-e Connected</h3>

				<table class="table">

					<tr>

						<td>Account Number</td>

						<td><?php echo strObscure($pera->account_number , 4)?></td>

					</tr>

					<tr>

						<td>Linked On</td>

						<td><?php echo $pera->created_at?></td>

					</tr>

					<tr>

						<td>

							<a href="/bank/edit/<?php echo $pera->id?>">Edit</a>

						</td>

						<td>

							<?php 

								Form::open([

									'method' => 'post',

									'action' => '/bank/testConnection'

								]);

								

								Form::hidden('pera_id' , $pera->id);



								Form::submit('' , 'Connect' , [

									'class' => 'btn btn-primary btn-sm'

								]);

								

								Form::close();

							?>

						</td>

					</tr>

				</table>

			<?php endif?>

		</div>



		<div class="col-md-12">

			<h4>Logs</h4>

			<table class="table">

				<thead>

					<th>#</th>

					<th>Control Number</th>

					<th>Description</th>

					<th>Date Time</th>

				</thead>



				<tbody>

					<?php $counter = 1?>

					<?php foreach($logs as $log) :?>

						<tr>

							<td><?php echo $counter++?></td>

							<td><?php echo $log->control_number?></td>

							<td><?php echo $log->description?></td>

							<td><?php echo $log->created_at?></td>

						</tr>

					<?php endforeach?>

				</tbody>

			</table>

		</div>

	</div>

<?php endbuild()?>

<?php occupy('tmp/layout')?>