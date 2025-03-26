<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Dashboard</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #0056b3;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 10px;
            display: block;
            margin: 5px 0;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background: #003d82;
        }

        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 20px;
            flex: 1;
            width: 100%;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header h1 {
            font-size: 24px;
            color: #333;
        }

        .logout-btn {
            background: #DAA520;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #B8860B;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card h2 {
            margin: 10px 0;
            color: #0056b3;
        }
        .card h3{
            margin: 10px 0;
            color: #0056b3;
        }

        .card p {
            font-size: 22px;
            font-weight: bold;
        }

        /* Volunteer Table */
        .table-container {
            margin-top: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background: #0056b3;
            color: white;
        }

        .approve-btn {
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .approve-btn:hover {
            background: #218838;
        }

        .reject-btn {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .reject-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Voluntree</h2>
        <h3>Organization Dashboard</h3>
        <a href="#"><i class="fas fa-home"></i> Dashboard</a>
        <a href="#"><i class="fas fa-users"></i> Volunteers</a>
        <a href="#"><i class="fas fa-briefcase"></i> Projects</a>
        <a href="#"><i class="fas fa-calendar"></i> Events</a>
        <a href="#"><i class="fas fa-cogs"></i> Settings</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <h1>Welcome, Organization!</h1>
            <button class="logout-btn" onclick="window.location.href='home.php'">Logout</button>
        </div>

        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Volunteers</h3>
                <p>120</p>
            </div>
            <div class="card">
                <h3>Active Projects</h3>
                <p>8</p>
            </div>
            <div class="card">
                <h3>Upcoming Events</h3>
                <p>5</p>
            </div>
        </div>

        <!-- Volunteer Applications Table -->
        <div class="table-container">
            <h2>Pending Volunteer Applications</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Approve</th>
                    <th>Reject</th>
                </tr>
                <tr>
                    <td>John Doe</td>
                    <td>john@example.com</td>
                    <td>Pending</td>
                    <td>
                        <button class="approve-btn">Approve</button>
                        <button class="reject-btn">Reject</button>
                    </td>
                </tr>
                <tr>
                    <td>Jane Smith</td>
                    <td>jane@example.com</td>
                    <td>Pending</td>
                    <td>
                        <button class="approve-btn">Approve</button>
                        <button class="reject-btn">Reject</button>
                    </td>
                </tr>
            </table>
        </div>

    </div>

</body>
</html>
