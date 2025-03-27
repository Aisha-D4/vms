<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'organization') {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connection.php';

// Get organization stats
try {
    // Total opportunities created
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM volunteer_opportunities 
                           WHERE organization_id = :org_id");
    $stmt->bindParam(':org_id', $_SESSION['user_id']);
    $stmt->execute();
    $totalOpportunities = $stmt->fetch()['total'];
    
    // Total volunteers
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT volunteer_id) as total FROM volunteer_assignments 
                           WHERE opportunity_id IN (SELECT id FROM volunteer_opportunities 
                                                  WHERE organization_id = :org_id)");
    $stmt->bindParam(':org_id', $_SESSION['user_id']);
    $stmt->execute();
    $totalVolunteers = $stmt->fetch()['total'];
    
    // Total hours volunteered
    $stmt = $conn->prepare("SELECT SUM(hours_worked) as total FROM volunteer_assignments 
                           WHERE status = 'completed' 
                           AND opportunity_id IN (SELECT id FROM volunteer_opportunities 
                                                WHERE organization_id = :org_id)");
    $stmt->bindParam(':org_id', $_SESSION['user_id']);
    $stmt->execute();
    $totalHours = $stmt->fetch()['total'] ?: 0;
    
    // Organization details
    $stmt = $conn->prepare("SELECT * FROM organizations WHERE id = :org_id");
    $stmt->bindParam(':org_id', $_SESSION['user_id']);
    $stmt->execute();
    $organization = $stmt->fetch();
    
    // Recent opportunities
    $stmt = $conn->prepare("SELECT * FROM volunteer_opportunities 
                           WHERE organization_id = :org_id
                           ORDER BY created_at DESC
                           LIMIT 3");
    $stmt->bindParam(':org_id', $_SESSION['user_id']);
    $stmt->execute();
    $recentOpportunities = $stmt->fetchAll();
    
    // Recent volunteers
    $stmt = $conn->prepare("SELECT v.*, u.first_name, u.last_name 
                           FROM volunteers v
                           JOIN users u ON v.user_id = u.id
                           WHERE v.user_id IN (SELECT DISTINCT volunteer_id FROM volunteer_assignments 
                                             WHERE opportunity_id IN (SELECT id FROM volunteer_opportunities 
                                                                    WHERE organization_id = :org_id))
                           ORDER BY v.last_activity DESC
                           LIMIT 3");
    $stmt->bindParam(':org_id', $_SESSION['user_id']);
    $stmt->execute();
    $recentVolunteers = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Profile | Volunteer Hub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <!-- Include organization navigation -->
    <?php include 'org_nav.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Column - Profile Info -->
            <div class="md:w-1/3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-green-600 p-6 text-center">
                        <div class="h-24 w-24 rounded-full bg-white mx-auto flex items-center justify-center text-green-600 text-3xl font-bold mb-4">
                            <?php echo substr($_SESSION['organization_name'], 0, 2); ?>
                        </div>
                        <h2 class="text-xl font-bold text-white"><?php echo htmlspecialchars($_SESSION['organization_name']); ?></h2>
                        <p class="text-green-100">Member since <?php echo date("F Y", strtotime($_SESSION['registration_date'])); ?></p>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Opportunities Created:</span>
                            <span class="font-bold"><?php echo $totalOpportunities; ?></span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Total Volunteers:</span>
                            <span class="font-bold"><?php echo $totalVolunteers; ?></span>
                        </div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-gray-600">Total Hours Contributed:</span>
                            <span class="font-bold"><?php echo $totalHours; ?></span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <h3 class="text-lg font-semibold mb-2">About Us</h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($organization['description'] ?? 'No description provided'); ?></p>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h3 class="text-lg font-semibold mb-2">Contact Info</h3>
                            <p class="text-gray-600 mb-1"><i class="fas fa-envelope mr-2"></i> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                            <p class="text-gray-600 mb-1"><i class="fas fa-phone mr-2"></i> <?php echo htmlspecialchars($organization['phone'] ?? 'Not provided'); ?></p>
                            <p class="text-gray-600"><i class="fas fa-map-marker-alt mr-2"></i> <?php echo htmlspecialchars($organization['location'] ?? 'Not provided'); ?></p>
                        </div>
                        
                        <div class="mt-6">
                            <a href="edit_profile.php" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center font-bold py-2 px-4 rounded">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                    <div class="bg-green-600 p-4">
                        <h2 class="text-lg font-bold text-white">Quick Actions</h2>
                    </div>
                    <div class="p-6">
                        <a href="create_opportunity.php" class="block w-full bg-green-100 hover:bg-green-200 text-green-800 text-center font-bold py-2 px-4 rounded mb-3">
                            <i class="fas fa-plus-circle mr-2"></i> Create Opportunity
                        </a>
                        <a href="volunteers.php" class="block w-full bg-green-100 hover:bg-green-200 text-green-800 text-center font-bold py-2 px-4 rounded mb-3">
                            <i class="fas fa-users mr-2"></i> Manage Volunteers
                        </a>
                        <a href="reports.php" class="block w-full bg-green-100 hover:bg-green-200 text-green-800 text-center font-bold py-2 px-4 rounded">
                            <i class="fas fa-chart-bar mr-2"></i> View Reports
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Main Content -->
            <div class="md:w-2/3">
                <!-- Recent Opportunities -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-green-600 p-4">
                        <h2 class="text-lg font-bold text-white">Recent Opportunities</h2>
                    </div>
                    <div class="p-6">
                        <?php if(count($recentOpportunities) > 0): ?>
                            <div class="space-y-4">
                                <?php foreach($recentOpportunities as $opportunity): ?>
                                    <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-bold"><?php echo htmlspecialchars($opportunity['title']); ?></h3>
                                                <p class="text-gray-600 text-sm"><?php echo date("M j, Y", strtotime($opportunity['start_date'])); ?> - <?php echo date("M j, Y", strtotime($opportunity['end_date'])); ?></p>
                                            </div>
                                            <span class="text-sm text-gray-500">
                                                <?php echo $opportunity['status']; ?>
                                            </span>
                                        </div>
                                        <p class="text-gray-700 mt-2"><?php echo substr(htmlspecialchars($opportunity['description']), 0, 150); ?>...</p>
                                        <div class="mt-3">
                                            <a href="opportunity.php?id=<?php echo $opportunity['id']; ?>" class="text-green-600 hover:text-green-800 text-sm">View Details</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-4 text-center">
                                <a href="opportunities.php" class="text-green-600 hover:text-green-800 text-sm">View all opportunities</a>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-600 text-center py-4">No opportunities created yet. Create your first opportunity to get started!</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Volunteers -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-green-600 p-4">
                        <h2 class="text-lg font-bold text-white">Recent Volunteers</h2>
                    </div>
                    <div class="p-6">
                        <?php if(count($recentVolunteers) > 0): ?>
                            <div class="grid md:grid-cols-3 gap-4">
                                <?php foreach($recentVolunteers as $volunteer): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 text-center">
                                        <div class="h-16 w-16 rounded-full bg-green-100 mx-auto flex items-center justify-center text-green-600 text-xl font-bold mb-3">
                                            <?php echo substr($volunteer['first_name'], 0, 1) . substr($volunteer['last_name'], 0, 1); ?>
                                        </div>
                                        <h3 class="font-bold"><?php echo htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name']); ?></h3>
                                        <p class="text-gray-600 text-sm mb-2"><?php echo htmlspecialchars($volunteer['skills'] ?? 'No skills listed'); ?></p>
                                        <a href="volunteer.php?id=<?php echo $volunteer['user_id']; ?>" class="text-green-600 hover:text-green-800 text-sm">View Profile</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-4 text-center">
                                <a href="volunteers.php" class="text-green-600 hover:text-green-800 text-sm">View all volunteers</a>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-600 text-center py-4">No volunteers yet. Create opportunities to attract volunteers!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>