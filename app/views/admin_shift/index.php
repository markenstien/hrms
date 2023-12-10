<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonRight('Shift Management', [
            $navigationHelper->setNav('', 'Add New Shift', _route('admin-shift:create'))
        ])?>
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Shifts')) ?>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>#</th>
                            <th>Code</th>
                            <th><?php echo $form->label('shift_name')?></th>
                            <th><?php echo $form->label('shift_description')?></th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            <?php foreach($shifts as $key => $row) :?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->shift_code?></td>
                                    <td><?php echo $row->shift_name?></td>
                                    <td><?php echo $row->shift_description?></td>
                                    <td><?php echo wLinkDefault(_route('admin-shift:edit', $row->id), 'Edit')?></td>
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