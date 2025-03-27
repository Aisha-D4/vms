<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (!in_array($_SESSION['role'], ['admin', 'organization', 'volunteer'])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/db_connection.php';

try {
    // Total users count
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $totalUsers = $stmt->fetch()['total'];
    
    // User counts by role
    $stmt = $conn->prepare("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $stmt->execute();
    $usersByRole = $stmt->fetchAll();
    
    // Format user roles data
    $roleLabels = [];
    $roleCounts = [];
    $roleColors = [
        'volunteer' => '#4338CA',
        'organization' => '#059669',
        'admin' => '#DC2626'
    ];
    $roleColorValues = [];
    
    foreach ($usersByRole as $role) {
        $roleLabels[] = ucfirst($role['role']) . 's';
        $roleCounts[] = $role['count'];
        $roleColorValues[] = $roleColors[$role['role']] ?? '#94A3B8';
    }
    
    // Active activities count
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM activities WHERE activity_date >= CURDATE()");
    $stmt->execute();
    $activeActivities = $stmt->fetch()['total'];
    
    // Recent users (last 5)
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, role, registration_date FROM users ORDER BY registration_date DESC LIMIT 5");
    $stmt->execute();
    $recentUsers = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Volunteer Hub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-blue-800 text-white w-64 space-y-6 py-7 px-2">
            <div class="flex items-center space-x-2 px-4">
                <i class="fas fa-hands-helping text-2xl"></i>
                <span class="text-xl font-bold">Volunteer Hub</span>
            </div>
            <nav>
                <a href="dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 bg-blue-700 text-white">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="activities.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    <i class="fas fa-tasks mr-2"></i>Activities
                </a>
                <a href="users.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    <i class="fas fa-users mr-2"></i>Users
                </a>
                <a href="settings.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    <i class="fas fa-cog mr-2"></i>Settings
                </a>
                <a href="logout.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            <?php echo $_SESSION['role'] === 'admin' ? 'bg-red-100 text-red-800' : 
                                   ($_SESSION['role'] === 'organization' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                            <?php echo ucfirst($_SESSION['role']); ?>
                        </span>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 uppercase">Total Users</p>
                                <p class="text-2xl font-semibold"><?php echo $totalUsers; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 uppercase">Active Activities</p>
                                <p class="text-2xl font-semibold"><?php echo $activeActivities; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 uppercase">This Month</p>
                                <p class="text-2xl font-semibold">+<?php echo rand(5, 20); ?>%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Recent Users</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($recentUsers as $user): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($user['email']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo $user['role'] === 'admin' ? 'bg-red-100 text-red-800' : 
                                                   ($user['role'] === 'organization' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date("M j, Y", strtotime($user['registration_date'])); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User distribution chart
            var ctx = document.getElementById('userDistributionChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: <?php echo json_encode($roleLabels); ?>,
                        datasets: [{
                            data: <?php echo json_encode($roleCounts); ?>,
                            backgroundColor: <?php echo json_encode($roleColorValues); ?>,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>