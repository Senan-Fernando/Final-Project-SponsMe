<?php
session_start();
require_once '../../Model/db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Log incoming data
        error_log("Received POST data: " . print_r($_POST, true));
        
        // Validate user ID
        if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
            throw new Exception("User ID is required");
        }
        $user_id = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
        if ($user_id === false) {
            throw new Exception("Invalid user ID format");
        }

        // Validate and sanitize input
        $required_fields = ['first_name', 'last_name', 'crew_name', 'leader_nic', 'mobile'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                throw new Exception("$field is required");
            }
        }

        $first_name = filter_var(trim($_POST['first_name']), FILTER_SANITIZE_STRING);
        $last_name = filter_var(trim($_POST['last_name']), FILTER_SANITIZE_STRING);
        $crew_name = filter_var(trim($_POST['crew_name']), FILTER_SANITIZE_STRING);
        $leader_nic = filter_var(trim($_POST['leader_nic']), FILTER_SANITIZE_STRING);
        $mobile = filter_var(trim($_POST['mobile']), FILTER_SANITIZE_STRING);
        $whatsapp = filter_var(trim($_POST['whatsapp'] ?? ''), FILTER_SANITIZE_STRING);

        // Validate NIC format
        if (!preg_match('/^[0-9]{9}[vVxX]?$|^[0-9]{12}$/', $leader_nic)) {
            throw new Exception("Invalid NIC format");
        }

        // Validate mobile number
        if (!preg_match('/^\d{10}$/', $mobile)) {
            throw new Exception("Invalid mobile number format");
        }

        // Begin transaction
        $conn->begin_transaction();

        // Check if user exists
        $check_stmt = $conn->prepare("SELECT id FROM organizers WHERE id = ?");
        if (!$check_stmt) {
            throw new Exception("Database prepare error: " . $conn->error);
        }
        
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("User not found");
        }
        
        $check_stmt->close();

        // Update user information
        $update_stmt = $conn->prepare("
            UPDATE organizers 
            SET first_name = ?,
                last_name = ?,
                crew_name = ?,
                leader_nic = ?,
                mobile = ?,
                whatsapp = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        if (!$update_stmt) {
            throw new Exception("Database prepare error: " . $conn->error);
        }

        $update_stmt->bind_param(
            "ssssssi",
            $first_name,
            $last_name,
            $crew_name,
            $leader_nic,
            $mobile,
            $whatsapp,
            $user_id
        );

        if (!$update_stmt->execute()) {
            throw new Exception("Update failed: " . $update_stmt->error);
        }

        $affected_rows = $update_stmt->affected_rows;
        $update_stmt->close();

        // Commit transaction
        $conn->commit();

        if ($affected_rows > 0) {
            $_SESSION['success'] = "Profile updated successfully";
            error_log("Profile updated successfully for user ID: $user_id");
        } else {
            $_SESSION['info'] = "No changes were made to the profile";
            error_log("No changes made for user ID: $user_id");
        }

    } catch (Exception $e) {
        // Rollback transaction on error
        if (isset($conn) && $conn->connect_errno === 0) {
            $conn->rollback();
        }
        
        error_log("Error updating profile: " . $e->getMessage());
        $_SESSION['error'] = "Error updating profile: " . $e->getMessage();
    } finally {
        // Close connection
        if (isset($conn) && $conn->connect_errno === 0) {
            $conn->close();
        }
    }

    // Redirect back with proper URL encoding
    header("Location: ../../Views/Event%20Organizer/Orgprof.php");
    exit();
}
?>