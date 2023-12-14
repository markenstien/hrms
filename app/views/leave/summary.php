<?php build('content') ?>
    <div class="container-fluid">
        <div class="card">
            <div class="text-center">
                <?php
                    $divider = wDivider(12);
                    $cardTitle = wCardTitle('Leave Summary');
                    $curYear  = date('Y');
                    $dateToday  = date('Y-m-d h:i:s A');

                    $backToListLink = wLinkDefault(_route('leave:index'), 'Back to list', ['icon' => 'arrow-left-circle', 'class' => 'noprint']);
                    $printLink = wLinkDefault('javascript:void(0)', 'Print', ['icon' => 'printer','onclick' => 'window.print()', 'class' => 'noprint']);
                    $cardHeaderValue = <<<EOF
                        {$cardTitle}
                        For year {$curYear} as of {$dateToday}
                        {$divider}
                        {$backToListLink}{$printLink}
                    EOF;
                    echo wCardHeader($cardHeaderValue);
                ?>
            </div>
            <div class="card-body">
                <?php $leaveCategories = Module::get('ee_leave')['categories'] ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td colspan="2" >Employee Data</td>
                            <td colspan="<?php echo count($leaveCategories)?>" style="text-align:center">Leave Summary</td>
                        </tr>
                        <tr>
                            <td style="background-color:var(--primary);color:#eee">Employee Name</td>
                            <td style="background-color:var(--primary);color:#eee">Employee ID</td>
                            <?php foreach($leaveCategories as $key => $row) :?>
                            <td style="background-color:var(--danger);color:#eee"><?php echo $row?></td>
                            <?php endforeach?>
                        </tr>

                        <?php foreach($summary as $key => $row) :?>
                            <?php $leavePointSummary = $row['leavePointSummary']?>
                            <tr>
                                <td><?php echo $row['user']->fullname?></td>
                                <td><?php echo wLinkDefault(_route('user:show', $row['user']->id), $row['user']->uid)?></td>
                                <?php foreach($leaveCategories as $key => $row) :?>
                                    <td><?php echo isset($leavePointSummary[$row]) ? "<span class='badge badge-info'>{$leavePointSummary[$row]}</span>" : '0'?></td>
                                <?php endforeach?>

                                
                            </tr>
                        <?php endforeach?>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>