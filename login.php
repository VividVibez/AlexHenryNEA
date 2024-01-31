<?php
 
// Store username 
// Store password

$username = strtolower($_POST["loginUsername"]);
$password = $_POST["loginPassword"];

// Re-hash password

$password_hash = password_hash($password, PASSWORD_DEFAULT);


// Setup sql to check if username and password are in the database

$check = "SELECT password_hash
          FROM user
          WHERE username=$username";


