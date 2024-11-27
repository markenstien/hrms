<?php build('content') ?>
    <div style="height:75px"></div>
        <div class="container">
            <div class="col-md-5 mx-auto mb-5">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="<?php echo _path_upload_get('logo_circle.png')?>" alt="bit-and-bytes logo"
                                style="width:150px">
                            <h4>Welcome back!</h4>
                        </div>

                        <?php Flash::show() ?>
                        <?php
                            Form::open([
                                'method' => 'post',
                                'action' => '/Login/punchLogin'
                            ]); 
                        ?>
                            <div class="form-group">
                                <?php
                                    Form::label('Email');
                                    Form::text('email' , '' , [
                                        'class' => 'form-control',
                                        'required' => '',
                                    ]);
                                ?>
                            </div>

                            <div class="form-group">
                                <?php
                                    Form::label('Password');
                                    Form::password('password' , '' , [
                                        'class' => 'form-control',
                                        'required' => '',
                                        'placeholder' => ''
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

                <?php echo wDivider(50)?>

                <div style="background-color: #fff;">
                    <div style="display: none;">
                        DEMO SYSTEM BY <span class="highlight">
                            <a href="https://chromaticsoftwares.com"><?php echo COMPANY_NAME?></a></span> 
                        Discover our 
                    </div>
                    <p class="text-center" style="padding: 30px;">
                        <span class="highlight">
                            <span style="display: none;"><?php echo APP_NAME?></span> 
                            Timekeeping And Payoll Management Software
                        </span>
                    </p>
                </div>
            </div>
        </div>

    <div id="stetch" style="height:300px;"></div>
<?php endbuild()?>

<?php build('scripts') ?>
    <script>
        setInterval(function(){
            window.location.reload(true); 
        }, (1000 * 3600) * 4);
    </script>
<?php endbuild()?>


<?php build('styles')?>
<style>
    span.highlight{
        background-color: #2f387b;
        padding-left: 2px;
        padding-right: 2px;
    }
    span.highlight a,
    span.highlight {
        color: #fff;
    }
</style>
<?php endbuild()?>
<?php loadTo('tmp/public_layout')?>