<?php build('content') ?>

    <div class="card">
        <div class='card-header'>
            <h4 class='card-title'>Logged Users</h4>
        </div>
        <div class='card-header'>
            <a href="/LoggedUsers/index"><h4 class='card-title'>Back</h4></a>
        </div>

        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table'>
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>PunchTime</th>
                    </thead>

                    <tbody>
                        <?php foreach($activeUsers as $key => $row) :?>
                        

                        <?php if($row->user->address == "Guiguinto, Bulacan"): ?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->user->firstname . ' ' .$row->user->lastname?></td>
                                <td>
                                    <?php
                                        $date=date_create($row->punch_time);
                                        echo date_format($date,"M d, Y");
                                        $time=date_create($row->punch_time);
                                        echo date_format($time," h:i A");
                                    ?>                         
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endbuild()?>

<?php build('script') ?>
<script type="text/javascript">
    $( document ).ready(function(evt) {

      data = setInterval(get_active ,1500);     
    

    });


     function get_active()
     {alert("df");
         
              $.ajax({
                method : 'post' ,
                url    : '/LoggedUsers/address',
                success: function(response)
                {

                  response = JSON.parse(response);
                  
                  console.log(response);

                  let html = ``;

                  if(response == false) {

                    $("#userTimeSheet").html('');

                    html += `<div class='userinfo-ajax'>
                        <strong> Not Found</strong>

                      </div>`;

                  }else{


                    for(let i in response) 
                    {
                     html += `<tr> 
                                <td class="text-warning">${response[i].time_in}</td>
                                <td class="text-danger">${response[i].time_out}</td>
                                <td class="text-info">${response[i].hours}Hrs ${response[i].minutes}mins</td>
                                <td class="text-success">${response[i].status}</td>
                              </tr>`;
                    }

                    $("#userTimeSheet").html(html);
                  }
                }
                }).done(function(promise) 
                {
                  if(promise != '')
                  {
                       $('#timeSheet_modal').modal('show');
                  }
             
                });
        }

</script>
<?php endbuild()?>

<?php loadTo('tmp/layout')?>