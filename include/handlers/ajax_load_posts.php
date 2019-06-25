<?php

    include_once('../../config/config.php');
    include_once('../classes/User.php');
    include_once('../classes/Post.php');

    $limit = 10;
    $post = new Post($con, $_REQUEST['userLoggedIn']);
    return $post->loadPostsFriends($_REQUEST, $limit);

?>