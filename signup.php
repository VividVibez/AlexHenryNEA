<?php
 
// Store username 
// Store password

$username = strtolower($_POST["signupUsername"]);
$password = $_POST["signupPassword"];


// Validate password and confirm password match

if ($password !== $_POST["signupPasswordConfirm"]) {
   die("Passwords must match" . "<br>\n");
}


// Validate username length
// Username must be 10 chars long

if (strlen($username) < 8) {
    die ("Username must be 10 characters" . "<br>\n");
} else {
    echo "Strong Username" . "<br>\n";
}

// Validate password strength
// Password must contain a capital and lowercase letter, special char and number

$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

// Return password requirments if password doesnt meet them. Return 'Strong password' if requirments are met

if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    echo 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.' . "<br>\n";
}else{
    echo 'Strong password' . "<br>\n";
}


// Store hashed password

$password_hash = password_hash($password, PASSWORD_DEFAULT);


// Connect to database by calling 'database.php'

$conn =  require __DIR__ . "/database.php";

// Setup prepared statements
// Check will see if username is already taken
// Insert will store the username and password hash in table
// Using a prepared stmt because it makes code less vanurable to sql injection

$check = "SELECT COUNT(*) 
          FROM user 
          WHERE username = '$username'";

$insert = "INSERT INTO user (username, password_hash)
           VALUES (?, ?)";

// Pass in the statment as an argument to create a prepared statment object

$stmt = mysqli_stmt_init($conn);

// Check for errors in sql and prepare stmt and sql

if ( ! mysqli_stmt_prepare($stmt, $insert)) {
    die(mysqli_error($conn));
}

// Check is username isn't taken

$rs = mysqli_query($conn,$check);
$data = mysqli_fetch_array($rs, MYSQLI_NUM);


if ($data[0] >= 1) {
    
    echo "Username isn't avaliable"  . "<br>\n";

} else {

    // Call bind param statment and specify type 

    mysqli_stmt_bind_param($stmt, "ss",
                       $username,
                       $password_hash);

    // Execute stmt and store username and password inside user table

    mysqli_stmt_execute($stmt);
    echo "Signup Successful" . "<br>\n";
}

?>