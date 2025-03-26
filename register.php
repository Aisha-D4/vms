<?php
include 'connect.php';

// Function to display a message and a link to go back to login.php
function displayMessage($message) {
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Message</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .message-box {
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
            .message-box a {
                color: #008080;
                text-decoration: none;
                font-weight: bold;
            }
            .message-box a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class='message-box'>
            <p>$message</p>
            <a href='login.php'>Go back to the homepage</a>
        </div>
    </body>
    </html>
    ";
    exit(); // Stop further execution
}

if (isset($_POST['signUp'])) {
    $userType = $_POST['userType'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the password

    // Check if email already exists
    if ($userType === 'volunteer') {
        $checkEmail = "SELECT * FROM volunteer WHERE email='$email'";
    } elseif ($userType === 'organization') {
        $checkEmail = "SELECT * FROM organization WHERE email='$email'";
    }

    $result = $conn->query($checkEmail);

    if (!$result) {
        // Handle query execution error
        displayMessage("Database error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        displayMessage("Email Address Already Exists!");
    } else {
        if ($userType === 'volunteer') {
            // Volunteer registration
            $firstName = $_POST['fName'];
            $lastName = $_POST['lName'];
            $insertQuery = "INSERT INTO volunteer (firstName, lastName, email, password)
                            VALUES ('$firstName', '$lastName', '$email', '$password')";
        } elseif ($userType === 'organization') {
            // Organization registration
            $organizationName = $_POST['organizationName'];
            $insertQuery = "INSERT INTO organization (organizationName, email, password)
                            VALUES ('$organizationName', '$email', '$password')";
        }

        // Execute the query
        if ($conn->query($insertQuery) === TRUE) {
            displayMessage("Signup successful! Please log in.");
        } else {
            displayMessage("Error: " . $conn->error);
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the password

    // Check if user exists in volunteers table
    $sql = "SELECT * FROM volunteer WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if (!$result) {
        // Handle query execution error
        displayMessage("Database error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        // Volunteer login successful
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['userType'] = 'volunteer';
        header("Location: volunteerdashboard.php");
        exit();
    } else {
        // Check if user exists in organizations table
        $sql = "SELECT * FROM organization WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);

        if (!$result) {
            // Handle query execution error
            displayMessage("Database error: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            // Organization login successful
            session_start();
            $row = $result->fetch_assoc();
            $_SESSION['email'] = $row['email'];
            $_SESSION['userType'] = 'organization';
            header("Location: Orgdashboard.php");
            exit();
        } else {
            // Login failed
            displayMessage("Incorrect Email or Password");
        }
    }
}
?>