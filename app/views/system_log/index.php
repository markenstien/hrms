<?php build('content') ?>
<div class="col-md-10 mx-auto text-center">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">System Logs</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered dataTable">
                    <thead>
                        <th>#</th>
                        <th>Type</th>
                        <th>User</th>
                        <th>Changes</th>
                        <th>Category</th>
                        <th>Log Time</th>
                    </thead>

                    <tbody>
                        <?php foreach($logs as $key => $row) :?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td>
                                    <?php if(isEqual($row->log_type, 'error')) :?>
                                        <span class="badge badge-danger">ERROR</span>
                                    <?php else:?>
                                        <span class="badge badge-primary">INFO</span>
                                    <?php endif?>
                                </td>
                                <td><?php echo $row->updated_by_name?></td>
                                <td><?php echo $row->log_text?></td>
                                <td><span class="badge badge-primary"><?php echo $row->log_category?></span></td>
                                <td><?php echo $row->created_at?></td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endbuild()?>

<?php build('headers')?>
 <style>
    table{
        border-collapse: collapse;
        width: 100%;
    }
    table td{
        text-align: left;
        border: 1px solid #000;
    }
 </style>
<?php endbuild()?>
<?php loadTo()?>