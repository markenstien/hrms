<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonLeft('Leave Management', [
            $navigationHelper->setNav('', 'Back', _route('leave-point:index'))
        ])?>
        <div class="col-md-6 mx-auto">
            <div class="card">
                <?php echo wCardHeader(wCardTitle('Leave Credit Management'))?>
                <div class="card-body">
                    <?php echo $form->start()?>
                        <?php echo $form->getCol('leave_point_category')?>
                        <?php echo $form->getCol('uid')?>
                        <div class="row">
                            <div class="col">
                                <?php echo $form->getCol('point')?>
                            </div>
                            <div class="col">
                                <?php echo $form->getCol('point_type')?>
                            </div>
                        </div>
                        <?php echo $form->getCol('remarks')?>

                        <div class="mt-2"><?php Form::submit('', 'Save Point Entry')?></div>
                    <?php echo $form->end()?>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>