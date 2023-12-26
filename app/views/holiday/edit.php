<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonLeft('Holiday Management', [
            $navigationHelper->setNav('', 'Back', _route('holiday:index'))
        ])?>
        <div class="col-md-6 mx-auto">
            <div class="card">
                <?php echo wCardHeader(wCardTitle('Holiday Form'))?>
                <div class="card-body">
                    <?php Flash::show()?>
                    <?php echo $form->start()?>
                        <?php echo $form->get('id')?>
                        <div class="form-group">
                            <?php echo $form->getCol('holiday_name')?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->getCol('holiday_name_abbr')?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->getCol('holiday_date')?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->getCol('holiday_work_type')?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->getCol('holiday_pay_type')?>
                        </div>

                        <div class="form-group">
                            <?php echo Form::submit('', 'Update Holiday')?>
                        </div>
                    <?php echo $form->end()?>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>

<?php loadTo('tmp/admin_layout')?>