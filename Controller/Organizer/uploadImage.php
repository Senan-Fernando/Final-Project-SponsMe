<?php
include '../../Model/db.php';
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userRole'] !== 'organizer') {
    header("Location: ../login.php");
    exit();
}

$uploadDir = '../../uploads/';
$userId = $_POST['user_id'];
$email = $_POST['email'];

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_image']['tmp_name'];
    $fileName = $userId . '_' . time() . '_' . basename($_FILES['profile_image']['name']);
    $destPath = $uploadDir . $fileName;

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $imagePath = 'uploads/' . $fileName;  // Store relative path

        // Delete existing profile image if any
        $deleteQuery = "DELETE FROM image_uploads WHERE user_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Insert the new image path into the database
        $insertQuery = "INSERT INTO image_uploads (user_id, email, image_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iss", $userId, $email, $imagePath);
        if ($stmt->execute()) {
            $_SESSION['upload_success'] = "Image uploaded successfully!";
        } else {
            $_SESSION['upload_error'] = "Database error: Could not save image path.";
        }
        $stmt->close();
    } else {
        $_SESSION['upload_error'] = "Error moving the uploaded file.";
    }
} else {
    $_SESSION['upload_error'] = "File upload failed. Error code: " . $_FILES['profile_image']['error'];
}

$conn->close();
header("Location: ../../Views/Event%20Organizer/Orgprof.php");
exit();
?>
