<?php

    include('../../config/config.php');
    include('../classes/User.php');
    include('../classes/Post.php');

    $limit = 10;
    $post = new Post($con, $_REQUEST['userLoggedIn']);
    return $post->loadPostsFriends($_REQUEST, $limit);

?>