<?php build('content') ?>
    <div class="container">
        <div class="text-center">
            <ul class="list-unstyled list-inline">
                <li class="list-inline-item"><a href="?view=clocked_in">Clock In</a></li>
                <li class="list-inline-item"><a href="?view=clocked_out">Clock Out</a></li>
            </ul>
            <h1><?php echo $currentPageTxt?>(<?php echo count($loggedUsers)?>)</h1>
            <?php Flash::show()?>
        </div>

        <ul class="list-unstyled list-inline">
            <li class="list-inline-item"><a href="?view=<?php echo $viewType?>&branch_type=all">
                <span class="badge badge-primary">All</span>
            </a></li>
            <li class="list-inline-item"><a href="?view=<?php echo $viewType?>&branch_type=by_department">
                <span class="badge badge-primary">By Department</span>
            </a></li>

            <?php foreach($branches as $key => $row) :?>
                <li class="list-inline-item"><a href="?view=<?php echo $viewType?>&branch_type=single_department&branch_id=<?php echo $key?>"><?php echo $row?></a></li>
            <?php endforeach?>
        </ul>

        <?php if(isEqual($displayType, 'all')) :?>
            <?php include_once VIEWS.DS.'logged_users/table_types/all.php'?>
        <?php endif?>

        <?php if(isEqual($displayType, ['by_department','single_department']) && !empty($groupedByBranch)) :?>
            <?php foreach($groupedByBranch as $groupIndex => $groupVal) :?>
                <?php $groupName = $groupVal['name']?>
                <div style="border: 1px solid #000; margin-bottom:15px; box-sizing:border-box;padding:10px">
                    <h4><?php echo $groupName?></h4>

                    <?php if(isEqual($viewType,'clocked_in')) :?>
                        <h5><?php echo wLinkDefault('/LoggedUsers/logoutByDepartment/'.$groupIndex, 'Logout All')?></h5>
                    <?php endif?>

                    <?php if(isEqual($viewType, 'clocked_out')) :?>
                        <table class="table table-bordered dataTable">
                            <thead>
                                <th style="width: 5%">#</th>
                                <th style="width: 40%;">Name</th>
                                <th style="width: 10%;">Action</th>
                            </thead>
                            <?php foreach($groupVal['users'] as $key => $row) :?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->fullname?></td>
                                    <td>
                                        <a href="/TimelogMetaController/log/<?php echo $row->id?>" class="btn btn-primary">
                                            <?php echo $actionTxt?>         
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach?>
                        </table>
                    <?php endif?>

                     <?php if(isEqual($viewType, 'clocked_in')) :?>
                        <table class="table table-bordered dataTable">
                            <thead>
                                <th style="width: 5%;">#</th>
                                <th style="width: 15%;">Name</th>
                                <th style="width: 15%;">Clock in Time</th>
                                <th style="width: 15%; background: green; color:#fff">Ongoig WH</th>
                                <th><span style="Total Worked Hours">Total WH</span></th>
                                <th><span style="Remaining Work Hours">Remaining WH</span></th>
                                <th style="width: 15%;">Action</th>
                            </thead>
                            <?php foreach($groupVal['users'] as $key => $row) :?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->fullname?></td>
                                    <td><?php echo $row->clock_in_time?></td>
                                    <td><?php  echo minutesToHours(timeDifferenceInMinutes($row->clock_in_time , $timeToday)) ?></td>
                                    <td><?php  echo minutesToHours($row->total_duration) ?></td>
                                    <td><?php  echo minutesToHours(hoursToMinutes($row->max_work_hours) - $row->total_duration) ?></td>
                                    <td>
                                        <a href="/TimelogMetaController/log/<?php echo $row->user_id?>" class="btn btn-primary">
                                            <?php echo $actionTxt?>         
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach?>
                        </table>
                    <?php endif?>
                </div>
            <?php endforeach?>

        <?php else:?>
            <p>No Data Found.</p>
        <?php endif?>
    </div>
<?php endbuild()?>
<?php loadTo()?>