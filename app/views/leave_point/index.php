<?php build('content') ?>

    <div class="container-fluid">
        <?php
            $navs = [$navigationHelper->setNav('', 'Leave Summary', _route('leave:summary'), [
                'icon' => 'fas fa-eye'
            ])];

            if(isEqual(whoIs('type'), ['HR', 'ADMIN', 'SUPERADMIN'])) {
                array_push($navs, $navigationHelper->setNav('', 'Add Leave Point', _route('leave-point:create')));
            }

        ?>
        <?php echo wControlButtonRight('Leave Management', $navs)?>
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Leave Credits'))?>
            <div class="card-body">
                <?php Flash::show()?>
                <div class="table-responsive">
                    <table class="table table-bordered dataTableAction">
                        <thead>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Type of Leave</th>
                            <th>Point</th>
                            <th>Remarks</th>
                            <th>Date</th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            <?php foreach($leave_point_logs as $key => $row) :?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->full_name?></td>
                                    <td><?php echo $row->leave_point_category?></td>
                                    <td><?php echo $row->point?></td>
                                    <td><?php echo $row->remarks?></td>
                                    <td><?php echo $row->created_at?></td>
                                    <td>
                                        <?php echo wLinkDefault(_route('leave-point:delete', $row->id), 'Delete Point')?>
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