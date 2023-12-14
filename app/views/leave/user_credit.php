<?php  build('content')?>
<div class="container-fluid">
    <?php echo wControlButtonLeft('Leave Credit Management', [
        $navigationHelper->setNav('', 'Back', _route('user:show', $user->id))
    ])?>
    <div class="col-md-6 mx-auto">
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Leave Credits'))?>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td>Employee</td>
                            <td><?php echo $user->fullname?></td>
                        </tr>
                        <tr>
                            <td>UID</td>
                            <td><?php echo $user->uid?></td>
                        </tr>
                        <tr>
                            <td>Leave Credits</td>
                            <td>
                                <?php foreach($leaveCredits as $key => $row) :?>
                                    <li><?php echo $row->leave_point_category?> (<?php echo $row->total_point?>)</li>
                                <?php endforeach?>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Leave</td>
                            <td><?php echo $leaveCreditTotal?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>  
    </div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>