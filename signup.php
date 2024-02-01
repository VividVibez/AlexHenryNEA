<?php

// Store username and password submitted through POST request
$username = strtolower($_POST["signupUsername"]); // Convert username to lowercase for consistency
$password = $_POST["signupPassword"];

// Validate password and confirm password match
if ($password !== $_POST["signupPasswordConfirm"]) {
   die("Passwords must match" . "<br>\n");
}

// Validate username length
// Username must be at least 8 characters long
if (strlen($username) < 8) {
    die ("Username must be 8 characters" . "<br>\n");
} else {
    echo "Strong Username" . "<br>\n";
}

// Validate password strength
// Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

// Return password requirements if password doesn't meet them. Return 'Strong password' if requirements are met
if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    die ("Password should be at least 8 characters in length and should include at least one uppercase letter, one number, and one special character." . "<br>\n");
} else {
    echo 'Strong password' . "<br>\n";
}

// Hash the password for security
$password = password_hash($password, PASSWORD_DEFAULT);

// Connect to database by including 'database.php'
$conn =  require __DIR__ . "/database.php";

// Setup prepared statements to prevent SQL injection
$check = "SELECT COUNT(*) 
          FROM user 
          WHERE username = '$username'";

$insert = "INSERT INTO user (username, password_hash)
           VALUES (?, ?)";

// Initialize a prepared statement
$stmt = mysqli_stmt_init($conn);

// Check for errors in SQL and prepare statement and SQL
if (!mysqli_stmt_prepare($stmt, $insert)) {
    die(mysqli_error($conn)); // Terminate script execution and display the error message if preparation fails
}

// Check if the username isn't taken
$rs = mysqli_query($conn, $check);
$data = mysqli_fetch_array($rs, MYSQLI_NUM);

if ($data[0] >= 1) {
    echo "Username isn't available"  . "<br>\n";
} else {
    // Bind parameters to the prepared statement specifying types
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);

    // Execute the statement to store the username and hashed password in the user table
    mysqli_stmt_execute($stmt);
    header("location: index.html");
}

?>