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

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            header("Location:home.html");
            exit();
        } else {
            echo "Invalid username or password";
        }

        mysqli_close($conn);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
