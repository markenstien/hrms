<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonLeft('User Management', [
            $navigationHelper->setnav('', 'Back', _route('user:index'))
        ])?>
        <div class="col-md-6 mx-auto">
            <div class="card">
                <?php echo wCardHeader(wCardTitle('Add New Employee'))?>
                <div class="card-body">
                    <?php Flash::show()?>
                    <?php echo $form->start()?>
                    <div class="form-group">
                        <?php echo $form->getCol('profile')?>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6"><?php echo $form->getCol('firstname')?></div>
                        <div class="col-md-6"><?php echo $form->getCol('lastname')?></div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6"><?php echo $form->getCol('birthdate')?></div>
                        <div class="col-md-6"><?php echo $form->getCol('gender')?></div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6"><?php echo $form->getCol('email')?></div>
                        <div class="col-md-6"><?php echo $form->getCol('mobile_number')?></div>
                    </div>

                    <div class="form-group">
                        <?php echo $form->getCol('address')?>
                    </div>
                    <hr/>

                    <div class="form-group row">
                        <div class="col-md-4"><?php echo $form->getCol('hire_date')?></div>
                        <div class="col-md-4"><?php echo $form->getCol('department_id')?></div>
                        <div class="col-md-4"><?php echo $form->getCol('shift_id')?></div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6"><?php echo $form->getCol('position_id')?></div>
                        <div class="col-md-6"><?php echo $form->getCol('computation_type')?></div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-4"><?php echo $form->getCol('salary_per_month')?></div>
                        <div class="col-md-4"><?php echo $form->getCol('salary_per_day')?></div>
                        <div class="col-md-4"><?php echo $form->getCol('salary_per_hour')?></div>
                    </div>

                    <div class="form-group"><?php echo $form->getCol('sss_number')?></div>
                    <div class="form-group"><?php echo $form->getCol('pagibig_number')?></div>
                    <div class="form-group"><?php echo $form->getCol('phil_health_number')?></div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-sm" value="Save Employee">
                    </div>
                    <?php echo $form->end()?>
                </div>
            </div>
        </div>
    </div>

<?php endbuild()?>

<?php loadTo('tmp/admin_layout')?>
