<?php build('content') ?>
    <div class="container-fluid">
        <?php 
            echo wControlButtonLeft('File Viewer', [
                $navigationHelper->setNav('', 'Back', _route('user:show', $userId)),
                $navigationHelper->setNav('', 'Delete', _route('attachment:delete', [
                    'id' => $attachmentId,
                    'userId' => $userId
                ]), [
                    'icon' => 'fa fa-trash',
                    'attributes' => [
                        'class' => 'form-verify'
                    ]
                ])
            ]);
        ?>
        <div class="card">
            <div class="card-body">
                <iframe src="<?php echo $file?>" frameborder="0" 
                style="overflow:hidden;overflow-x:hidden;overflow-y:hidden;height:90vh;width:100%;" 
                height="100%" width="100%"></iframe>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>