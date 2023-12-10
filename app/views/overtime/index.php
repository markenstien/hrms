<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Overtime</h4>
            <a href="/OvertimeController/create">Create Overtime</a>
        </div> 

        <div class="card-body">
            <?php Flash::show()?>
            <div class="table-resposive">
                <table class="table table-bordered">
                    <thead>
                        <th>#</th>
                        <th>Department</th>
                        <th>OT Hours</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>Date Completed</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach($overtimes as $key => $row) :?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->branch?></td>
                                <td><?php echo $row->extra_time?></td>
                                <td><?php echo $row->status?></td>
                                <td>
                                    <?php
                                        if(is_null($row->start_date_time)) {
                                            echo 'NOT AVAILABLE ON PREVIOUS VERSION';
                                        } else {
                                            echo date('Y-m-d h:i:s A', strtotime($row->start_date_time));
                                        }
                                    ?>                                
                                </td>
                                <td>
                                    <?php
                                        if(is_null($row->end_date_time)) {
                                            echo 'NOT AVAILABLE ON PREVIOUS VERSION';
                                        } else {
                                            echo date('Y-m-d h:i:s A', strtotime($row->end_date_time));
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="/OvertimeController/revert/<?php echo $row->id?>" class="btn btn-primary btn-sm">Complete</a>
                                    |
                                    <a href="/OvertimeController/delete/<?php echo $row->id?>" class="btn btn-primary btn-sm form-verify">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php occupy()?>