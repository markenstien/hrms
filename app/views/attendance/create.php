<?php build('content') ?>
<div class="container-fluid">
    <?php echo wControlButtonLeft('Attendance', [
        $navigationHelper->setNav('', 'Back', _route('attendance:index'))
    ])?>
    <div class="col-md-6 mx-auto">
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Attendance Form')) ?>
            <div class="card-body">
                <?php Flash::show()?>
                <?php echo $form->start()?>
                <?php echo $form->get('user_id')?>
                <?php
                    if(isEqual(whoIs('type'), 'SUPER_ADMIN')) {
                        echo $form->getCol('uid');
                    }
                ?>

                
                <div class="form-group"><?php echo $form->getCol('entry_type')?></div>
                <div class="form-group row">
                    <div class="col-md-6"><?php echo $form->getCol('start_date')?></div>
                    <div class="col-md-6"><?php echo $form->getCol('time_in')?></div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6"><?php echo $form->getCol('end_date')?></div>
                    <div class="col-md-6"><?php echo $form->getCol('time_out')?></div>
                </div>
                <div class="form-group"><?php echo $form->getCol('reason')?></div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Attendance">
                </div>
                <?php echo $form->end()?>
            </div>
        </div>
    </div>
</div>
<?php endbuild()?>

<?php loadTo('tmp/admin_layout')?>