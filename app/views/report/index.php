<?php build('content') ?>
    <div class="container-fluid">
        <?php Flash::show()?>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <?php echo wCardHeader(wCardTitle('Reports'))?>
                    <div class="card-body">
                        <?php
                            Form::open([
                                'method' => 'get'
                            ])
                        ?>

                            <div class="form-group">
                                <?php
                                    $categories = arr_layout_keypair($categories, ['id', 'category_name']);

                                    Form::label('Category');
                                    Form::select('category',$categories,'', [
                                        'class' => 'form-control',
                                        'required' => true
                                    ])
                                ?>
                            </div>

                            <div class="form-group">
                                <?php
                                    Form::label('Employee ID (optional)');
                                    Form::select('uid', arr_layout_keypair($employees,['uid', 'firstname@lastname']), '', [
                                        'class' => 'form-control'
                                    ])
                                ?>
                            </div>

                            <div class="form-group">
                                <?php Form::submit('', 'Apply Search')?>

                                <?php
                                    if(!is_null($result)) {
                                        echo wLinkDefault(_route('report:index'), 'Clear Filter', [
                                            'class' => 'btn btn-warning'
                                        ]);
                                    }
                                ?>
                            </div>
                        <?php Form::close()?>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <?php
                    if(!is_null($result)) {
                        grab("report/{$resultViewSource}", [
                            'result' => $result,
                            'categoryId' => $categoryId,
                            'req' => $req,
                            'deductionLabels' => $deductionLabels ?? []
                        ]);
                    }
                ?>

                <?php if(isset($contributionSummary)) :?>
                    <div class="card mt-3">
                        <?php
                            $userData = "{$contributionSummary['user']->firstname} {$contributionSummary['user']->lastname} # {$contributionSummary['user']->uid}";
                        ?>
                        <?php echo wCardHeader(wCardTitle('Summary of user : ' . $userData))?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <?php echo wCardHeader(wCardTitle('PAGIBIG'))?>
                                        <div class="card-body">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <th>Amount Paid</th>
                                                    <th>Balance</th>
                                                    <th>Amount Proceeding</th>
                                                    <th>Date</th>
                                                </thead>
                                                <?php $total = 0?>
                                                <?php foreach($contributionSummary['payments']['pagibig'] as $key => $row) :?>
                                                    <?php $total += $row->amount?>
                                                    <tr>
                                                        <td><?php echo amountHTML($row->amount)?></td>
                                                        <td><?php echo amountHTML($row->running_balance)?></td>
                                                        <td><?php echo amountHTML($row->amount_proceeding)?></td>
                                                        <td><?php echo $row->release_date?></td>
                                                    </tr>
                                                <?php endforeach?>
                                            </table>
                                            <h5>Total Payment : <?php echo amountHTML($total)?></h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <?php echo wCardHeader(wCardTitle('PHILHEALTH'))?>
                                        <div class="card-body">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <th>Amount Paid</th>
                                                    <th>Balance</th>
                                                    <th>Amount Proceeding</th>
                                                    <th>Date</th>
                                                </thead>
                                                <?php $total = 0?>
                                                <?php foreach($contributionSummary['payments']['philhealth'] as $key => $row) :?>
                                                    <?php $total += $row->amount?>
                                                    <tr>
                                                        <td><?php echo amountHTML($row->amount)?></td>
                                                        <td><?php echo amountHTML($row->running_balance)?></td>
                                                        <td><?php echo amountHTML($row->amount_proceeding)?></td>
                                                        <td><?php echo $row->release_date?></td>
                                                    </tr>
                                                <?php endforeach?>
                                            </table>
                                            <h5>Total Payment : <?php echo amountHTML($total)?></h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <?php echo wCardHeader(wCardTitle('SSS'))?>
                                        <div class="card-body">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <th>Amount Paid</th>
                                                    <th>Balance</th>
                                                    <th>Amount Proceeding</th>
                                                    <th>Date</th>
                                                </thead>
                                                <?php $total = 0?>
                                                <?php foreach($contributionSummary['payments']['sss'] as $key => $row) :?>
                                                    <?php $total += $row->amount?>
                                                    <tr>
                                                        <td><?php echo amountHTML($row->amount)?></td>
                                                        <td><?php echo amountHTML($row->running_balance)?></td>
                                                        <td><?php echo amountHTML($row->amount_proceeding)?></td>
                                                        <td><?php echo $row->release_date?></td>
                                                    </tr>
                                                <?php endforeach?>
                                            </table>
                                            <h5>Total Payment : <?php echo amountHTML($total)?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif?>

                <?php if(isset($otherLoanSummary)) :?>
                    <?php
                        $userData = "{$otherLoanSummary['user']->firstname} {$otherLoanSummary['user']->lastname} # {$otherLoanSummary['user']->uid}";
                    ?>
                    <div class="card mt-3">
                        <?php echo wCardHeader(wCardTitle('Summary of user : '.$userData))?>
                        <div class="card-body">
                            <p>List of payments for <strong><?php echo $otherLoanSummary['deduction']->deduction_name?></strong> </p>
                            <div class="table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Paid Amount</th>
                                        <th>Balance</th>
                                        <th>Amount Proceeding</th>
                                        <th>Date</th>
                                    </thead>

                                    <?php foreach($otherLoanSummary['payments'] as $key => $row):?>
                                        <tr>
                                            <td><?php echo ++$key?></td>
                                            <td><?php echo $row->amount?></td>
                                            <td><?php echo amountHTML($row->running_balance)?></td>
                                            <td><?php echo amountHTML($row->amount_proceeding)?></td>
                                            <td><?php echo $row->release_date ?? 'N/A'?></td>
                                        </tr>
                                    <?php endforeach?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif?>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>