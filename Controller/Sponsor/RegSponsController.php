<?php
include '../../Model/db.php';

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

    // Default response
    $response = ["status" => false, "message" => "An unknown error occurred"];

    // Validate sponsor_events selection
    if (empty($sponsor_events)) {
        $response = ["status" => false, "message" => "Please select a Sponsor Event!"];
    }
    // Check if email or company registration code already exists
    else {
        $check_existing = "SELECT * FROM sponsors WHERE email='$email' OR company_registration_code='$company_registration_code'";
        $result = $conn->query($check_existing);

        if ($result->num_rows > 0) {
            $response = ["status" => false, "message" => "Email or Company Registration Code already exists!"];
        }
        // Validate password match
        elseif ($password !== $confirm_password) {
            $response = ["status" => false, "message" => "Passwords do not match!"];
        }
        else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert into database
            $sql = "INSERT INTO sponsors (company_name, unit, company_registration_code, address, emp_id, sponsor_events, email, mobile_no, facebook, instagram, password, company_status) 
                    VALUES ('$company_name', '$unit', '$company_registration_code', '$address', '$emp_id', '$sponsor_events', '$email', '$mobile_no', '$facebook', '$instagram', '$hashed_password', 'Main')";

            if ($conn->query($sql) === TRUE) {
                $response = ["status" => true, "message" => "Registration successful! You can now log in.", "redirect" => "../../Views/Login.php"];
            } else {
                error_log("Database Error: " . $conn->error);
                $response = ["status" => false, "message" => "Something went wrong. Please try again."];
            }
        }
    }

    // Return JSON response for AJAX handling
    header('Content-Type: application/json');
    echo json_encode($response);
    
    $conn->close();
    exit();
}
?>