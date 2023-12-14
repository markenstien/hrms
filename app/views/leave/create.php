<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonLeft('Leave Request', [
            $navigationHelper->setNav('', 'Back', _route('leave:index'))
        ])?>
        <div class="col-md-6 mx-auto">
            <div class="card">
                <?php echo wCardHeader(wCardTitle('Create Leave Request'))?>
                <div class="card-body">
                    <?php Flash::show()?>
                    <?php echo $form->start()?>
                        <?php echo $form->getCol('user_id', [
                            'value' => whoIs('id')
                        ])?>
                        <?php echo $form->getCol('leave_category')?>
                        <?php echo $form->getCol('date_filed')?>
                        <?php echo $form->getCol('start_date')?>
                        <?php echo $form->getCol('end_date')?>

                        <div class="mt-2"><?php Form::submit('', 'Save Leave Request')?></div>
                    <?php echo $form->end()?>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>