<?php include("includes/header.php");
include ("classes/User.php");
include ("classes/Post.php");

if (isset($_GET['profile_username'])) {
    $username = $_GET['profile_username'];

    $username_details_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user_array = mysqli_fetch_array($username_details_query);

    $num_friends = (substr_count($user_array['friend_array'], ","))
        - 1;
}

if (isset($_POST['remove_friend'])) {
    $user = new User($conn, $userLoggedIn);
    $user->removeFriend($username);
}

if (isset($_POST['add_friend'])) {
    $user = new User($conn, $userLoggedIn);
    $user->requestFriend($username);
}

if (isset($_POST['respond_request'])) {
    header("Location: requests.php");
}


?>
<style>
.wrapper {
    margin-left: 0;
    padding-left: 0;

}
</style>

<div class="profile_left_section">
    <img src="<?php echo $user_array['profile_pic'] ?>">

    <div class="profile_information">
        <p><?php echo "Posts: ". $user_array['num_posts']; ?></p>
        <p><?php echo "Likes: ". $user_array['num_likes']; ?></p>
        <p><?php echo "Friends: ". $num_friends; ?></p>
    </div>

    <form action="<?php echo $username ?>" method="POST">
        <?php
        $profile_user_obj = new User($conn, $username);
        if ($profile_user_obj->isClosed()) {
            header("Location: user_closed.php");
        }

        $logged_in_user_obj = new User($conn, $userLoggedIn);

        if($userLoggedIn != $username) {
            if ($logged_in_user_obj->isFriend($username)) {
                echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
            }
            else if($logged_in_user_obj->receiveRequest($username)){
                echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
            }
            else if($logged_in_user_obj->sendRequest($username)){
                echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
            }
            else {
                echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';
            }
            }

               ?>
        <input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post Something">
    </form>

</div>

<div class="main_column column">
    <?php echo $username?>



</div>

<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Post Something</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Post something to the user's profile and newsfeed for your friends to see!</p>
                <form class="profile_post" action="" method="POST">
                <div class="form-group">
                    <textarea class="form-control" name="post_body"></textarea>
                    <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
                    <input type="hidden" name="user_to" value="<?php echo $username ?>">
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
            </div>
        </div>
    </div>
</div>

</div>
</body>

</html>