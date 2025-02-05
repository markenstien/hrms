<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonLeft('User Management', [
            $navigationHelper->setNav('', 'Back', _route('user:index'))
        ])?>
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Employee Preview'))?>
            <div class="card-body">
                <?php Flash::show()?>
                <div class="row">
                    <div class="col-md-4">
                        <img src="<?php echo $user->profile_url?>" alt="" style="width: 150px;">
                        <section class="mt-3">
                            <table class="table table-bordered table-sm">
                                <tr><td>Access : <?php echo $user->type?></tr>
                                <tr><td>Name : <?php echo $user->fullname?> <span>(<?php echo $user->uid?>)</span> </td></tr>
                                <tr><td>Position / Department : <?php echo $user->position_name?> / <?php echo $user->department_name?></td></tr>
                                <tr><td>Email : <?php echo $user->email?></td></tr>
                                <tr><td>Mobile Number : <?php echo $user->mobile_number?></td></tr>
                                <tr>
                                    <td>
                                        <?php
                                            if(isEqual(whoIs('type'), ['HR','SUPER_ADMIN', 'ADMIN']) || isEqual($user->id, whoIs('id'))) {
                                                echo wLinkDefault(_route('user:edit-credentials', $user->id), 'Change Password');
                                            }

                                            if(isEqual(whoIs('type'), ['HR','SUPER_ADMIN', 'ADMIN']) || isEqual($user->id, whoIs('id'))) {
                                                echo wLinkDefault(_route('user:edit', $user->id), 'Edit');
                                            }
                                        ?>
                                      <?php 
                                        
                                      ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo wLinkDefault(_route('leave:user', $user->id), 'Leave Credits')?></td>
                                </tr>
                                
                                <?php if(isEqual(whoIs('id'), $user->id)) :?>
                                    <tr>
                                        <td>Username : <?php echo $user->username?> </td>
                                    </tr>
                                <?php endif?>
                            </table>
                        </section>
                    </div>

                    <div class="col-md-8">
                        <section>
                            <h4>Paylsips</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <th>#</th>
                                    <th>Cutoff</th>
                                    <th>View</th>
                                </thead>

                                <tbody>
                                    <?php foreach($payslips as $key => $row) :?>
                                        <tr>
                                            <td><?php echo ++$key?></td>
                                            <td><?php echo "{$row->start_date} To {$row->end_date}"?></td>
                                            <td><?php echo wLinkDefault(_route('payroll:view-payslip', $row->payroll_id, [
                                                'user_id' => seal($row->user_id)
                                            ]), 'Show Payslip')?></td>
                                        </tr>
                                    <?php endforeach?>
                                </tbody>
                            </table>
                        </section>

                        <section>
                            <h4>File Management</h4>
                            <?php echo wLinkDefault('#', 'Upload File', [
                                'data-toggle' => 'modal',
                                'data-target' => '#exampleModal'
                            ])?>
                            <table class="table table-bordered">
                                <thead>
                                    <th>#</th>
                                    <th>File Name</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <?php if(isEqual(whoIs('type'), ['admin', 'superadmin'])) :?>
                                    <th>Approval</th>
                                    <?php endif?>
                                </thead>

                                <tbody>
                                    <?php foreach($files as $key => $row) :?>
                                        <tr>
                                            <td><?php echo ++$key?></td>
                                            <td><?php echo $row->display_name?></td>
                                            <td>
                                                <?php echo wLinkDefault(_route('viewer:show', [
                                                    'file' => seal($row->full_url),
                                                    'attachmentId' => $row->id,
                                                    'userId' => seal($userId)
                                                ]), 'Show')?>
                                                &nbsp;

                                                <?php echo wLinkDefault(_route('attachment:edit', $row->id), 'Edit', [
                                                    ''
                                                ])?>

                                                &nbsp;
                                                <?php
                                                    if(isEqual(whoIs('type'), 'HR') || isEqual($user->id, whoIs('id'))){
                                                        echo wLinkDefault(_route('attachment:delete', [
                                                            'id' => $row->id
                                                        ]), 'Delete');
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo $row->is_visible ? '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-warning">Pending</span>';?></td>
                                            <?php if(isEqual(whoIs('type'), ['ADMIN', 'SUPERADMIN'])) :?>
                                            <td>
                                                <?php
                                                    if(!$row->is_visible) {
                                                        echo wLinkDefault(_route('attachment:update-visibility', [
                                                            'visible' => 'yes',
                                                            'id'      => seal($row->id)
                                                        ]), 'Approve', [
                                                            'class' => 'btn btn-sm btn-primary'
                                                        ]);
                                                    }
                                                ?>
                                            </td>
                                            <?php endif?>
                                        </tr>
                                    <?php endforeach?>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
                                            
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $attachmentForm->start()?>
                        <?php
                            echo Form::hidden('user_id', $userId);
                            echo Form::hidden('g_key', 'user_resources');
                            echo Form::hidden('route', seal(_route('user:show', $userId)));
                            echo Form::hidden('path', seal(PATH_UPLOAD.DS.'user_resources'));
                            echo Form::hidden('g_url', seal(GET_PATH_UPLOAD.DS.'user_resources'));
                        ?>
                        <div class="form-group">
                            <?php echo $attachmentForm->getCol('display_name');?>
                        </div>

                        <div class="form-group">
                            <?php echo $attachmentForm->getCol('file')?>
                        </div>

                        <div class="form-group">
                            <?php echo Form::submit('upload_file', 'Upload File')?>
                        </div>
                    <?php echo $attachmentForm->end()?>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>