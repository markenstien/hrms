<?php build('content') ?>



<?php

    $today = date_long(today() , 'M d Y');

?>



<?php if( !empty($parsed['timesheetsGrouped'])) :?>

    <h2>List of time sheets</h2>

    <?php foreach($parsed['timesheetsGrouped'] as $key => $timesheets) :?>

        <?php

            $duration = 0;

            $className = uniqid('cs');

        ?>



        <section class="container-box">

            <!--<a href="#" data-target=".<?php echo $className?>" class='tbody-toggle'>Show Data</a>-->

            <h4><?php echo ucwords($key)?></h4>

            

            <div class="table-responsive">

               <!-- <table class="table <?php echo $className?> tbodyHidden">-->
                <table class="table">
                    <thead>

                        <th>#</th>

                        <th>Time In</th>

                        <th>Time Out</th>

                        <th>Duration</th>

                       <!-- <th>Amount</th>-->

                        <th>Allowance</th>

                        <th>Status</th>

                        <th>OT</th>

                    </thead>

                    <tbody>

                        <?php foreach($timesheets as $tsheet) :?>

                            <?php $duration += $tsheet->duration?>

                            <tr>

                                <td>#</td>
                                 <td> <h5><?php
                                      $date=date_create($tsheet->time_in);
                                      echo date_format($date,"M d, Y");
                                      $time=date_create($tsheet->time_in);
                                      echo '<b>'.date_format($time," h:i A").'</b>';
                                    ?>  </h5>    
                                </td>

                                <td> 
                                    <h5><?php
                                      $date=date_create($tsheet->time_out);
                                      echo date_format($date,"M d, Y");
                                      $time=date_create($tsheet->time_out);
                                      echo '<b>'.date_format($time," h:i A").'</b>';
                                    ?>   </h5>
                                </td>

                                <td>
                                    <?php if($tsheet->duration < 60): ?>
                                        <h5><b><?php echo $tsheet->duration." mins"?></b></h5>
                                    <?php else: ?>
                                            <?php $hrs = floor($tsheet->duration / 60);
                                                  $mins = fmod($tsheet->duration, 60); ?>

                                            <h5><b><?php echo $hrs." hr/s "?><?php echo $mins." mins"?></b></h5>
                                    <?php endif; ?>   
                                </td>

                                <!--<td><?php echo $tsheet->meta->rate ?? "Rate Not Found" ?></td>-->

                                <td><h5><b><?php echo $tsheet->amount?></b></h5></td>

                                <td>
                                    <?php if(isEqual($tsheet->status,"approved")): ?>
                                        <span class="badge badge-success"><?php echo $tsheet->status?></span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><?php echo $tsheet->status?></span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if($tsheet->is_ot) :?>
                                        <span class="badge badge-danger">OT</span>
                                    <?php else:?>
                                         <span class="badge badge-primary">REG</span>
                                    <?php endif?>
                                </td>
                            </tr>

                        <?php endforeach?>

                    </tbody>

                </table>

            </div>

            <h4>Work hours Rendered : <?php echo minutesToHours($duration) ?></h4>

        </section>

    <?php endforeach?>

<?php else:?>

    <div class="alert alert-info">

        <p class="text-center">No time sheets</p>

    </div>

<?php endif?>



<?php if($trashedTimesheets) :?>

<div class="card">

    <div class="card-header">

        <h4> Deleted Timesheets </h4>

    </div>



    <div class="card-body">

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <th>#</th>

                    <th>Time In</th>

                    <th>Time Out</th>

                    <th>Duration</th>

                    <th>Daily Rate</th>

                    <th>Allowance</th>

                    <th>OT</th>

                    <th>Status</th>

                    <th>Action</th>

                </thead>



                <tbody>

                    <?php foreach($trashedTimesheets as $key => $row): ?>

                    <tr>

                        <td><?php echo ++$key?></td>

                        <td><?php echo date_long($row->time_in, 'M d,Y h:i:s A')?></td>

                        <td><?php echo date_long($row->time_out, 'M d,Y h:i:s A')?></td>

                        <td><?php echo minutesToHours($row->duration)?></td>

                        <td><?php echo $row->meta->rate?></td>

                        <td>
                            <?php if($row->is_ot) :?>
                                <span class="badge badge-danger">OT</span>
                            <?php else:?>
                                 <span class="badge badge-primary">REG</span>
                            <?php endif?>
                        </td>

                        <td><?php echo $row->amount?></td>

                        <td><?php echo $row->status?></td>

                        <td>

                            <a href="/timesheet/show/<?php echo $row->id?>">Show</a>

                        </td>

                    </tr>

                    <?php endforeach?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php else:?>

    <div class="alert alert-info">

        <p class="text-center">No deleted time sheets</p>

    </div>

<?php endif;?>

<?php endbuild()?>



<?php build('headers')?>

	<style type="text/css">

		.tbodyHidden{

			display: none;

		}



		.container-box{

			border: 1px solid #000;

			margin-bottom: 5px;



			padding: 15px;

		}

	</style>

<?php endbuild()?>



<?php build('scripts')?>



	<script type="text/javascript">

		$( document ).ready(function() {



			$('.tbody-toggle').click( function(evt) 

			{

				let target = $(this).data('target');



				$(target).toggle();

			});

		});

	</script>

<?php endbuild()?>



<?php loadTo('tmp/layout')?>