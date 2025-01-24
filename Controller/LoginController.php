<?php
include '../Model/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Start session
    session_start();

    // Check in sponsors table
    $sql_sponsor = "SELECT id, first_name, last_name, email, password FROM sponsors WHERE email = ?";
    $stmt_sponsor = $conn->prepare($sql_sponsor);
    $stmt_sponsor->bind_param("s", $email);
    $stmt_sponsor->execute();
    $result_sponsor = $stmt_sponsor->get_result();

    $isSponsor = false;
    $isOrganizer = false;

    if ($result_sponsor->num_rows > 0) {
        $sponsor = $result_sponsor->fetch_assoc();

        if (password_verify($password, $sponsor['password'])) {
            // Save role and email in session
            $_SESSION['userEmail'] = $sponsor['email'];
            $_SESSION['userRole'] = 'sponsor';
            $_SESSION['userId'] = $sponsor['id'];
            $_SESSION['userName'] = $sponsor['first_name'] . ' ' . $sponsor['last_name'];

            echo "<script>
                localStorage.setItem('userRole', 'sponsor');
                localStorage.setItem('userId', '{$sponsor['id']}');
                localStorage.setItem('userName', '{$sponsor['first_name']} {$sponsor['last_name']}');
                sessionStorage.setItem('userEmail', '{$sponsor['email']}');
                window.location.href = '../Views/Event Sponsor/Sponsorprof.php';
            </script>";

            $isSponsor = true;
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
            // Save role and email in session
            $_SESSION['userEmail'] = $organizer['email'];
            $_SESSION['userRole'] = 'organizer';
            $_SESSION['userId'] = $organizer['id'];
            $_SESSION['userName'] = $organizer['first_name'] . ' ' . $organizer['last_name'];

            echo "<script>
                localStorage.setItem('userRole', 'organizer');
                localStorage.setItem('userId', '{$organizer['id']}');
                localStorage.setItem('userName', '{$organizer['first_name']} {$organizer['last_name']}');
                sessionStorage.setItem('userEmail', '{$organizer['email']}');
                window.location.href = '../Views/Event Organizer/Orgprof.php';
            </script>";

            $isOrganizer = true;
            exit();
        } else {
            echo "Invalid password for organizer.";
            exit();
        }
    }

    // If both roles exist
    if ($isSponsor && $isOrganizer) {
        $_SESSION['userRole'] = 'sponsor,organizer';
        echo "<script>
            localStorage.setItem('userRole', 'sponsor,organizer');
            window.location.href = '../Views/Common/Dashboard.php';
        </script>";
        exit();
    }

    // If no match found
    echo "No account found with this email.";
    exit();
}
?>
