<!DOCTYPE html>
<html lang="en">

<head>

  <title><?php echo $pageTitle?? 'Korpee | Optimizing work productivity'?></title>
  <meta content="<?php echo HEADING_META['keywords']?>" name="keywords">
  <meta content="<?php echo HEADING_META['description']?>" name="description">
  <meta content="width=device-width" name="viewport" type="image/x-icon">
  <meta content="website" property="og:type">
  <meta content="<?php echo HEADING_META['og:url']?>" property="og:url">
  <meta content="<?php echo HEADING_META['og:title']?>" property="og:title">
  <meta content="<?php echo HEADING_META['og:description']?>" property="og:description">
  <meta content="<?php echo HEADING_META['og:image']?>" property="og:image">

  <link rel="icon" href="<?php echo HEADING_META['favicon']?>">
  <!-- Custom fonts for this template-->
  <link href="<?php echo _path_public('ui/ui-main/vendor/fontawesome-free/css/all.min.css')?>" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?php echo _path_public('ui/ui-main/css/sb-admin-2.min.css')?>" rel="stylesheet">
  <link href="<?php echo _path_public('ui/ui-main/css/custom.css')?>" rel="stylesheet">

  <?php produce('headers') ?>
  <?php produce('styles') ?>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
        <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <?php produce('content') ?>
      </div>
      <!-- End of Content Wrapper -->
      </div>
      <!-- End of Page Wrapper -->
      <!-- Scroll to Top Button-->
      <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
      </a>
      <!-- Bootstrap core JavaScript-->
      <script src="<?php echo _path_public('ui/ui-main/vendor/jquery/jquery.min.js')?>"></script>
      <script src="<?php echo _path_public('ui/ui-main/vendor/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
      <!-- Core plugin JavaScript-->
      <script src="<?php echo _path_public('ui/ui-main/vendor/jquery-easing/jquery.easing.min.js')?>"></script>

      <!-- Custom scripts for all pages-->
      <script src="<?php echo _path_public('ui/ui-main/js/sb-admin-2.min.js')?>"></script>

       <!-- Page level plugins -->
      <script src="<?php echo _path_public('ui/ui-main/vendor/datatables/jquery.dataTables.min.js')?>"></script>
      <script src="<?php echo _path_public('ui/ui-main/vendor/datatables/dataTables.bootstrap4.min.js')?>"></script>
      <script src="<?php echo _path_public('ui/ui-main/js/demo/datatables-demo.js')?>"></script>
      <?php produce('scripts') ?>
      </body>
</html>