<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonRight('Holiday Management', [
            $navigationHelper->setNav('', 'Add Holiday', _route('holiday:create'))
        ])?>
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Holidays'))?>
            <div class="card-body">
                <?php Flash::show()?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>#</th>
                            <th><?php echo $form->label('holiday_name')?></th>
                            <th><?php echo $form->label('holiday_name_abbr')?></th>
                            <th><?php echo $form->label('holiday_date')?></th>
                            <th><?php echo $form->label('holiday_work_type')?></th>
                            <th><?php echo $form->label('holiday_pay_type')?></th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            <?php foreach($holidays as $key => $row) :?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->holiday_name?></td>
                                    <td><?php echo $row->holiday_name_abbr?></td>
                                    <td><?php echo $row->holiday_date?></td>
                                    <td><?php echo Module::get('holidays')['workTypeList'][$row->holiday_work_type]?></td>
                                    <td><?php echo Module::get('holidays')['payTypeList'][$row->holiday_pay_type]?></td>
                                    <td><?php echo wLinkDefault(_route('holiday:edit', $row->id), 'Edit')?></td>
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