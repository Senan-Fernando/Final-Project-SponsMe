<?php
include '../../Model/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile_no = $_POST['mobile_no'];
    $whatsapp = $_POST['whatsapp'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $sql = "SELECT COUNT(*) AS count FROM organizers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error in preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Error: Email is already registered.";
        exit();
    }

    // Insert new organizer into the database
    $sql = "INSERT INTO organizers (first_name, last_name, email, mobile_no, whatsapp, password) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error in preparing statement: " . $conn->error);
    }
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $mobile_no, $whatsapp, $password);

    if ($stmt->execute()) {
        header('Location: ../../Views/Login.php');
        exit();
    } else {
        echo "Error in execution: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
