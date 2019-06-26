<?php
    require_once 'config/config.php';
    include_once("include/classes/User.php");
    include_once("include/classes/Post.php");
    
    if (isset($_SESSION['username'])) {
        $userLoggedIn = $_SESSION['username'];
        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
        $user = mysqli_fetch_array($user_details_query);

    } else {
        header("Location: register.php");
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel='stylesheet' type='text/css' href='assets/css/bootstrap.min.css'>
    <link rel='stylesheet' type='text/css' href='assets/css/style.css'>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src='assets/js/bootstrap.bundle.min.js'></script>
    <script src="assets/js/bootbox.min.js"></script>
    <script src='assets/js/demo.js'></script>
    
    <title>Welcome to Swiftfeed</title>
</head>
<body>
    <div class='top-bar'>
        <div class='logo'>
            <a href='index.php'>Swiftfeed</a>
        </div>

        <nav>
            <a href='<?php echo $userLoggedIn ?>'>
                <?php echo $user['first_name'] ?>
            </a>
            <a href='index.php'>
                <i class="fa fa-home fa-lg"></i>
            </a>
            <a href='#'>
                <i class="fa fa-envelope fa-lg"></i>
            </a>
            <a href='#'>
                <i class="fa fa-bell-o fa-lg"></i>
            </a>
            <a href='#'>
                <i class="fa fa-users fa-lg"></i>
            </a>
            <a href='#'>
                <i class="fa fa-cog fa-lg"></i>
            </a>
            <a href='include/handlers/logout.php'>
                <i class="fa fa-sign-out fa-lg"></i>
            </a>
        </nav>
    </div>

    <div class='wrapper'>