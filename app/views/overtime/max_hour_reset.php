<?php build('content') ?>
<div class="col-md-5 mx-auto">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?php echo $title?></h4>
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
                        Form::label('New max work hour');
                        Form::text('extra_time','' , [
                            'class' => 'form-control',
                            'required' => true,
                            'placeholder' => 'extra hours only eg. (2) is 2 hours'
                        ])
                    ?>
                </div>

                <div class="form-group">
                    <?php Form::submit('', 'Reset Max Work Hours')?>
                </div>
            <?php Form::close()?>
        </div>
    </div>
</div>
<?php endbuild()?>
<?php loadTo()?>