<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit();
}

// Get the logged-in user's data
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #282c34;
            color: white;
            font-family: sans-serif;
            text-align: center;
        }
        a {
            color: #0cc0df;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Hello, <?php echo htmlspecialchars($username); ?>! Welcome to my World!</h1>
    <a href="logout.php">Logout</a> <!-- Link to logout page -->
</body>
</html>