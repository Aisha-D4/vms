<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            margin: 0;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        .search-bar {
            display: flex;
            padding: 10px;
            background: white;
            margin: 20px;
            border-radius: 5px;
        }
        .search-bar input {
            flex: 1;
            padding: 8px;
            border: none;
        }
        .search-bar button {
            padding: 8px 15px;
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        .actions {
            display: flex;
            flex-direction: column;
            margin: 20px;
        }
        .actions button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 15px;
            margin: 5px 0;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }
        .overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px;
            padding: 20px;
            background: white;
            border-radius: 10px;
        }
        .overview-card {
            background: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .overview-card h3 {
            margin: 0;
            color: #007BFF;
        }
        .overview-card p {
            font-size: 24px;
            margin: 5px 0 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Volunteer Management System</h1>
        <div class="nav-links">
            <a href="#">Volunteers</a>
            <a href="#">Events</a>
            <a href="#">Reports</a>
            <a href="#">Settings</a>
        </div>
    </nav>
    
    <div class="search-bar">
        <input type="text" placeholder="Search...">
        <button>Enter</button>
    </div>
    
    <div class="actions">
        <button>Add New Volunteer</button>
        <button>Create Event</button>
        <button>Generate Report</button>
    </div>
    
    <div class="overview">
        <div class="overview-card">
            <h3>Total Volunteers</h3>
            <p>12</p>
        </div>
        <div class="overview-card">
            <h3>Active Events</h3>
            <p>5</p>
        </div>
        <div class="overview-card">
            <h3>Hours Logged</h3>
            <p>456</p>
        </div>
        <div class="overview-card">
            <h3>New Applications</h3>
            <p>12</p>
        </div>
    </div>
</body>
</html>
