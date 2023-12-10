<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" 
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" 
        crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo _path_public('css/main/lumen.css')?>">
    <link rel="stylesheet" href="<?php echo _path_public('css/main/dataTable.css')?>">
    <script src="<?php echo _path_base('js/jquery.js')?>"></script>
    <script src="<?php echo _path_base('js/core.js')?>"></script>
    <script src="<?php echo _path_base('js/global.js')?>"></script>
    <script src="<?php echo _path_base('js/dataTable.js')?>"></script>
    <?php produce('headers')?>
</head>
<body>
    <?php produce('content')?>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" 
    integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script>
       $('.dataTable').DataTable();
    </script>
    <?php produce('scripts')?>
</body>
</html>