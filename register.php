<?php
// Include the database connection
include('connect.php');

// Initialize variables to hold error and success messages
$success_message = "";
$error_message = "";

// Initialize username and email variables
$username = "";
$email = "";

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the values from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match! Please try again.";
    } else {
        // Check if the user already exists
        $sql_check = "SELECT * FROM users WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        
        if ($result->num_rows > 0) {
            // If user exists, show error message
            $error_message = "User already exists! Please use a different email.";
        } else {
            // If the user doesn't exist, hash the password and insert the new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert the new user into the database
            $sql_insert = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt_insert->execute()) {
                // If the insertion is successful, set the success message
                $success_message = "Registration successful! You can now log in.";
                // Clear username and email after successful registration
                $username = "";
                $email = "";
            } else {
                // If there was an error during insertion
                $error_message = "Error: " . $stmt_insert->error;
            }
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* CSS Styles */
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            box-sizing: border-box;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url(anderson.jpg);
            background-size: cover;
            background-repeat: no-repeat;
        }
        .box {
            padding: 20px 30px;
            width: 300px;
            height: 600px; /* Adjusted for confirm password */
            backdrop-filter: blur(50px);
            border-radius: 5px;
            background: rgba(49, 49, 49, .2);
            border: 1px solid rgba(255, 255, 255, .5);
        }
        .box .form {
            padding: 10px 20px;
        }
        .box .form h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
        }
        .box .form form {
            margin-top: 50px;
            align-items: center;
        }
        .box .form form .inputbox input {
            padding: 10px 20px;
            border: none;
            outline: none;
            background: none;
            border-bottom: 1px solid #12151e;
            margin-bottom: 30px;
            color: #fff;
        }
        .box .form form .inputbox span {
            position: absolute;
            transform: translateY(-80px);
            font-size: 14px;
            color: #fff;
        }
        .box .form form .sub {
            padding: 10px 20px;
            color: #fff;
            border: none;
            outline: none;
            background: #0cc0df;
            cursor: pointer;
            font-size: 16px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #fff;
        }
        .login-link a {
            color: #0cc0df;
            text-decoration: none;
            font-size: 16px;
        }
        /* Alert box styles */
        .alert {
            color: #fff;
            background-color: #0cc0df;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .error {
            background-color: #e74c3c;
        }
        .success {
            background-color: #2ecc71;
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="form">
            <h2>Register</h2>
            <!-- Form sends data to itself (register.php) -->
            <form action="register.php" method="POST">
                <!-- Display success or error message -->
                <?php if ($success_message) { ?>
                    <div class="alert success"><?php echo $success_message; ?></div>
                <?php } elseif ($error_message) { ?>
                    <div class="alert error"><?php echo $error_message; ?></div>
                <?php } ?>

                <!-- Form inputs for registration -->
                <div class="inputbox">
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    <span>Username</span>
                </div>
                <div class="inputbox">
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <span>Email</span>
                </div>
                <div class="inputbox">
                    <input type="password" name="password" id="password" required>
                    <span>Password</span>
                </div>
                <div class="inputbox">
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <span>Confirm Password</span>
                </div>
                <input type="submit" value="Register" class="sub" id="submit">
            </form>
            <div class="login-link">
                <a href="login.html">
                    <i class="fas fa-sign-in-alt"></i> Already Registered? Login here
                </a>
            </div>
        </div>
    </div>
</body>
</html>