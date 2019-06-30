<?php

    include_once('include/header.php');
    include_once('include/form_handlers/settings_handler.php');


?>
<div class='main-column column'>
    <h4>Account Settings</h4>
    <?php
        echo "<img src='" . $user['profile_pic'] . "' id='small_profile_pic'>";
    ?>
    <br>
    <a href="upload.php">Upload new profile picture</a>
    
    <br><br><br>

    <p>Modifying the value and click 'Update Details'</p>
    <?php
        $user_obj = new User($con, $userLoggedIn);
        $user = $user_obj->getUserDetail();
    ?>
    <form action="settings.php" method='POST'>
        First name: <input type="text" name='first_name' value='<?php echo $user['first_name'] ?>' class='settings_input'><br>
        Last name: <input type="text" name='last_name' value='<?php echo $user['last_name'] ?>'class='settings_input'><br>
        Email: <input type="text" name='email' value='<?php echo $user['email'] ?>'class='settings_input'><br>
        <?php echo isset($message) ? $message: "" ?>
        <input type="submit" name='update_details' id='save_details' value='Update Details' class='info settings_submit'>
    </form>

    <h4>Change Password</h4>
    <form action="settings.php" method='POST'>
        Old Password: <input type="password" name='password'class='settings_input'><br>
        New Password: <input type="password" name='new_password'class='settings_input'><br>
        Re-enter New Password: <input type="password" name='new_re_password'class='settings_input'><br>
        <?php echo isset($change_password_message) ? $change_password_message: "" ?>
        <input type="submit" name='update_password' id='save_password' value='Change Password' class='info settings_submit'>
    </form>

    <h4>Close Account</h4>
    <form action='settings.php' method='POST'>
        <input type="submit" name='close_account' id='close_account' value='Close Account' class='danger settings_submit'>
    </form>
</div>
</div>
</body>
</html>