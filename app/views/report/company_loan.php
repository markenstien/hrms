<?php
    $deductionLabels = $data['deductionLabels'];
    $loans = $data['result'];
    $req = $data['req'];
?>
<div class="card">
    <?php echo wCardHeader(wCardTitle('Result'))?>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <th>EE#</th>
                    <th>NAME</th>
                    <?php foreach($deductionLabels as $key => $row) :?>
                        <th><?php echo strtoupper($row)?></th>
                    <?php endforeach?>
                </thead>

                <tbody>
                    <?php foreach($loans as $key => $userLoans) :?>
                        <?php 
                        $user = $userLoans[0];
                        $req['uid'] = $user->uid;
                        ?>
                        <tr>
                            <td><?php echo $user->uid?></td>
                            <td><?php echo $user->fullname?></td>
                            <?php foreach($deductionLabels as $dKey => $row) :?>
                                <?php
                                    $loadData =  tmpExtract($userLoans, $dKey);
                                    if($loadData) {
                                        $req['deduction_id'] = $loadData->id;
                                    } else {
                                        $req['deduction_id'] = '';
                                    }
                                    $totalAmout = 0;
                                    if($loadData) {
                                        $totalAmout= $loadData->initial_balance -  $loadData->balance;
                                    }
                                ?>
                                <td><?php echo amountHTML($totalAmout)?> <?php echo wLinkDefault(_route('report:index', $req), 'Review')?></td>
                            <?php endforeach?>
                        </tr>
                    <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    /**
     * Valid keys
     * PAGIBIG
     * SSS
     * PHILHEALTH
     */
    function tmpExtract($userLoans, $source) {
        $retVal = false;
        foreach($userLoans as $key => $row) {
            if(isEqual($row->deduction_id, $source)) {
                $retVal = $row;
                break;
            }
        }
        return $retVal;
    }
?>