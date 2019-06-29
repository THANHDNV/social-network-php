<?php
    include_once('Notification.php');
    class Post {
        private $user_obj;
        private $con;

        public function __construct($con, $user) {
            $this->con = $con;
            $this->user_obj = new User($con, $user);
        }

        public function getPost($post_id) {
            $userLoggedIn = $this->user_obj->getUsername();

            $opened_query = mysqli_query($this->con, "UPDATE notifications SET opened='yes' user_to='$userLoggedIn' AND link LIKE '%=$post_id'");

            $str = '';
            $data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' AND id='$post_id' ORDER BY id desc");

            if (mysqli_num_rows($data_query) > 0) { 

                $row = mysqli_fetch_array($data_query);
                $id = $row['id'];
                $body = $row['body'];
                $added_by = $row['added_by'];
                $date_time = $row['date'];

                if($row['user_to'] == 'none') {
                    $user_to = '';
                } else {
                    $user_to_obj = new User($this->con, $row['user_to']);
                    $user_to_name = $user_to_obj->getFirstAndLastName();
                    $user_to = ' to <a href="' . $row['user_to'] . '">' . $user_to_name . '</a>';
                }

                $added_by_obj = new User($this->con, $added_by);
                if ($added_by_obj->isClosed()) {
                    return;
                }

                if (!$added_by_obj->isFriend($userLoggedIn)) {
                    echo "You cannot see this post because you are not friends with this user.";
                    return;
                }

                if ($userLoggedIn == $added_by) {
                    $delete_button = '<button class="delete_button btn-danger" id="post' . $id . '">X</button>';
                } else {
                    $delete_button = '';
                }

                $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
                $user_row = mysqli_fetch_array($user_details_query);
                $first_name = $user_row['first_name'];
                $last_name = $user_row['last_name'];
                $profile_pic = $user_row['profile_pic'];
                
                ?>
                <script>
                    function toggle<?php echo $id; ?>() {
                        var target = $(event.target)

                        if (target.is("a") || target.is("button")) {
                            return;
                        }

                        var element = document.getElementById("toggleComment<?php echo $id; ?>");

                        if (element.style.display == "block") {
                            element.style.display = "none";
                        } else {
                            element.style.display = "block"
                        }
                    }
                </script>

                <?php

                $comment_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
                $comment_check_num = mysqli_num_rows($comment_check);

                $date_time_now = date('Y-m-d H:i:s');
                $start_date = new DateTime($date_time);
                $end_date = new DateTime($date_time_now);

                $interval = $start_date->diff($end_date);

                if ($interval->y >= 1) {
                    if ($interval == 1) {
                        $time_message = $interval->y . 'year ago';
                    } else {
                        $time_message = $interval->y . 'years ago';
                    }
                } else if ($interval->m >=1) {
                    if ($interval->d == 0) {
                        $days = ' ago';
                    } else if ($interval->d == 1) {
                        $days = ' ' . $interval->d . ' day ago';
                    } else {
                        $days = ' ' . $interval->d . ' days ago';
                    }

                    if ($interval->m == 1) {
                        $time_message = $interval->m . ' month' . $days;
                    } else {
                        $time_message = $interval->m . ' months' . $days;
                    }
                } else if ($interval->d >= 1) {
                    if ($interval->d == 1) {
                        $time_message = 'Yesterday';
                    } else {
                        $time_message = $interval->d . ' days ago';
                    }
                } else if ($interval->h >= 1) {
                    if ($interval->h == 1) {
                        $time_message = $interval->h . ' hour ago';
                    } else {
                        $time_message = $interval->h . ' hours ago';
                    }
                } else if ($interval->i >= 1) {
                    if ($interval->i == 1) {
                        $time_message = $interval->i . ' minute ago';
                    } else {
                        $time_message = $interval->i . ' minutes ago';
                    }
                } else {
                    if ($interval->s < 30) {
                        $time_message = 'Just now';
                    } else {
                        $time_message = $interval->s . ' seconds ago';
                    }
                }
                $str .= "<div class='status_post' id='post-" . $page . "-" . ($count-1) . "' onClick='javascript:toggle$id()'>
                        <div class='post_profile_pic'>
                            <img src='$profile_pic' width='50'>
                        </div>
                        
                        <div class='posted_by' style='color: #acacac'>
                            <a href='$added_by'>$first_name $last_name</a>$user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
                            $delete_button
                        </div>
                        <div id='$id' class='post_body'>
                            $body
                            <br>
                            <br>
                            <br>
                        </div>

                        <div class='newsfeedPostOptions'>
                            Comments($comment_check_num)&nbsp;&nbsp;&nbsp;
                            <iframe src='like.php?post_id=$id' scrolling='no'></iframe>
                        </div>
                    </div>
                    <div class='post_comment' id='toggleComment$id' style='display: none'>
                        <iframe src='comment_frame.php?post_id=$id' id='comment$id" . "_frame' class='comment_frame' frameborder='0'></iframe>
                    </div>
                    <hr>";
                ?>
                <script>

                $(document).ready(function() {
                    $('#post<?php echo $id; ?>').on('click', function() {
                        console.log(<?php echo $id; ?>)
                        bootbox.confirm("Are you sure you want to delete this post?", function(result) {
                            $.post("include/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result: result});

                            if (result) {
                                location.reload();
                            }
                        })
                    })
                })

                </script>

                <?php
            } else {
                $str = "<p>No post found! If you clicked a link, it may be broken.</p>";
            }
            
            echo $str;
        }

        public function submitPost($body, $user_to, $imageName) {
            $body = strip_tags($body);
            $body = mysqli_real_escape_string($this->con, $body);
            $check_empty = preg_replace('/\s+/', '', $body);

            if ($check_empty != '') {

                $body_array = preg_split("/\s+/", $body);

                foreach($body_array as $key=>$value) {
                    if (strpos($value, "www.youtube.com/watch?v=") !== false) {
                        $link = preg_split("!&!", $value);
                        $value = preg_replace("!watch\?v=!", "embed/", $link[0]);
                        $value = '<br><iframe src="' . $value . '" width="420" height="315" ></iframe><br>';
                        $body_array[$key] = $value;
                    }
                }

                $body = implode(' ', $body_array);

                $date_added = date('Y-m-d H:i:s');
                $added_by = $this->user_obj->getUsername();

                if ($user_to == $added_by) {
                    $user_to = "none";
                }

                $query = mysqli_query($this->con, "INSERT INTO posts VALUES('','$body', '$added_by', '$user_to', '$date_added', 'no', 'no', 'no', '$imageName')");
                $return_id = mysqli_insert_id($query);

                if ($user_to != 'none') {
                    $notification = new Notification($this->con, $added_by);
                    $notification->insertNoti($return_id, $user_to, 'profile_post');
                }

                $num_posts = $this->user_obj->getNumPosts();
                $num_posts++;
                $update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
                
                $stopWords = "a about above across after again against all almost alone along already
                also although always among am an and another any anybody anyone anything anywhere are 
                area areas around as ask asked asking asks at away b back backed backing backs be became
                because become becomes been before began behind being beings best better between big 
                both but by c came can cannot case cases certain certainly clear clearly come could
                d did differ different differently do does done down down downed downing downs during
                e each early either end ended ending ends enough even evenly ever every everybody
                everyone everything everywhere f face faces fact facts far felt few find finds first
                for four from full fully further furthered furthering furthers g gave general generally
                get gets give given gives go going good goods got great greater greatest group grouped
                grouping groups h had has have having he her here herself high high high higher
                highest him himself his how however i im if important in interest interested interesting
                interests into is it its itself j just k keep keeps kind knew know known knows
                large largely last later latest least less let lets like likely long longer
                longest m made make making man many may me member members men might more most
                mostly mr mrs much must my myself n necessary need needed needing needs never
                new new newer newest next no nobody non noone not nothing now nowhere number
                numbers o of off often old older oldest on once one only open opened opening
                opens or order ordered ordering orders other others our out over p part parted
                parting parts per perhaps place places point pointed pointing points possible
                present presented presenting presents problem problems put puts q quite r
                rather really right right room rooms s said same saw say says second seconds
                see seem seemed seeming seems sees several shall she should show showed
                showing shows side sides since small smaller smallest so some somebody
                someone something somewhere state states still still such sure t take
                taken than that the their them then there therefore these they thing
                things think thinks this those though thought thoughts three through
                thus to today together too took toward turn turned turning turns two
                u under until up upon us use used uses v very w want wanted wanting
                wants was way ways we well wells went were what when where whether
                which while who whole whose why will with within without work
                worked working works would x y year years yet you young younger
                youngest your yours z lol haha omg hey ill iframe wonder else like 
                hate sleepy reason for some little yes bye choose";

                $stopWords = preg_split('/[\s,]+/', $stopWords);

                $no_punctuation = preg_replace('/[^a-zA-Z0-9]+/', '', $body);
                if(strpos($no_punctuation, "height") === false &&strpos($no_punctuation, "weight") === false && strpos($no_punctuation, "http") === false) {
                    $no_punctuation = preg_split('/[\s,]+/', $no_punctuation);

                    foreach($stopWords as $value) {
                        foreach($no_punctuation as $key=>$value2) {
                            if (strtolower($value) == strtolower($value2)) {
                                $no_punctuation[$key] = '';
                            }
                        }
                    }

                    foreach($no_punctuation as $value) {
                        $this->calculateTrend(ucfirst($value));
                    }
                }
            }
        }

        public function calculateTrend($term) {
            if ($term != '') {
                $query = mysqli_query($this->con, "SELECT * FROM trends WHERE title='$term'");
                if (mysqli_num_rows($query) === 0) {
                    $insert_query = mysqli_query($this->con, "INSERT INTO trends VALUES('$term', '1')");
                } else {
                    $update_query = mysqli_query($this->con,"UPDATE trends SET hits=hits+1 WHERE title='$term'");
                }
            }
        }

        public function loadPostsFriends($data, $limit) {

            $page = $data['page'];
            $userLoggedIn = $this->user_obj->getUsername();

            if ($page == 1) {
                $start = 0;
            } else {
                $start = ($page - 1) * $limit;
            }

            $str = '';
            $data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id desc");

            if (mysqli_num_rows($data_query) > 0) {

                $num_iteration = 0;
                $count = 1;

                while ($row = mysqli_fetch_array($data_query)) {
                    $id = $row['id'];
                    $body = $row['body'];
                    $added_by = $row['added_by'];
                    $date_time = $row['date'];
                    $image = $row['image'];
    
                    if($row['user_to'] == 'none') {
                        $user_to = '';
                    } else {
                        $user_to_obj = new User($this->con, $row['user_to']);
                        $user_to_name = $user_to_obj->getFirstAndLastName();
                        $user_to = ' to <a href="' . $row['user_to'] . '">' . $user_to_name . '</a>';
                    }
    
                    $added_by_obj = new User($this->con, $added_by);
                    if ($added_by_obj->isClosed()) {
                        continue;
                    }

                    if (!$added_by_obj->isFriend($userLoggedIn)) {
                        continue;
                    }

                    if ($num_iteration++ < $start) {
                        continue;
                    }

                    if ($count > $limit) {
                        break;
                    } else {
                        $count++;
                    }

                    if ($userLoggedIn == $added_by) {
                        $delete_button = '<button class="delete_button btn-danger" id="post' . $id . '">X</button>';
                    } else {
                        $delete_button = '';
                    }

                    $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
                    $user_row = mysqli_fetch_array($user_details_query);
                    $first_name = $user_row['first_name'];
                    $last_name = $user_row['last_name'];
                    $profile_pic = $user_row['profile_pic'];
                    
                    ?>
                    <script>
                        function toggle<?php echo $id; ?>() {
                            var target = $(event.target)

                            if (target.is("a") || target.is("button")) {
                                return;
                            }

                            var element = document.getElementById("toggleComment<?php echo $id; ?>");

                            if (element.style.display == "block") {
                                element.style.display = "none";
                            } else {
                                element.style.display = "block"
                            }
                        }
                    </script>

                    <?php

                    $comment_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
                    $comment_check_num = mysqli_num_rows($comment_check);

                    $date_time_now = date('Y-m-d H:i:s');
                    $start_date = new DateTime($date_time);
                    $end_date = new DateTime($date_time_now);
    
                    $interval = $start_date->diff($end_date);
    
                    if ($interval->y >= 1) {
                        if ($interval == 1) {
                            $time_message = $interval->y . 'year ago';
                        } else {
                            $time_message = $interval->y . 'years ago';
                        }
                    } else if ($interval->m >=1) {
                        if ($interval->d == 0) {
                            $days = ' ago';
                        } else if ($interval->d == 1) {
                            $days = ' ' . $interval->d . ' day ago';
                        } else {
                            $days = ' ' . $interval->d . ' days ago';
                        }
    
                        if ($interval->m == 1) {
                            $time_message = $interval->m . ' month' . $days;
                        } else {
                            $time_message = $interval->m . ' months' . $days;
                        }
                    } else if ($interval->d >= 1) {
                        if ($interval->d == 1) {
                            $time_message = 'Yesterday';
                        } else {
                            $time_message = $interval->d . ' days ago';
                        }
                    } else if ($interval->h >= 1) {
                        if ($interval->h == 1) {
                            $time_message = $interval->h . ' hour ago';
                        } else {
                            $time_message = $interval->h . ' hours ago';
                        }
                    } else if ($interval->i >= 1) {
                        if ($interval->i == 1) {
                            $time_message = $interval->i . ' minute ago';
                        } else {
                            $time_message = $interval->i . ' minutes ago';
                        }
                    } else {
                        if ($interval->s < 30) {
                            $time_message = 'Just now';
                        } else {
                            $time_message = $interval->s . ' seconds ago';
                        }
                    }

                    if ($image != "") {
                        $imageDiv = "<div class='postedImage'><img src='" . $imageName . "'></div>";
                    } else {
                        $imageDiv = "";
                    }

                    $str .= "<div class='status_post' id='post-" . $page . "-" . ($count-1) . "' onClick='javascript:toggle$id()'>
                            <div class='post_profile_pic'>
                                <img src='$profile_pic' width='50'>
                            </div>
                            
                            <div class='posted_by' style='color: #acacac'>
                                <a href='$added_by'>$first_name $last_name</a>$user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
                                $delete_button
                            </div>
                            <div id='$id' class='post_body'>
                                $body
                                <br>
                                $imageDiv
                                <br>
                                <br>
                            </div>

                            <div class='newsfeedPostOptions'>
                                Comments($comment_check_num)&nbsp;&nbsp;&nbsp;
                                <iframe src='like.php?post_id=$id' scrolling='no'></iframe>
                            </div>
                        </div>
                        <div class='post_comment' id='toggleComment$id' style='display: none'>
                            <iframe src='comment_frame.php?post_id=$id' id='comment$id" . "_frame' class='comment_frame' frameborder='0'></iframe>
                        </div>
                        <hr>";
                }
                ?>
                <script>

                $(document).ready(function() {
                    $('#post<?php echo $id; ?>').on('click', function() {
                        console.log(<?php echo $id; ?>)
                        bootbox.confirm("Are you sure you want to delete this post?", function(result) {
                            $.post("include/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result: result});

                            if (result) {
                                location.reload();
                            }
                        })
                    })
                })

                </script>

                <?php
            }

            if ($count > $limit) {
                $str .= '<input type="hidden" class="nextPage" value="' . ($page +  1) . '">
                <input type="hidden" class="noMorePosts" value="false">';
            } else {
                $str .= '<input type="hidden" class="noMorePosts" value="true"><p style="text-align: center">No more post to show</p>';
            }
            
            
            echo $str;
        }

        public function loadProfilePosts($data, $limit) {

            $page = $data['page'];
            $profileUser = $data['profileUsername'];

            $userLoggedIn = $this->user_obj->getUsername();

            if ($page == 1) {
                $start = 0;
            } else {
                $start = ($page - 1) * $limit;
            }

            $str = '';
            $data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' AND ((added_by='$profileUser' AND user_to='none') OR user_to='$profileUser') ORDER BY id desc");

            if (mysqli_num_rows($data_query) > 0) {

                $num_iteration = 0;
                $count = 1;

                while ($row = mysqli_fetch_array($data_query)) {
                    $id = $row['id'];
                    $body = $row['body'];
                    $added_by = $row['added_by'];
                    $date_time = $row['date'];

                    if ($num_iteration++ < $start) {
                        continue;
                    }

                    if ($count > $limit) {
                        break;
                    } else {
                        $count++;
                    }

                    if ($userLoggedIn == $added_by) {
                        $delete_button = '<button class="delete_button btn-danger" id="post' . $id . '">X</button>';
                    } else {
                        $delete_button = '';
                    }

                    $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
                    $user_row = mysqli_fetch_array($user_details_query);
                    $first_name = $user_row['first_name'];
                    $last_name = $user_row['last_name'];
                    $profile_pic = $user_row['profile_pic'];
                    
                    ?>
                    <script>
                        function toggle<?php echo $id; ?>() {
                            var target = $(event.target)

                            if (target.is("a") || target.is("button")) {
                                return;
                            }

                            var element = document.getElementById("toggleComment<?php echo $id; ?>");

                            if (element.style.display == "block") {
                                element.style.display = "none";
                            } else {
                                element.style.display = "block"
                            }
                        }
                    </script>

                    <?php

                    $comment_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
                    $comment_check_num = mysqli_num_rows($comment_check);

                    $date_time_now = date('Y-m-d H:i:s');
                    $start_date = new DateTime($date_time);
                    $end_date = new DateTime($date_time_now);
    
                    $interval = $start_date->diff($end_date);
    
                    if ($interval->y >= 1) {
                        if ($interval == 1) {
                            $time_message = $interval->y . 'year ago';
                        } else {
                            $time_message = $interval->y . 'years ago';
                        }
                    } else if ($interval->m >=1) {
                        if ($interval->d == 0) {
                            $days = ' ago';
                        } else if ($interval->d == 1) {
                            $days = ' ' . $interval->d . ' day ago';
                        } else {
                            $days = ' ' . $interval->d . ' days ago';
                        }
    
                        if ($interval->m == 1) {
                            $time_message = $interval->m . ' month' . $days;
                        } else {
                            $time_message = $interval->m . ' months' . $days;
                        }
                    } else if ($interval->d >= 1) {
                        if ($interval->d == 1) {
                            $time_message = 'Yesterday';
                        } else {
                            $time_message = $interval->d . ' days ago';
                        }
                    } else if ($interval->h >= 1) {
                        if ($interval->h == 1) {
                            $time_message = $interval->h . ' hour ago';
                        } else {
                            $time_message = $interval->h . ' hours ago';
                        }
                    } else if ($interval->i >= 1) {
                        if ($interval->i == 1) {
                            $time_message = $interval->i . ' minute ago';
                        } else {
                            $time_message = $interval->i . ' minutes ago';
                        }
                    } else {
                        if ($interval->s < 30) {
                            $time_message = 'Just now';
                        } else {
                            $time_message = $interval->s . ' seconds ago';
                        }
                    }
                    $str .= "<div class='status_post' id='post-" . $page . "-" . ($count-1) . "' onClick='javascript:toggle$id()'>
                            <div class='post_profile_pic'>
                                <img src='$profile_pic' width='50'>
                            </div>
                            
                            <div class='posted_by' style='color: #acacac'>
                                <a href='$added_by'>$first_name $last_name</a> &nbsp;&nbsp;&nbsp;&nbsp;$time_message
                                $delete_button
                            </div>
                            <div id='$id' class='post_body'>
                                $body
                                <br>
                                <br>
                                <br>
                            </div>

                            <div class='newsfeedPostOptions'>
                                Comments($comment_check_num)&nbsp;&nbsp;&nbsp;
                                <iframe src='like.php?post_id=$id' scrolling='no'></iframe>
                            </div>
                        </div>
                        <div class='post_comment' id='toggleComment$id' style='display: none'>
                            <iframe src='comment_frame.php?post_id=$id' id='comment$id" . "_frame' class='comment_frame' frameborder='0'></iframe>
                        </div>
                        <hr>";
                        ?>
                        <script>
        
                        $(document).ready(function() {
                            $('#post<?php echo $id; ?>').on('click', function() {
                                console.log(<?php echo $id; ?>)
                                bootbox.confirm("Are you sure you want to delete this post?", function(result) {
                                    $.post("include/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result: result});
        
                                    if (result) {
                                        location.reload();
                                    }
                                })
                            })
                        })
        
                        </script>
        
                        <?php
                }
            }

            if ($count > $limit) {
                $str .= '<input type="hidden" class="nextPage" value="' . ($page +  1) . '">
                <input type="hidden" class="noMorePosts" value="false">';
            } else {
                $str .= '<input type="hidden" class="noMorePosts" value="true"><p style="text-align: center">No more post to show</p>';
            }
            
            
            echo $str;            
        }
    }
?>