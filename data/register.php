<?php
global $conn;


// Declaring Variables to prevent errors
$fname = '';
$lname = '';
$email = '';
$email_confirm = '';
$password = '';
$password_confirm = '';
$date = '';
$error_array = array();

if (isset($_POST['register_button'])) {
    // Registration form values
    // Strip tags removes any HTML tags.
    // str_replace will replace a space with no space.
    // First letter is uppercase while the rest is lower case.

    $fname = strip_tags($_POST['reg_fname']);
    $fname = str_replace(' ', '', $fname);
    $fname = ucfirst(strtolower($fname));
    $_SESSION['reg_fname'] = $fname;

    $lname = strip_tags($_POST['reg_lname']);
    $lname = str_replace(' ', '', $lname);
    $lname = ucfirst(strtolower($lname));
    $_SESSION['reg_lname'] = $lname;

    $email = strip_tags($_POST['reg_email']);
    $email = str_replace(' ', '', $email);
    $email = ucfirst(strtolower($email));
    $_SESSION['reg_email'] = $email;

    $email_confirm = strip_tags($_POST['reg_email_confirm']);
    $email_confirm = str_replace(' ', '', $email_confirm);
    $email_confirm = ucfirst(strtolower($email_confirm));
    $_SESSION['reg_email_confirm'] = $email_confirm;

    $password = strip_tags($_POST['reg_password']);
    $password_confirm = strip_tags($_POST['reg_password_confirm']);
    $date = date("Y-m-d");

    if ($email == $email_confirm) {
        // Check if email is in valid format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            // Check if email already exists
            $email_check = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
            //Count the number of rows returned (it will contain the number of results)
            $num_rows = mysqli_num_rows($email_check);
            if ($num_rows > 0) {
                array_push($error_array, "Email already taken<br>");
            }
        } else {
            array_push($error_array, "Invalid email format<br>");
        }
    } else {
        array_push($error_array, "Email's don't match<br>");
    }


    if (strlen($fname) > 25 || strlen($fname < 2)) {
        array_push($error_array, 'First name must be between 2 and 25 characters<br>.');
    }

    if (strlen($lname) > 25 || strlen($lname < 2)) {
        array_push($error_array, 'Last name must be between 2 and 25 characters<br>');
    }

    if ($password != $password_confirm) {
        array_push($error_array, 'Your passwords do not match<br>');
    } else {
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Your password can only contain english characters or numbers<br>");
        }
    }

    if (empty($error_array)) {
        $password = md5($password); //md5 encrypts the password in the database.

        $username = strtolower($fname."_".$lname);
        $check_username_query = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        // If username exists, add number to username
        while (mysqli_num_rows($check_username_query) != 0) {
            $i++;
            $username = $username."_".$i;
            $check_username_query = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");

        }

        // Profile Picture for user
        $rand_profile = rand(1, 2);

        if ($rand_profile == 1) {
            $profile_pic = "assets/images/profile_pics/default/head_deep_blue.png";
        } else {
            if ($rand_profile == 2) {
                $profile_pic = "assets/images/profile_pics/default/head_red.png";
            }
        }

        $query = mysqli_query($conn,
            "INSERT INTO users VALUES (id, '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");
        array_push($error_array, "<span style='color: green'>Successfully registered. Please log in");
    }
}


