<?php
    if (isset($_POST['update_details'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];

        $query = mysqli_query($con, "SELECT * from users WHERE email='$email'");
        $row = mysqli_fetch_array($query);
        $matched_user = $row['username'];

        if ($matched_user == '' || $matched_user== $userLoggedIn) {
            $query = mysqli_query($con, "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email' WHERE username='$userLoggedIn'");
            $message = "Details uppdated<br><br>";
        } else {
            $message = "That email is already in use!<br><br>";
        }
    } else {
        $message = '';
    }

    if (isset($_POST['update_password'])) {
        $old_password = strip_tags($_POST['password']);
        $new_password = strip_tags($_POST['new_password']);
        $new_re_password = strip_tags($_POST['new_re_password']);

        $query = mysqli_query($con, "SELECT password FROM users WHERE username='$userLoggedIn'");
        $row = mysqli_fetch_array($query);
        $db_password = $row['password'];

        if (md5($old_password) == $db_password) {
            if ($new_password == $new_re_password) {

                if (strlen($new_password) < 5 || strlen($new_password) > 30) {
                    $change_password_message = "Sorry! Your password must be between 5 to 30 characters<br><br>";
                } else {
                    $hash_password = md5($new_password);
                    $query = mysqli_query($con, "UPDATE users SET password='$hash_password' WHERE username='$userLoggedIn'");
                    $change_password_message = 'Your password has been update<br><br>';
                }
                
            } else {
                $change_password_message = 'Password does not match<br><br>';
            }
        } else {
            $change_password_message = 'Wrong password!<br><br>';
        }
    } else {
        $change_password_message = '';
    }

    if (isset($_POST['close_account'])) {
        header('Location: close_account.php');
    }

?>