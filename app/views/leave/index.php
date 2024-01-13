<?php

use function PHPSTORM_META\map;

 build('content') ?>
<div class="container-fluid">
    <?php
        echo wControlButtonRight('Leave Management', [
            $navigationHelper->setNav('', 'Request Leave', _route('leave:create')),
            $navigationHelper->setNav('', 'Filter', '#', [
                'link-attributes' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#modalSearch',
                ],
                'icon' => 'fas fa-filter'
            ]),
            $navigationHelper->setNav('', 'Leave Credits', _route('leave-point:index'))
        ])
    ?>
    <div class="card">
        <?php
            echo wCardHeader(wCardTitle('Leave Management'))
        ?>
        <div class="card-body">
            <?php Flash::show()?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <th>#</th>
                        <th>User</th>
                        <th>Category</th>

                        <th>Reference Date</th>
                        <th>Start Date</th>
                        <th>End Date</th>

                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Approved By</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach($leaves as $key => $row) : ?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->employee_fullname?></td>
                                <td><?php echo $row->leave_category?></td>

                                <td><?php echo $row->date_filed?></td>
                                <td><?php echo $row->start_date?></td>
                                <td><?php echo $row->end_date?></td>

                                <td><?php echo $row->status?></td>
                                <td><?php echo $row->remarks?></td>
                                <td><?php echo $row->approver_fullname?></td>
                                <td>
                                    <?php
                                        if(isEqual(whoIs('id'), $row->user_id) && isEqual($row->status,'pending')) {
                                            echo wLinkDefault(_route('leave:edit', $row->id), '', [
                                                'icon' => 'fas fa-edit',
                                                'class' => 'btn btn-sm btn-primary'
                                            ]);
                                        }

                                        if(isEqual(whoIs('type'), ['SUPER_ADMIN', 'HR'])) {
                                            if(isEqual($row->status,'pending')) {
                                                echo '|' . ' ' .wLinkDefault(_route('leave:approve', $row->id), '', [
                                                    'icon' => 'fas fa-check-circle',
                                                    'class' => 'btn btn-sm btn-primary'
                                                ]);
                                                
                                                echo '&nbsp; |' . ' ' .wLinkDefault(_route('leave:delete', $row->id), '', [
                                                    'icon' => 'fas fa-trash',
                                                    'class' => 'text-danger form-verify',
                                                    'class' => 'btn btn-sm btn-danger'
                                                ]);
                                            }
    
                                            if(empty($row->remarks)) {
                                                $noAction = true;
                                                echo '|' . ' '.wLinkDefault(_route('leave:admin-approval', $row->id), '', [
                                                    'icon' => 'fas fa-check-circle',
                                                    'class' => 'btn btn-sm btn-success'
                                                ]);
                                            }
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    <!-- Modal -->
    <div class="modal fade" id="modalSearch" tabindex="-1" role="dialog" aria-labelledby="modalSearchLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSearchLabel">Leave Filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-text">
                        <?php
                            Form::open([
                                'method' => 'get',
                                'action' => ''
                            ])
                        ?>
                            <?php echo $form->getCol('status', [
                                'required' => false
                            ])?>
                            <?php echo $form->getCol('user_id', [
                                'required' => false
                            ])?>
                            <?php echo $form->getCol('leave_category', [
                                'required' => false
                            ])?>
                            <?php echo $form->getCol('remarks')?>

                            <div class="mt-2">
                                <?php Form::submit('filter', 'Apply Filter')?>
                            </div>
                        <?php Form::close()?>
                    </p>
                </div>
            </div>
        </div>
    </div>

<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>