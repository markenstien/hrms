<?php build('content')?>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Timesheet</h4>
        </div>

        <?php if(!isset($datesCreated)) :?>
            <div class="card-body">
                <?php
                    Form::open([
                        'method' => 'get'
                    ])
                ?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <?php
                                    Form::label('Start Date');
                                    Form::date('start_date', '', [
                                        'class' => 'form-control'
                                    ]);
                                ?>
                            </div>

                            <div class="col-md-3">
                                <?php
                                    Form::label('End Date');
                                    Form::date('end_date', '', [
                                        'class' => 'form-control'
                                    ]);
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php Form::submit('montly_filter', 'Apply Filter')?>
                    </div>
                <?php Form::close()?>
            </div>
        <?php endif?>

        <?php if(isset($datesCreated)) :?>
        <?php
            $totalPerDate = [];
        ?>
        <div class="card-body">
            <h4>Timesheet Board</h4>
            <a href="?" class="btn btn-primary">Re-Filter</a>
            <a href="#summary" class="btn btn-primary">Summary</a>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td>User</td>
                        <td>Rate(Per Day)</td>
                        <?php foreach($datesCreated as $key => $date) :?>
                            <?php $day = date('D', strtotime($date)); ?>
                            <?php $totalPerDate[$date] = 0; ?>
                            <td>
                                <div style="width: 75px;"><?php echo $date?></div>
                                <div><?php echo $day?></div>
                            </td>
                        <?php endforeach?>
                    </tr>
                    <?php foreach($timesheetsByUsersGroupedByDates as $key => $user) :?>
                        <tr>
                            <td><?php echo $user['fullname']?></td>
                            <td><?php echo $user['rate_per_day']?></td>
                            <?php foreach($datesCreated as $dateKey => $date) :?>
                                <?php
                                    $timesheets = $user['timesheetByDate'];
                                ?>
                                <?php foreach($timesheets as $tKey => $row) :?>
                                    <?php if(isEqual($date, $tKey)) :?>
                                        <?php
                                            $extractTimesheet = extractTimesheet($row);
                                            $totalPerDate[$tKey] += $extractTimesheet['amount'];
                                        ?>
                                        <td>
                                            <div><?php echo $extractTimesheet['amountText']?></div>
                                            <div><?php echo $extractTimesheet['duration']?></div>
                                            <div><?php echo $tKey?></div>
                                        </td>
                                        <?php
                                            unset($user['timesheetByDate'][$tKey]);
                                            break;
                                        ?>
                                    <?php else:?>
                                        <td>
                                            <?php echo "-{$date}-"?>
                                        </td>
                                        <?php break;?>
                                    <?php endif?>
                                <?php endforeach?>
                            <?php endforeach?>
                        </tr>
                    <?php endforeach?>

                    <tr>
                        <td></td>
                        <td></td>
                        <?php foreach($datesCreated as $key => $date) :?>
                            <td><?php echo $totalPerDate[$date]?></td>
                        <?php endforeach?>
                    </tr>
                </table>
            </div>

            <div class="mt-5"></div>
            <h4 id="summary">Summary</h4>
            (<?php echo $_GET['start_date'] ." TO ". $_GET['end_date']?> (<?php echo count($datesCreated) .' days'?>) )
            <?php $perUserTotal = 0?>
            <div class="row">
                <div class="col-md-6">
                    <h5>Per User</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td>Name</td>
                                <td>Worked Hours</td>
                                <td>Rate</td>
                                <td>Earning</td>
                            </tr>
                            <?php foreach($timesheetsGrouped as $key => $row) :?>
                                <?php if(empty($row['name'])) continue?>
                                <?php $perUserTotal += $row['amount']?>
                                <tr>
                                    <td><?php echo $row['name']?></td>
                                    <td><?php echo minutesToHours($row['totalDuration'])?></td>
                                    <td><?php echo $row['ratePerHour']?></td>
                                    <td><?php echo convertToCash($row['amount'])?></td>
                                </tr>
                            <?php endforeach?>
                        </table>
                    </div>
                    <h5>Total : <?php echo convertToCash($perUserTotal)?></h5>
                </div>

                <div class="col-md-6">
                    <h5>Per Day</h5>
                    <?php $perDayTotal = 0?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td>Date</td>
                                <td>Payout</td>
                            </tr>
                            <?php foreach($totalPerDate as $key => $row) :?>
                                <?php if(empty($row)) continue?>
                                <?php $perDayTotal += $row?>
                                <tr>
                                    <td><?php echo $key?></td>
                                    <td><?php echo convertToCash($row)?></td>
                                </tr>
                            <?php endforeach?>
                        </table>
                    </div>
                    <h5>Total : <?php echo convertToCash($perDayTotal)?></h5>
                </div>
            </div>
        </div>
        <?php endif?>
    </div> 
<?php endbuild()?>
<?php
    function extractTimesheet($timesheets) 
    {
        $duration = 0;
        $amount = 0;

        foreach($timesheets as $key => $row) {
            $duration += $row->duration;
            $amount += $row->amount;
        }

        return [
            'duration' => minutesToHours($duration),
            'amount'   => $amount,
            'amountText' => amountHTML($amount)
        ];
    }
?>
<?php occupy()?>