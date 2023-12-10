<?php build('content') ?>

    

    <div class='card'>

        <div class='card-header'>
            <h4 class='card-title'>Timesheets</h4>
            <?php Flash::show()?>
        </div>


        <div class='card-body'>
            <?php
                $ids = [];
                Form::open([
                    'method' => 'post',
                    'action' => '/TimesheetContainer/approveTimeSheets'
                ]);

                foreach($timesheets as $key => $row) {
                    $ids [] = $row->id;
                }
                Form::hidden('timesheet_ids', seal($ids));
            ?>
                <div class="form-group">
                    <?php Form::submit('', 'Approve All')?>
                </div>
            <?php Form::close()?>

            <?php if(isset($_GET['branch_id'])) :?>
                &nbsp; | <?php echo wLinkDefault('?', 'Remove Filter')?>
            <?php endif?>
            <div class="table-responsive">
                <table class='table table-bordered'>
                    <thead>
                        <th>User</th>
                        <th>Department</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Duration</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php $totalAmount = 0?>
                        <?php foreach($timesheets as $key => $row):?>
                            <?php $totalAmount += $row->amount?>
                                <tr>
                                    <form class="post" action="/TimesheetAction/update_timesheet" method="post">
                                        <?php
                                            Form::hidden('id' , $row->id);
                                            Form::hidden('userid' , $row->user_id);
                                            $date_time_in=date_create($row->time_in);
                                            $date_time_out=date_create($row->time_out);
                                        ?>
                                        <td><?php echo $row->full_name?></td>
                                        <td><?php echo wLinkDefault('/TimesheetContainer/cancelled_timeSheet?branch_id='.$row->branch_id, $row->branch_name)?></td>
                                        <td>
                                            <input type="datetime-local"  name="time_in" value="<?php echo date_format($date_time_in,"Y-m-d\TH:i"); ?>">
                                        </td>
                                        <td>
                                            <input type="datetime-local"  name="time_out" value="<?php echo date_format($date_time_out,"Y-m-d\TH:i"); ?>">
                                        </td>
                                        <td><?php echo minutesToHours($row->duration)?></td>
                                        <td><?php echo $row->amount?></td>
                                        <td><?php echo $row->status?></td>
                                        <td><?php echo $row->remarks?></td>
                                        <td>
                                            <input type="submit" name="" value="Process"
                                                class="btn btn-success btn-sm form-confirm">

                                            <a href="/TimesheetAction/approve/<?php echo $row->id?>&token=<?php echo seal($row->id) ?>" class='btn btn-primary btn-sm'> Approve </a>
                                        </td>
                                    </form>
                                </tr>
                        <?php endforeach?>
                        <tr>
                            <td colspan='4'>Total</td>
                            <td><?php echo amountHTML($totalAmount)?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>



<?php endbuild()?>

<?php loadTo('tmp/layout')?>