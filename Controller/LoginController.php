<?php
include '../Model/db.php';
session_start();
header("Content-Type: application/json");

// Enable error reporting for debugging (Remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ["icon" => "error", "title" => "Login Failed", "text" => "Invalid email or password."];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $isSponsor = false;
    $isOrganizer = false;

    // Check sponsors table
    $sql_sponsor = "SELECT id, company_name, email, password FROM sponsors WHERE email = ?";
    $stmt_sponsor = $conn->prepare($sql_sponsor);
    
    if (!$stmt_sponsor) {
        echo json_encode(["icon" => "error", "title" => "Database Error", "text" => "Failed to prepare statement: " . $conn->error]);
        exit();
    }

    $stmt_sponsor->bind_param("s", $email);
    $stmt_sponsor->execute();
    $result_sponsor = $stmt_sponsor->get_result();

    if ($result_sponsor->num_rows > 0) {
        $sponsor = $result_sponsor->fetch_assoc();
        if (password_verify($password, $sponsor['password'])) {
            $_SESSION['userEmail'] = $sponsor['email'];
            $_SESSION['userRole'] = 'sponsor';
            $_SESSION['userId'] = $sponsor['id'];
            $_SESSION['userName'] = $sponsor['company_name']; // Changed from first_name/last_name to company_name

            $response = [
                "icon" => "success",
                "title" => "Login Successful",
                "text" => "Welcome, {$sponsor['company_name']}!", // Changed from first_name to company_name
                "redirect" => "../Views/Event Sponsor/Sponsorprof.php"
            ];

            $isSponsor = true;
        }
    }

    // Check organizers table
    if (!$isSponsor) {
        $sql_organizer = "SELECT id, first_name, last_name, email, password FROM organizers WHERE email = ?";
        $stmt_organizer = $conn->prepare($sql_organizer);

        if (!$stmt_organizer) {
            echo json_encode(["icon" => "error", "title" => "Database Error", "text" => "Failed to prepare statement: " . $conn->error]);
            exit();
        }

        $stmt_organizer->bind_param("s", $email);
        $stmt_organizer->execute();
        $result_organizer = $stmt_organizer->get_result();

        if ($result_organizer->num_rows > 0) {
            $organizer = $result_organizer->fetch_assoc();
            if (password_verify($password, $organizer['password'])) {
                $_SESSION['userEmail'] = $organizer['email'];
                $_SESSION['userRole'] = 'organizer';
                $_SESSION['userId'] = $organizer['id'];
                $_SESSION['userName'] = $organizer['first_name'] . ' ' . $organizer['last_name'];

                $response = [
                    "icon" => "success",
                    "title" => "Login Successful",
                    "text" => "Welcome, {$organizer['first_name']}!",
                    "redirect" => "../Views/Event Organizer/Orgprof.php"
                ];

                $isOrganizer = true;
            }
        }
    }

    // If both roles exist
    if ($isSponsor && $isOrganizer) {
        $_SESSION['userRole'] = 'sponsor,organizer';
        $response = [
            "icon" => "success",
            "title" => "Login Successful",
            "text" => "You have multiple roles.",
            "redirect" => "../Views/Common/Dashboard.php"
        ];
    }

    echo json_encode($response);
    $conn->close();
}
?>