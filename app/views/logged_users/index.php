<?php build('content') ?>
    <div class="card">
        <div class='card-header'>
            <h4 class='card-title'><?php echo isEqual($_GET['view'] ?? '', 'clocked_out') ? 'Clocked-out-Users': 'Clocked-in-Users'?></h4>
            <label for="#">Select Branch</label>
            <?php Form::select('branch_id', $branches, '' , [
                'class' => 'form-control',
                'id' => 'idBranch'
            ])?>
            <?php Flash::show()?>
            <ul>
                <li><a href="?view=clocked_in">Clocked In</a></li>
                <li><a href="?view=clocked_out">Clocked Out</a></li>
            </ul>
        </div>
        <div class='card-body'>
            <div class="table-responsive">
                <?php if (isEqual($viewType , 'clocked_in')) :?>
                <table class="table table-bordered dataTable">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Clock in Time</th>
                        <th>Hours Worked</th>
                        <th>Branch</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach ($loggedUsers as $key => $row):?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->fullname?></td>
                                <td><?php echo $row->clock_in_time?></td>
                                <td><?php  echo minutesToHours(timeDifferenceInMinutes($row->clock_in_time , $timeToday)) ?></td>
                                <td><?php echo $row->branch_name?></td>
                                <td>
                                    <a href="/TimelogMetaController/log/<?php echo $row->user_id?>" class="btn btn-primary">Clock Out</a>
                                </td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
                <?php else:?>
                    <table class="table table-bordered dataTable">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Branch</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach ($loggedUsers as $key => $row):?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->fullname?></td>
                                <td><?php echo $row->branch_name?></td>
                                <td>
                                    <a href="/TimelogMetaController/log/<?php echo $row->id?>" class="btn btn-primary">Clock In</a>
                                </td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
                <?php endif?>
            </div>
        </div>
    </div>
<?php endbuild()?>

<?php build('scripts') ?>
    <script>
        $(document).ready(function() {
            $("#idBranch").change(function() {
                let value = $(this).val();
                insertParam("branch_id", value);
            });
        });

        function insertParam(key, value) {
            key = encodeURIComponent(key);
            value = encodeURIComponent(value);

            // kvp looks like ['key1=value1', 'key2=value2', ...]
            var kvp = document.location.search.substr(1).split('&');
            let i=0;

            for(; i<kvp.length; i++){
                if (kvp[i].startsWith(key + '=')) {
                    let pair = kvp[i].split('=');
                    pair[1] = value;
                    kvp[i] = pair.join('=');
                    break;
                }
            }

            if(i >= kvp.length){
                kvp[kvp.length] = [key,value].join('=');
            }

            // can return this or...
            let params = kvp.join('&');

            // reload page with new params
            document.location.search = params;
        }
    </script>
<?php endbuild()?>
<?php loadTo('tmp/layout')?>