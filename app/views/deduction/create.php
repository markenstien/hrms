<?php build('content') ?>
<div class="container-fluid">
	<?php echo wControlButtonLeft('Deduction & Contributions', [
		$navigationHelper->setNav('', 'Back', _route('deduction:deduction'))
	])?>
	<div class="col-md-6 mx-auto">
		<div class="card">
			<?php echo wCardHeader(wCardTitle('Add New Deductions'))?>
			<div class="card-body">
				<?php
					Form::open([
						'method' => 'post'
					]);
				?>
					<div class="form-group">
						<?php
							Form::label('Deduction Code');
							Form::text('deduction_code','', [
								'class' => 'form-control',
								'required' => true
							]);
						?>
					</div>

					<div class="form-group">
						<?php
							Form::label('Name');
							Form::text('deduction_name','', [
								'class' => 'form-control',
								'required' => true
							]);
						?>
					</div>

					<div class="form-group">
						<?php
							Form::label('Deduction Category');
							Form::select('category_id',$deductionCategoryArr,'', [
								'class' => 'form-control',
								'required' => true
							]);
						?>
					</div>

					<div class="form-group">
						<?php
							Form::label('Deduction Description');
							Form::textarea('deduction_description','', [
								'class' => 'form-control'
							]);
						?>
					</div>

					<div class="form-group">
						<?php
							Form::submit('', 'Create New Deductions')
						?>
					</div>
				<?php Form::close() ?>
			</div>

			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<th>#</th>
							<th>Code</th>
							<th>Name</th>
							<th>Action</th>
						</thead>

						<tbody>
							<?php foreach($deductions as $key => $row) :?>
								<tr>
									<td><?php echo ++$key?></td>
									<td><?php echo $row->deduction_code?></td>
									<td><?php echo $row->deduction_name?></td>
									<td>
										<a href="/DeductionController/applyDeduction/<?php echo $row->id?>">Create Deduction</a> | 
										<a href="/DeductionController/delete/<?php echo $row->id?>">Delete</a>
									</td>
								</tr>
							<?php endforeach?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>