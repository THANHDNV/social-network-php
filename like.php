<?php
    require_once 'config/config.php';
    include_once('include/classes/Notification.php');
    
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
    <link rel='stylesheet' type='text/css' href='assets/css/style.css'>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            background-color: white;
        }
        form {
            position: absolute;
            top: 0px;
        }
    </style>
    <title>Swiftfeed</title>
</head>
<body>
<?php
    if (isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
    }

    $get_likes = mysqli_query($con, "SELECT likes, added_by FROM posts WHERE id='$post_id'");
    $row = mysqli_fetch_array($get_likes);
    $total_likes = $row['likes'];
    $user_liked = $row['added_by'];

    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user_liked'");
    $row = mysqli_fetch_array($user_details_query);
    $total_user_likes = $row['num_likes'];

    //like button
    if (isset($_POST['like_button'])) {
        $total_likes++;
        $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
        $total_user_likes++;
        $user_likes_query = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
        $insert_user_query = mysqli_query($con, "INSERT INTO likes VALUES('','$userLoggedIn', '$post_id')");

        //insert notification
        if ($user_liked != $userLoggedIn) {
            $notification = new Notification($this->con, $userLoggedIn);
            $notification->insertNoti($post_id, $user_liked, 'like');
        }
    }

    //unlike button

    if (isset($_POST['unlike_button'])) {
        $total_likes--;
        $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
        $total_user_likes--;
        $user_likes_query = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
        $insert_user_query = mysqli_query($con, "DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");

        //insert notification
    }

    //check for previous like
    $check_query = mysqli_query($con, "SELECT * FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
    $num_row = mysqli_num_rows($check_query);
    if ($num_row > 0) {
        echo '<form action="like.php?post_id='. $post_id . '" method="POST">
            <input type="submit" class="comment_like" name="unlike_button" value="Unlike">
            <div class="like_value">
                ' . $total_likes . ' Unlikes
            </div>
        </form>';
    } else {
        echo '<form action="like.php?post_id='. $post_id . '" method="POST">
            <input type="submit" class="comment_like" name="like_button" value="Like">
            <div class="like_value">
                ' . $total_likes . ' Likes
            </div>
        </form>';
    }

?>
</body>
</html>