<?php
// Start the session to access session variables
session_start();
// Unset the session variables
session_unset();

session_destroy();

header("location: login.php");

exit;
?>
?>