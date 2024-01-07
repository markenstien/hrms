<?php build('content') ?>
    <div class="container-fluid">
        <?php
            echo wControlButtonRight('Recruitment', [
                $navigationHelper->setNav('', 'Add new Candidate', _route('recruitment:create'))
            ]);
        ?>

        <div class="card">
            <?php echo wCardHeader(wCardTitle('Candidates'))?>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Expected Salary</th>
                            <th>Result</th>
                            <th>Mobile Number</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Remarks</th>
                            <th>Final Remarks</th>
                            <th>Date of Entry</th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            <?php foreach($recruits as $key => $row) :?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->firstname . ' '.$row->lastname?></td>
                                    <td><?php echo $row->position_name?></td>
                                    <td><?php echo $row->expected_salary?></td>
                                    <td><?php echo $row->result?></td>
                                    <td><?php echo $row->mobile_number?></td>
                                    <td><?php echo $row->email?></td>
                                    <td><?php echo $row->address?></td>
                                    <td><?php echo $row->remarks?></td>
                                    <td><?php echo $row->recruit_status?></td>
                                    <td><?php echo $row->created_at?></td>
                                    <td><?php echo wLinkDefault( _route('recruitment:show', $row->id), 'Show')?></td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endbuild() ?>
<?php loadTo('tmp/admin_layout')?>