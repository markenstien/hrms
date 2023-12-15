<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonLeft('Attendance', [
            $navigationHelper->setNav('', 'Back', _route('attendance:index'))
        ])?>
        <div class="card">
            <?php echo wCardHeader(wCardTitle('On Duty'))?>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Time In</th>
                            <th>Duration</th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            <?php foreach($loggedUsers as $key => $row) :?>
                                <?php
                                    $rowId = random_letter(12);
                                    $actionURL = $QRTokenService::getLink($QRTokenService::LOGIN_TOKEN, [
                                        'device' => 'web',
                                        'userId' => $row->user_id,
                                        'token' => $token,
                                        'route' => seal((_route('attendance:logged-in')))
                                    ]);
                                ?>
                                <tr>
                                    <td><?php echo $row->uid?></td>
                                    <td><?php echo $row->fullname?></td>
                                    <td><?php echo $row->department_name?></td>
                                    <td><?php echo $row->position_name?></td>
                                    <td><span class="clockIn" data-target="<?php echo $rowId?>"><?php echo $row->clock_in?></span></td>
                                    <td><span class="duration" id="<?php echo $rowId?>"></span></td>
                                    <td><?php echo wLinkDefault($actionURL, 'Clock Out')?></td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>

<?php build('scripts') ?>
    <script>
        $(document).ready(function(){
            setInterval(function() {
                let clockInRows = $('.clockIn');

                $('.clockIn').each(function(index,element){
                    let target = $(element).data('target');
                    let clockInValue = $(element).html();

                    let differenceInMinutes = dateDifferenceInMinutes(clockInValue);
                    let differenceText = minutesToHours(differenceInMinutes);

                    $(`#${target}`).html(differenceText);
                });

               
                
                //every 1 sec
            }, 1000);
        })

    </script>
<?php endbuild()?>

<?php loadTo('tmp/admin_layout')?>