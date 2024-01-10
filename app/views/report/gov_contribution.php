<?php
    //grouped by user
    $contributions = $data['result'];
    $contributionId = $data['categoryId'];
    $req = $data['req'];
?>
<div class="card">
    <?php echo wCardHeader(wCardTitle('Result'))?>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <th>EE#</th>
                    <th>Name</th>
                    <th>TOTAL PAGIBIG</th>
                    <th>TOTAL PHILHEALTH</th>
                    <th>TOTAL SSS</th>
                    <th>Action</th>
                </thead>

                <tbody>
                    <?php foreach($contributions as $key => $usersContributions) :?>
                        <?php
                            $pagibig = tmpExtract($usersContributions, 'PAGIBIG');
                            $philhealth = tmpExtract($usersContributions, 'PHILHEALTH');
                            $sss = tmpExtract($usersContributions, 'SSS');
                            $user = $usersContributions[0];
                            $req['uid'] = $user->uid;
                        ?>
                        <tr>
                            <td><?php echo $user->uid?></td>
                            <td><?php echo $user->fullname?></td>
                            <td>
                                <?php 
                                    if($pagibig) {
                                        echo amountHTML($pagibig->initial_balance - $pagibig->balance);
                                    } else {
                                        echo 'N/A';
                                    }
                                ?>
                            </td>

                            <td>
                                <?php 
                                    if($philhealth) {
                                        echo amountHTML($philhealth->initial_balance - $philhealth->balance);
                                    }
                                ?>
                            </td>

                            <td>
                                <?php 
                                    if($sss) {
                                        echo amountHTML($sss->initial_balance - $sss->balance);
                                    }
                                ?>
                            </td>

                            <td><?php echo wLinkDefault(_route('report:index', $req), 'Review')?></td>
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
    function tmpExtract($userContributionArray, $source) {
        $retVal = false;
        foreach($userContributionArray as $key => $row) {
            if(isEqual($row->deduction_code, $source)) {
                $retVal = $row;
                break;
            }
        }
        return $retVal;
    }
?>