<?php
include '../../Model/db.php';
header("Content-Type: application/json");

$response = ["icon" => "error", "title" => "Error", "text" => "An unknown error occurred."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $crew_name = mysqli_real_escape_string($conn, $_POST['crew_name']);
    $leader_nic = mysqli_real_escape_string($conn, $_POST['leader_nic']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if email already exists
    $check_email = "SELECT * FROM organizers WHERE email='$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        $response = [
            "icon" => "error",
            "title" => "Oops...",
            "text" => "Email already exists!"
        ];
        echo json_encode($response);
        exit();
    }

    // Validate password match
    if ($password !== $confirm_password) {
        $response = [
            "icon" => "error",
            "title" => "Password Mismatch",
            "text" => "Passwords do not match!"
        ];
        echo json_encode($response);
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert into database
    $sql = "INSERT INTO organizers (crew_name, leader_nic, first_name, last_name, email, mobile, whatsapp, password) 
            VALUES ('$crew_name', '$leader_nic', '$first_name', '$last_name', '$email', '$mobile', '$whatsapp', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        $response = [
            "icon" => "success",
            "title" => "Registration Successful",
            "text" => "You can now log in!",
            "redirect" => "../../Views/Login.php"
        ];
    } else {
        $response = [
            "icon" => "error",
            "title" => "Database Error",
            "text" => "Something went wrong. Please try again."
        ];
    }

    echo json_encode($response);
    $conn->close();
}
?>
