<?php
    include_once("include/header.php");
    include_once("include/classes/User.php");
    include_once("include/classes/Post.php");

    if (isset($_POST['post'])) {
        $post = new Post($con, $userLoggedIn);
        $post->submitPost($_POST['post_text'], 'none');
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

    <div class='main-column column'>
        <form class='post-form' action='index.php' method="post">
            <textarea name='post_text' id='post-text' placeholder="Got something to say?"></textarea>
            <input type='submit' name='post' id='post-button' value='Post'>
            <hr>
        </form>

        <?php
            $user_obj = new User($con, $userLoggedIn);
            echo $user_obj->getFirstAndLastName();
        ?>
    </div>
</div>
</body>
</html>