<?php build('content') ?>
<div class="container-fluid">
    <?php
        echo wControlButtonLeft('Attachment Management', [
            $navigationHelper->setNav('', 'Back', _route('user:show', $userId))
        ])
    ?>
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit Attachment</div>
            </div>
            <div class="card-body">
                <?php echo $_attachmentForm->start([
                    'url' => _route('attachment:edit', $id)
                ])?>
                    <?php echo Form::hidden('id', $id)?>
                    <?php echo Form::hidden('user_id', $userId)?>
                    <div class="form-group">
                        <?php echo $_attachmentForm->getCol('display_name')?>
                    </div>

                    <div class="form-group">
                        <?php Form::submit('', 'Update Attachment')?>
                    </div>
                <?php echo $_attachmentForm->end()?>
            </div>
        </div>
    </div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>