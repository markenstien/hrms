<?php build('content') ?>
<div class="container-fluid">
	<?php echo wControlButtonRight('Deduction Management', [
		$navigationHelper->setNav('', 'Add Deduction', _route('deduction:create'))
	])?>
	<div class="card">
		<?php echo wCardHeader(wCardTitle('Deductions'))?>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<th>#</th>
						<th>Employee</th>
						<th>Deduction</th>
						<th>Balance</th>
						<th>Deduction</th>
						<th>Deduction Cycle</th>
						<th>Action</th>
					</thead>

					<tbody>
						<?php foreach($deductions as $key => $row) :?>
							<tr>
								<td><?php echo ++$key?></td>
								<td><?php echo $row->fullname?></td>
								<td><?php echo "($row->deduction_code)".$row->deduction_name?></td>
								<td><?php echo $row->balance?></td>
								<td><?php echo $row->deduction_amount?></td>
								<td><?php echo $row->deduction_cycle?></td>
								<td>
									<a href="/DeductionController/deleteItem/<?php echo $row->id?>">Delete</a>
								</td>
							</tr>
						<?php endforeach?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>