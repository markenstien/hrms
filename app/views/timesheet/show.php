<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4>Time sheet</h4>

            <?php if(isEqual($timesheet->status , 'pending') && isEqual(whoIs()['type'] , 'admin')) :?>
            <a href="/timesheetAction/approve/<?php echo $timesheet->id?>&token=<?php echo seal($timesheet->id)?>&next=true"
                class='btn btn-primary btn-lg'>Approve</a>
            <?php endif?>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Duration</th>
                        <th>Rate</th>
                        <th>Allowance</th>
                    </thead>

                    <tbody>
                        <tr>
                            <td><?php echo date_long($timesheet->time_in, 'M d,Y h:i:s A')?></td>
                            <td><?php echo date_long($timesheet->time_out, 'M d,Y h:i:s A')?></td>
                            <td><?php echo minutesToHours($timesheet->duration)?></td>
                            <td><?php echo $timesheet->meta->rate?></td>
                            <td><?php echo $timesheet->amount?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h5>Logs</h5>
            <?php foreach($logs as $key =>$row) :?>
                <p><?php echo $row->punch_time .'' . "($row->type)"?></p>
            <?php endforeach?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Task Photos</h4>
        </div>

        <div class="card-body">
            <?php foreach($taskPhotos as $key => $photo) :?>
                <div style="display:inline-block; margin:15px">
                    <img src="<?php echo $photo->file_path.DS.$photo->file_name?>" style="width:300px;">
                    <div>Uploaded On : <?php echo $photo->created_at?></div>
                </div>
            <?php endforeach?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/layout')?>