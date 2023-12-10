<?php build('content') ?>
    <div class="container-fluid">
        <?php grab('timesheet_container/inc/filter')?>
        <?php if(isset($_GET['btn_filter'])) :?>
            <div class="table-responsive">
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
                <table class="table table-bordered">
                    <thead>
                        <th>Name</th>
                        <th>Rate</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                        <th>Sun</th>
                        <th>Total</th>
                    </thead>

                    <tbody>
                        <?php foreach($tsheetsGroupByUserGroupedByDays as $key => $user) : ?>
                            <?php
                                $timesheetDays = $user['timesheetByDays'];
                                $totalIncome = 0;

                                if(empty($user['fullname'])) continue;
                            ?>
                            <tr>
                                <td><span title="<?php echo $user['fullname']?>"><?php echo $user['fullname']?></span></td>
                                <td>
                                    <div><?php echo $user['rate_per_day']?></div>
                                    <div><?php echo $user['rate_per_hour']?></div>
                                    <div><?php echo minutesToHours($user['max_work_hours'] * 60)?></div>
                                </td>
                                <td>
                                    <?php if(isset($timesheetDays['Mon'])) :?>
                                        <?php
                                            $sheet = extractTimesheet($timesheetDays['Mon']);   
                                            if($timesheetDays['Mon']) {
                                                $date = date('m-d', strtotime($timesheetDays['Mon'][0]->time_in));
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
                                                $date = date('m-d', strtotime($timesheetDays['Tue'][0]->time_in));
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
                                                $date = date('m-d', strtotime($timesheetDays['Wed'][0]->time_in));
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
                                                $date = date('m-d', strtotime($timesheetDays['Thu'][0]->time_in));
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
                                                $date = date('m-d', strtotime($timesheetDays['Fri'][0]->time_in));
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
                                                $date = date('m-d', strtotime($timesheetDays['Sat'][0]->time_in));
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
                                                $date = date('m-d', strtotime($timesheetDays['Sun'][0]->time_in));
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
                    </tbody>
                </table>
            </div>
        <?php else:?>
            <div class="text-center">
                <h1>Filter 1 week range to get result</h1>
            </div>
        <?php endif?>
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

<?php build('scripts')?>
    <script defer>
        $(document).ready(function(){
            $('#dataTable').DataTable({
                paging : false
            });
        });
    </script>
<?php endbuild()?>
<?php occupy('tmp/layout')?>