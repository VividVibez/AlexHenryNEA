<?php
// Start the session to access session variables
session_start();
// Unset the session variable named "usr"
unset($_SESSION["usr"]);
// Redirect the user to the login.php page
header("location: login.php");
?>
?>