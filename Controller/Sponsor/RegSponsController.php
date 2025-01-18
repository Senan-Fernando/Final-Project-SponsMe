<?php
// RegSpons.php
include '../../Model/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $sponsor_events = $_POST['sponsor_events'];
    $mobile_no = $_POST['mobile_no'];
    $facebook = isset($_POST['facebook']) ? $_POST['facebook'] : null;
    $instagram = isset($_POST['instagram']) ? $_POST['instagram'] : null;
    $whatsapp = isset($_POST['whatsapp']) ? $_POST['whatsapp'] : null;
    $youtube = isset($_POST['youtube']) ? $_POST['youtube'] : null;
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO sponsors (first_name, last_name, email, address, sponsor_events, mobile_no, facebook, instagram, whatsapp, youtube, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $first_name, $last_name, $email, $address, $sponsor_events, $mobile_no, $facebook, $instagram, $whatsapp, $youtube, $password);

    if ($stmt->execute()) {
        header('Location: ../../Views/Login.php'); 
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>