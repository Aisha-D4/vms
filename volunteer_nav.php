<?php
// Get current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Function to determine if a menu item is active
function isActive($page_name) {
    global $current_page;
    return $current_page === $page_name ? 'bg-blue-700' : '';
}

// Get volunteer stats
if (!isset($upcomingOpportunities)) {
    try {
        require_once '../db_connection.php';
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM volunteer_opportunities 
                               WHERE id IN (SELECT opportunity_id FROM volunteer_assignments 
                                           WHERE volunteer_id = :user_id AND status = 'upcoming')");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $upcomingOpportunities = $stmt->fetch()['total'];
        
        // Get completed opportunities count
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM volunteer_assignments 
                               WHERE volunteer_id = :user_id AND status = 'completed'");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $completedOpportunities = $stmt->fetch()['total'];
        
        // Get total hours volunteered
        $stmt = $conn->prepare("SELECT SUM(hours_worked) as total FROM volunteer_assignments 
                               WHERE volunteer_id = :user_id AND status = 'completed'");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $totalHours = $stmt->fetch()['total'] ?: 0;
    } catch(PDOException $e) {
        $upcomingOpportunities = 0;
        $completedOpportunities = 0;
        $totalHours = 0;
    }
}
?>

<!-- Top Header Bar -->
<div class="bg-blue-600 text-white w-full h-16 flex items-center">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <div class="flex items-center">
            <a href="../index.php" class="flex items-center">
                <img src="../images/logo.png" alt="Volunteer Hub" class="h-8 mr-2" onerror="this.onerror=null; this.src='../images/logo-placeholder.png';">
                <span class="font-bold text-xl">Volunteer Hub</span>
            </a>
        </div>
        
        <div class="flex items-center">
            <!-- Notifications -->
            <div class="relative mx-2">
                <a href="notifications.php" class="p-2 rounded-full hover:bg-blue-700">
                    <i class="fas fa-bell text-xl"></i>
                </a>
            </div>
            
            <!-- User Dropdown -->
            <div class="relative group">
                <button class="flex items-center focus:outline-none">
                    <div class="h-8 w-8 rounded-full bg-white flex items-center justify-center text-blue-600 font-medium mr-2">
                        <?php echo substr($_SESSION['first_name'], 0, 1) . substr($_SESSION['last_name'], 0, 1); ?>
                    </div>
                    <span class="hidden md:inline-block"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></span>
                    <i class="fas fa-chevron-down ml-1"></i>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                    <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <a href="settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    <div class="border-t border-gray-100"></div>
                    <a href="../logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-grow min-h-screen">
    <!-- Sidebar Navigation -->
    <div class="w-64 bg-blue-900 text-white flex-shrink-0">
        <div class="p-4 border-b border-blue-800">
            <h2 class="text-lg font-medium">Volunteer Dashboard</h2>
            <p class="text-blue-200 text-sm mt-1"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></p>
        </div>
        
        <div class="p-4 border-b border-blue-800">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm">Completed:</span>
                <span class="font-bold"><?php echo $completedOpportunities; ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm">Total Hours:</span>
                <span class="font-bold"><?php echo $totalHours; ?></span>
            </div>
        </div>
        
        <nav class="mt-5 px-2">
            <a href="dashboard.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('dashboard.php'); ?> hover:bg-blue-800 hover:text-white mb-1">
                <i class="fas fa-home mr-3 text-blue-300 group-hover:text-blue-200"></i>
                Dashboard
            </a>
            
            <a href="opportunities.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('opportunities.php'); ?> hover:bg-blue-800 hover:text-white mb-1">
                <i class="fas fa-search mr-3 text-blue-300 group-hover:text-blue-200"></i>
                Find Opportunities
            </a>
            
            <a href="my_opportunities.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('my_opportunities.php'); ?> hover:bg-blue-800 hover:text-white mb-1">
                <i class="fas fa-calendar-check mr-3 text-blue-300 group-hover:text-blue-200"></i>
                My Opportunities
                <?php if ($upcomingOpportunities > 0): ?>
                    <span class="ml-auto bg-yellow-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        <?php echo $upcomingOpportunities > 9 ? '9+' : $upcomingOpportunities; ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="history.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('history.php'); ?> hover:bg-blue-800 hover:text-white mb-1">
                <i class="fas fa-history mr-3 text-blue-300 group-hover:text-blue-200"></i>
                Volunteer History
            </a>
            
            <a href="messages.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('messages.php'); ?> hover:bg-blue-800 hover:text-white mb-1">
                <i class="fas fa-envelope mr-3 text-blue-300 group-hover:text-blue-200"></i>
                Messages
            </a>
            
            <a href="badges.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('badges.php'); ?> hover:bg-blue-800 hover:text-white mb-1">
                <i class="fas fa-award mr-3 text-blue-300 group-hover:text-blue-200"></i>
                Badges & Achievements
            </a>
            
            <a href="recommendations.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('recommendations.php'); ?> hover:bg-blue-800 hover:text-white mb-1">
                <i class="fas fa-lightbulb mr-3 text-blue-300 group-hover:text-blue-200"></i>
                Recommendations
            </a>
        </nav>
    </div>
</div>