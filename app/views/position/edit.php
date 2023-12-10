<?php build('content') ?>
    <?php echo wControlButtonLeft($pageMainTitle, [
        $navigationHelper->setNav('menu', 'Back', _route('position:index'))
    ])?>

    <div class="col-md-6 mx-auto">
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Add New Position'))?>
            <div class="card-body">
                <?php echo $form->start()?>
                <?php echo $form->get('id')?>
                <div class="form-group">
                    <?php echo $form->getCol('position_name');?>
                </div>

                <div class="form-group row">
                    <div class="col-md-6"><?php echo $form->getCol('min_rate');?></div>
                    <div class="col-md-6"><?php echo $form->getCol('max_rate');?></div>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Entry">
                </div>
                <?php echo $form->end()?>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>