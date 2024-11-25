<?php build('content') ?>
<div class="container-fluid">
    <?php echo wControlButtonLeft('Attendance', [
        $navigationHelper->setNav('', 'Back', _route('attendance:index'))
    ])?>
    <div class="col-md-6 mx-auto">
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Timesheet Import')) ?>
            <div class="card-body">
                <?php Flash::show()?>
                <form action="" enctype="multipart/form-data" method="post">
                    <?php echo $form->get('user_id')?>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="file" name="timesheet_file">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-primary" value="Import File">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endbuild()?>

<?php loadTo('tmp/admin_layout')?>