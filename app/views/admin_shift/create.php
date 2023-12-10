<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonLeft('Shifts', [
            $navigationHelper->setNav('', 'Back', _route('admin-shift:index'))
        ])?>

        <div class="card">
            <?php echo wCardHeader(wCardTitle('Add New Shift')) ?>
            <div class="card-body">
                <?php Flash::show()?>
                <?php echo $form->start()?>
                    <div class="form-group">
                        <?php echo $form->getRow('shift_name')?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->getRow('shift_description')?>
                    </div>

                <?php echo wDivider()?>
                
                <section>
                    <!-- SCHEDULES -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th>Day</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>Rest Day</th>
                            </thead>

                            <tbody>
                                <?php foreach($daysoftheweek as $key => $day) :?>
                                    <tr>
                                        <td>
                                            <?php
                                                Form::hidden("day[{$key}][day]" , $day);
                                                echo $day;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                Form::time("day[{$key}][time_in]" , '08:00');
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                Form::time("day[{$key}][time_out]" , '17:00');
                                            ?>
                                        </td>
                                        <td>
                                            <label for="<?php echo "r-off{$key}"?>">
                                                <?php Form::radio("day[{$key}][rd]" , 1 , ['id' => "r-off{$key}"]) ?>
                                                Rest Day
                                            </label>
                                            <label for="<?php echo "w-off{$key}"?>">
                                                <?php Form::radio("day[{$key}][rd]" , 0 , ['id' => "w-off{$key}" , 'checked' => '']) ?>
                                                Work Day
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach?>
                            </tbody>
                        </table>
                    </div>
                </section>
                
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Schedule">
                </div>
                <?php echo $form->end()?>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>