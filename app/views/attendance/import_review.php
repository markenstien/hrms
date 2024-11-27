<?php build('content') ?>
<div class="container-fluid">
    <?php echo wControlButtonLeft('Attendance', [
        $navigationHelper->setNav('', 'Back', _route('attendance:index'))
    ])?>
    <div class="col-md-12 mx-auto">
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Timesheet Import Review')) ?>

            <div class="card-body">
                <a href="<?php echo _route('attendance:import')?>" class="btn btn-primary btn-sm">Cancel</a>
                <a href="<?php echo _route('attendance:save-and-import', [
                    'path' => $file_name
                ])?>" class="btn btn-primary btn-sm">Import</a>
                <hr>
                <table class="table table-sm table-bordered">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Duration</th>
                    </thead>

                    <tbody>
                        <?php $counter = 0?>
                        <?php foreach($timesheets as $date => $timelogs) :?>
                            <tr>
                                <td colspan="5" style="background-color: #0D0CB5; color:#fff"><?php echo $date?></td>
                            </tr>
                            <?php foreach($timelogs as $key => $row) :?>
                                <tr>
                                    <td><?php echo $key?></td>
                                    <td><?php echo $row['name']?></td>
                                    <td><?php echo $row['in'] ?? 'NO IN FOUND'?></td>
                                    <td><?php echo $row['out'] ?? 'NO OUT FOUND'?></td>
                                    <td><?php echo empty($row['duration_in_minutes']) ? '':minutesToHours($row['duration_in_minutes'])?></td>
                                </tr>
                            <?php endforeach?>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endbuild()?>

<?php loadTo('tmp/admin_layout')?>