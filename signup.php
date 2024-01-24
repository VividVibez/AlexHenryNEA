<?php

if (empty($_POST["signupUsername"])) {
   die("Name is required");
}

#if (strlen($_POST["signupPassword"]) < 12) {
 #  die("Password must be at least 10 characters");
#}

// Given password
$password = $_POST["signupPassword"];

//validate password and confirm password match
if ($password !== $_POST["signupPasswordConfirm"]) {
   die("Passwords must match");
}

// Validate password strength
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    echo 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
}else{
    echo 'Strong password.';
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

print_r($_POST);
var_dump($password_hash)
?>