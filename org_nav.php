<?php
// Get current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Function to determine if a menu item is active
function isActive($page_name) {
    global $current_page;
    return $current_page === $page_name ? 'bg-green-700' : '';
}

// Get pending volunteer applications count
if (!isset($pendingApplications)) {
    try {
        require_once '../db_connection.php';
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM volunteer_applications 
                               WHERE opportunity_id IN (SELECT id FROM volunteer_opportunities 
                                                      WHERE organization_id = :org_id) 
                               AND status = 'pending'");
        $stmt->bindParam(':org_id', $_SESSION['user_id']);
        $stmt->execute();
        $pendingApplications = $stmt->fetch()['total'];
        
        // Get upcoming opportunities count
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM volunteer_opportunities 
                               WHERE organization_id = :org_id 
                               AND start_date >= CURDATE()");
        $stmt->bindParam(':org_id', $_SESSION['user_id']);
        $stmt->execute();
        $upcomingOpportunities = $stmt->fetch()['total'];
    } catch(PDOException $e) {
        $pendingApplications = 0;
        $upcomingOpportunities = 0;
    }
}
?>

<!-- Top Header Bar -->
<div class="bg-green-600 text-white w-full h-16 flex items-center">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <div class="flex items-center">
            <a href="../index.php" class="flex items-center">
                <img src="../images/logo.png" alt="Volunteer Hub" class="h-8 mr-2" onerror="this.onerror=null; this.src='../images/logo-placeholder.png';">
                <span class="font-bold text-xl">Volunteer Hub</span>
            </a>
        </div>
        
        <div class="flex items-center">
            <!-- Quick Add Button -->
            <div class="relative group mr-4">
                <button class="p-2 rounded-full hover:bg-green-700">
                    <i class="fas fa-plus"></i>
                </button>
                <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                    <a href="create_opportunity.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">New Opportunity</a>
                    <a href="invite_volunteer.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Invite Volunteer</a>
                </div>
            </div>
            
            <!-- User Dropdown -->
            <div class="relative group">
                <button class="flex items-center focus:outline-none">
                    <div class="h-8 w-8 rounded-full bg-white flex items-center justify-center text-green-600 font-medium mr-2">
                        <?php echo substr($_SESSION['organization_name'], 0, 2); ?>
                    </div>
                    <span class="hidden md:inline-block"><?php echo htmlspecialchars($_SESSION['organization_name']); ?></span>
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
    <div class="w-64 bg-green-900 text-white flex-shrink-0">
        <div class="p-4 border-b border-green-800">
            <h2 class="text-lg font-medium">Organization Dashboard</h2>
            <p class="text-green-200 text-sm mt-1"><?php echo htmlspecialchars($_SESSION['organization_name']); ?></p>
        </div>
        
        <nav class="mt-5 px-2">
            <a href="dashboard.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('dashboard.php'); ?> hover:bg-green-800 hover:text-white mb-1">
                <i class="fas fa-home mr-3 text-green-300 group-hover:text-green-200"></i>
                Dashboard
            </a>
            
            <a href="opportunities.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('opportunities.php'); ?> hover:bg-green-800 hover:text-white mb-1">
                <i class="fas fa-calendar-alt mr-3 text-green-300 group-hover:text-green-200"></i>
                My Opportunities
                <?php if ($upcomingOpportunities > 0): ?>
                    <span class="ml-auto bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        <?php echo $upcomingOpportunities > 9 ? '9+' : $upcomingOpportunities; ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="create_opportunity.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('create_opportunity.php'); ?> hover:bg-green-800 hover:text-white mb-1">
                <i class="fas fa-plus-circle mr-3 text-green-300 group-hover:text-green-200"></i>
                Create Opportunity
            </a>
            
            <a href="volunteers.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('volunteers.php'); ?> hover:bg-green-800 hover:text-white mb-1">
                <i class="fas fa-users mr-3 text-green-300 group-hover:text-green-200"></i>
                Volunteers
                <?php if ($pendingApplications > 0): ?>
                    <span class="ml-auto bg-yellow-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        <?php echo $pendingApplications > 9 ? '9+' : $pendingApplications; ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="reports.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('reports.php'); ?> hover:bg-green-800 hover:text-white mb-1">
                <i class="fas fa-chart-bar mr-3 text-green-300 group-hover:text-green-200"></i>
                Reports
            </a>
            
            <a href="messages.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('messages.php'); ?> hover:bg-green-800 hover:text-white mb-1">
                <i class="fas fa-envelope mr-3 text-green-300 group-hover:text-green-200"></i>
                Messages
            </a>
            
            <a href="settings.php" class="group flex items-center px-3 py-2 text-base font-medium rounded-md <?php echo isActive('settings.php'); ?> hover:bg-green-800 hover:text-white mb-1">
                <i class="fas fa-cog mr-3 text-green-300 group-hover:text-green-200"></i>
                Settings
            </a>
        </nav>
    </div>
</div>