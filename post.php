<?php

    include_once('include/header.php');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

    } else {
        $id = 0;
    }
?>

<div class='user-details column'>
    <a href='#'><img src='<?php echo $user['profile_pic'] ?>'></a>

    <div class='user-details-left-right'>
        <a href="<?php echo $userLoggedIn ?>">
            <?php
                echo $user['first_name'] . ' ' . $user['last_name'];
            ?>
        </a>
        <br>
        <?php
            echo "Posts: ". $user['num_posts'] . '<br>';
            echo "Likes: ". $user['num_likes'];
        ?>
    </div>
</div>

<div class="main-column column" id='main-column'>
    <div class='post_area'>
        <?php
            $post = new Post($con, $userLoggedIn);
            $post->getPost($id);
        ?>
    </div>
</div>
</div>
</body>
</html>