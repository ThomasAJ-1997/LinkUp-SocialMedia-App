<?php include("includes/header.php");
include ("classes/User.php");
include ("classes/Post.php");

if (isset($_POST['post'])) {
    $post = new Post($conn, $usernameLoggedIn);
    $post->submitPost($_POST['post_text'], 'none');
    header("location: index.php");

}
?>

<div class="user_details column">
    <a href="<?php echo $usernameLoggedIn ?>"><img src="<?php echo $user['profile_pic'] ?>"></a>
    <div class="user_details_left_right">
    <a href="<?php echo $usernameLoggedIn ?>">
    <?php echo $user['first_name'] . " " . $user['last_name'] ?>
    </a>
    <br>
    <?php echo "Posts: " . $user['num_posts']. "<br>";
    echo "Likes: " . $user["num_likes"]?>
    </div>
</div>

<div class="main_column column">
    <form class="post_form" action="index.php" method="POST">
        <textarea name="post_text" id="post_text" placeholder="Share your coding day!"></textarea>
        <input type="submit" name="post" id="post_button" value="Publish">
        <hr>
        <br>
    </form>

    <?php $user_obj = new User($conn, $usernameLoggedIn);
    echo $user_obj->getFirstAndLastName();
    ?>
</div>


</div>
</body>

</html>