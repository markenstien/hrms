<?php build('content') ?>
    <div class="container-fluid">
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Attendance Approval'))?>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Employee ID</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Hours</th>
                            <th>File Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            <?php foreach($timesheets as $key => $row) :?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->fullname?></td>
                                    <td><?php echo $row->uid?></td>
                                    <td><?php echo $row->time_in?></td>
                                    <td><?php echo $row->time_out?></td>
                                    <td><?php echo minutesToHours(dateDifferenceInMinutes($row->time_in, $row->time_out))?></td>
                                    <td><?php echo $row->created_at?></td>
                                    <td><?php echo $row->status?></td>
                                    <td>
                                        <?php echo wLinkDefault(_route('attendance:approval', [
                                            'timesheet' => seal($row->id),
                                            'userId'    => whoIs('id'),
                                            'action'    => 'approve'
                                        ]), 'Approve')?> | 

                                        <?php echo wLinkDefault(_route('attendance:approval', [
                                            'timesheet' => seal($row->id),
                                            'userId'    => whoIs('id'),
                                            'action'    => 'cancel'
                                        ]), 'Cancel')?>
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