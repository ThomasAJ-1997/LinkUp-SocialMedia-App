<?php
class User {
    private $user;
    private $conn;

    public function __construct($conn, $user) {
        if(!$user) {
            exit("user variable is null");
        }
        // Constructor is called when an object of the user class is created.
        $this->conn = $conn;
        $user_details_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
        $this->user = mysqli_fetch_array($user_details_query);

        if(!$this->user) {
            exit("this->user variable is null. No results found for username: " . $user . "<br>");
        }
    }
    //$user_obj = new User($conn, "Thomas Jones")
    public function getUsername() {
        return $this->user['username'];
    }

    public function getNumPosts() {
        return $this->user['num_posts'];
    }

    public function getFirstAndLastName() {
        $user_fname = $this->user['first_name'];
        $user_lname = $this->user['last_name'];

        return $user_fname . " " . $user_lname;
    }
}
