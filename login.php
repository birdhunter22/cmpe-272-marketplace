<?php
session_start();
$servername = "ec2-52-8-168-99.us-west-1.compute.amazonaws.com";
$username = "atom";
$password = "@tomPWD";
$dbname = "user_auth";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // For debugging purposes: compare plain-text passwords directly
        if ($pass == $row['password']) {
            $_SESSION['username'] = $user;
            header("Location: welcome.php");
            exit(); // It's good practice to call exit() after a header redirection
        } else {
            echo "<p style='color:red;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color:red;'>No user found.</p>";
    }

    // Print query result for debugging
    echo "<h3>Query Result:</h3>";
    echo "<pre>";
    print_r($row);
    echo "</pre>";
}

if (isset($_POST['register'])) {
    $new_user = $_POST['new_username'];
    $new_pass = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $new_email = $_POST['new_email'];

    $sql = "INSERT INTO users (username, password, email) VALUES ('$new_user', '$new_pass', '$new_email')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>New user created successfully.</p>";
        header("Location: index.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
