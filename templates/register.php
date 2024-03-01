<?php
session_start();

function connectToDatabase() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "techdb";

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectToDatabase();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $collegename = $_POST["collegename"];

    // Validate password match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match";
        redirectAfterMessage();
    }

    $existingUserQuery = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $existingUserQuery);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo "Username already exists";
            redirectAfterMessage();
        } else {
            $insertQuery = "INSERT INTO users (username, password, firstname, lastname, email, phone, collegename) 
                            VALUES ('$username', '$password', '$firstname', '$lastname', '$email', '$phone', '$collegename')";
            $insertResult = mysqli_query($conn, $insertQuery);

            if ($insertResult) {
                // Registration successful
                $_SESSION['username'] = $username; // Store username in session
                echo "Registration successful";
                redirectAfterMessage($username);
            } else {
                echo "Error: " . mysqli_error($conn);
                redirectAfterMessage();
            }
        }

        mysqli_close($conn);
    } else {
        echo "Error: " . mysqli_error($conn);
        redirectAfterMessage();
    }
}

function redirectAfterMessage($username = null) {
    // Redirect to 'http://127.0.0.1:5000/' after 5 seconds
    $redirectUrl = 'http://127.0.0.1:5000/';
    if ($username) {
        $redirectUrl .= "?username=" . urlencode($username);
    }

    echo "<script>
            setTimeout(function() {
                window.location.href = '$redirectUrl';
            }, 5000);
          </script>";
    exit(); // Ensure that the script stops executing after the redirect
}
?>
