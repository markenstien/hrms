<?php build('content') ?>
    <div class="container-fluid">
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Payslips'))?>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
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
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>