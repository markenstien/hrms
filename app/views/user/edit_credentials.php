<?php build('content') ?>
    <?php
        echo wControlButtonLeft('Credential Management', [
            $navigationHelper->setNav('', 'Back', _route('user:show', $user->id))
        ]);
    ?>
    <div class="container-fluid">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <?php echo wCardHeader(wCardTitle('Edit Credentials'))?>
                <div class="card-body">
                    <?php
                        Form::open([
                            'method' => 'post'
                        ]);

                        Form::hidden('user_id', $user->id);
                        Form::hidden('action_type', 'change_username');
                    ?>
                        <div class="form-group">
                            <?php
                                Form::label('Username');
                                Form::text('username', '', [
                                    'class' => 'form-control',
                                    'required' => true
                                ])
                            ?>
                        </div>

                        <div class="form-group">
                            <?php
                                Form::label('Password');
                                Form::password('password', '', [
                                    'class' => 'form-control',
                                    'required' => true
                                ])
                            ?>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-sm" value="Change Username">
                        </div>
                    <?php Form::close()?>

                    <?php echo wDivider()?>
                    <hr>
                    <?php
                        Form::open([
                            'method' => 'post'
                        ]);

                        Form::hidden('user_id', $user->id);
                        Form::hidden('action_type', 'change_password');
                    ?>
                        <div class="form-group">
                            <?php
                                Form::label('New Password');
                                Form::password('new_password', '', [
                                    'class' => 'form-control',
                                    'required' => true
                                ])
                            ?>
                        </div>

                        <div class="form-group">
                            <?php
                                Form::label('Password');
                                Form::password('password', '', [
                                    'class' => 'form-control',
                                    'required' => true
                                ])
                            ?>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-sm" value="Change Password">
                        </div>
                    <?php Form::close()?>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/admin_layout')?>