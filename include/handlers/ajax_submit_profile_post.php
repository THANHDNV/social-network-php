<?php

    require_once '../../config/config.php';
    include_once("../classes/User.php");
    include_once("../classes/Post.php");
    include_once("../classes/Notification.php");

    if (isset($_POST['post_body'])) {
        $post = new Post($con, $_POST['user_from']);
        $post->submitPost($_POST['post_body'], $_POST['user_to']);
        return 'posted';
    }

    return 'nothing'
?>