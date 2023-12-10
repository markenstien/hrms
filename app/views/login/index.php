<?php build('content') ?>

    <div style="height:75px"></div>
    <div class="container">
        <div class="col-md-5 mx-auto">
            <h3 class="text-center"> Bits and Bytes </h3>
            <?php Flash::show()?>
            <hr>
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

            <p class="text-info">You can see your credentials below your name on the <strong>dashboard page</strong> </p>
            <p class="text-warning">
                <strong>Tips: </strong> Save your credentials on your browser 
            </p>           
            <div class="form-group">
                <?php
                    Form::submit('' , 'Login' , [
                        'class' => 'btn btn-primary'
                    ]);
                ?>
            </div>

            
            <?php Form::close();?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/public_layout')?>