<?php build('content') ?>
    <div class="container">
        <?php
            Form::open([
                'method' => 'get'
            ])
        ?>

        <div class="row">
            <div class="form-group">
                <?php
                    Form::label('Start Date');
                    Form::date('start_date', '', [
                        'class' => 'form-control'
                    ]);
                ?>
            </div>
            
            <div class="form-group">
                <?php
                    Form::label('Start Date');
                    Form::date('end_date', '', [
                        'class' => 'form-control'
                    ]);
                ?>
            </div>

            <div class="form-group">
                <?php
                    Form::label('Users');
                    Form::select('user_id', arr_layout_keypair($users,['id','fullname']) , '', [
                        'class' => 'form-control'
                    ]);
                ?>
            </div>

            <div class="form-group">
                <?php
                    Form::label('Action Button');
                    Form::submit('btn_filter', 'Apply Filter',[
                        'style' => 'display:block',
                        'class' => 'btn btn-primary'
                    ]);
                ?>
            </div>
        </div>
        <?php Form::close()?>
        <?php Flash::show()?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <th>#</th>
                    <th>Control number</th>
                    <th>Name</th>
                    <th>Account Number</th>
                    <th>Amount</th>
                    <th>Resent</th>
                    <th>Date</th>
                    <th>Action</th>
                </thead>

                <tbody>
                    <?php $total = 0?>
                    <?php foreach($walletByUsers as $tKey => $tRow) :?>
                        <?php $total += $tRow->amount?>
                        <tr <?php echo $tRow->is_resent ? 'style = "background:blue;color:white"' : ''?>>
                            <td><?php echo ++$tKey?></td>
                            <td><?php echo $tRow->control_number?></td>
                            <td><?php echo $tRow->fullname?></td>
                            <td><?php echo $tRow->account_number?></td>
                            <td><?php echo $tRow->amount?></td>
                            <td><?php echo $tRow->is_resent?></td>
                            <td><?php echo $tRow->created_at?></td>
                            <td>
                                <a href="/Wallet/resend/<?php echo $tRow->id?>?token=<?php echo $token?>">Resend</a>
                            </td>
                        </tr>
                    <?php endforeach?>

                    <tr>
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $total?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endbuild()?>
<?php occupy()?>