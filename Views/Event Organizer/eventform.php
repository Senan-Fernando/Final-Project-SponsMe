<?php
include '../../Model/db.php';
session_start();

// Check if the session is set for the organizer
if (!isset($_SESSION['userId']) || $_SESSION['userRole'] !== 'organizer') {
    header("Location: ../login.php");
    exit();
}

$organizer_id = $_SESSION['userId'];

// If form is submitted, store data in session to pass to Matched.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store form data in session
    $_SESSION['event_data'] = [
        'location' => $_POST['location'],
        'event_type' => $_POST['eventType'],
        'event_topic' => $_POST['eventTopic'],
        'sponsorship_type' => $_POST['sponsorshipType'],
        'target_audience' => $_POST['targetAudience'],
        'organizer_id' => $organizer_id
    ];
    
    // Redirect to Matched.php
    header("Location: Matched.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <div class="bg-blue-900 text-white w-full md:w-1/4 p-6 flex flex-col items-center md:items-start">
        <h2 class="text-2xl font-bold mb-6">SponsMe</h2>
        <nav class="flex flex-col gap-4 w-full">
            <a href="../home.php" class="bg-blue-700 p-3 rounded text-center md:text-left">Home</a>
            <a href="Orgprof.php" class="bg-blue-700 p-3 rounded text-center md:text-left">Profile</a>
            <a href="eventform.php" class="bg-blue-700 p-3 rounded text-center md:text-left">Seek Sponsorship</a>
        </nav>
        <div class="mt-auto">
            <a href="../../Controller/Logout.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex justify-center items-center p-4">
        <div class="bg-[#1F509A] rounded-lg shadow-lg p-6 w-full md:w-3/4">
            <h3 class="text-2xl font-bold text-center text-white mb-6">Event Form</h3>

            <!-- Event Form Container -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <!-- Input Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="location" class="block text-white font-medium mb-2">Location</label>
                        <input type="text" id="location" name="location" class="form-control" placeholder="Enter Location" required>
                    </div>
                    <div>
                        <label for="eventType" class="block text-white font-medium mb-2">Event Type</label>
                        <select id="eventType" name="eventType" class="form-select" required>
                            <option selected disabled>Select Event Type</option>
                            <option value="Concert">Concert</option>
                            <option value="Raves">Raves</option>
                            <option value="Gaming">Gaming</option>
                            <option value="Sports">Sports</option>
                            <option value="Charity">Charity</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="eventTopic" class="block text-white font-medium mb-2">Event Topic</label>
                        <input type="text" id="eventTopic" name="eventTopic" class="form-control" placeholder="Enter Event Topic" required>
                    </div>
                    <div>
                        <label for="sponsorshipType" class="block text-white font-medium mb-2">Sponsorship Type</label>
                        <select id="sponsorshipType" name="sponsorshipType" class="form-select" required>
                            <option selected disabled>Select Sponsorship Type</option>
                            <option value="Platinum">Platinum</option>
                            <option value="Gold">Gold</option>
                            <option value="Silver">Silver</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="targetAudience" class="block text-white font-medium mb-2">Target Audience</label>
                    <input type="text" id="targetAudience" name="targetAudience" class="form-control" placeholder="Enter Target Audience" required>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-6 bg-green-600 hover:bg-green-700 transition-colors">
                        Find Matching Sponsors
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>