<?php
    $fname = '';
    $lname = '';
    $em = '';
    $em2 = '';
    $password = '';
    $password2= '';
    $date = '';
    $errors = array();

    if (isset($_POST['reg_button'])) {
        $fname = strip_tags($_POST['reg_fname']);
        $fname = trim($fname);
        $fname = ucfirst(strtolower($fname));
        $_SESSION['reg_fname'] = $fname;

        $lname = strip_tags($_POST['reg_lname']);
        $lname = trim($lname);
        $lname = ucfirst(strtolower($lname));
        $_SESSION['reg_lname'] = $lname;

        $em = strip_tags($_POST['reg_email']);
        $em = trim($em);
        $_SESSION['reg_email'] = $em;

        $em2 = strip_tags($_POST['reg_email2']);
        $em2 = trim($em2);
        $_SESSION['reg_email2'] = $em2;

        $password = strip_tags($_POST['reg_password']);
        $password2 = strip_tags($_POST['reg_password2']);

        $date = date("Y-m-d");

        if ($em == $em2) {
            if (filter_var($em, FILTER_VALIDATE_EMAIL)) {
                $em = filter_var($em, FILTER_VALIDATE_EMAIL);

                $e_check = mysqli_query($con, "SELECT email from users WHERE email='$em'");

                $num_rows = mysqli_num_rows($e_check);

                if ($num_rows > 0) {
                    $errors['reg_email'] = "Email already in use<br>";
                }

            } else {
                $errors['reg_email'] = "Invalid format<br>";
            }
        } else {
            $errors['reg_email2'] = "Emails don't match<br>";
        }

        if (strlen($fname) > 25 || strlen($fname) < 2) {
            $errors['reg_fname'] = "Your first name must be between 2 and 25 characters<br>";
        }
        if (strlen($lname) > 25 || strlen($lname) < 2) {
            $errors['reg_lname'] = "Your first name must be between 2 and 25 characters<br>";
        }
        if ($password != $password2) {
            $errors['reg_password2'] = "Your passwords do not match<br>";
        } else {
            if (preg_match("/[^a-zA-Z0-9]/", $password)) {
                $errors['reg_password'] = 'Your password can only contain English characters or numbers<br>';
            }
        }

        if (strlen($password) > 30 || strlen($password) < 5) {
            $errors['reg_password'] = "Your password must be between 5 and 30 characters<br>";
        }
        if (empty($errors)) {
            $password = md5($password);
            
            $username = strtolower($fname . '_' . $lname);
            $check_username_query = mysqli_query($con, "SELECT username from users WHERE username='$username'");

            $i = 0;
            $username_tmp = $username;
            while(mysqli_num_rows($check_username_query) != 0) {
                $i++;
                $username_tmp = $username . '_' . $i;
                $check_username_query = mysqli_query($con, "SELECT username from users WHERE username='$username_tmp'");
            }
            $username = $username_tmp;

            $rand = rand(1, 2);

            switch($rand) {
                case 1:
                    $profile_pic = 'assets/images/profile_pics/default/head_pete_river.png';
                    break;
                case 2:
                    $profile_pic = 'assets/images/profile_pics/default/head_emerald.png';
                    break;
                default:
                    $profile_pic = 'assets/images/profile_pics/default/head_pete_river.png';
                    break;
            }

            $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

            $errors['success'] = '<span style="color: #4267b2">Congratulation! You\'re good to go! Let\'s login!</span>';
            $_SESSION['reg_fname'] = '';
            $_SESSION['reg_lname'] = '';
            $_SESSION['reg_email'] = '';
            $_SESSION['reg_email2'] = '';
        }
    }
?>