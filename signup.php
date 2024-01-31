<?php

// Store username 
// Store password

$username = strtolower($_POST["signupUsername"]);
$password = $_POST["signupPassword"];


// Validate password and confirm password match

if ($password !== $_POST["signupPasswordConfirm"]) {
   die("Passwords must match");
}


// Validate username length
// Username must be 10 chars long

if (strlen($username) < 8) {
    die ("Username must be 10 characters");
} else {
    echo 'Strong Username';
}


// Validate password strength
// Password must contain a capital and lowercase letter, special char and number

$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

// Return password requirments if password doesnt meet them. Return 'Strong password' if requirments are met

if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    echo 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
}else{
    echo 'Strong password';
}


// Store hashed password

$password_hash = password_hash($password, PASSWORD_DEFAULT);


// Connect to database by calling 'database.php'

$conn =  require __DIR__ . "/database.php";

// Setup prepared statement
// Using a prepared stmt because it makes code less vanurable to sql injection

$sql = "INSERT INTO user (username, password_hash)
        VALUES ($username, $password_hash)";



echo "Signup Sucessful";
?>