<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonRight('Attendance Management',[
            $navigationHelper->setNav('', 'File AC', _route('attendance:create'))
        ]);?>
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Attendance'))?>
            <div class="card-body">
                <?php
                    if(true) {
                        echo wLinkDefault(_route('attendance:approval'), 'Approvals');
                    }
                ?>
                
                <?php Flash::show()?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Employee ID</th>
                            <th>Entry Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Hours</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Approval Date</th>
                        </thead>

                        <tbody>
                            <?php foreach($attendanceList as $key => $row) :?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->fullname?></td>
                                    <td><?php echo $row->uid?></td>
                                    <td><?php echo $row->entry_type?></td>
                                    <td><?php echo $row->time_in?></td>
                                    <td><?php echo $row->time_out?></td>
                                    <td><?php echo minutesToHours(dateDifferenceInMinutes($row->time_in, $row->time_out))?></td>
                                    <td><?php echo $row->status?></td>
                                    <td><?php echo $row->approver_name?></td>
                                    <td><?php echo $row->approval_date?></td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
                <div class="row" style="display: none;">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-sm">Clock In</button>
                        <button class="btn btn-primary btn-sm">Clock Out</button>

                        <?php echo wDivider()?>
                        <div class="col-md-5">
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <td>Clock In Time</td>
                                    <td>12:30 am</td>
                                </tr>

                                <tr>
                                    <td>Schedule</td>
                                    <td>8:00am - 5:00pm</td>
                                </tr>
                            </table>
                            <h4>Hours On Duty : <span>2hrs 5mins</span></h4>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>