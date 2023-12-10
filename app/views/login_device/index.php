<?php build('content') ?>
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">
				Registered Devices
			</h4>
			<a href="/LoginDevice/create">Register Device Login</a>

			<?php Flash::show()?>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<th>#</th>
						<th>Name</th>
						<th>Login Key</th>
						<th>Device</th>
						<th>Action</th>
					</thead>

					<tbody>
						<?php foreach($registeredDevices as $key => $row) :?>
							<?php if(!isset($row->user->firstname)) continue?>
							<tr>
								<td><?php echo ++$key?></td>
								<td><?php echo $row->user->firstname . ' ' . $row->user->lastname?></td>
								<td><?php echo $row->login_key?></td>
								<td><?php echo $row->type?></td>
								<td>
									<a href="/LoginDevice/edit/<?php echo $row->id?>">Edit</a>
								</td>
							</tr>
						<?php endforeach?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php endbuild()?>
<?php loadTo('tmp/layout')?>