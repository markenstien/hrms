
<?php if(isEqual($viewType,'clocked_in')) :?>
<h5><?php echo wLinkDefault('/LoggedUsers/logoutAll', 'Logout All')?></h5>
<div class="table-responsive">
	<table class="table table-bordered dataTable">
		<thead>
			<th style="width: 5%;">#</th>
            <th style="width: 15%;">Name</th>
            <th style="width: 15%;">Clock in Time</th>
            <th style="width: 15%; background: green; color:#fff">Ongoig WH</th>
            <th><span style="Total Worked Hours">Total WH</span></th>
            <th><span style="Remaining Work Hours">Remaining WH</span></th>
            <th>Department</th>
            <th style="width: 15%;">Action</th>
		</thead>

		<tbody>
			<?php foreach($loggedUsers as $key => $row) :?>
				<tr>
					<td><?php echo ++$key?></td>
                    <td><?php echo $row->fullname?></td>
                    <td><?php echo $row->clock_in_time?></td>
                    <td><?php echo minutesToHours(timeDifferenceInMinutes($row->clock_in_time , $timeToday)) ?></td>
                    <td><?php echo minutesToHours($row->total_duration) ?></td>
                    <td><?php echo minutesToHours(hoursToMinutes($row->max_work_hours) - $row->total_duration) ?></td>
                    <td><?php echo $row->branch_name?></td>
                    <td>
                        <a href="/TimelogMetaController/log/<?php echo $row->user_id?>" class="btn btn-primary">
                            <?php echo $actionTxt?>         
                        </a>
                    </td>
				</tr>
			<?php endforeach?>
		</tbody>
	</table>
</div>
<?php else:?>
<div class="table-responsive">
	<table class="table table-bordered dataTable">
        <thead>
            <th>#</th>
            <th>Name</th>
            <th>Branch</th>
            <th>Action</th>
        </thead>

        <tbody>
            <?php foreach ($loggedUsers as $key => $row):?>
                <tr>
                    <td><?php echo ++$key?></td>
                    <td><?php echo $row->fullname?></td>
                    <td><?php echo $row->branch_name?></td>
                    <td>
                        <a href="/TimelogMetaController/log/<?php echo $row->id?>" class="btn btn-primary">Clock In</a>
                    </td>
                </tr>
            <?php endforeach?>
        </tbody>
    </table>
</div>
<?php endif?>