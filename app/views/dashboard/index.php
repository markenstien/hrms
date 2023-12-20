<?php build('content')?>
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="row">
      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 rounded-0 border-4">
          <div class="card-body py-1">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-lg font-weight-bold mb-1">Departments</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800 text-right"><?php echo $summary['totalDepartment']?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-building fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-4">
        <div class="card border-left-info shadow h-100 py-2 rounded-0 border-4">
          <div class="card-body py-1">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-lg font-weight-bold mb-1">Shifts</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800 text-right"><?php echo $summary['totalShifts']?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-4">
        <div class="card border-left-success shadow h-100 py-2 rounded-0 border-4">
          <div class="card-body py-1">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-lg font-weight-bold mb-1">Employees</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800 text-right"><?php echo $summary['totalEmployee']?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-id-badge fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Requests Card Example -->
      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-4">
        <div class="card border-left-danger shadow h-100 py-2 rounded-0 border-4">
          <div class="card-body py-1">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-lg font-weight-bold mb-1">Users</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800 text-right"><?php echo $summary['totalUsers']?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>