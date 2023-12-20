<?php build('content') ?>
    <div class="container-fluid">
        <?php
            echo wControlButtonLeft('Interview Result', [
                $navigationHelper->setNav('', 'Back to candidate', _route('recruitment:show', $candidateId))
            ])
        ?>
        <div class="col-md-6 mx-auto">
            <div class="card">
                <?php echo wCardHeader(wCardTitle($req['title']))?>
                <div class="card-body">
                    <?php echo $form->start()?>
                        <?php echo $form->get('recruitment_id')?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo $form->getCol('interview_title')?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo $form->getCol('interview_number')?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo $form->getCol('interviewer_name')?>
                        </div>
                        <div class="form-group">
                            <?php echo $form->getCol('remarks')?>
                        </div>
                        <div class="form-group">
                            <?php echo $form->getCol('result')?>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Submit Interview Result">
                        </div>
                    <?php echo $form->end()?>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>