<?php
include '../Model/db.php';
session_start();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $confirm_password = $_POST['confirm_password'];

    if (password_verify($confirm_password, $new_password)) {
        // Update sponsors table
        $sql_sponsor = "UPDATE sponsors SET password = ?, reset_code = NULL WHERE email = ?";
        $stmt_sponsor = $conn->prepare($sql_sponsor);
        $stmt_sponsor->bind_param("ss", $new_password, $email);
        $stmt_sponsor->execute();

        // Update organizers table
        $sql_organizer = "UPDATE organizers SET password = ?, reset_code = NULL WHERE email = ?";
        $stmt_organizer = $conn->prepare($sql_organizer);
        $stmt_organizer->bind_param("ss", $new_password, $email);
        $stmt_organizer->execute();

        echo json_encode([
            "icon" => "success",
            "title" => "Password Reset",
            "text" => "Your password has been updated successfully."
        ]);
    } else {
        echo json_encode([
            "icon" => "error",
            "title" => "Error",
            "text" => "Passwords do not match."
        ]);
    }

    $conn->close();
}
?>