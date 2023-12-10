<?php build('content') ?>
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Payout</h4>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered dataTable">
					<thead>
						<th>#</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Wallet</th>
					</thead>

					<tbody>
						<?php foreach($wallets as $key => $row) :?>
							<tr>
								<td><?php echo ++$key?></td>
								<td><?php echo $row->firstname?></td>
								<td><?php echo $row->lastname?></td>
								<td><?php echo $row->wallet_total?></td>
							</tr>
						<?php endforeach?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php endbuild()?>
<?php loadTo()?>