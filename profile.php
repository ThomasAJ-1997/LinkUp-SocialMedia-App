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

    <form action="<?php echo $username ?>">
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
        }
        ?>
    </form>

</div>

<div class="main_column column">
    <?php echo $username?>
</div>


</div>
</body>

</html>