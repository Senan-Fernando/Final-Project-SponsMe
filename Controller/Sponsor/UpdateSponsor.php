<?php
session_start();
include '../../Model/db.php';

// Check if the user is logged in
if (!isset($_SESSION['userEmail'])) {
    header('Location: ../../Views/Login.php');
    exit();
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $mobile_no = $_POST['mobile_no'];
    $whatsapp = $_POST['whatsapp'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $youtube = $_POST['youtube'];
    $email = $_SESSION['userEmail'];
    $profile_picture = null;

    // Check if a profile picture is uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['profile_picture']['tmp_name']);
        $profile_picture = base64_encode($imageData);
    }

    // Update query
    $sql = "UPDATE `sponsors` SET 
            `first_name` = ?, 
            `last_name` = ?, 
            `address` = ?, 
            `mobile_no` = ?, 
            `whatsapp` = ?, 
            `facebook` = ?, 
            `instagram` = ?, 
            `youtube` = ?, 
            `profile_picture` = ?
            WHERE `email` = ?";

    // Separate full name into first and last name
    $name_parts = explode(' ', $name, 2);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssss",
        $first_name,
        $last_name,
        $address,
        $mobile_no,
        $whatsapp,
        $facebook,
        $instagram,
        $youtube,
        $profile_picture,
        $email
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('Profile updated successfully!');
                window.location.href = '../../Views/Event Sponsor/Sponsorprof.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating profile. Please try again.');
                window.location.href = '../../Views/Event Sponsor/Sponsorprof.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
