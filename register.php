<?php
global $error_array;
include 'DB_connection.php';
include 'data/register.php';
include 'data/login.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkUp - Register</title>
</head>

<body>

<form method="POST" action="register.php">
    <input type="email" name="log_email" placeholder="hello@example.com"
    value="<?php if (isset($_SESSION['reg_fname'])) {
        echo $_SESSION['reg_fname'];
    } ?>" required>
    <br>
    <input type="password" name="log_password" placeholder="Password">
    <br>
    <input type="submit" name="login_button" value="Login">
    <?php if(in_array("Email or password was incorrect<br>", $error_array))
        echo "Email or password was incorrect<br>"?>
</form>

    <form method="POST" action="register.php">
        <input type="text" name="reg_fname" placeholder="First Name"
            value="<?php if (isset($_SESSION['reg_fname'])) {
                        echo $_SESSION['reg_fname'];
                    } ?>" required>
        <br>
        <?php if (in_array('First name must be between 2 and 25 characters<br>', $error_array)) echo 'First name must be between 2 and 25 characters<br>.' ?>

        <input type="text" name="reg_lname" placeholder="Last Name"
            value="<?php if (isset($_SESSION['reg_lname'])) {
                        echo $_SESSION['reg_lname'];
                    } ?>" required>
        <br>
        <?php if (in_array('Last name must be between 2 and 25 characters<br>.', $error_array)) echo 'Last name must be between 2 and 25 characters<br>.' ?>

        <input type="email" name="reg_email" placeholder="Email"
            value="<?php if (isset($_SESSION['reg_email'])) {
                        echo $_SESSION['reg_email'];
                    } ?>" required>
        <br>
        <input type="email" name="reg_email_confirm" placeholder="Confirm Email"
            value="<?php if (isset($_SESSION['reg_email_confirm'])) {
                        echo $_SESSION['reg_email_confirm'];
                    } ?>" required>
        <br>
        <?php if (in_array("Email already taken<br>", $error_array)) echo "Email already taken<br>";
        else if (in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>";
        else if (in_array("Email's don't match<br>", $error_array)) echo "Email's don't match<br>";
        ?>

        <input type="password" name="reg_password" placeholder="Password"
            required>
        <br>
        <input type="password" name="reg_password_confirm" placeholder="Confirm Password" required>
        <br>

        <?php if (in_array('Your passwords do not match<br>', $error_array)) echo 'Your passwords do not match<br>';
        else if (in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
        else if (in_array('Your password must be between 5 and 30 characters<br>', $error_array)) echo 'Your password must be between 5 and 30 characters<br>';

        ?>


        <input type="submit" name="register_button" value="Register">

        <?php
        if (in_array("<span style='color: green'>Successfully registered. Please log in", $error_array)) {
            echo "<span style='color: green'>Successfully registered. Please log in";
        }
        ?>
    </form>

</body>

</html>