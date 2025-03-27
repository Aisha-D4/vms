<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'volunteer') {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connection.php';

// Get volunteer stats
try {
    // Completed opportunities
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM volunteer_assignments 
                           WHERE volunteer_id = :user_id AND status = 'completed'");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $completedOpportunities = $stmt->fetch()['total'];
    
    // Total hours
    $stmt = $conn->prepare("SELECT SUM(hours_worked) as total FROM volunteer_assignments 
                           WHERE volunteer_id = :user_id AND status = 'completed'");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $totalHours = $stmt->fetch()['total'] ?: 0;
    
    // Upcoming opportunities
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM volunteer_opportunities 
                           WHERE id IN (SELECT opportunity_id FROM volunteer_assignments 
                                       WHERE volunteer_id = :user_id AND status = 'upcoming')");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $upcomingOpportunities = $stmt->fetch()['total'];
    
    // Recommended opportunities (based on skills and past activities)
    $stmt = $conn->prepare("SELECT vo.*, o.organization_name 
                           FROM volunteer_opportunities vo
                           JOIN organizations o ON vo.organization_id = o.id
                           WHERE vo.id NOT IN (SELECT opportunity_id FROM volunteer_assignments 
                                             WHERE volunteer_id = :user_id)
                           AND (vo.skills_required LIKE CONCAT('%', (SELECT skills FROM volunteer_profiles WHERE user_id = :user_id), '%')
                               OR vo.category IN (SELECT category FROM volunteer_assignments va
                                                 JOIN volunteer_opportunities vo2 ON va.opportunity_id = vo2.id
                                                 WHERE va.volunteer_id = :user_id
                                                 GROUP BY vo2.category
                                                 ORDER BY COUNT(*) DESC LIMIT 3))
                           ORDER BY vo.start_date ASC
                           LIMIT 3");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $recommendedOpportunities = $stmt->fetchAll();
    
    // Volunteer details
    $stmt = $conn->prepare("SELECT * FROM volunteers WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $volunteer = $stmt->fetch();
    
    // Recent activities
    $stmt = $conn->prepare("SELECT va.*, vo.title, o.organization_name 
                           FROM volunteer_assignments va
                           JOIN volunteer_opportunities vo ON va.opportunity_id = vo.id
                           JOIN organizations o ON vo.organization_id = o.id
                           WHERE va.volunteer_id = :user_id
                           ORDER BY va.assignment_date DESC
                           LIMIT 5");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $recentActivities = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Volunteer Hub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <!-- Include volunteer navigation -->
    <?php include 'volunteer_nav.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Column - Profile Info -->
            <div class="md:w-1/3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-blue-600 p-6 text-center">
                        <div class="h-24 w-24 rounded-full bg-white mx-auto flex items-center justify-center text-blue-600 text-3xl font-bold mb-4">
                            <?php echo substr($_SESSION['first_name'], 0, 1) . substr($_SESSION['last_name'], 0, 1); ?>
                        </div>
                        <h2 class="text-xl font-bold text-white"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></h2>
                        <p class="text-blue-100">Volunteer since <?php echo date("F Y", strtotime($_SESSION['registration_date'])); ?></p>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Completed Opportunities:</span>
                            <span class="font-bold"><?php echo $completedOpportunities; ?></span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Total Hours:</span>
                            <span class="font-bold"><?php echo $totalHours; ?></span>
                        </div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-gray-600">Upcoming Opportunities:</span>
                            <span class="font-bold"><?php echo $upcomingOpportunities; ?></span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <h3 class="text-lg font-semibold mb-2">Skills</h3>
                            <div class="flex flex-wrap gap-2">
                                <?php 
                                $skills = explode(',', $volunteer['skills']);
                                foreach($skills as $skill): 
                                    if(trim($skill)): ?>
                                        <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full"><?php echo htmlspecialchars(trim($skill)); ?></span>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h3 class="text-lg font-semibold mb-2">Contact Info</h3>
                            <p class="text-gray-600 mb-1"><i class="fas fa-envelope mr-2"></i> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                            <p class="text-gray-600"><i class="fas fa-phone mr-2"></i> <?php echo htmlspecialchars($volunteer['phone'] ?? 'Not provided'); ?></p>
                        </div>
                        
                        <div class="mt-6">
                            <a href="edit_profile.php" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Badges Section -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                    <div class="bg-blue-600 p-4">
                        <h2 class="text-lg font-bold text-white">Badges & Achievements</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="h-16 w-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-medal text-yellow-500 text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium">Dedicated Volunteer</p>
                            </div>
                            <div class="text-center">
                                <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-star text-blue-500 text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium">50+ Hours</p>
                            </div>
                            <div class="text-center">
                                <div class="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-heart text-green-500 text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium">Community Hero</p>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="badges.php" class="text-blue-600 hover:text-blue-800 text-sm">View all badges</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Main Content -->
            <div class="md:w-2/3">
                <!-- Recommended Opportunities -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-blue-600 p-4">
                        <h2 class="text-lg font-bold text-white">Recommended Opportunities</h2>
                    </div>
                    <div class="p-6">
                        <?php if(count($recommendedOpportunities) > 0): ?>
                            <div class="grid md:grid-cols-2 gap-4">
                                <?php foreach($recommendedOpportunities as $opportunity): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <h3 class="font-bold text-lg mb-1"><?php echo htmlspecialchars($opportunity['title']); ?></h3>
                                        <p class="text-gray-600 text-sm mb-2"><?php echo htmlspecialchars($opportunity['organization_name']); ?></p>
                                        <p class="text-gray-700 mb-3"><?php echo substr(htmlspecialchars($opportunity['description']), 0, 100); ?>...</p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-500">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                <?php echo date("M j, Y", strtotime($opportunity['start_date'])); ?>
                                            </span>
                                            <a href="opportunity.php?id=<?php echo $opportunity['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-4 text-center">
                                <a href="recommendations.php" class="text-blue-600 hover:text-blue-800 text-sm">View all recommendations</a>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-600 text-center py-4">No recommendations available. Complete your profile to get personalized recommendations.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-blue-600 p-4">
                        <h2 class="text-lg font-bold text-white">Recent Volunteer Activities</h2>
                    </div>
                    <div class="p-6">
                        <?php if(count($recentActivities) > 0): ?>
                            <div class="space-y-4">
                                <?php foreach($recentActivities as $activity): ?>
                                    <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-bold"><?php echo htmlspecialchars($activity['title']); ?></h3>
                                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($activity['organization_name']); ?></p>
                                            </div>
                                            <span class="text-sm text-gray-500">
                                                <?php echo date("M j, Y", strtotime($activity['assignment_date'])); ?>
                                            </span>
                                        </div>
                                        <div class="mt-2">
                                            <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
                                                <?php echo $activity['status'] === 'completed' ? 'Completed' : 'Upcoming'; ?>
                                            </span>
                                            <?php if($activity['status'] === 'completed' && $activity['hours_worked'] > 0): ?>
                                                <span class="inline-block bg-blue-100 rounded-full px-3 py-1 text-sm font-semibold text-blue-800">
                                                    <?php echo $activity['hours_worked']; ?> hours
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if(!empty($activity['feedback'])): ?>
                                            <div class="mt-2 bg-gray-50 p-3 rounded">
                                                <p class="text-sm text-gray-700"><?php echo htmlspecialchars($activity['feedback']); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-4 text-center">
                                <a href="history.php" class="text-blue-600 hover:text-blue-800 text-sm">View full history</a>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-600 text-center py-4">No recent activities found. Start volunteering to build your history!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>