<?php build('content') ?>

    

    <div class='card'>

        <div class='card-header'>

            <h4 class='card-title'>Timesheets</h4>

            <?php Flash::show()?>

        </div>



        <div class='card-body'>


            <section>

                <?php if(isset($_GET['filter'])) :?>

                    <a href="?" class='btn btn-warning'>Remove Filter</a>

                <?php endif?>

            </section>

            <table class='table'>

                <thead>

                    <th>User</th>

                    <th>Time In</th>

                    <th>Time Out</th>

                    <th>Duration</th>

                    <th>Amount</th>

                    <th>Status</th>

                    <th>Action</th>

                </thead>



                <tbody>
                    <tr>
                        <td><?php echo $row->full_name?></td>
                        <td><?php echo $row->time_in?></td>
                        <td><?php echo $row->time_out?></td>
                        <td><?php echo minutesToHours($row->duration)?></td>
                        <td><?php echo $row->amount?></td>
                        <td><?php echo $row->status?></td>
                        <td>
                            
                        </td>
                    </tr>

                    <?php $totalAmount = 0?>

                    <?php foreach($timesheets as $key => $row):?>

                    <?php

                        /**

                         * QUICK FIX ONLY

                         */

                        if(isset($_GET['filter']))

                            if(!isEqual($row->employee_name , $_GET['value']))

                            continue; 

                    ?>

                    <?php $totalAmount += $row->amount?>

                        <tr>

                            <td>
                                <?php
                                    Form::checkbox('timesheet_ids[]' , $row->id , [
                                        'checked' => '',
                                        'id'      => 'timesheetId-'.$row->id
                                    ]);
                                    Form::label($row->employee_name , 'timesheetId-'.$row->id);
                                ?>
                                <a href="?filter=employee_name&value=<?php echo $row->employee_name?>">Filter</a>
                            </td>
                            <form class="post" action="/TimesheetAction/update_timesheet" method="post">
                            <td>
                                <?php 
                                    Form::hidden('id' , $row->id);
                                    Form::hidden('userid' , $row->user_id);
                                    $date=date_create($row->time_in);
                                ?>   
                                 <input type="datetime-local"  name="time_in" value="<?php echo date_format($date,"Y-m-d\TH:i"); ?>">
                            </td>

                            <td>
                                <?php 
                                    $date=date_create($row->time_out);
                                ?> 
                                 <input type="datetime-local"  name="time_out" value="<?php echo date_format($date,"Y-m-d\TH:i"); ?>">
                            </td>


                            <td><?php echo minutesToHours($row->duration)?></td>
                            <td><?php echo $row->amount?></td>
                            <td><?php echo $row->meta->rate?></td>

                            <td>
                                <?php if($row->is_ot) :?>
                                    <span class="badge badge-danger">OT</span>
                                <?php else:?>
                                     <span class="badge badge-primary">REG</span>
                                <?php endif?>
                            </td>

                            

                            <td><?php echo $row->status?></td>

                            <td>

                                 <input type="submit" name="" value="Process"
                                     class="btn btn-success btn-sm form-confirm">
                            </form>

                                <a href="/TimesheetAction/approve/<?php echo $row->id?>&token=<?php echo seal($row->id) ?>" 
                                    class='btn btn-primary btn-sm'> Approve </a>

                            </td>

                        </tr>

                    <?php endforeach?>

                    <tr>

                        <td colspan='4'>Total</td>

                        <td><?php echo amountHTML($totalAmount)?></td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>



<?php endbuild()?>

<?php loadTo('tmp/layout')?>