<?php build('content') ?>
    <div class="container">
        <div class="col-md-5">
            <h4>Connect to : Pera-E</h4>
            <a href="/bank/create"> Return </a>
            <?php Flash::show()?>
            <?php
                Form::open([
                    'method' => 'post',
                    'action' => '/Bank/update'
                ]);

                Form::hidden('pera_id' , $pera->id);
            ?>

            <div class="form-group">
                <?php
                    Form::label('Key');
                    Form::text('apiKey' , $pera->api_key , [
                        'class' => 'form-control',
                        'required' => ''
                    ]);
                ?>
            </div>

            <div class="form-group">
                <?php
                    Form::label('Secret');
                    Form::text('apiSecret' , $pera->api_secret , [
                        'class' => 'form-control',
                        'required' => ''
                    ]);
                ?>
            </div>

            <?php
                Form::submit('save', 'Save Changes' , [
                    'class' => 'btn btn-primary btn-sm'
                ]);

                Form::submit('delete', 'Delete' , [
                    'class' => 'btn btn-danger btn-sm'
                ]);
            ?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/layout')?>