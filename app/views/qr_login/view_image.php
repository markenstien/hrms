<?php build('content') ?>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <?php if($lastestLoginImage) :?>
                    <h4>Scan To Create Your attendance</h4>
                    <img src="<?php echo base64_decode($lastestLoginImage->src_url)?>" alt="">
                <?php endif?>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>