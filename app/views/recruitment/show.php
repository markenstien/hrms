<?php build('content') ?>
<div class="container-fluid">
    <div class="card">
        <?php echo wCardHeader(wCardTitle('Candidate Details'))?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <td style="width:25%">Name</td>
                                <td><?php echo "{$candidate->firstname} {$candidate->lastname}"?></td>
                            </tr>
                            <tr>
                                <td style="width:25%"><?php echo $form->label('mobile_number')?></td>
                                <td><?php echo "{$candidate->mobile_number}"?></td>
                            </tr>
                            <tr>
                                <td style="width:25%"><?php echo $form->label('email')?></td>
                                <td><?php echo "{$candidate->email}"?></td>
                            </tr>
                            <tr>
                                <td style="width:25%"><?php echo $form->label('position_id')?></td>
                                <td><?php echo $candidate->position_id?></td>
                            </tr>
                            <tr>
                                <td style="width:25%"><?php echo $form->label('expected_salary')?></td>
                                <td><?php echo $candidate->expected_salary?></td>
                            </tr>
                            <tr>
                                <td style="width:25%"><?php echo $form->label('address')?></td>
                                <td><?php echo $candidate->address?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->label('result')?></td>
                                <td><span class="badge badge-primary"><?php echo $candidate->result?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-7">
                    <section class="mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><h4>Interviewer Remarks</h4></h4>
                            </div>
                            <div class="card-body">
                                <p><?php echo $candidate->remarks?></p>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><h4>Interviews</h4></h4>
                            </div>
                            <div class="card-body">
                                <?php foreach($seriesOfInterview as $key => $row): ?>
                                    <?php foreach($interviews as $intKey => $intRow) :?>
                                        <?php if($intRow->interview_title == $row['name']) :?>
                                            <?php echo wLinkDefault(_route('recruitment-interviews:show', $intRow->id), "{$intRow->interview_title} : {$intRow->result}", [
                                                'class' => 'btn btn-success'
                                            ])?>
                                        <?php else:?>
                                            <?php echo wLinkDefault(_route('recruitment-interviews:create', $candidate->id, [
                                                'title' => $row['name'],
                                                'number' => $row['number']
                                            ]), 'Start : '.$row['name'], [
                                                'class' => 'btn btn-primary'
                                            ])?>
                                        <?php endif?>
                                    <?php endforeach?>

                                <?php endforeach?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>