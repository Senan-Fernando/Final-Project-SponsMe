<?php
include '../../Model/db.php';
header("Content-Type: application/json");

// Default error response
$response = ["icon" => "error", "title" => "Error", "text" => "An unknown error occurred."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = mysqli_real_escape_string($conn, $_POST['companyname']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    $company_registration_code = mysqli_real_escape_string($conn, $_POST['company_registration_code']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
    $sponsor_events = isset($_POST['sponsor_events']) ? mysqli_real_escape_string($conn, $_POST['sponsor_events']) : null;
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile_no = mysqli_real_escape_string($conn, $_POST['mobile_no']);
    $facebook = mysqli_real_escape_string($conn, $_POST['facebook']);
    $instagram = mysqli_real_escape_string($conn, $_POST['instagram']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate sponsor_events selection
    if (empty($sponsor_events)) {
        echo json_encode(["icon" => "error", "title" => "Selection Error", "text" => "Please select a Sponsor Event!"]);
        exit();
    }

    // Check if email or company registration code already exists
    $check_existing = "SELECT * FROM sponsors WHERE email='$email' OR company_registration_code='$company_registration_code'";
    $result = $conn->query($check_existing);

    if ($result->num_rows > 0) {
        echo json_encode(["icon" => "error", "title" => "Duplicate Entry", "text" => "Email or Company Registration Code already exists!"]);
        exit();
    }

    // Validate password match
    if ($password !== $confirm_password) {
        echo json_encode(["icon" => "error", "title" => "Password Mismatch", "text" => "Passwords do not match!"]);
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert into database
    $sql = "INSERT INTO sponsors (company_name, unit, company_registration_code, address, emp_id, sponsor_events, email, mobile_no, facebook, instagram, password, company_status) 
            VALUES ('$company_name', '$unit', '$company_registration_code', '$address', '$emp_id', '$sponsor_events', '$email', '$mobile_no', '$facebook', '$instagram', '$hashed_password', 'Main')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["icon" => "success", "title" => "Registration Successful", "text" => "You can now log in!", "redirect" => "../../Views/Login.php"]);
    } else {
        error_log("Database Error: " . $conn->error);
        echo json_encode(["icon" => "error", "title" => "Database Error", "text" => "Something went wrong. Please try again."]);
    }

    $conn->close();
    exit();
}
?>
