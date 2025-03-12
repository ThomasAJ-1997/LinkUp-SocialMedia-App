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

if(isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
}

$get_likes = mysqli_query($conn, "SELECT likes, added_by FROM posts WHERE id='$post_id'");
$row = mysqli_fetch_array($get_likes);
$total_likes = $row['likes'];
$user_liked = $row['added_by'];

$user_details_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$user_liked'");
$row = mysqli_fetch_array($user_details_query);
$total_user_likes = $row['num_likes'];

// Like Button
if (isset($_POST['like_button'])) {
    $total_likes++;
    $query = mysqli_query($conn, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
    $total_user_likes++;
    $user_likes = mysqli_query($conn, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
    $insert_user = mysqli_query($conn, "INSERT INTO likes VALUES(id, '$userLoggedIn', '$post_id') ");

    // Insert like notification

}

// Unlike Button
// Like Button
if (isset($_POST['unlike_button'])) {
    $total_likes--;
    $query = mysqli_query($conn, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
    $total_user_likes--;
    $user_likes = mysqli_query($conn, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
    $insert_user = mysqli_query($conn, "DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");

}


// Check previous likes
$check_query = mysqli_query($conn, "SELECT * FROM likes WHERE username='$userLoggedIn'AND post_id='$post_id'");
$num_rows = mysqli_num_rows($check_query);
$like_text = '';

if ($total_likes == 0)
    $like_text = ' Be the first to like';
else if ($total_likes == 1)
    $like_text = ' Like';
else
    $like_text = ' Likes';

if ($num_rows > 0) {
    echo '<form action="like.php?post_id=' . $post_id . '" method="POST">
    <input type="submit" class="comment_like" name="unlike_button"  value="Unlike">
    <div class="like_value">' .$total_likes . $like_text;

} else {
    echo '<form action="like.php?post_id=' . $post_id . '" method="POST">
    <input type="submit" class="comment_like" name="like_button"  value="Like">
    <div class="like_value">' .$total_likes . $like_text;
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
    body {
        background-color: #fff;
    }

    form {
        position: absolute;
        top: 0;
    }
</style>



</body>
</html>

