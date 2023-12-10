<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Approved Timesheets</h4>
            <a href="?action=today">Today</a> | 
            <a href="?action=all">Show All</a>
        </div>

        <div class="card-body">
            <div class="col-md-5">
                <?php
                    Form::open([
                        'method' => 'get'
                    ])
                ?>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <?php
                                Form::label('Start Date');
                                Form::date('start_date','',null);
                            ?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <?php
                                Form::label('End Date');
                                Form::date('end_date','',null);
                            ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <?php
                            Form::submit('btn_filter', 'Apply Filter')
                        ?>
                    </div>
                </div>
                <?php Form::close()?>
            </div>
            <?php if(isset($_GET['btn_filter']) || isset($_GET['action'])) :?>
                <div class="table-responsive">
                    <?php $total = 0?>
                    <table class="table table-bordered">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Rate Per Hour</th>
                            <th>Time Duration</th>
                            <th>Amount</th>
                        </thead>
                        <tbody>
                            <?php $counter = 1?>
                            <?php $overallTotal = 0?>
                            <?php foreach($timesheetsGroupedByBranch as $key => $items) :?>
                                <?php if(empty($key)) continue?>
                                <?php $branchTotal = 0?>
                                <tr style="background-color: red; color:#fff;font-weight:bold">
                                    <td colspan="5"><?php echo $key?></td>
                                </tr>
                                <?php foreach($items as $itemKey => $itemRow) :?>
                                    <?php $branchTotal += $itemRow['amount']?>
                                    <?php $overallTotal += $itemRow['amount']?>
                                    <tr>
                                        <td><?php echo $counter++?></td>
                                        <td><?php echo $itemRow['name']?></td>
                                        <td><?php echo $itemRow['ratePerHour']?></td>
                                        <td><?php echo minutesToHours($itemRow['totalDuration'])?></td>
                                        <td><?php echo amountHTML($itemRow['amount'])?></td>
                                    </tr>
                                <?php endforeach?>
                                <tr>
                                    <td>Total : </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo amountHTML($branchTotal)?></td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                    <h3>Total : <?php echo amountHTML($overallTotal)?></h3>
                </div>
            <?php else:?>
                <h1 class="text-center">Apply Filter to show report</h1>
            <?php endif?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>