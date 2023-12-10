<?php build('content') ?>

    <div class="card">
        <div class='card-header'>
            <h4 class='card-title'>Logged Users</h4>
        </div>

        <div class='card-body'>
            <div class='table-responsive'>
                <h4  style="color:green;"><b>Construction Department</b></h4>
                <table class='table'>
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Work Duration Today</th>
                        <th>PunchTime</th>
                        <th>Time Passed</th>
                    </thead>

                    <tbody>
                        <?php $counter = 0;?>
                        <?php foreach($activeUsers as $key => $row) :?>
                        

                        <?php if(@$row->user->department == "contractions"): ?>
                            <tr>
                                <td><?php echo ++$counter?></td>
                                <td><?php echo $row->user->firstname . ' ' .$row->user->lastname?></td>
                                   <td>
                                    <?php
                                        // if time in
                                        $total_worktime_minutes = time_diff_minutes($row->punch_time) +  $row->user->workHoursToday;

                                        $hours =  floor($total_worktime_minutes / 60);
                                        $minutes =  floor($total_worktime_minutes % 60);
                                        echo $hours.'hours '.$minutes.'minutes';
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                        $date=date_create($row->punch_time);
                                        echo date_format($date,"M d, Y");
                                        $time=date_create($row->punch_time);
                                        echo date_format($time," h:i A");
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                       echo time_diff_autologout($row->punch_time);
                                    ?>                         
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
<br><br>

         <div class='card-body'>
            <div class='table-responsive'>
                 <h4 style="color:green;"><b>Coffee Production Employee</b></h4> <br>
                <table class='table'>
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Work Duration Today</th>
                        <th>PunchTime</th>
                        <th>Time Passed</th>
                    </thead>

                    <tbody>
                        <?php $counter = 0;?>
                        <?php foreach($activeUsers as $key => $row) :?>
                        

                        <?php if(@$row->user->department == "factory worker"): ?>
                            <tr>
                                <td><?php echo ++$counter?></td>
                                <td><?php echo $row->user->firstname . ' ' .$row->user->lastname?></td>
                                <td>
                                    <?php
                                        // if time in
                                        $total_worktime_minutes = time_diff_minutes($row->punch_time) +  $row->user->workHoursToday;

                                        $hours =  floor($total_worktime_minutes / 60);
                                        $minutes =  floor($total_worktime_minutes % 60);
                                        echo $hours.'hours '.$minutes.'minutes';
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                        $date=date_create($row->punch_time);
                                        echo date_format($date,"M d, Y");
                                        $time=date_create($row->punch_time);
                                        echo date_format($time," h:i A");
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                       echo time_diff_autologout($row->punch_time);
                                    ?>                         
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endbuild()?>

<?php loadTo('tmp/layout')?>