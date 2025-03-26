<?php
session_start(); // Start the session

// Check if the user is logged in and is a volunteer
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'volunteer') {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Retrieve volunteer information from the session or database
include 'connect.php';

$email = $_SESSION['email'];
$query = "SELECT * FROM volunteer WHERE email = '$email'";
$result = $conn->query($query);
$volunteerData = $result->fetch_assoc();

// Placeholder function to get volunteer statistics (you'll need to implement actual database queries)
function getVolunteerStatistics($volunteerId) {
    // These would be actual database queries in a real implementation
    return [
        'totalHours' => 45, // Total volunteering hours
        'opportunitiesCompleted' => 6, // Number of volunteer opportunities completed
        'currentCauses' => ['Environmental', 'Community Support', 'Education'],
        'memberSince' => '2025' // From the registration date
    ];
}

$stats = getVolunteerStatistics($volunteerData['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard</title>
    <style>
        /* Added navigation bar styles */
        .top-nav {
            background-color: #007BFF;
            padding: 10px 0;
        }
        .top-nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        .top-nav li {
            margin: 0 15px;
        }
        .top-nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .top-nav a:hover {
            text-decoration: underline;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-section {
            display: flex;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            background-color: #007BFF;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 60px;
            margin-right: 20px;
        }

        .profile-info {
            flex-grow: 1;
        }

        .profile-info h2 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .profile-info p {
            margin: 5px 0;
            color: #666;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .dashboard-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }

        .dashboard-card h3 {
            color: #007BFF;
            margin-bottom: 10px;
        }

        .dashboard-card p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .logout-btn {
            background-color: #DC3545;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #C82333;
        }

        .causes-section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .causes-list {
            display: flex;
            gap: 10px;
        }

        .cause-tag {
            background-color: #007BFF;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <!-- Added navigation bar -->
    <div class="top-nav">
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="#">My Profile</a></li>
            <li><a href="#">Opportunities</a></li>
        </ul>
    </div>

    <div class="dashboard-container">
        <div class="header">
            <h1>Volunteer Dashboard</h1>
            <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
        </div>

        <div class="profile-section">
            <div class="profile-photo">
                <?php echo strtoupper(substr($volunteerData['firstName'], 0, 1)); ?>
            </div>
            <div class="profile-info">
                <h2>Hi, <?php echo htmlspecialchars($volunteerData['firstName'] . ' ' . $volunteerData['lastName']); ?>!</h2>
                <p>Member Since: <?php echo $stats['memberSince']; ?></p>
                <p>Email: <?php echo htmlspecialchars($volunteerData['email']); ?></p>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Total Volunteer Hours</h3>
                <p><?php echo $stats['totalHours']; ?></p>
            </div>
            <div class="dashboard-card">
                <h3>Opportunities Completed</h3>
                <p><?php echo $stats['opportunitiesCompleted']; ?></p>
            </div>
            <div class="dashboard-card">
                <h3>Recommended Matches</h3>
                <p>3</p>
            </div>
        </div>

        <div class="causes-section">
            <h3>My Causes</h3>
            <div class="causes-list">
                <?php foreach ($stats['currentCauses'] as $cause): ?>
                    <span class="cause-tag"><?php echo htmlspecialchars($cause); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>