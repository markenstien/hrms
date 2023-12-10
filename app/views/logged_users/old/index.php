<?php build('content') ?>


    <div class="card">

        <div class='card-header'>

            <h4 class='card-title'>Logged Users</h4>
            <?php Flash::show()?>
        </div>

        <div class='card-header'>
        </div>

        <?php
            date_default_timezone_set("Asia/Manila");
            $date_today = date("Y-m-d");

            $date_search1 = $activeUsers[0]->date_search;

        ?>

        <div class='card-body'>

            <form action="/LoggedUsers/index" method="post" required>
              <input type="date"  name="date_search" value="<?php echo $date_search1 ?>">
              <input type="submit">
            </form>
            <br>
            <h2><b><?php echo $date_today." to ".$date_search1; ?></b></h2>
            <br><br>

            <div class='table-responsive'>
                <h4 style="color:green;"><b>Rise Coffee Employee</b></h4> <br>
                <form action="/User/update_work_time" method="post" required>
                  <b>Enter Work Time Limitation</b>
                  <input type="text"  name="work_hour">
                  <input type="hidden" name="branch_id" value="1">
                  <input type="hidden" name="department" value="factory worker">
                  <input type="submit">
                </form>
                 <form action="/User/rollback_work_time" method="post" required>
                  <input type="hidden" name="branch_id" value="1">
                  <input type="hidden" name="department" value="factory worker">
                  <input type="submit" value="Reset">
                </form>
                <table class='table'>

                    <thead>

                        <th>#</th>
                        <th>Status</th>
                        <th>Fullname</th>
                        <th>Rates</th>
                        <th>Work Duration</th>
                        <th>Salary</th>
                        <th>PunchTime</th>
                        <th>Action</th>
                        <th></th>
                        <th></th>

                    </thead>


                    <?php $count = 0;?>
                    <tbody>
                        <?php foreach($activeUsers as $key => $row) :?>

   
                        <?php if($row->user->branch_id == 1 && $row->user->department == "factory worker" && $row->clock_info->type == "time_in"): ?>     
                            <tr>
                                <td><?php echo ++$count?></td>
                                 <td>
                                    <span class="badge badge-success"> Time In </span>
                                </td>
                                <td><?php echo $row->user->firstname . ' ' .$row->user->lastname?></td>
                                <td>
                                    <ul>
                                        <li>/Hour: <?php echo $row->user->userMeta->rate_per_hour?></li>
                                        <li>/Day: <?php echo $row->user->userMeta->rate_per_day?></li>
                                    </ul>
                                      <ul>
                                        <li>Regular : <?php echo $row->user->userMeta->work_hours?></li>
                                        <li>Max : <?php echo $row->user->userMeta->max_work_hours?></li>
                                    </ul>
                                </td>
                                <td>
                                    <?php
                                        

                                        // if time in
                                        $total_worktime_minutes = time_diff_minutes($row->clock_info->punch_time) +  $row->user->workHoursToday;
                                        // if time out
                                        if($row->clock_info->type == "time_out"){
                                            $total_worktime_minutes =  $row->user->workHoursToday;
                                        }

                                        if($date_today != $row->date_search)
                                        {
                                            $total_worktime_minutes = $row->total_time;
                                        }

                                        $hours =  floor($total_worktime_minutes / 60);
                                        $minutes =  floor($total_worktime_minutes % 60);

                                        echo $hours.'hours '.$minutes.'minutes';
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                         $salary_today = ($row->user->userMeta->rate_per_hour/60) * $total_worktime_minutes;
                                       
                                        if($date_today != $row->date_search){
                                           $salary_today = $row->total_salary;
                                        }

                                        echo '&#8369; '.number_format($salary_today,2);
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                        $date=date_create($row->clock_info->punch_time);
                                        echo date_format($date,"M d, Y");
                                        $time=date_create($row->clock_info->punch_time);
                                        echo date_format($time," h:i A");
                                    ?>                         
                                </td>

                                <td>
                                    <a href="api/Authentication/index_web/?deviceType=rfid&loginKey=<?php echo $row->user->deviceLogin->login_key?>" class='btn btn-sm btn-danger' id="logout">Logout</a>
                                 
                                </td>
                                <td>
                                    <a href="/Timesheet/search/<?php echo $row->id?>" target="_blank">View TimeSheet</a>
                                </td>
                                 <td>
                                    <a href="/user/edit/<?php echo $row->id?>" target="_blank">Edit</a>
                                </td>
                            </tr>

                        <?php endif;?>
                        <?php endforeach?>

                        <!-- TIME OUT --->
                        <?php foreach($activeUsers as $key => $row) :?>

                        <?php if($row->user->branch_id == 1 && $row->user->department == "factory worker" && $row->clock_info->type == "time_out"): ?>     
                            <tr>
                                <td><?php echo ++$count?></td>
                                  <td>
                                    <span class="badge badge-danger"> Time Out </span>
                   
                                </td>
                                <td><?php echo $row->user->firstname . ' ' .$row->user->lastname?></td>
                                <td>
                                    <ul>
                                        <li>/Hour: <?php echo $row->user->userMeta->rate_per_hour?></li>
                                        <li>/Day: <?php echo $row->user->userMeta->rate_per_day?></li>
                                    </ul>
                                      <ul>
                                        <li>Regular : <?php echo $row->user->userMeta->work_hours?></li>
                                        <li>Max : <?php echo $row->user->userMeta->max_work_hours?></li>
                                    </ul>
                                </td>
                                <td>
                                    <?php
                                        

                                        // if time in
                                        $total_worktime_minutes = time_diff_minutes($row->clock_info->punch_time) +  $row->user->workHoursToday;
                                        // if time out
                                        if($row->clock_info->type == "time_out"){
                                            $total_worktime_minutes =  $row->user->workHoursToday;
                                        }

                                        if($date_today != $row->date_search)
                                        {
                                            $total_worktime_minutes = $row->total_time;
                                        }

                                        $hours =  floor($total_worktime_minutes / 60);
                                        $minutes =  floor($total_worktime_minutes % 60);

                                        echo $hours.'hours '.$minutes.'minutes';
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                         $salary_today = ($row->user->userMeta->rate_per_hour/60) * $total_worktime_minutes;
                                       
                                        if($date_today != $row->date_search){
                                           $salary_today = $row->total_salary;
                                        }

                                        echo '&#8369; '.number_format($salary_today,2);
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                        $date=date_create($row->clock_info->punch_time);
                                        echo date_format($date,"M d, Y");
                                        $time=date_create($row->clock_info->punch_time);
                                        echo date_format($time," h:i A");
                                    ?>                         
                                </td>
                              
                                <td>
                                    
                                    <a href="api/Authentication/index_web/?deviceType=rfid&loginKey=<?php echo $row->user->deviceLogin->login_key?>" class='btn btn-sm btn-info' id="logout">Time In</a>
                           
                                </td>
                                  <td>
                                    <a href="/Timesheet/search/<?php echo $row->id?>" target="_blank">View TimeSheet</a>
                                </td>
                                 <td>
                                    <a href="/user/edit/<?php echo $row->id?>" target="_blank">Edit</a>
                                </td>
                            </tr>

                        <?php endif;?>
                        <?php endforeach?>
                    </tbody>

                </table>

                <br>
                <h4  style="color:green;"><b>Construction Department</b></h4>
                  <form action="/User/update_work_time" method="post" required>
                  <b>Enter Work Time Limitation</b>
                  <input type="text"  name="work_hour">
                  <input type="hidden" name="branch_id" value="1">
                  <input type="hidden" name="department" value="contractions">
                  <input type="submit">
                </form>
                 <form action="/User/rollback_work_time" method="post" required>
                  <input type="hidden" name="branch_id" value="1">
                  <input type="hidden" name="department" value="contractions">
                  <input type="submit" value="Reset">
                </form>
                 <table class='table'>

                    <thead>

                        <th>#</th>
                        <th>Status</th>
                        <th>Fullname</th>
                        <th>Rates</th>
                        <th>Work Duration</th>
                        <th>Salary</th>
                        <th>PunchTime</th>
                        <th>Action</th>
                        <th></th>
                        <th></th>

                    </thead>


                    <?php $count = 0;?>
                    <tbody>
                        <?php foreach($activeUsers as $key => $row) :?>

                        <?php if($row->user->branch_id == 1 && $row->user->department == "contractions" && $row->clock_info->type == "time_in"): ?>  
                            <tr>
                                <td><?php echo ++$count?></td>
                                <td>
                                    <span class="badge badge-success"> Time In </span>
                                </td>
                                <td><?php echo $row->user->firstname . ' ' .$row->user->lastname?></td>
                                <td>
                                    <ul>
                                        <li>/Hour: <?php echo $row->user->userMeta->rate_per_hour?></li>
                                        <li>/Day: <?php echo $row->user->userMeta->rate_per_day?></li>
                                    </ul>
                                      <ul>
                                        <li>Regular : <?php echo $row->user->userMeta->work_hours?></li>
                                        <li>Max : <?php echo $row->user->userMeta->max_work_hours?></li>
                                    </ul>
                                </td>
                                <td>
                                    <?php
                                        // if time in
                                        $total_worktime_minutes = time_diff_minutes($row->clock_info->punch_time) +  $row->user->workHoursToday;
                                        // if time out
                                        if($row->clock_info->type == "time_out"){
                                            $total_worktime_minutes =  $row->user->workHoursToday;
                                        }

                                          if($date_today != $row->date_search)
                                        {
                                            $total_worktime_minutes = $row->total_time;
                                        }

                                        $hours =  floor($total_worktime_minutes / 60);
                                        $minutes =  floor($total_worktime_minutes % 60);
                                        echo $hours.'hours '.$minutes.'minutes';
                                    ?>                         
                                </td>
                                 <td>
                                    <?php
                                        $salary_today = ($row->user->userMeta->rate_per_hour/60) * $total_worktime_minutes;
                                       
                                        if($date_today != $row->date_search){
                                           $salary_today = $row->total_salary;
                                        }

                                        echo '&#8369; '.number_format($salary_today,2);
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                        $date=date_create($row->clock_info->punch_time);
                                        echo date_format($date,"M d, Y");
                                        $time=date_create($row->clock_info->punch_time);
                                        echo date_format($time," h:i A");
                                    ?>                         
                                </td>
                                <td>
                                    <a href="api/Authentication/index_web/?deviceType=rfid&loginKey=<?php echo $row->user->deviceLogin->login_key?>" class='btn btn-sm btn-danger' id="logout">Logout</a>
                                </td>
                                  <td>
                                    <a href="/Timesheet/search/<?php echo $row->id?>" target="_blank">View TimeSheet</a>
                                </td>
                                 <td>
                                    <a href="/user/edit/<?php echo $row->id?>" target="_blank">Edit</a>
                                </td>
                            </tr>
                        <?php endif; ?> 
                        <?php endforeach?>

                        <!--time out-->
                        <?php foreach($activeUsers as $key => $row) :?>

                        <?php if($row->user->branch_id == 1 && $row->user->department == "contractions" && $row->clock_info->type == "time_out"): ?>  
                            <tr>
                                <td><?php echo ++$count?></td>
                                <td>
                                    <span class="badge badge-danger"> Time Out </span>              
                                </td>
                                <td><?php echo $row->user->firstname . ' ' .$row->user->lastname?></td>
                                <td>
                                    <ul>
                                        <li>/Hour: <?php echo $row->user->userMeta->rate_per_hour?></li>
                                        <li>/Day: <?php echo $row->user->userMeta->rate_per_day?></li>
                                    </ul>
                                      <ul>
                                        <li>Regular : <?php echo $row->user->userMeta->work_hours?></li>
                                        <li>Max : <?php echo $row->user->userMeta->max_work_hours?></li>
                                    </ul>
                                </td>
                                <td>
                                    <?php
                                        // if time in
                                        $total_worktime_minutes = time_diff_minutes($row->clock_info->punch_time) +  $row->user->workHoursToday;
                                        // if time out
                                        if($row->clock_info->type == "time_out"){
                                            $total_worktime_minutes =  $row->user->workHoursToday;
                                        }

                                          if($date_today != $row->date_search)
                                        {
                                            $total_worktime_minutes = $row->total_time;
                                        }

                                        $hours =  floor($total_worktime_minutes / 60);
                                        $minutes =  floor($total_worktime_minutes % 60);
                                        echo $hours.'hours '.$minutes.'minutes';
                                    ?>                         
                                </td>
                                 <td>
                                    <?php
                                        $salary_today = ($row->user->userMeta->rate_per_hour/60) * $total_worktime_minutes;
                                       
                                        if($date_today != $row->date_search){
                                           $salary_today = $row->total_salary;
                                        }

                                        echo '&#8369; '.number_format($salary_today,2);
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                        $date=date_create($row->clock_info->punch_time);
                                        echo date_format($date,"M d, Y");
                                        $time=date_create($row->clock_info->punch_time);
                                        echo date_format($time," h:i A");
                                    ?>                         
                                </td>
                                <td>
                                    <a href="api/Authentication/index_web/?deviceType=rfid&loginKey=<?php echo $row->user->deviceLogin->login_key?>" class='btn btn-sm btn-info' id="logout">Time In</a>
                                </td>
                                  <td>
                                    <a href="/Timesheet/search/<?php echo $row->id?>" target="_blank">View TimeSheet</a>
                                </td>
                                 <td>
                                    <a href="/user/edit/<?php echo $row->id?>" target="_blank">Edit</a>
                                </td>
                            </tr>
                        <?php endif; ?> 
                        <?php endforeach?>
                    </tbody>

                </table>

                 <br>
                 <h4 style="color:green;"><b>Xavierville Employee</b></h4>
                <form action="/User/update_work_time" method="post" required>
                  <b>Enter Work Time Limitation</b>
                  <input type="text"  name="work_hour">
                  <input type="hidden" name="branch_id" value="2">
                  <input type="hidden" name="department" value="worker">
                  <input type="submit">
                </form>
                 <form action="/User/rollback_work_time" method="post" required>
                  <input type="hidden" name="branch_id" value="2">
                  <input type="hidden" name="department" value="worker">
                  <input type="submit" value="Reset">
                </form>
                 <table class='table'>

                    <thead>

                        <th>#</th>
                        <th>Status</th>
                        <th>Fullname</th>
                        <th>Rates</th>
                        <th>Work Duration</th>
                        <th>Salary</th>
                        <th>PunchTime</th>
                        <th>Action</th>
                        <th></th>
                        <th></th>

                    </thead>


                    <?php $count = 0;?>
                    <tbody>
                        <?php foreach($activeUsers as $key => $row) :?>
                        <tr>
                            <td><?php echo ++$count?></td>
                             <td>
                                <span class="badge badge-success"> Time In </span>           
                            </td>
                            <td><?php echo $row->user->firstname . ' ' .$row->user->lastname?></td>
                            <td>
                                <ul>
                                    <li>/Hour: <?php echo $row->user->userMeta->rate_per_hour?></li>
                                    <li>/Day: <?php echo $row->user->userMeta->rate_per_day?></li>
                                </ul>
                                 <ul>
                                    <li>Regular : <?php echo $row->user->userMeta->work_hours?></li>
                                    <li>Max : <?php echo $row->user->userMeta->max_work_hours?></li>
                                </ul>

                            </td>
                            <td>
                                <?php
                                    // if time in
                                    $total_worktime_minutes = time_diff_minutes($row->clock_info->punch_time) +  $row->user->workHoursToday;
                                    // if time out
                                    if($row->clock_info->type == "time_out"){
                                        $total_worktime_minutes =  $row->user->workHoursToday;
                                    }

                                      if($date_today != $row->date_search)
                                    {
                                        $total_worktime_minutes = $row->total_time;
                                    }

                                    $hours =  floor($total_worktime_minutes / 60);
                                    $minutes =  floor($total_worktime_minutes % 60);
                                    echo $hours.'hours '.$minutes.'minutes';
                                ?>                         
                            </td>
                             <td>
                                <?php

                                    $salary_today = ($row->user->userMeta->rate_per_hour/60) * $total_worktime_minutes;
                                   
                                    if($date_today != $row->date_search){
                                       $salary_today = $row->total_salary;
                                    }

                                    echo '&#8369; '.number_format($salary_today,2);
                                ?>                         
                            </td>
                            <td>
                                <?php
                                    $date=date_create($row->clock_info->punch_time);
                                    echo date_format($date,"M d, Y");
                                    $time=date_create($row->clock_info->punch_time);
                                    echo date_format($time," h:i A");
                                ?>                         
                            </td>
                           <td>
                            
                            <a href="api/Authentication/index_web/?deviceType=rfid&loginKey=<?php echo $row->user->deviceLogin->login_key?>" class='btn btn-sm btn-danger' id="logout">Logout</a>
                                 
                            </td>
                              <td>
                                <a href="/Timesheet/search/<?php echo $row->id?>" target="_blank">View TimeSheet</a>
                            </td>
                             <td>
                                <a href="/user/edit/<?php echo $row->id?>"  target="_blank">Edit</a>
                            </td>
                        </tr>                             
                        <?php endforeach?>

                        <!--TIME OUT-->
                        <?php foreach($activeUsers as $key => $row) :?>

                        <?php if($row->user->branch_id == 2 && $row->clock_info->type == "time_out"): ?>  
                            <tr>
                                <td><?php echo ++$count?></td>
                                 <td>
                                    <span class="badge badge-danger"> Time Out </span>
                                </td>
                                <td><?php echo $row->user->firstname . ' ' .$row->user->lastname?></td>
                                <td>
                                    <ul>
                                        <li>/Hour: <?php echo $row->user->userMeta->rate_per_hour?></li>
                                        <li>/Day: <?php echo $row->user->userMeta->rate_per_day?></li>
                                    </ul>
                                     <ul>
                                        <li>Regular : <?php echo $row->user->userMeta->work_hours?></li>
                                        <li>Max : <?php echo $row->user->userMeta->max_work_hours?></li>
                                    </ul>

                                </td>
                                <td>
                                    <?php
                                        // if time in
                                        $total_worktime_minutes = time_diff_minutes($row->clock_info->punch_time) +  $row->user->workHoursToday;
                                        // if time out
                                        if($row->clock_info->type == "time_out"){
                                            $total_worktime_minutes =  $row->user->workHoursToday;
                                        }

                                          if($date_today != $row->date_search)
                                        {
                                            $total_worktime_minutes = $row->total_time;
                                        }

                                        $hours =  floor($total_worktime_minutes / 60);
                                        $minutes =  floor($total_worktime_minutes % 60);
                                        echo $hours.'hours '.$minutes.'minutes';
                                    ?>                         
                                </td>
                                 <td>
                                    <?php

                                        $salary_today = ($row->user->userMeta->rate_per_hour/60) * $total_worktime_minutes;
                                       
                                        if($date_today != $row->date_search){
                                           $salary_today = $row->total_salary;
                                        }

                                        echo '&#8369; '.number_format($salary_today,2);
                                    ?>                         
                                </td>
                                <td>
                                    <?php
                                        $date=date_create($row->clock_info->punch_time);
                                        echo date_format($date,"M d, Y");
                                        $time=date_create($row->clock_info->punch_time);
                                        echo date_format($time," h:i A");
                                    ?>                         
                                </td>

                               <td>
                                    
                                    <a href="api/Authentication/index_web/?deviceType=rfid&loginKey=<?php echo $row->user->deviceLogin->login_key?>" class='btn btn-sm btn-info' id="logout">Time In</a>
                                    
                                </td>
                                  <td>
                                    <a href="/Timesheet/search/<?php echo $row->id?>" target="_blank">View TimeSheet</a>
                                </td>
                                 <td>
                                    <a href="/user/edit/<?php echo $row->id?>"  target="_blank">Edit</a>
                                </td>
                            </tr>
                        <?php endif; ?>                              
                        <?php endforeach?>
                    </tbody>

                </table>

            </div>

        </div>

    </div>

 <script type="text/javascript" defer>

 /* $( document ).ready(function(){

    $(document).on("click", "a", function() {

         if (confirm("Warning!! This will Logout the Employee, Are You Sure?"))
         {
            return true;
         }else
         {
           return false;
         }
      });
    });*/

 
</script>  

<?php endbuild()?>

<?php loadTo('tmp/layout')?>