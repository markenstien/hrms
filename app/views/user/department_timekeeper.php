<?php build('content') ?>
<div class="col-md-5">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Branch Timekeepers</h4>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<th>#</th>
						<th>Name</th>
						<th>Branch</th>
						<th>Action</th>
					</thead>

					<tbody>
						<?php foreach($users as $key => $row) :?>
							<tr>
								<td><?php echo ++$key?></td>
								<td><?php echo $row->fullname?></td>
								<td><?php echo $row->branch_name?></td>
								<td>
									<a href="/User/department_timekeeper/?user_id=<?php echo $row->id?>&action=remove">Remove</a>
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
<?php loadTo()?>