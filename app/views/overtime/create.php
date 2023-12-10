<?php build('content') ?>
<div class="col-md-5 mx-auto">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Create Overtime</h4>
            <a href="/OvertimeController/">Overtimes</a>
        </div>

        <div class="card-body">
            <?php
                Flash::show();

                Form::open([
                    'method' => 'post'
                ]);
            ?>
                <div class="form-group">
                    <?php
                        Form::label('Select Department');
                        Form::select('department_id', $branchSelect, '', [
                            'class' => 'form-control',
                            'required' => true
                        ]);
                    ?>
                </div>                  
                
                <div class="form-group">
                    <?php
                        Form::label('Extra Time');
                        Form::text('extra_time','' , [
                            'class' => 'form-control',
                            'required' => true,
                            'placeholder' => 'extra hours only eg. (2) is 2 hours'
                        ])
                    ?>
                </div>

                <div class="form-group">
                    <?php Form::submit('', 'Start OT')?>
                </div>
            <?php Form::close()?>
        </div>

        <div class="card-body">
            <h4>Logs</h4>
            <table class="table table-bordered">
                <thead>
                    <th>Log Type</th>
                    <th>Log Text</th>
                    <th>Log Date</th>
                </thead>

                <tbody>
                    <?php foreach($logs as $key => $row) :?>
                        <tr>
                            <td><span class="badge badge-info"><?php echo $row->log_type?></span></td>
                            <td><?php echo $row->log_text?></td>
                            <td><?php echo $row->created_at?></td>
                        </tr>
                    <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endbuild()?>
<?php loadTo()?>