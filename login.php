<?php
session_start();
include 'functions.php';
if (isset($_SESSION["usr"])) {
    header("location: question1.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Analyst</title>
    <link rel="stylesheet" href="index.css"> <!-- Link to external CSS file -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Link to external boxicons CSS file -->
    <script src="index.js" defer></script> <!-- Link to external JavaScript file with 'defer' attribute for asynchronous loading -->
</head>
<body>
    <div class="wrapper">
        
        <h1>Login</h1>
        <!-- Form for user login -->
        <form class="login" method="post" novalidate>

            <!-- Input field for username -->
            <div class="input-box">
                <input type="text"  placeholder="Username" id="loginUsername" name="loginUsername">
                <i class='bx bxs-user'></i> <!-- Icon for username input -->
            </div>

            <!-- Input field for password -->
            <div class="input-box">
                <input type="password"  placeholder="Password" id="loginPassword" name="loginPassword">
                <i class='bx bxs-lock-alt'></i> <!-- Icon for password input -->
            </div>        

            <!-- Remember Me checkbox and Forgot Password link -->
            <div class="remember-me">
                <label><input type="checkbox">Remember Me</label> <!-- Checkbox to remember user login -->
                <a href="">Forgot Password?</a> <!-- Link to reset password -->
            </div>

            <!-- Button to submit the form -->
            <button type="submit" class="button">Login</button>

            <!-- Link to register page for new users -->
            <div class="register">
                <p>Don't have an account?<a href="signup.php">Register</a></p> <!-- Link to register page -->
            </div>

            <?php

            // Check if form is submitted
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Check if loginUsername and loginPassword are set in $_POST
                if (isset($_POST["loginUsername"]) && isset($_POST["loginPassword"])) {
                    // Store username and password after sanitizing
                    $username = strtolower($_POST["loginUsername"]); // Convert username to lowercase for consistency
                    $password = $_POST["loginPassword"];

                    // Re-hash password for consistency with stored hashed passwords in the database
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    // Run user verification function and inform user if they are logged in or not
                    if (userVerify($password, $username) == TRUE) {
                        $_SESSION["usr"] = $username;
                        // Redirect user to main page if login is successful
                        $url = "question1.php";
                        header("location:" . $url);
                        end;
                    } else {
                        // Display error message if username or password are incorrect
                        echo "<div class='alert'>Username or Password are incorrect</div>";
                    }

                } else {
                    // Handle the case where either loginUsername or loginPassword is not set
                    die ("Please provide both username and password.");
                }
            }
            ?>
        </form> 
    </div>
</body>
</html>


