<?php build('content') ?>
	<div class="container-fluid">
		<?php echo wControlButtonRight($pageMainTitle, [
            $navigationHelper->setNav('menu', 'Add New Department', _route('department:create'))
        ])?>
		<div class="card">
			<?php echo wCardHeader(wCardTitle('Departments')) ?>
			<div class="card-body">
				<?php Flash::show()?>
				<div class="table-responsive">
					<table class="table table-bordered" id="dataTable">
						<thead>
							<th>#</th>
							<th>Branch</th>
							<th>Action</th>
						</thead>

						<tbody>
							<?php foreach($branches as $key => $branch) :?>
								<tr>
									<td><?php echo ++$key?></td>
									<td><?php echo $branch->branch?></td>
									<td><?php echo wLinkDefault(_route('department:edit', $branch->id))?></td>
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