<?php
class User {
    private $user;
    private $conn;

    public function __construct($conn, $user) {
        // Constructor is called when an object of the user class is created.
        $this->conn = $conn;
        $user_details_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
        $this->user = mysqli_fetch_array($user_details_query);

    }
    //$user_obj = new User($conn, "Thomas Jones")
    public function getUsername() {
        return $this->user['username'];
    }

    public function getNumPosts()
    {
        $username = $this->user['username'];
        $query = mysqli_query($this->conn, "SELECT num_posts FROM users WHERE username = '$username'");
        $row = mysqli_fetch_array($query);
        return $row['num_posts'];
    }
    public function getFirstAndLastName() {
        $user_fname = $this->user['first_name'];
        $user_lname = $this->user['last_name'];

        return $user_fname . " " . $user_lname;
    }

    public function getProfile() {
        return $this->user['profile_pic'];
    }

    public function isClosed() {
        $username = $this->user['username'];
        $query = mysqli_query($this->conn, "SELECT user_closed FROM users WHERE username='$username'");
        $row = mysqli_fetch_array($query);

        if($row['user_closed'] == 'yes') {
            return true;
        } else {
            return false;
        }
    }

    public function isFriend($check_friend_status)
    {
        $username_check = "," . $check_friend_status . ",";

        if((strstr($this->user['friend_array'], $username_check) ||
        $check_friend_status == $this->user['username'])) {
            return true;
        } else {
            return false;
        }
    }

    public function receiveRequest($user_from) {
        $user_to = $this->user['username'];
        $check_request_query = mysqli_query($this->conn, "SELECT * FROM friend_request WHERE user_to = '$user_to' AND user_from = '$user_from'");;
        if(mysqli_num_rows($check_request_query) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function sendRequest($user_to)
    {
        $user_from = $this->user['username'];
        $check_request_query = mysqli_query($this->conn,
            "SELECT * FROM friend_request WHERE user_to='$user_to' AND user_from='$user_from'");
        if (mysqli_num_rows($check_request_query) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function removeFriend($user_to_remove) {
        $logged_in_user = $this->user['username'];
        $query = mysqli_query($this->conn, "SELECT friend_array FROM users WHERE username='$user_to_remove'");
        $row = mysqli_fetch_array($query);
        $friend_array_for_username = $row['friend_array'];

        $new_friend_array = str_replace($user_to_remove . ",", "", $this->user['friend_array']);
        $remove_friend = mysqli_query($this->conn, "UPDATE users SET friend_array='$new_friend_array' WHERE username='$logged_in_user'");

        $new_friend_array = str_replace($this->user['username'] . ",", "", $friend_array_for_username);
        $remove_friend = mysqli_query($this->conn, "UPDATE users SET friend_array='$new_friend_array' WHERE username='$user_to_remove'");



    }

    public function requestFriend($user_to) {
        $user_from = $this->user['username'];
        $query = mysqli_query($this->conn, "INSERT INTO friend_request  VALUES(id, '$user_to', '$user_from')");
    }
}
