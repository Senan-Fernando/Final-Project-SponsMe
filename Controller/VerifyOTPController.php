<?php
include '../Model/db.php';
session_start();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    // Check OTP in sponsors table
    $sql_sponsor = "SELECT id FROM sponsors WHERE email = ? AND reset_code = ?";
    $stmt_sponsor = $conn->prepare($sql_sponsor);
    $stmt_sponsor->bind_param("ss", $email, $otp);
    $stmt_sponsor->execute();
    $result_sponsor = $stmt_sponsor->get_result();

    // Check OTP in organizers table
    $sql_organizer = "SELECT id FROM organizers WHERE email = ? AND reset_code = ?";
    $stmt_organizer = $conn->prepare($sql_organizer);
    $stmt_organizer->bind_param("ss", $email, $otp);
    $stmt_organizer->execute();
    $result_organizer = $stmt_organizer->get_result();

    if ($result_sponsor->num_rows > 0 || $result_organizer->num_rows > 0) {
        echo json_encode([
            "icon" => "success",
            "title" => "OTP Verified",
            "text" => "Please set your new password."
        ]);
    } else {
        echo json_encode([
            "icon" => "error",
            "title" => "Invalid OTP",
            "text" => "The OTP you entered is incorrect."
        ]);
    }

    $conn->close();
}
?>