<?php build('content') ?>
<div class="container-fluid">
    <div class="col-md-6">
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Edit Leave Request')) ?>
            <div class="card-body">
                <?php echo wLinkDefault(_route('leave:index'), 'Back to lists', [
                    'icon' => 'arrow-left-circle'
                ]); ?>
                    <?php
                        echo $form->start();
                        echo $form->getCol('id');
                        echo $form->getCol('user_id');
                        echo $form->getCol('leave_category');
                        echo $form->getCol('date_filed');
                        echo $form->getCol('start_date');
                        echo $form->getCol('end_date');
                    ?>
                        <div class="mt-2">
                            <?php Form::submit('', 'Save Leave Request')?>

                            <?php echo wLinkDefault(_route('leave:delete', $leave->id,[
                                'route' => seal(_route('leave:index'))
                            ]), 'Delete', [
                                'icon' =>  'trash-circle',
                                'class' => 'btn btn-danger form-verify'
                            ])?>
                        </div>
                    <?php echo $form->end()?>
            </div>
        </div>
    </div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>