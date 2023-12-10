<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonRight('Payroll Management', [
            $navigationHelper->setNav('', 'Generate Payroll', _route('payroll:create'))
        ]);?>
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Payroll List')) ?>
            <div class="card-body">
                <?php Flash::show()?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <th>#</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Show</th>
                        </thead>

                        <tbody>
                            <?php foreach($payrollList as $key => $row) :?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->start_date?></td>
                                    <td><?php echo $row->end_date?></td>
                                    <td><?php echo is_null($row->release_date) ? 'For-Relase' : 'Released'?></td>
                                    <td><?php echo wLinkDefault(_route('payroll:show', $row->id), 'Show')?></td>
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