<?php build('content') ?>
    <div class="container-fluid">
        <?php grab('timesheet_container/inc/filter')?>
        <?php
            $overAllTotal = [
                'mon' => 0,
                'tue' => 0,
                'wed' => 0,
                'thu' => 0,
                'fri' => 0,
                'sat' => 0,
                'sun' => 0
            ];
        ?>
        <div class="table-responsive">
            <?php foreach ($groupedByBranch as $key => $branch) :?>
                <?php if(empty($branch['name'])) continue?>
                <h1><?php echo $branch['name']?></h1>
                <?php
                    $totals = [
                        'mon' => 0,
                        'tue' => 0,
                        'wed' => 0,
                        'thu' => 0,
                        'fri' => 0,
                        'sat' => 0,
                        'sun' => 0
                    ];
                ?>

                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <th>Name</th>
                            <th>Rate</th>
                            <th>Mon</th>
                            <th>Thu</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                            <th>Sun</th>
                            <th>Total</th>
                        </thead>

                        <tbody>
                            <?php foreach($branch['users'] as $key => $user) : ?>
                                <?php
                                    $timesheetDays = $user['timesheetByDays'];
                                    $totalIncome = 0;
                                    if(empty($user['fullname'])) continue;
                                ?>
                                <tr>
                                    <td><?php echo $user['fullname']?></td>
                                    <td>
                                        <div><?php echo $user['rate_per_day']?></div>
                                        <div><?php echo $user['rate_per_hour']?></div>
                                    </td>
                                    <td>
                                        <?php if(isset($timesheetDays['Mon'])) :?>
                                            <?php
                                                $sheet = extractTimesheet($timesheetDays['Mon']);
                                                if($timesheetDays['Mon']) {
                                                    $date = date('Y-m-d', 
                                                        strtotime($timesheetDays['Mon'][0]->time_in));
                                                    echo "<div>{$date}</div>";
                                                }   
                                                echo '<div>'.$sheet['amountText']. '</div>';
                                                echo $sheet['duration'];
                                                $totals['mon'] += $sheet['amount'];
                                                $totalIncome += $sheet['amount'];
                                            ?>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <?php if(isset($timesheetDays['Tue'])) :?>
                                            <?php
                                                $sheet = extractTimesheet($timesheetDays['Tue']); 
                                                if($timesheetDays['Tue']) {
                                                    $date = date('Y-m-d', 
                                                        strtotime($timesheetDays['Tue'][0]->time_in));
                                                    echo "<div>{$date}</div>";
                                                }  
                                                echo '<div>'.$sheet['amountText']. '</div>';
                                                echo $sheet['duration'];

                                                $totals['tue'] += $sheet['amount'];
                                                $totalIncome += $sheet['amount'];
                                            ?>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <?php if(isset($timesheetDays['Wed'])) :?>
                                            <?php
                                                $sheet = extractTimesheet($timesheetDays['Wed']); 
                                                if($timesheetDays['Wed']) {
                                                    $date = date('Y-m-d', 
                                                        strtotime($timesheetDays['Wed'][0]->time_in));
                                                    echo "<div>{$date}</div>";
                                                }  
                                                echo '<div>'.$sheet['amountText']. '</div>';
                                                echo $sheet['duration'];

                                                $totals['wed'] += $sheet['amount'];
                                                $totalIncome += $sheet['amount'];
                                            ?>
                                        <?php endif?>    
                                    </td>
                                    <td>
                                        <?php if(isset($timesheetDays['Thu'])) :?>
                                            <?php
                                                $sheet = extractTimesheet($timesheetDays['Thu']);
                                                if($timesheetDays['Thu']) {
                                                    $date = date('Y-m-d', 
                                                        strtotime($timesheetDays['Thu'][0]->time_in));
                                                    echo "<div>{$date}</div>";
                                                }   
                                                echo '<div>'.$sheet['amountText']. '</div>';
                                                echo $sheet['duration'];

                                                $totals['thu'] += $sheet['amount'];
                                                $totalIncome += $sheet['amount'];
                                            ?>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <?php if(isset($timesheetDays['Fri'])) :?>
                                            <?php
                                                $sheet = extractTimesheet($timesheetDays['Fri']); 
                                                if($timesheetDays['Fri']) {
                                                    $date = date('Y-m-d', 
                                                        strtotime($timesheetDays['Fri'][0]->time_in));
                                                    echo "<div>{$date}</div>";
                                                }  
                                                echo '<div>'.$sheet['amountText']. '</div>';
                                                echo $sheet['duration'];

                                                $totals['fri'] += $sheet['amount'];
                                                $totalIncome += $sheet['amount'];
                                            ?>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <?php if(isset($timesheetDays['Sat'])) :?>
                                            <?php
                                                $sheet = extractTimesheet($timesheetDays['Sat']);
                                                if($timesheetDays['Sat']) {
                                                    $date = date('Y-m-d', 
                                                        strtotime($timesheetDays['Sat'][0]->time_in));
                                                    echo "<div>{$date}</div>";
                                                }   
                                                echo '<div>'.$sheet['amountText']. '</div>';
                                                echo $sheet['duration'];

                                                $totals['sat'] += $sheet['amount'];
                                                $totalIncome += $sheet['amount'];
                                            ?>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <?php if(isset($timesheetDays['Sun'])) :?>
                                            <?php
                                                $sheet = extractTimesheet($timesheetDays['Sun']); 
                                                if($timesheetDays['Sun']) {
                                                    $date = date('Y-m-d', 
                                                        strtotime($timesheetDays['Sun'][0]->time_in));
                                                    echo "<div>{$date}</div>";
                                                }  
                                                echo '<div>'.$sheet['amountText']. '</div>';
                                                echo $sheet['duration'];

                                                $totals['sun'] += $sheet['amount'];
                                                $totalIncome += $sheet['amount'];
                                            ?>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <?php echo amountHTML($totalIncome)?>
                                    </td>
                                </tr>
                            <?php endforeach?>
                            <tr>
                                <td>Total</td>
                                <td></td>
                                <td><?php echo amountHTML($totals['mon'])?></td>
                                <td><?php echo amountHTML($totals['tue'])?></td>
                                <td><?php echo amountHTML($totals['wed'])?></td>
                                <td><?php echo amountHTML($totals['thu'])?></td>
                                <td><?php echo amountHTML($totals['fri'])?></td>
                                <td><?php echo amountHTML($totals['sat'])?></td>
                                <td><?php echo amountHTML($totals['sun'])?></td>
                                <td><?php echo amountHTML(tmpSumAll($totals))?></td>
                            </tr>
                            <?php
                                $overAllTotal['mon'] += $totals['mon'];
                                $overAllTotal['tue'] += $totals['tue'];
                                $overAllTotal['wed'] += $totals['wed'];
                                $overAllTotal['thu'] += $totals['thu'];
                                $overAllTotal['fri'] += $totals['fri'];
                                $overAllTotal['sat'] += $totals['sat'];
                                $overAllTotal['sun'] += $totals['sun'];
                            ?>
                        </tbody>
                    </table>
            <?php endforeach?>
        </div>

        <h1>Total Summary</h1>
        <ul>
            <li>Monday : <?php echo $overAllTotal['mon']?></li>
            <li>Tuesday : <?php echo $overAllTotal['tue']?></li>
            <li>Wedenssday : <?php echo $overAllTotal['wed']?></li>
            <li>Thursday : <?php echo $overAllTotal['thu']?></li>
            <li>Friday : <?php echo $overAllTotal['fri']?></li>
            <li>Saturday : <?php echo $overAllTotal['sat']?></li>
            <li>Sunday : <?php echo $overAllTotal['sun']?></li>
            <li>Total : <?php echo tmpSumAll($overAllTotal)?></li>
        </ul>
    </div>
<?php endbuild()?>
<?php
    function tmpSumAll($numbers = []) {
        $total = 0;
        foreach($numbers as $key => $row) {
            $total += $row;
        }

        return $total;
    }
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