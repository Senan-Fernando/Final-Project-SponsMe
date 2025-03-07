<?php
include '../../Model/db.php';
session_start();

// Check if the session is set for the organizer
if (!isset($_SESSION['userId']) || $_SESSION['userRole'] !== 'organizer') {
    header("Location: ../login.php");
    exit();
}

// Check if event data exists in session
if (!isset($_SESSION['event_data'])) {
    // Redirect to the event form if no event data is found
    header("Location: eventform.php");
    exit();
}

$organizer_id = $_SESSION['userId'];
$event_data = $_SESSION['event_data'];

// Extract event data
$location = $event_data['location'];
$event_type = $event_data['event_type'];
$event_topic = $event_data['event_topic'];
$sponsorship_type = $event_data['sponsorship_type'];
$target_audience = $event_data['target_audience'];

// Find matching sponsors based on event type
// Using LIKE to match sponsors who have the event type in their sponsor_events field
$query = "SELECT `id`, `company_name`, `unit`, `company_registration_code`, `address`, 
          `emp_id`, `sponsor_events`, `email`, `mobile_no`, `facebook`, `instagram`,
          `company_status`, `profile_picture` 
          FROM `sponsors` 
          WHERE `sponsor_events` LIKE ? ";

$stmt = $conn->prepare($query);
$event_type_param = "%$event_type%";
$stmt->bind_param("s", $event_type_param);
$stmt->execute();
$result = $stmt->get_result();

// Store matched sponsors
$matched_sponsors = [];
while ($row = $result->fetch_assoc()) {
    $matched_sponsors[] = $row;
}

// Close the statement
$stmt->close();

// If needed, you can also save this match data to a database
// For demonstration, I'm just using session data
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matched Sponsorships</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <div class="bg-blue-900 text-white w-full md:w-1/4 p-6">
        <h2 class="text-2xl font-bold mb-6 text-center md:text-left">SponsMe</h2>
        <nav class="flex flex-col gap-4">
            <a href="../home.php" class="bg-blue-700 p-3 rounded text-center md:text-left">Home</a>
            <a href="Orgprof.php" class="bg-blue-700 p-3 rounded text-center md:text-left">Profile</a>
            <a href="eventform.php" class="bg-blue-700 p-3 rounded text-center md:text-left">Seek Sponsorship</a>
        </nav>
        <div class="mt-auto">
            <a href="../../Controller/Logout.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Matched Sponsorships</h3>

        <!-- Event Details Summary -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <h4 class="text-xl font-semibold mb-3">Your Event Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="font-medium">Location: <span class="font-normal"><?php echo htmlspecialchars($location); ?></span></p>
                    <p class="font-medium">Event Type: <span class="font-normal"><?php echo htmlspecialchars($event_type); ?></span></p>
                </div>
                <div>
                    <p class="font-medium">Event Topic: <span class="font-normal"><?php echo htmlspecialchars($event_topic); ?></span></p>
                    <p class="font-medium">Sponsorship Type: <span class="font-normal"><?php echo htmlspecialchars($sponsorship_type); ?></span></p>
                </div>
                <div>
                    <p class="font-medium">Target Audience: <span class="font-normal"><?php echo htmlspecialchars($target_audience); ?></span></p>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4 flex flex-col md:flex-row">
            <li class="nav-item">
                <a class="nav-link active" href="Matched.php">Matched Sponsorship</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="Accepted.php">Accepted Sponsorships</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="AllSponsers.php">All Available Sponsorships</a>
            </li>
        </ul>

        <!-- Sponsorship Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (count($matched_sponsors) > 0): ?>
                <?php foreach ($matched_sponsors as $sponsor): ?>
                    <div class="bg-[#1F509A] rounded-lg shadow-md hover:shadow-lg p-4 flex flex-col items-center transform hover:scale-105 transition duration-300">
                        <!-- Sponsor Logo/Image -->
                        <div class="bg-white w-full h-32 rounded mb-4 flex items-center justify-center overflow-hidden">
                            <?php if (!empty($sponsor['profile_picture'])): ?>
                                <img src="../../<?php echo htmlspecialchars($sponsor['profile_picture']); ?>" alt="<?php echo htmlspecialchars($sponsor['company_name']); ?>" class="max-h-full max-w-full object-contain">
                            <?php else: ?>
                                <div class="text-center text-gray-500">
                                    <i class="fas fa-building text-5xl mb-2"></i>
                                    <p>No Image</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Sponsor Details -->
                        <div class="text-white w-full mb-4">
                            <h4 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($sponsor['company_name']); ?></h4>
                            <p class="text-sm mb-1"><i class="fas fa-envelope mr-2"></i><?php echo htmlspecialchars($sponsor['email']); ?></p>
                            <p class="text-sm mb-1"><i class="fas fa-phone mr-2"></i><?php echo htmlspecialchars($sponsor['mobile_no']); ?></p>
                            <p class="text-sm mb-2"><i class="fas fa-map-marker-alt mr-2"></i><?php echo htmlspecialchars($sponsor['address']); ?></p>

                            <?php
                            // Convert sponsor_events to a readable format
                            $sponsor_events = explode(",", $sponsor['sponsor_events']);
                            $sponsor_events = array_map('trim', $sponsor_events);
                            ?>
                            <p class="text-sm"><i class="fas fa-calendar-alt mr-2"></i>Sponsors: <?php echo htmlspecialchars(implode(", ", $sponsor_events)); ?></p>
                        </div>

                        <!-- Social Media Links -->
                        <div class="flex justify-center gap-4 mb-4">
                            <?php if (!empty($sponsor['facebook'])): ?>
                                <a href="<?php echo htmlspecialchars($sponsor['facebook']); ?>" target="_blank" class="text-white hover:text-blue-300">
                                    <i class="fab fa-facebook-f text-xl"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($sponsor['instagram'])): ?>
                                <a href="<?php echo htmlspecialchars($sponsor['instagram']); ?>" target="_blank" class="text-white hover:text-pink-300">
                                    <i class="fab fa-instagram text-xl"></i>
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Action Button -->
                        <a href="ViewSpons.php?sponsor_id=<?php echo $sponsor['id']; ?>" class="btn btn-success w-full">
                            View Sponsor
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center p-8 bg-white rounded-lg shadow">
                    <i class="fas fa-search text-5xl text-gray-400 mb-4"></i>
                    <h4 class="text-xl font-bold mb-2">No Matching Sponsors Found</h4>
                    <p class="text-gray-600 mb-4">We couldn't find any sponsors that match your event type "<?php echo htmlspecialchars($event_type); ?>".</p>
                    <a href="eventform.php" class="btn btn-primary">Modify Search</a>
                    <a href="AllSponsers.php" class="btn btn-secondary ml-2">View All Sponsors</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>