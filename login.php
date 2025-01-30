<?php
session_start(); // Make sure session is started!

// Include the database connection
include('connect.php');

// Initialize response array
$response = array('success' => false, 'message' => '');

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // No need to escape this field as we will compare it directly

    // Query the database to check if the user exists
    $sql = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, now verify the password
        $user = $result->fetch_assoc();
        
        // Check if the entered password matches the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['username'] = $username; // Store username in session
            $_SESSION['user_id'] = $user['id']; // Store user id in session
            $_SESSION['email'] = $email; // Optionally store email

            $response['success'] = true;
            $response['message'] = 'Login successful!';
        } else {
            // Incorrect password
            $response['message'] = 'Invalid password. Please try again.';
        }
    } else {
        // User doesn't exist
        $response['message'] = 'Invalid account or you must sign in first.';
    }

    // Send JSON response
    echo json_encode($response);
    exit();
}

// Close the database connection
$conn->close();
?>