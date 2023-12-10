<?php build('content') ?>

    <div class="card">
        <div class='card-header'>
            <h4 class='card-title'>Logged Users</h4>
        </div>

        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table'>
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>PunchTime</th>
                        <th>Time Passed</th>
                    </thead>

                    <tbody>
                        <?php foreach($activeUsers as $key => $row) :?>
                        

                        <?php if($row->user->branch_id == 2): ?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->user->firstname . ' ' .$row->user->lastname?></td>
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