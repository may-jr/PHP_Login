


<?php
session_start();

// Database connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

// Try to establish a database connection.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Checking if the login data was submitted.
if (!isset($_POST['username'], $_POST['password'])) {
    // Data was not sent, prompt the user to fill both fields.
    exit('Please fill both username and password fields!');
}

// Prepare our SQL statement to prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    // Bind parameters (s = string), in this case, the username is a string so we use "s".
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        if (password_verify($_POST['password'], $password)) {
            // Verification success! User has logged in!
            // Create sessions to remember the user is logged in.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['id'] = $id;
            // Redirect to the home page.
            header("Location: home.php");
            exit;
        } else {
            // Incorrect password.
            echo 'Incorrect username and/or password!';
        }
    } else {
        // Incorrect username.
        echo 'Incorrect username and/or password!';
    }
    $stmt->close();
}
?>


