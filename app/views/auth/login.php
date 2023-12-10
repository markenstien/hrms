<?php build('content')?>

    <div class="container">

        <div class="card">
            <div class="card-header">
                <h4>Automatic Login</h4>
            </div>

            <div class="card-body">
            <?php if( !empty( Cookie::get('auth') )) :?>
                <a href="/api/RememberMe/resetSession">Re login</a>
            <?php endif?>
            </div>
        </div>
    </div>
    
<?php endbuild()?>
<?php loadTo('tmp/layout')?>