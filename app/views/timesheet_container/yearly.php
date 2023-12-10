<?php build('content')?>
	<?php if(!isset($run)) :?>
	<div class="col-md-5 mx-auto">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Filter Result</h4>
			</div>
			<div class="card-body">
				<?php
					Form::open([
						'method' => 'get'
					]);
				?>
					<div class="form-group">
						<?php
							Form::label('Select Year');
							Form::select('year', date_generate_year(2), date('Y'), [
								'class' => 'form-control',
								'required' => true
							]);
						?>
					</div>

					<div class="form-group">
						<?php Form::submit('btn_result', 'Apply Filter' , ['class' => 'btn btn-primary btn-sm'])?>
					</div>
				<?php Form::close()?>
			</div>
		</div>
	</div>
	<?php else:?>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Yearly Report : <?php echo $req['year']?></h4>
				<a href="?">Remove Filter</a>
			</div>
			<div class="card-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<td>#</td>
							<td>Name</td>
							<?php foreach($months as $monthKey => $month) :?>
								<td><?php echo $month?></td>
							<?php endforeach?>
							<td>Total</td>
						</tr>
						<?php $counter = 0?>
						<?php $grandTotal = 0?>
						<?php foreach($timesheets as $key => $row) :?>
							<?php
								$total = 0; 
								$totalDuration = 0; 
								if(empty($row['timesheets']))
									continue;
								$timesheetsItems = $row['timesheets'];
								$counter++;
							?>
							<tr>
								<td><?php echo $counter?></td>
								<td><?php echo $row['name']?></td>
								<?php foreach($months as $monthKey => $monthRow) :?>
									<?php echo wDisplayTotal($timesheetsItems, $monthRow, $total, $totalDuration)?>
								<?php endforeach?>
								<td>
									<div><?php echo amountHTML($total)?></div>
									<?php echo minutesToHours($totalDuration)?>
								</td>
							</tr>
							<?php $grandTotal += $total?>
						<?php endforeach?>
					</thead>
				</table>

				<h4>Total : <?php echo amountHTML($grandTotal)?></h4>
			</div>
		</div>
	<?php endif?>
<?php endbuild()?>

<?php 
	function wDisplayTotal($timesheetsItems, $month, &$total = 0, &$totalDuration = 0) {
		$retVal = "<td>--N/A--</td>";
		foreach($timesheetsItems as $key => $row) {
			if(isEqual($row->month_name, $month)) {
				$total += $row->total;
				$totalDuration += $row->total_duration;
				$amount = amountHTML($row->total);
				$hoursToMinutes = minutesToHours($row->total_duration);

				$retVal = "<td>
					{$amount}
					<div>{$hoursToMinutes}</div>
				</td>";
			}
		}
		return $retVal;
	}
?>
<?php loadTo()?>