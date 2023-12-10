<?php build('content')?>
    <?php
        $total = 0;
    ?>
    <div class="card">
        <div class="card-header">
            <h4>Wallets</h4>
            <a href="/dashboard">
                Back to Dashboard
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <th>#</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </thead>

                    <tbody>
                        <?php foreach($wallets as $key => $wallet) :?>
                            <?php $total += $wallet->amount;?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $wallet->description?></td>
                                <td><?php echo amountHTML($wallet->amount)?></td>
                                <td><?php echo date_long($wallet->created_at, 'M d ,Y h:i:s A')?></td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <h3>Total : <?php echo amountHTML($total)?></h3>
<?php endbuild()?>

<?php loadTo('tmp/layout')?>