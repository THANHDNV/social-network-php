<?php

    require_once '../../config/config.php';
    // include_once("include/classes/User.php");
    // include_once("include/classes/Post.php");

    if (isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
    }

    if (isset($_POST['result'])) {
        if ($_POST['result'] == 'true') {
            $query = mysqli_query($con, "UPDATE posts SET deleted='yes' WHERE id='$post_id'");
        }
    }
?>