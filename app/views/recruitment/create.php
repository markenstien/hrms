<?php build('content') ?>
<div class="contaienr-fluid">
    <?php echo wControlButtonLeft('Recruitment Management', [
        $navigationHelper->setNav('', 'Back', _route('recruitment:index'))
    ])?>
    <div class="col-md-6 mx-auto">
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Create New Candidate'))?>
            <div class="card-body">
                <!-- form here -->
                <?php echo $form->start()?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo $form->getCol('firstname')?>
                        </div>
                    </div>

                    <div class="col-md-6">
                    <div class="form-group">
                            <?php echo $form->getCol('lastname')?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo $form->getCol('email')?>
                        </div>
                    </div>

                    <div class="col-md-6">
                    <div class="form-group">
                            <?php echo $form->getCol('mobile_number')?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo $form->getCol('position_id')?>
                        </div>
                    </div>

                    <div class="col-md-6">
                    <div class="form-group">
                            <?php echo $form->getCol('expected_salary')?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo $form->getCol('address')?>
                </div>

                <div class="form-group">
                    <?php echo $form->getCol('remarks')?>
                </div>

                <div class="form-group">
                    <?php echo $form->getCol('result')?>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit Candidate">
                </div>
                <?php echo $form->end()?>
            </div>
        </div>
    </div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>