<?php

global $conn;
include 'DB_connection.php';
include 'classes/User.php';
include 'classes/Post.php';

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$userLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);

} else {
    header("location: register.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>

    <!-- CSS  -->
    <link rel="stylesheet" type="text/css" href="assets/css/header_style.css">
</head>
<body>

<style type="text/css">
    * {
        font-size: 14px;
        font-family: "Madimi One", sans-serif;
    }

</style>

<script>
function toggle() {
    const element = document.getElementById("comment_section");

    if(element.style.display === "block")
        element.style.display = "none";
    else
        element.style.display = "block";

}
</script>

<?php
// Get post ID.
if(isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
}

$user_query = mysqli_query($conn, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
$row = mysqli_fetch_array($user_query);

$posted_to = $row['added_by'];

if (isset($_POST['postComment' . $post_id])) {
    $post_body = $_POST['post_body'];
    $post_body = mysqli_escape_string($conn, $post_body);
    // Escape string: escapes special characters in a string for use in query.
    $date_time_present = Date("Y-m-d H:i:s");
    $insert_post = mysqli_query($conn, "INSERT INTO comments VALUES(id, '$post_body', '$userLoggedIn', '$posted_to', '$date_time_present', 'no', '$post_id')");
    echo "<p>Comment posted. </p>";
}
?>

<form action="comment_section.php?post_id=<?php echo $post_id; ?>" class="comment_form" id="comment_section"
      name="postComment<?php echo $post_id; ?>" method="POST">
    <textarea name="post_body"></textarea>
    <input type="submit" name="postComment<?php echo $post_id; ?>" value="Post">
</form>

<!--Load the comments-->
<?php
$get_comments = mysqli_query($conn, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
$count = mysqli_num_rows($get_comments);

if ($count != 0) {
    while($comment = mysqli_fetch_array($get_comments
    )) {
        $comment_body = $comment['post_body'];
        $posted_to = $comment['posted_to'];
        $posted_by = $comment['posted_by'];
        $date_added = $comment['date_added'];
        $removed = $comment['removed'];

        $date_time_now = date("Y-m-d H:i:s");
        $start_date = new DateTime($date_added); //Time of post
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

        $user_obj = new user($conn, $posted_by);

        ?>
        <div class="comment_section">
            <a href="<?php echo $posted_by ?>" target="_parent"><img src="<?php echo $user_obj->getProfile(); ?>" title="<?php echo $posted_by; ?>" style="float: left;" height="30px"></a>
            <a href="<?php echo $posted_by; ?>" target="_parent">
                <span><?php echo $user_obj->getFirstAndLastName(); ?></span></a>
            &nbsp;  &nbsp;  &nbsp;  &nbsp;
            <?php echo $time_message . "<br>" . $comment_body; ?>
            <hr>
        </div>

        <?php
    }
}
else {
    echo "<p class='center-comment'><br><br>Be the first to comment.</p>";
}
?>



</body>
</html>

