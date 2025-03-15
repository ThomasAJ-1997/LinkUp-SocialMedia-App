<?php
class Post {

    private $user_obj;
    private $conn;

    public function __construct($conn, $user) {
        if(!$user) {
            exit("user variable is null");
        }
        // Constructor is called when an object of the user class is created.
        $this->conn = $conn;
        $this->user_obj = new User($conn, $user);

        if(!$this->user_obj) {
            exit("this->user variable is null. No results found for username: " . $user . "<br>");
        }
    }
    //$user_obj = new User($conn, "Thomas Jones")

    public function submitPost($body, $user_to) {
        $body = strip_tags($body); //removes the HTML tags
        $body = mysqli_real_escape_string($this->conn, $body);
        $body = str_replace('\r\n', '\n', $body);
        $body = nl2br($body);
        $check_empty = preg_replace('/\s*/', '', $body); // Deletes all spaces

        if($check_empty != "") {
            $date_added = date("Y-m-d H:i:s");
            $added_by = $this->user_obj->getUsername();

            if ($user_to == $added_by) {
                $user_to = 'none';
            }

            $query = mysqli_query($this->conn, "INSERT INTO posts VALUE(id, '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')");
            $returned_id = mysqli_insert_id($this->conn);

            //Update post count for user
            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_query = mysqli_query($this->conn, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

            }
        }

    /**
     * @throws DateMalformedStringException
     */
    public function loadPostsForFriends($data, $limit)
    {

        global $conn;
        $page = $data['page'];
        $userLoggedIn = $this->user_obj->getUsername();

        if ($page == 1)
            $start = 0;
        else
            $start = ($page - 1) * $limit;

        $str = ""; //String to return
        $data_query = mysqli_query($this->conn, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");
        if(mysqli_num_rows($data_query) > 0) {

            $num_iterations = 0; // Number of results checked.
            $count = 1;

            while ($row = mysqli_fetch_array($data_query)) {
                $id = $row['id'];
                $body = $row['body'];
                $added_by = $row['added_by'];
                $date_time = $row['date_added'];

                //Prepare user_to string so it can be included even if not posted to a user
                if ($row['user_to'] == "none") {
                    $user_to = "";
                } else {
                    $user_to_obj = new User($conn, $row['user_to']);
                    $user_to_name = $user_to_obj->getFirstAndLastName();
                    $user_to = "to <a href='".$row['user_to']."'>".$user_to_name."</a>";
                }

                //Check if user who posted, has their account closed
                $added_by_obj = new User($this->conn, $added_by);
                if ($added_by_obj->isClosed()) {
                    continue;
                }

                $user_logged_obj = new User($this->conn, $userLoggedIn);
                if ($user_logged_obj->isFriend($added_by)) {

                    if ($num_iterations++ < $start) {
                        continue;
                    }

                    // When 10 posts are loaded, break.
                    if ($count > $limit) {
                        break;
                    } else {
                        $count++;
                    }

                    $user_details_query = mysqli_query($this->conn,
                        "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
                    $user_row = mysqli_fetch_array($user_details_query);
                    $first_name = $user_row['first_name'];
                    $last_name = $user_row['last_name'];
                    $profile_pic = $user_row['profile_pic'];

                    ?>
                    <script>
                        function toggle<?php echo $id; ?>() {

                            const target = $(event.target);
                            if (!target.is("a")) {
                                var element = document.getElementById("toggleComment<?php echo $id; ?>");

                                if (element.style.display == "block")
                                    element.style.display = "none";
                                else
                                    element.style.display = "block";
                            }
                        }
                    </script>
                    <?php
                    $comment_num = mysqli_query($this->conn, "SELECT * FROM comments WHERE post_id='$id'");
                    $comments_checked = mysqli_num_rows($comment_num);

                    $date_time_now = date("Y-m-d H:i:s");
                    $start_date = new DateTime($date_time); //Time of post
                    $end_date = new DateTime($date_time_now); //Current time
                    $interval = $start_date->diff($end_date); //Difference between dates

                    if ($interval->y >= 1) {
                        if ($interval->y == 1) {
                            $time_message = $interval->y." year ago";
                        } //1 year ago
                        else {
                            $time_message = $interval->y." years ago";
                        } //1+ year ago
                    } else {
                        if ($interval->m >= 1) {
                            if ($interval->d == 0) {
                                $days = " ago";
                            } else {
                                if ($interval->d == 1) {
                                    $days = $interval->d." day ago";
                                } else {
                                    $days = $interval->d." days ago";
                                }
                            }


                            if ($interval->m == 1) {
                                $time_message = $interval->m." month".$days;
                            } else {
                                $time_message = $interval->m." months".$days;
                            }
                        } else {
                            if ($interval->d >= 1) {
                                if ($interval->d == 1) {
                                    $time_message = "Yesterday";
                                } else {
                                    $time_message = $interval->d." days ago";
                                }
                            } else {
                                if ($interval->h >= 1) {
                                    if ($interval->h == 1) {
                                        $time_message = $interval->h." hour ago";
                                    } else {
                                        $time_message = $interval->h." hours ago";
                                    }
                                } else {
                                    if ($interval->i >= 1) {
                                        if ($interval->i == 1) {
                                            $time_message = $interval->i." minute ago";
                                        } else {
                                            $time_message = $interval->i." minutes ago";
                                        }
                                    } else {
                                        if ($interval->s < 30) {
                                            $time_message = "Just now";
                                        } else {
                                            $time_message = $interval->s." seconds ago";
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $str .= "<div class='status_post' onclick='toggle$id()'>
								<div class='post_profile_pic'>
									<img src='$profile_pic' width='50'>
								</div>

								<div class='posted_by' style='color:#ACACAC;'>
									<a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
								</div>
								<div id='post_body'>
									$body
									<br>
									<br>
								</div>
								
								<div class='newsfeedPostOptions'>
								 Comments($comments_checked)&nbsp;&nbsp;&nbsp;
								 <div class='noScroll'>
								 <iframe src='like.php?post_id=$id' scrolling='no'></iframe>
								 </div>
								 
								 </div>

							</div>
							<div class='post_comment' id='toggleComment$id' style='display: none;'>
							<iframe src='comment_section.php?post_id=$id' class='comment_frame' id='comment_iframe'></iframe>
							</div>
							<hr>";
                } // end isFriend Function
            } // end of while loop

            if($count > $limit) {
                $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
                <input type='hidden' class='noMorePosts' value='false'>";
            } else {
                $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center'> No more posts to show </p>";
            }
        }

        echo $str;
}



}