<?php build('content') ?>
    
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Manual Timesheet</h4>
                <a href="/DisputeController/">Disputes</a>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <?php
                        Form::label('Start Date');
                        Form::date('start_date' , '' , ['class' => 'form-control'])
                    ?>
                </div>

                <div class="form-group">
                    <?php
                        Form::label('End Date');
                        Form::date('end_date' , '' , ['class' => 'form-control'])
                    ?>
                </div>

                <div class="form-group">
                    <?php
                        Form::label('Start Time');
                        Form::time('start_time' , '' , ['class' => 'form-control'])
                    ?>
                </div>

                <div class="form-group">
                    <?php
                        Form::label('End Time');
                        Form::time('end_ttime' , '' , ['class' => 'form-control'])
                    ?>
                </div>

                <div class="form-group">
                    <?php
                        Form::label('Category');
                        Form::select('category' , $category , '', ['class' => 'form-control', 'required' => true])
                    ?>
                </div>

                <div class="form-group">
                    <?php
                        Form::label('User');
                        Form::select('user_id' , $users , '', ['class' => 'form-control', 'required' => true])
                    ?>
                </div>

                <div class="form-group">
                    <?php
                        Form::label('Reason');
                        Form::textarea('reason' , '', ['class' => 'form-control', 'required' => true]);
                        Form::small('Explain your reason for the dispute');
                    ?>
                </div>

                <div class="form-group">
                    <?php Form::submit('' , 'Save')?>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>