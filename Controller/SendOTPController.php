<?php
include '../Model/db.php';
session_start();
header("Content-Type: application/json");

// Notify.lk credentials
$user_id = "NotifyDEMO"; // Replace with your User ID
$api_key = "rUGNbLQfBn3JzbkNvuzX"; // Replace with your API Key
$sender_id = "27043"; // Replace with your Sender ID

// Function to send SMS via Notify.lk (using cURL)
function sendSMS($user_id, $api_key, $to, $message, $sender_id) {
    $url = "https://app.notify.lk/api/v1/sms/send";
    $data = [
        "user_id" => $user_id,
        "api_key" => $api_key,
        "sender_id" => $sender_id,
        "to" => $to,
        "message" => $message
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response && json_decode($response)->status === "success";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if email exists and phone matches
    $sql_sponsor = "SELECT id, mobile_no FROM sponsors WHERE email = ? AND mobile_no = ?";
    $stmt_sponsor = $conn->prepare($sql_sponsor);
    $stmt_sponsor->bind_param("ss", $email, $phone);
    $stmt_sponsor->execute();
    $result_sponsor = $stmt_sponsor->get_result();

    $sql_organizer = "SELECT id, mobile FROM organizers WHERE email = ? AND mobile = ?";
    $stmt_organizer = $conn->prepare($sql_organizer);
    $stmt_organizer->bind_param("ss", $email, $phone);
    $stmt_organizer->execute();
    $result_organizer = $stmt_organizer->get_result();

    if ($result_sponsor->num_rows > 0) {
        $user_type = 'sponsor';
    } elseif ($result_organizer->num_rows > 0) {
        $user_type = 'organizer';
    } else {
        echo json_encode([
            "icon" => "error",
            "title" => "Error",
            "text" => "Email or phone number not found."
        ]);
        exit();
    }

    // Generate OTP
    $otp = rand(100000, 999999);

    // Update database with OTP
    $sql_update = "UPDATE " . ($user_type === 'sponsor' ? 'sponsors' : 'organizers') . " SET reset_code = ? WHERE email = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $otp, $email);
    $stmt_update->execute();

    // Send OTP via SMS
    $message = "Your SponsMe OTP is: $otp. Valid for 10 minutes.";
    if (sendSMS($user_id, $api_key, $phone, $message, $sender_id)) {
        echo json_encode([
            "icon" => "success",
            "title" => "OTP Sent",
            "text" => "An OTP has been sent to your phone."
        ]);
    } else {
        echo json_encode([
            "icon" => "error",
            "title" => "Error",
            "text" => "Failed to send OTP. Please try again."
        ]);
    }

    $conn->close();
}
?>