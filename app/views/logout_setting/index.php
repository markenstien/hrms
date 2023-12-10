<?php build('content')?>
    <div class='card'>
        <div class='card-header'>
            <h4 class='card-title'>Automatic logout settings</h4>
        </div>

        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table'>
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Automatic Logout Duration</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach($logoutSettings as $key => $row) : ?>
                        <tr>
                            <td><?php echo ++$key?></td>
                            <td><?php echo $row->firstname .' ' .$row->lastname?></td>
                            <td><?php echo $row->username?></td>
                            <td><?php echo minutesToHours($row->max_duration)?></td>
                            <td><a href="/AutomaticLogoutSetting/edit/<?php echo $row->id?>">Edit</a></td>
                        </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/layout')?>