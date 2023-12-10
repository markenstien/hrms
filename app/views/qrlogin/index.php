<?php build('content') ?>

    <?php if(!$user) :?>
    <div class="col-sm-12 col-md-3 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Login page</h4>
                <?php Flash::show()?>
            </div>

            <div class="card-body">
                <?php
                    Form::open([
                        'method' => 'post',
                        'action' => '/QRLoginController/login'
                    ]);
                ?>

                <div class="form-group">
                    <?php
                        Form::label('Username');
                        Form::text('username' , '' , ['class' => 'form-control']);
                    ?>
                </div>

                <div class="form-group">
                    <?php
                        Form::label('Password');
                        Form::text('password' , '' , ['class' => 'form-control']);
                    ?>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-sm btn-success" value="Login">
                </div>
                <?php Form::close()?>
            </div>
        </div>
    </div>
    <?php endif?>
    
    <?php if($user) :?>
    <div class="col-sm-12 col-md-3 mx-auto mt-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Account</h4>
                <?php Flash::show()?>
            </div>

            <div class="card-body">
                <h1><?php echo $user->firstname . ' ' . $user->lastname?></h1>
                    <a href="/QRLoginController/logoutAccount">Logout Account</a>
                <hr>

                <?php
                    Form::open([
                        'method' => 'post',
                        'action' => '/QRLoginController/logTime'
                    ]);
                ?>
                <?php if(isEqual($logType,$timelogMeta::$CLOCKED_IN)) :?>
                    <h3>Clock-In Time : <input type="text" id="clockInTime" 
                        value="<?php echo $logLast->clock_in_time?>"
                        readonly></h3>
                    <h3>Work Duration : <span id="duration"></span></h3>
                    <hr>
                    <div class="form-group">
                        <?php Form::submit('btn_log' , 'Clock Out' , ['class' => 'btn btn-danger'])?>
                    </div>
                <?php else:?>
                    <p>Currently Logged Out</p>
                    <hr>
                    <div class="form-group">
                        <?php Form::submit('btn_log' , 'Clock In' , ['class' => 'btn btn-primary'])?>
                    </div>
                <?php endif?>
                <?php Form::close()?>
            </div>
        </div>
    </div>
    <?php endif?>
<?php endbuild()?>

<?php build('scripts')?>
    <script>
        $(document).ready(function(){
            $("#duration").html(
                dateDifference(new Date(),new Date($("#clockInTime").val()))
            );
        })
    </script>
<?php endbuild()?>
<?php loadTo('tmp/public')?>