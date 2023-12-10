<?php build('content') ?>

    <div class="table-responsive table-bordered">
        <table class="table table-bordered">
            <thead>
                <th>#</th>
                <th>User</th>
                <th>Rate</th>
                <th>Duration</th>
                <th>Amount</th>
                <th>Time In</th>
                <th>Time Out</th>
            </thead>


            <tbody>
                <?php foreach($logs as $key => $row) :?>
                    <tr>
                        <td><?php echo ++$key?></td>
                        <td><?php echo $row->fullname?></td>
                        <td><?php echo $row->rate_per_day?></td>
                        <td><?php echo minutesToHours($row->duration)?></td>
                        <td><?php echo $row->amount?></td>
                        <td><?php echo date('M d Y h:i:s a', strtotime($row->time_in))?></td>
                        <td><?php echo date('M d Y h:i:s a', strtotime($row->time_out))?></td>
                    </tr>
                <?php endforeach?>
            </tbody>
        </table>
    </div>
<?php endbuild()?>
<?php occupy()?>