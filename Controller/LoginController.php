<?php
include '../Model/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check in sponsors table
    $sql_sponsor = "SELECT id, first_name, last_name, email, password FROM sponsors WHERE email = ?";
    $stmt_sponsor = $conn->prepare($sql_sponsor);
    $stmt_sponsor->bind_param("s", $email);
    $stmt_sponsor->execute();
    $result_sponsor = $stmt_sponsor->get_result();

    if ($result_sponsor->num_rows > 0) {
        $sponsor = $result_sponsor->fetch_assoc();

        if (password_verify($password, $sponsor['password'])) {
            // Save role in local storage
            echo "<script>
                localStorage.setItem('userRole', 'sponsor');
                localStorage.setItem('userId', '{$sponsor['id']}');
                localStorage.setItem('userName', '{$sponsor['first_name']} {$sponsor['last_name']}');
            </script>";

            // Redirect to sponsor profile
            header('Location: ../Views/Event Sponsor/Sponsorprof.php');
            exit();
        } else {
            echo "Invalid password for sponsor.";
            exit();
        }
    }

    // Check in organizers table
    $sql_organizer = "SELECT id, first_name, last_name, email, password FROM organizers WHERE email = ?";
    $stmt_organizer = $conn->prepare($sql_organizer);
    $stmt_organizer->bind_param("s", $email);
    $stmt_organizer->execute();
    $result_organizer = $stmt_organizer->get_result();

    if ($result_organizer->num_rows > 0) {
        $organizer = $result_organizer->fetch_assoc();

        if (password_verify($password, $organizer['password'])) {
            // Save role in local storage
            echo "<script>
                localStorage.setItem('userRole', 'organizer');
                localStorage.setItem('userId', '{$organizer['id']}');
                localStorage.setItem('userName', '{$organizer['first_name']} {$organizer['last_name']}');
            </script>";

            // Redirect to organizer profile
            header('Location: ../Views/Event Organizer/Orgprof.php');
            exit();
        } else {
            echo "Invalid password for organizer.";
            exit();
        }
    }

    // If no match found
    echo "No account found with this email.";
    exit();
}
?>
