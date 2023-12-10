<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        Form::open([
            'method' => 'post',
            'action' => '/UploadTest/upload',
            'enctype' => 'multipart/form-data'
        ]);
    ?>

    <div class="form-group">
        <?php
            Form::file('images[]' , [
                'class' => 'form-control',
                'multiple' => ''
            ]);
        ?>
    </div>

    <?php
        Form::submit('' , '_Upload');
    ?>
    <?php Form::close()?>
</body>
</html>