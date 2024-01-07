<?php build('content') ?>
<div class="container-fluid">
    <div class="col-md-6">
        <div class="card">
            <?php echo wCardHeader(
                wCardTitle('Candidate to Employee')
            )?>
            <div class="card-body">
                <?php echo $userForm->start()?>
                    <div class="form-group">
                        <?php echo $userForm->getCol('firstname')?>
                    </div>
                <?php echo $userForm->end()?>
            </div>
        </div>
    </div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>