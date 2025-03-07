<?php
session_start();
include '../../Model/db.php';

// Check if the user is logged in
if (!isset($_SESSION['userEmail'])) {
    header('Location: ../../Views/Login.php');
    exit();
}

// Create upload directory if it doesn't exist
$uploadDir = '../../uploads/sponsor_profiles/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// First, check if profile_picture column exists in the sponsors table
$checkColumnQuery = "SHOW COLUMNS FROM sponsors LIKE 'profile_picture'";
$columnExists = $conn->query($checkColumnQuery)->num_rows > 0;

// If the column doesn't exist, add it
if (!$columnExists) {
    $alterTableQuery = "ALTER TABLE sponsors ADD COLUMN profile_picture VARCHAR(255)";
    if (!$conn->query($alterTableQuery)) {
        die("Error adding profile_picture column: " . $conn->error);
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get existing data
    $email = $_SESSION['userEmail'];
    $sponsor_id = $_POST['sponsor_id'];
    $company_status = $_POST['company_status'];

    // Get all form fields
    $company_name = isset($_POST['company_name']) ? $_POST['company_name'] : '';
    $unit = isset($_POST['unit']) ? $_POST['unit'] : '';
    $company_registration_code = isset($_POST['company_registration_code']) ? $_POST['company_registration_code'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $emp_id = isset($_POST['emp_id']) ? $_POST['emp_id'] : '';
    $sponsor_events = isset($_POST['sponsor_events']) ? $_POST['sponsor_events'] : '';
    $mobile_no = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : '';
    $facebook = isset($_POST['facebook']) ? $_POST['facebook'] : '';
    $instagram = isset($_POST['instagram']) ? $_POST['instagram'] : '';

    // Get current data
    $query = "SELECT * FROM sponsors WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $sponsor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_data = $result->fetch_assoc();
    $profile_picture_path = isset($current_data['profile_picture']) ? $current_data['profile_picture'] : '';
    $stmt->close();

    // Process profile picture if uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $uploadedFileType = finfo_file($fileInfo, $_FILES['profile_picture']['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($uploadedFileType, $allowedTypes)) {
            echo "<script>
                    alert('Invalid file type. Only JPG, PNG and GIF are allowed.');
                    window.location.href = '../../Views/Event Sponsor/SponsorProf.php';
                  </script>";
            exit();
        }

        // Create a unique filename
        $fileType = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $fileName = 'sponsor_' . $sponsor_id . '_' . time() . '.' . $fileType;
        $targetFilePath = $uploadDir . $fileName;
        $relativeFilePath = '../../uploads/sponsor_profiles/' . $fileName;

        // Delete previous profile picture if it exists
        if (!empty($current_data['profile_picture']) && file_exists('../../' . $current_data['profile_picture'])) {
            unlink('../../' . $current_data['profile_picture']);
        }

        // Upload new file
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
            $profile_picture_path = $relativeFilePath;

            // Also update the image_uploads table to keep track of all uploads
            $imageUploadSql = "INSERT INTO image_uploads (user_id, email, image_path, uploaded_at) 
                               VALUES (?, ?, ?, NOW())";
            $imageStmt = $conn->prepare($imageUploadSql);

            if ($imageStmt === false) {
                // Log error but continue with the profile update
                error_log("Failed to prepare image upload statement: " . $conn->error);
            } else {
                $imageStmt->bind_param("iss", $sponsor_id, $email, $relativeFilePath);
                $imageStmt->execute();
                $imageStmt->close();
            }
        } else {
            echo "<script>
                    alert('Error uploading profile picture. Please try again.');
                    window.location.href = '../../Views/Sponsor/SponsorProf.php';
                  </script>";
            exit();
        }
    }

    // Build SQL query based on company status
    if ($company_status === 'Main') {
        $sql = "UPDATE sponsors SET 
                company_name = ?,
                unit = ?, 
                company_registration_code = ?, 
                address = ?, 
                emp_id = ?, 
                sponsor_events = ?, 
                mobile_no = ?, 
                facebook = ?, 
                instagram = ?, 
                profile_picture = ? 
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . $conn->error . " SQL: " . $sql);
        }

        $stmt->bind_param(
            "ssssssssssi",
            $company_name,
            $unit,
            $company_registration_code,
            $address,
            $emp_id,
            $sponsor_events,
            $mobile_no,
            $facebook,
            $instagram,
            $profile_picture_path,
            $sponsor_id
        );
    } else {
        // For non-Main status, don't update company_name
        $sql = "UPDATE sponsors SET 
                unit = ?, 
                company_registration_code = ?, 
                address = ?, 
                emp_id = ?, 
                sponsor_events = ?, 
                mobile_no = ?, 
                facebook = ?, 
                instagram = ?, 
                profile_picture = ? 
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . $conn->error . " SQL: " . $sql);
        }

        $stmt->bind_param(
            "sssssssssi",
            $unit,
            $company_registration_code,
            $address,
            $emp_id,
            $sponsor_events,
            $mobile_no,
            $facebook,
            $instagram,
            $profile_picture_path,
            $sponsor_id
        );
    }

    // Execute query
    if ($stmt->execute()) {
        echo "<script>
                alert('Profile updated successfully!');
                window.location.href = '../../Views/Event Sponsor/SponsorProf.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating profile: " . $stmt->error . "');
                window.location.href = '../../Views/Event Sponsor/SponsorProf.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
