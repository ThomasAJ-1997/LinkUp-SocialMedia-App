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


            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_query = mysqli_query($this->conn, "UPDATE users SET num_posts='$num_posts' WHERE username = '$date_added'");


            }
        }
}