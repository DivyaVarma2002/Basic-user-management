<!-- edit_profile.php -->
<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Include the database connection file
include('db.php');

// Get the logged-in user's ID
$user_id = $_SESSION['id'];

// Fetch current user data from the database
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated data from the form
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    // Validate input data (you can add more validations as needed)
    if (empty($new_username) || empty($new_email)) {
        echo "Username and email are required.";
    } else {
        // Update user data in the database
        $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_username, $new_email, $user_id);

        if ($stmt->execute()) {
            echo "Profile updated successfully.";
            // Optionally, update the session variables
            $_SESSION['username'] = $new_username;
        } else {
            echo "Error updating profile: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Edit Profile</h1>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <br>
        <input type="submit" value="Update Profile">
    </form>
    <br>
    <a href="profile.php">Back to Profile</a>
</body>
</html>
