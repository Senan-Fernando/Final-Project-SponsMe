<?php
session_start();
include '../../Model/db.php';
// Check if sponsor is logged in
if (!isset($_SESSION['userEmail'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo "Error: Not logged in";
    exit();
}
// Validate input
if (!isset($_POST['request_id']) || !isset($_POST['status'])) {
    header('HTTP/1.1 400 Bad Request');
    echo "Error: Missing required parameters";
    exit();
}
$request_id = $_POST['request_id'];
$status = $_POST['status'];
// Validate status
if (!in_array($status, ['accepted', 'rejected'])) {
    header('HTTP/1.1 400 Bad Request');
    echo "Error: Invalid status value";
    exit();
}
// Get the sponsor_id based on the logged-in user's email
$userEmail = $_SESSION['userEmail'];
$sponsorQuery = "SELECT id FROM sponsors WHERE email = ?";
$stmt = $conn->prepare($sponsorQuery);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $sponsorRow = $result->fetch_assoc();
    $sponsor_id = $sponsorRow['id'];
    
    // Check if this request belongs to the logged-in sponsor
    $checkQuery = "SELECT id FROM sponsorship_requests WHERE id = ? AND sponsor_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $request_id, $sponsor_id);
    $stmt->execute();
    $checkResult = $stmt->get_result();
    
    if ($checkResult && $checkResult->num_rows > 0) {
        // Update the status
        $updateQuery = "UPDATE sponsorship_requests SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $status, $request_id);
        
        if ($stmt->execute()) {
            echo "Success: Status updated to " . $status;
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo "Error: " . $conn->error;
        }
    } else {
        header('HTTP/1.1 403 Forbidden');
        echo "Error: You do not have permission to update this request";
    }
} else {
    header('HTTP/1.1 404 Not Found');
    echo "Error: Sponsor not found for this email";
}
$conn->close();
?>