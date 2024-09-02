<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful!";
        header("Location: login.html");
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        header("Location: register.html");
    }
    
    $stmt->close();
    $conn->close();
}
?>
