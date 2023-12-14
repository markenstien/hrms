<?php build('content') ?>
<div class="container-fluid">
    <div class="card">
        <?php echo wCardHeader(wCardTitle('Leave Request Admin Approval'))?>
        <div class="card-body">
            <?php Flash::show()?>
            <?php Form::open(['method' => 'post'])?>
            <?php Form::hidden('id', $leave->id)?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td><?php Form::select('remarks', Module::get('ee_leave')['admin-approval-category'], '', ['class' => 'form-control', 'required' => true])?></td>
                        <td><?php Form::submit('', 'Apply')?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="background-color:var(--primary);color:#fff">Leave Info</td>
                    </tr>
                    <tr>
                        <td>Employee</td>
                        <td><?php echo $leave->employee_fullname?></td>
                    </tr>
                    <tr>
                        <td>Reason</td>
                        <td><?php echo $leave->leave_category?></td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td><?php echo $leave->start_date?></td>
                    </tr>
                    <tr>
                        <td>Approved By</td>
                        <td><?php echo $leave->approver_fullname?></td>
                    </tr>

                    <tr>
                        <td>Status</td>
                        <td><?php echo $leave->status?></td>
                    </tr>
                    <tr>
                        <td>Remarks</td>
                        <td><?php echo $leave->remarks?></td>
                    </tr>
                    
                </table>
            </div>
            <?php Form::close()?>
        </div>
    </div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>