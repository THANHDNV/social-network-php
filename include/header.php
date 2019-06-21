<?php
    require_once 'config/config.php';
    if (isset($_SESSION['username'])) {
        $userLoggedIn = $_SESSION['username'];
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src='assets/js/bootstrap.bundle.min.js'></script>
    
    <title>Welcome to Swiftfeed</title>
</head>
<body>
    <div class='top-bar'>
        <div class='logo'>
            <a href='index.php'>Swiftfeed</a>
        </div>

        <nav>

        </nav>
    </div>