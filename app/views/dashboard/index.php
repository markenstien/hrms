<?php build('content')?>
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <?php Flash::show()?>
        <h1>Underdevelopment</h1>
      </div>
    </div>
  </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>