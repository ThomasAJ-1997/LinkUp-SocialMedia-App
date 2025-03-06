<?php

global $conn;
include './DB_connection.php';

if(isset($_SESSION['username'])){
    $usernameLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$usernameLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);
} else {
    header("location: register.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to LinkUp</title>

<!--  JavaScript  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./assets/js/bootstrap.js"></script>

<!-- CSS  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/header_style.css">
</head>

<body>

<div class="navigation_banner">
    <div class="logo">
    <a href="index.php">LinkUp</a>
    </div>

    <nav>
        <a href="#" class="welcome-message"><?php echo $user['first_name'] ?> </a>
        <a href="index.php"><i class="fa fa-home fs-1" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-comment fs-1" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-bell fs-1" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-users fs-1" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-cogs fs-1" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-sign-out fs-1" aria-hidden="true"></i></a>
    </nav>
</div>
