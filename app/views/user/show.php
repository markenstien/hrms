<?php build('content') ?>
    <div class="container-fluid">
        <?php echo wControlButtonLeft('User Management', [
            $navigationHelper->setNav('', 'Back', _route('user:index'))
        ])?>
        <div class="card">
            <?php echo wCardHeader(wCardTitle('Employee Preview'))?>
            <div class="card-body">
                <?php Flash::show()?>
                <div class="row">
                    <div class="col-md-4">
                        <img src="<?php echo $user->profile_url?>" alt="" style="width: 150px;">
                        <section class="mt-3">
                            <table class="table table-bordered table-sm">
                                <tr><td>Access : <?php echo $user->type?></tr>
                                <tr><td>Name : <?php echo $user->fullname?> <span>(<?php echo $user->uid?>)</span> </td></tr>
                                <tr><td>Position / Department : <?php echo $user->position_name?> / <?php echo $user->department_name?></td></tr>
                                <tr><td>Email : <?php echo $user->email?></td></tr>
                                <tr><td>Mobile Number : <?php echo $user->mobile_number?></td></tr>
                                <tr>
                                    <td><?php echo wLinkDefault(_route('user:edit-credentials', $user->id), 'Edit Credentials')?> 
                                    | <?php echo wLinkDefault(_route('user:edit', $user->id), 'Edit General')?></td>
                                </tr>
                                <tr>
                                    <td><?php echo wLinkDefault(_route('leave:user', $user->id), 'Leave Credits')?></td>
                                </tr>
                                
                                <?php if(isEqual(whoIs('id'), $user->id)) :?>
                                    <tr>
                                        <td>Username : <?php echo $user->username?> </td>
                                    </tr>
                                <?php endif?>
                            </table>
                        </section>
                    </div>

                    <div class="col-md-8">
                        <section>
                            <h4>Paylsips</h4>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>