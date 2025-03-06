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
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
</head>


<?php if(isset($_POST['register_button'])) {
    echo '
<script>

$(document).ready(function() {
    $("#first").hide();
    $("#second").show();
})
</script>

';
}?>

<div class="register-wrapper">
    <div class="black-fill">
    <div class="login-box">
        <div class="login-header">
            <h1>LinkUp</h1>
            <p>Login or register for an account</p>
        </div>

        <div id="first">

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
                    echo"<p class='popup-message'>Email or password was incorrect<br><p>"?>
                <br>
                <a href="#" id="signup" class="signup">Register Account</a>
            </form>
        </div>

        <div id="second">

            <form method="POST" action="register.php">
                <input type="text" name="reg_fname" placeholder="First Name"
                    value="<?php if (isset($_SESSION['reg_fname'])) {
                                echo $_SESSION['reg_fname'];
                            } ?>" required>
                <br>
                <?php if (in_array('First name must be between 2 and 25 characters<br>', $error_array)) echo"<p class='popup-message'>First name must be between 2 and 25 character<br><p>"?>

                <input type="text" name="reg_lname" placeholder="Last Name"
                    value="<?php if (isset($_SESSION['reg_lname'])) {
                                echo $_SESSION['reg_lname'];
                            } ?>" required>
                <br>
                <?php if (in_array('Last name must be between 2 and 25 characters<br>.', $error_array))  echo"<p class='popup-message'>'Last name must be between 2 and 25 characters<br><p>"  ?>

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
                <?php if (in_array("Email already taken<br>", $error_array))  echo"<p class='popup-message'>Email already taken<br><p>";
                else if (in_array("Invalid email format<br>", $error_array)) echo"<p class='popup-message'>Invalid email format<br><p>";
                else if (in_array("Email's don't match<br>", $error_array)) echo"<p class='popup-message'>Emails do not match<br><p>";
                ?>

                <input type="password" name="reg_password" placeholder="Password"
                    required>
                <br>
                <input type="password" name="reg_password_confirm" placeholder="Confirm Password" required>
                <br>

                <?php if (in_array('Your passwords do not match<br>', $error_array)) echo"<p class='popup-message'>Your passwords do not match<br><p>";
                else if (in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo"<p class='popup-message'>Your password can only contain english characters or numbers<br><p>";
                else if (in_array('Your password must be between 5 and 30 characters<br>', $error_array)) echo"<p class='popup-message'>Your password must be between 5 and 30 characters<br><p>";

                ?>


                <input type="submit" name="register_button" value="Register">
                <br>

                <?php
                if (in_array("<span style='color: green'>Successfully registered. Please log in", $error_array)) {
                    echo "<span style='color: green'>Successfully registered. Please log in";
                }
                ?>
                <br>
                <a href="#" id="signIn" class="signIn">Have an account? Sign in here</a>
            </form>
        </div>
    </div>
    </div>
</div>

</html>