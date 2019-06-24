<?php
    require_once 'config/config.php';
    require_once 'include/form_handlers/register_handler.php';
    require_once 'include/form_handlers/login_handler.php'
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='assets/css/register_style.css' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src='assets/js/register.js'></script>
    <title>Welcome to Swiftfeed</title>
</head>
<body>

    <?php
        if (isset($_POST['reg_button'])) {
            echo '
            <script>
                $(document).ready(function() {
                    $("#first").hide();
                    $("#second").show();
                })
            </script>
            ';
        }
    ?>

    <div class='wrapper'>
        <div class='login-box'>
            <div class='login-header'>
                <h1>Swiftfeed!</h1>
                Login or sign up below!
            </div>
            <div class='first' id='first'>
                <form action='register.php' method='POST'>
                    <input type='email' name='log_email' placeholder='Email' value="<?php
                        if (isset($_SESSION['log_email'])) {
                            echo $_SESSION['log_email'];
                        }
                    ?>" required>
                    <br />
                    <input type='password' name='log_password' placeholder='Password'>
                    <br/>
                    <input type='submit' name='login_button' value='Login'>
                    <?php if (isset($errors['login'])) echo ('<br>' . $errors['login']) ?>
                    <br>
                    <a href='#' id='signup' class='signup'>Need an account? Register here!</a>
                </form>
            </div>
            
            <div class='second' id='second'>
                <form action='register.php' method='POST'>
                    <input type='text' name='reg_fname' placeholder='First Name' value="<?php
                        if (isset($_SESSION['reg_fname'])) {
                            echo $_SESSION['reg_fname'];
                        }
                    ?>" required>
                    <br>
                    <?php if (isset($errors['reg_fname'])) echo ($errors['reg_fname']) ?>
                    <input type='text' name='reg_lname' placeholder='Last Name' value="<?php
                        if (isset($_SESSION['reg_lname'])) {
                            echo $_SESSION['reg_lname'];
                        }
                    ?>" required>
                    <br>
                    <?php if (isset($errors['reg_lname'])) echo ($errors['reg_lname']) ?>
                    <input type='email' name='reg_email' placeholder='Email' value="<?php
                        if (isset($_SESSION['reg_email'])) {
                            echo $_SESSION['reg_email'];
                        }
                    ?>" required>
                    <br>
                    <?php if (isset($errors['reg_email'])) echo ($errors['reg_email']) ?>
                    <input type='email' name='reg_email2' placeholder='Confirm Email' value="<?php
                        if (isset($_SESSION['reg_email2'])) {
                            echo $_SESSION['reg_email2'];
                        }
                    ?>" required>
                    <br>
                    <?php if (isset($errors['reg_email2'])) echo ($errors['reg_email2']) ?>
                    <input type='password' name='reg_password' placeholder='Password' required>
                    <br>
                    <?php if (isset($errors['reg_password'])) echo ($errors['reg_password']) ?>
                    <input type='password' name='reg_password2' placeholder='Confirm Password' required>
                    <br>
                    <?php if (isset($errors['reg_password2'])) echo ($errors['reg_password2']) ?>
                    <input type='submit' name='reg_button' value='Register'>
                    <br>
                    <?php if (isset($errors['success'])) echo ($errors['success']) .'<br>' ?>
                    <a href='#' id='signin' class='signin'>Already have an account? Sign in here!</a>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>