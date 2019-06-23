<?php
    include_once("include/header.php");
    include_once("include/classes/User.php");
    include_once("include/classes/Post.php");

    if (isset($_POST['post'])) {
        $post = new Post($con, $userLoggedIn);
        $post->submitPost($_POST['post_text'], 'none');

        $user_obj = new User($con, $userLoggedIn);
        $user = $user_obj->getUserDetail();
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

        <div class='posts_area'></div>
        <img id='loading' src='assets/images/icons/loading.gif'>
    </div>

    <script>

        var userLoggedIn = '<?php echo $userLoggedIn ?>'

        $(document).ready(function() {
            $('#loading').show();

            $.ajax({
                url: "include/handlers/ajax_load_posts.php",
                method: "POST",
                data: "page=1&userLoggedIn=" + userLoggedIn,
                cache: false,
                success: function(data) {
                    $('#loading').hide();
                    $('.posts_area').html(data)
                }
            })
        })

        $(window).scroll(function() {
            var height = $('.posts_area').height();
            var scroll_top = $(this).scrollTop();
            var page = $('.posts_area').find('.nextPage').val();
            var noMorePosts = $('.posts_area').find('.noMorePosts').val();

            if ((document.body.scrollHeight == Math.ceil(document.body.scrollTop + window.innerHeight)) && noMorePosts == 'false') {
                $('#loading').show();
                var ajaxReq = $.ajax({
                    url: "include/handlers/ajax_load_posts.php",
                    method: "POST",
                    data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
                    cache: false,
                    success: function(response) {
                        $('.posts_area').find('.nextPage').remove()
                        $('.posts_area').find('.noMorePosts').remove()

                        $('#loading').hide();
                        $('.posts_area').append(response)
                    }
                })
            }

            return false;
        })
    </script>
</div>
</body>
</html>