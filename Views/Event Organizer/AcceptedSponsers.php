<?php
include '../../Model/db.php';
session_start();

// Check if the session is set for the organizer
if (!isset($_SESSION['userId']) || $_SESSION['userRole'] !== 'organizer') {
    header("Location: ../login.php");
    exit();
}

$organizer_id = $_SESSION['userId']; // Get the organizer's ID from the session

// Fetch accepted sponsorship requests for the logged-in organizer
$query = "SELECT sr.id, sr.sponsor_id, sr.unit, sr.location, sr.event_type, sr.event_topic, 
                 sr.sponsorship_type, sr.target_audience, sr.message, sr.request_date, 
                 sr.status, s.company_name 
          FROM sponsorship_requests sr
          JOIN sponsors s ON sr.sponsor_id = s.id
          WHERE sr.organizer_id = ? AND sr.status = 'accepted'";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any accepted sponsorship requests exist
$accepted_sponsorships = [];
while ($row = $result->fetch_assoc()) {
    $accepted_sponsorships[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Sponsorship</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <div class="bg-blue-900 text-white w-full md:w-1/4 p-6">
        <h2 class="text-2xl font-bold mb-6 text-center md:text-left">SponsMe</h2>
        <nav class="flex flex-col gap-4">
            <a href="#" onclick="window.location.href='../home.php'" class="bg-blue-700 p-3 rounded text-center">Home</a>
            <a href="#" onclick="window.location.href='Orgprof.php'" class="bg-blue-700 p-3 rounded text-center">Profile</a>
        </nav>
        <div class="mt-auto">
            <a href="login.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Accepted Sponsorship</h3>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4 flex flex-col md:flex-row">
            <li class="nav-item">
                <a class="nav-link active" href="Accepted.php">Accepted Sponsorships</a>
            </li>
        </ul>

        <!-- Requests Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($accepted_sponsorships)): ?>
                <?php foreach ($accepted_sponsorships as $sponsorship): ?>
                    <div class="bg-[#1F509A] rounded-lg shadow-md hover:shadow-lg p-4 flex flex-col items-center transform hover:scale-105 transition duration-300 cursor-pointer">
                        <div class="bg-blue-300 w-full h-32 rounded mb-4 flex items-center justify-center">
                            <button class="btn btn-success w-10" style="cursor: pointer;" onclick="acceptAction()">
                                <i class="bi bi-check-circle"></i>
                            </button>
                        </div>
                        <h4 class="text-white font-semibold"><?php echo htmlspecialchars($sponsorship['company_name']); ?></h4>
                        <p class="text-white"><?php echo htmlspecialchars($sponsorship['unit']); ?></p>
                        <button class="btn btn-primary w-full" onclick="window.location.href='ViewSponsorship.php?sponsor_id=<?php echo $sponsorship['sponsor_id']; ?>'">View and download</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-white">No accepted sponsorships found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
