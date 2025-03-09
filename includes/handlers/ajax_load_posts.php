<?php

  include("../../DB_connection.php");
  include("../../classes/User.php");
  include("../../classes/Post.php");

  $limit = 10; //Number of posts to be loaded per call

  $posts = new Post($conn, $_REQUEST['userLoggedIn']);
  $posts -> loadPostsForFriends($_REQUEST, $limit);
?>