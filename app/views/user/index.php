<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonRight('Employee Management', [
            $navigationHelper->setNav('', 'Add Employee', _route('user:create'))
        ])?>
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Users'))?>
            <div class="card-body">
                <?php Flash::show()?>
                <div class='table-responsive'>
                    <table class='table table-bordered' id="dataTable">
                        <thead>
                            <th>#</th>
                            <th>Code</th>
                            <th>Profile</th>
                            <th style="width: 12%;">Name</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Access</th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            <?php $counter = 0?>
                            <?php foreach($users as $key => $user) :?> 
                                <tr>
                                    <td><?php echo ++$counter?></td>
                                    <td><?php echo $user->uid?></td>
                                    <td><img src="<?php echo $user->profile_url ?? 'https://korpee.app/public/uploads/logo-circle.jpg'?>" alt="user profile" style="width: 75px;"></td>
                                    <td><?php echo $user->fullname?></td>
                                    <td><?php echo $user->position_name?></td>
                                    <td><?php echo $user->department_name?></td>
                                    <td><?php echo $user->type?></td>
                                    <td>
                                        <a href="<?php echo _route('user:show', $user->id)?>">Show</a>
                                    </td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>