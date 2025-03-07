<?php
// Include database connection
include '../../Model/db.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['action']) && $_GET['action'] === 'fetchCompany') {
    $registrationCode = $_GET['code'];
    
    $query = "SELECT `company_name`, `unit`, `address` FROM `sponsors` WHERE `company_registration_code` = ?";
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $registrationCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if company found
    if ($result->num_rows > 0) {
        $companyData = $result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'company_name' => $companyData['company_name'],
            'unit' => $companyData['unit'],
            'address' => $companyData['address']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No company found with that registration code'
        ]);
    }
    
    $stmt->close();
    exit;
}

// If this is a POST request to register a new sponsor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyRegistrationCode = $_POST['company_registration_code'];
    $companyName = $_POST['companyname'];
    $unitName = $_POST['unitname'];
    $address = $_POST['address'];
    $empId = $_POST['emp_id'];
    $sponsorEvents = $_POST['sponsor_events'];
    $email = $_POST['email'];
    $mobileNo = $_POST['mobile_no'];
    $facebook = $_POST['facebook'] ?? '';
    $instagram = $_POST['instagram'] ?? '';
    $password = $_POST['password'];
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $checkEmailQuery = "SELECT * FROM `sponsors` WHERE `email` = ?";
    $stmtCheck = $conn->prepare($checkEmailQuery);
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    $emailResult = $stmtCheck->get_result();
    
    if ($emailResult->num_rows > 0) {
        $_SESSION['error'] = "Email already registered. Please use a different email.";
        header("Location: ../../Views/Event Sponsor/RegExistingCompany.php");
        exit;
    }
    
    // Insert new sponsor record with company_status set to 'Sub'
    $insertQuery = "INSERT INTO `sponsors` 
                    (`company_name`, `unit`, `company_registration_code`, `address`, 
                     `emp_id`, `sponsor_events`, `email`, `mobile_no`, 
                     `facebook`, `instagram`, `password`, `company_status`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Sub')";
    
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssssssssss", 
                     $companyName, $unitName, $companyRegistrationCode, $address,
                     $empId, $sponsorEvents, $email, $mobileNo,
                     $facebook, $instagram, $hashedPassword);
    
    if ($stmt->execute()) {
        // Registration successful
        $_SESSION['success'] = "Sponsor registration successful. You can now login.";
        header("Location: ../../Views/Login.php");
        exit;
    } else {
        // Registration failed
        $_SESSION['error'] = "Registration failed: " . $conn->error;
        header("Location: ../../Views/Event Sponsor/RegExistingCompany.php");
        exit;
    }
    
    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>