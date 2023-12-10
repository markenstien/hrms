<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Disputes</h4>
            <a href="#">Create</a>
        </div>

        <div class="card-body">
            <div class="filter">
                <?php
                    Form::open([
                        'method' => 'get'
                    ]);
                ?>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <?php
                                Form::label('Start Date');
                                Form::date('start_date' , '' , ['class' => 'form-control' , 'required' => true])
                            ?>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <?php
                                Form::label('End Date');
                                Form::date('end_date' , '' , ['class' => 'form-control' , 'required' => true])
                            ?>
                        </div>  
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <?php
                                Form::label('Type');
                                Form::select('dispute_type' , ['issues', 'manual'  ,'approved'] , '', ['class' => 'form-control' , 'required' => true])
                            ?>
                        </div>  
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <?php
                                Form::label('User');
                                Form::select('user_id' , $users ,'',['class' => 'form-control'])
                            ?>
                        </div>  
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <?php
                                Form::label('Apply Filter');
                                Form::submit('btn_filter','Apply Filter',['class' => 'btn-primary btn-sm' ,'style' => 'display:block'])
                            ?>
                            <?php if(isset($_GET['btn_filter'])) :?>
                            <a href="?" class="btn btn-warning btn-sm">Remove Filter</a>
                            <?php endif?>
                        </div>
                        
                    </div>
                </div>

                <?php Form::close()?>
            </div>

            <?php if(!empty($issues)) :?>
                <div class="table-responsive">
                    <table class="table table-bordered dataTable">
                        <thead>
                            <th>#</th>
                            <th>fullname</th>
                            <th>remarks</th>
                            <th>amount</th>
                            <th>payout status</th>
                            <th>Date</th>
                        </thead>

                        <tbody>
                            <?php $amountTotal = 0?>
                            <?php foreach($issues as $key => $row) :?>
                                <?php $amountTotal += $row->amount?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->fullname?></td>
                                    <?php if($row->flushed_hours > 0) :?>
                                        <td>
                                            <p>
                                                Work hours has been flushed,
                                                Duration: <strong><?php echo $row->flushed_hours?></strong>,
                                                Rate/hr : <strong><?php echo $row->rate_per_hour?></strong>
                                            </p>
                                        </td>
                                        <td><?php echo number_format($row->flushed_hour_amount , 2)?></td>
                                    <?php else:?>
                                        <td><?php echo $row->remarks?></td>
                                        <td><?php echo $row->amount?></td>
                                    <?php endif?>
                                    
                                    <td><?php echo $row->flushed_hours > 0 ? 'Flushed Hour':$row->payout_status?></td>
                                    <td><?php echo $row->date?></td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                        <tr>
                            <td colspan="3">Total</td>
                            <td colspan="3"><h3><?php echo number_format($amountTotal , 2)?></h3></td>
                        </tr>
                    </table>
                </div>
            <?php endif?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>