<?php

include ("includes/header.php");
include './classes/User.php';
include './classes/Post.php';
?>

<div class="main_column column" id="main_column">
    <h4>Friend Requests</h4>
    <?php
    $query = mysqli_query($conn, "SELECT * FROM friend_request WHERE user_to='$userLoggedIn'");

    if(mysqli_num_rows($query) == 0) {
        echo 'You have no friend requests.';
    } else {
        while($row = mysqli_fetch_array($query)) {
            $user_from = $row['user_from'];
            $user_from_obj = new User($conn, $user_from);

            echo $user_from_obj->getFirstAndLastName() . " " . "Sent you a friend request";

            $user_from_friend_array = $user_from_obj->getFriendArray();

            if(isset($_POST['accept_request' . $user_from])) {
                $add_friend_query = mysqli_query($conn, "UPDATE users SET friend_array =CONCAT(friend_array, '$user_from,') WHERE username='$userLoggedIn' ");
                $add_friend_query = mysqli_query($conn, "UPDATE users SET friend_array =CONCAT(friend_array, '$userLoggedIn,') WHERE username='$user_from' ");

                $delete_query = mysqli_query($conn, "DELETE FROM friend_request WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
                echo "You're now friends.";
                header("location: request.php");
            }

            if(isset($_POST['ignore_request' . $user_from])) {
                $delete_query = mysqli_query($conn, "DELETE FROM friend_request WHERE user_to = '$userLoggedIn' AND user_from = '$user_from'");
                echo "Friend Request Declined.";
                header("location: request.php");
            }

            ?>
            <form action="request.php" method="POST">
                <input type="submit" name="accept_request<?php echo $user_from; ?>" id="accept_button" value="Accept">
                <input type="submit" name="ignore_request<?php echo $user_from; ?>" id="ignore_button" value="Ignore">
            </form>
            <?php
        }
    }
    ?>


</div>
