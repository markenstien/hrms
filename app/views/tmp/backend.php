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

    <div class="wrapper">

        <?php if( !empty( $whoIs = whoIs()) ):?>
            <?php if(isEqual($whoIs['type'] , 'admin')) :?>
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <a class="navbar-brand" href="/dashboard">Timekeeping</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarColor02">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="/user/create">User
                                <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="/loggedUsers">Logged Users
                                <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" 
                                    role="button" aria-haspopup="true" aria-expanded="false">Timesheets</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/TimesheetContainer/cancelled_timeSheet">Cancelled</a>
                                    <a class="dropdown-item" href="/TimesheetContainer/approved">Approved</a>
                                    <a class="dropdown-item" href="/TimesheetContainer/weekly">Weekly Approved</a>
                                    <a class="dropdown-item" href="/TimesheetContainer/monthly">Monthly Approved</a>
                                    <a class="dropdown-item" href="/TimesheetContainer/yearly">Yearly Approved</a>
                                    <a class="dropdown-item" href="/TimesheetContainer/logs">Logs</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" 
                                    role="button" aria-haspopup="true" aria-expanded="false">Others</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/LoginDevice">Device Login</a>
                                    <a class="dropdown-item" href="/AutomaticLogoutSetting">Auto logout settings</a>
                                    <a class="dropdown-item" href="/branch">Branches</a>
                                    <a class="dropdown-item" href="/DisputeController">Disputes</a>
                                    <a class="dropdown-item" href="/OvertimeController">Overtimes</a>
                                    <a class="dropdown-item" href="/OvertimeController/maxWorkHourReset">Reset Max Hours</a>
                                    <a class="dropdown-item" href="/User/department_timekeeper">Department Timekeepers</a>
                                    <a class="dropdown-item" href="/SystemLogController">System Logs</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" 
                                    role="button" aria-haspopup="true" aria-expanded="false">Payroll</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/PayrollController/create">Payroll</a>
                                    <a class="dropdown-item" href="/DeductionController/create">Deductions</a>
                                    <a class="dropdown-item" href="/DeductionController/deductions">Users Deductions</a>
                                </div>
                            </li>

                            <li class="nav-item active">
                                <a class="nav-link" href="/logout">Logout</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            <?php endif?>

            <?php if(isEqual($whoIs['type'], 'staff') && (isEqual($whoIs['branch_id'], USER_TYPE_TIMEKEEPER_ID) || $whoIs['is_branch_timekeeper']) == false) : ?>
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <a class="navbar-brand" href="/dashboard">Timekeeping</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarColor02">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="/logout">Logout</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            <?php endif;?>
        <?php endif;?> 

        <main> 
            <?php produce('content') ?>
        </main>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" 
    integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('.dataTable').DataTable({
                pageLength : 100
            });
        })
    </script>
    <?php produce('scripts')?>
</body>
</html>