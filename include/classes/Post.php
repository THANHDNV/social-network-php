<?php
    class Post {
        private $user_obj;
        private $con;

        public function __construct($con, $user) {
            $this->con = $con;
            $this->user_obj = new User($con, $user);
        }

        public function submitPost($body, $user_to) {
            $body = strip_tags($body);
            $body = mysqli_real_escape_string($this->con, $body);
            $check_empty = preg_replace('/\s+/', '', $body);

            if ($check_empty != '') {

                $date_added = date('Y-m-d H:i:s');
                $added_by = $this->user_obj->getUsername();

                if ($user_to == $added_by) {
                    $user_to = "none";
                }

                $query = mysqli_query($this->con, "INSERT INTO posts VALUES('','$body', '$added_by', '$user_to', '$date_added', 'no', 'no', 'no')");

                $num_posts = $this->user_obj->getNumPosts();
                $num_posts++;
                $update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

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

                    $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
                    $user_row = mysqli_fetch_array($user_details_query);
                    $first_name = $user_row['first_name'];
                    $last_name = $user_row['last_name'];
                    $profile_pic = $user_row['profile_pic'];
                    
                    ?>
                    <script>
                        function toggle<?php echo $id; ?>() {
                            var element = document.getElementById("toggleComment<?php echo $id; ?>");

                            if (element.style.display == "block") {
                                element.style.display = "none";
                            } else {
                                element.style.display = "block"
                            }
                        }
                    </script>

                    <?php

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
                            </div>
                            <div id='$id' class='post_body'>
                                $body
                                <br>
                            </div>
                        </div>
                        <div class='post_comment' id='toggleComment$id' style='display: none'>
                            <iframe src='comment_frame.php?post_id=$id' id='comment$id" . "_iframe' frameborder='0'></iframe>
                        </div>
                        <hr>";
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