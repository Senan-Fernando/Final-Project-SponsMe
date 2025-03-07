<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <script>
        // Clear localStorage and sessionStorage
        localStorage.clear();
        sessionStorage.clear();

        // Redirect to the login page after clearing storage
        window.location.href = '../Views/home.php';
    </script>
</head>
<body>
    <p>Logging out... Please wait.</p>
</body>
</html>
