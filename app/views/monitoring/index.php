<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Monitoring</h4>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable">
                            <thead>
                                <th>#</th>
                                <th>Name</th>
                                <th>Branch</th>
                                <th>Clock-in Time</th>
                                <th>Work Duration</th>
                                <th>Logout</th>
                            </thead>

                            <tbody id="loggedUsers">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <embed src="/Login/loginQRToken" type="">>
                </div>
            </div>
            
        </div>
    </div>
<?php endbuild()?>

<?php build('scripts') ?>
    <script>
        $(document).ready(function()
        {
            $("#dataTable").DataTable({
                paging:false
            });
            setInterval(() => {
                let url = getURL('api/Monitoring/getLoggedUsers');
                let dateNow = new Date();
                //load if getting new data    

                $("#loggedUsers").html("<tr><td colspan='4'>refreshing..</td></tr>");
                $.ajax({
                    url:url,
                    method:'GET',
                    success: function(response)
                    {
                        let html = '';
                        let responseData = response.data;

                        if (responseData) 
                        {
                            console.log(responseData);
                            
                            let counter = 1;
                            for(let i in responseData) 
                            {
                                $dateConvert = new Date(responseData[i].clock_in_time);
                                let dateValue = dateDifference(dateNow,$dateConvert);
                                html += `
                                    <tr>
                                        <td>${counter}</td>
                                        <td>${responseData[i].fullname}</td>
                                        <td>${responseData[i].branch_name}</td>
                                        <td>${responseData[i].clock_in_time}</td>
                                        <td><span class='duration' data-time="${responseData[i].clock_in_time}">${dateValue}</span></td>
                                        <td>
                                            <a href='/TimelogMetaController/log/${responseData[i].user_id}?redirect=prevpage' class='btn btn-primary'> 
                                                Logout
                                            </a>
                                        </td>
                                    </tr>
                                `;
                                counter++;
                            }
                        }
                        $("#loggedUsers").html(html);

                    },
                    
                });
            }, 4000);
        });
    </script>
<?php endbuild()?>
<?php loadTo('tmp/public')?>