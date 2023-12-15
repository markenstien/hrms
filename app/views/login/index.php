<?php build('content') ?>
    <div style="height:75px"></div>
        <div class="container">
            <div class="col-md-5 mx-auto mb-5">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="<?php echo _path_upload_get('bits.png')?>" alt="bit-and-bytes logo"
                                style="width:150px">
                            <h4>Welcome back!</h4>
                        </div>

                        <?php
                            Form::open([
                                'method' => 'post',
                                'action' => '/Login/punchLogin'
                            ]); 
                        ?>
                            <div class="form-group">
                                <?php
                                    Form::label('Username');
                                    Form::text('username' , '' , [
                                        'class' => 'form-control',
                                        'required' => ''
                                    ]);
                                ?>
                            </div>

                            <div class="form-group">
                                <?php
                                    Form::label('Password');
                                    Form::password('password' , '' , [
                                        'class' => 'form-control',
                                        'required' => ''
                                    ]);
                                ?>
                            </div>
                            
                            <div class="form-group">
                                <?php
                                    Form::submit('' , 'Login' , [
                                        'class' => 'btn btn-primary'
                                    ]);
                                ?>
                            </div>

                        <?php Form::close()?>
                    </div>
                    <?php if($showToken) :?>
                        <div class="card-footer">
                            <div class="text-center">
                                <img src="<?php echo base64_decode($token->src_url)?>" alt="">
                                <p class="#">Scan here for attendance</p>
                            </div>
                        </div>
                    <?php endif?>
                </div>
            </div>
        </div>
<?php endbuild()?>

<?php build('scripts') ?>
    <script>
        setInterval(function(){
            window.location.reload(true); 
        }, (1000 * 3600) * 4);
    </script>
<?php endbuild()?>


<?php loadTo('tmp/public_layout')?>