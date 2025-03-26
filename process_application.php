<?php
session_start();
include 'connect.php';

// Ensure only logged-in organizations can process applications
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'organization') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Validate input
if (!isset($_POST['applicationId']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$applicationId = intval($_POST['applicationId']);
$action = $_POST['action'];

try {
    // Prepare statement to update application status
    $query = "UPDATE volunteer_applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    // Set status based on action
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    $stmt->bind_param("si", $status, $applicationId);
    
    // Execute the update
    $result = $stmt->execute();
    
    if ($result) {
        // If approved, you might want to add the volunteer to your organization's volunteers table
        if ($action === 'approve') {
            $insertVolunteerQuery = "INSERT INTO organization_volunteers (organization_id, volunteer_id, status) 
                                     SELECT organization_id, volunteer_id, 'active' 
                                     FROM volunteer_applications 
                                     WHERE id = ?";
            $insertStmt = $conn->prepare($insertVolunteerQuery);
            $insertStmt->bind_param("i", $applicationId);
            $insertStmt->execute();
            $insertStmt->close();
        }
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}
?>