<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="width: 300px;">
        <?php if(!empty($typeOfAction)) :?>
            <?php if(isEqual($typeOfAction, 'login')) :?>
                <a href="<?php echo $actionURL?>">Login</a>
            <?php else:?>
                <a href="<?php echo $actionURL?>">Logout</a>
            <?php endif?>
        <?php else:?>
            <form action="" method="post">
                <fieldset>
                    <legend>Login for attendance</legend>
                    <?php
                        if(!empty($errors)) {
                            echo "<span style='color:red'>{$errors}</span>";
                        }
                    ?>
                    <div>
                        <label for="#">Username</label>    
                        <input type="text" placeholder="username" name="username">
                    </div>

                    <div>
                        <label for="#">Password</label>    
                        <input type="password" placeholder="password" name="password">
                    </div>

                    <div>
                        <input type="submit" value="Login">
                    </div>
                </fieldset>
            </form>
        <?php endif?>
    </div>
</body>
</html>