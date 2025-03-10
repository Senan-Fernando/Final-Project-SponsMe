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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-gray-100 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
<!-- Sidebar -->
<div class="bg-gradient-to-b from-blue-900 to-blue-800 text-white w-full md:w-1/4 p-6 shadow-2xl flex flex-col h-screen fixed md:sticky top-0">
    <!-- Logo and Brand -->
    <div class="mb-8 border-b border-blue-700 pb-4">
        <h2 class="text-3xl font-bold mb-2 text-center md:text-left">
            <i class="fas fa-handshake me-2"></i>SponsMe
        </h2>
        <p class="text-blue-200 text-sm opacity-75 text-center md:text-left">Connecting Events with Sponsors</p>
    </div>
    
    <!-- User Profile Summary -->
    <div class="bg-blue-800 rounded-lg p-4 mb-6 flex items-center">
        <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center mr-3">
            <i class="fas fa-user text-xl"></i>
        </div>
        <div>
            <p class="font-medium"><?php echo isset($_SESSION['userName']) ? htmlspecialchars($_SESSION['userName']) : 'Organizer'; ?></p>
            <p class="text-xs text-blue-300"><?php echo $_SESSION['userRole']; ?></p>
        </div>
    </div>
    
    <!-- Navigation Categories -->
    <nav class="flex-1">
        <!-- Main Category -->
        <div class="mb-2 group">
            <div class="flex items-center px-3 py-2 cursor-pointer">
                <span class="uppercase text-xs font-semibold text-blue-300">Main</span>
                <i class="fas fa-chevron-down ml-auto text-xs text-blue-300 group-hover:rotate-180 transition-transform duration-300"></i>
            </div>
            <div class="overflow-hidden transition-all duration-300 max-h-0 group-hover:max-h-32">
                <ul class="mt-1 space-y-1">
                    <li>
                        <a href="../index.php" class="flex items-center p-3 rounded-lg transition-all duration-200 
                           <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-blue-700 shadow-md' : 'hover:bg-blue-700/50'; ?>">
                            <i class="fas fa-home w-6"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="Orgprof.php" class="flex items-center p-3 rounded-lg transition-all duration-200 
                           <?php echo basename($_SERVER['PHP_SELF']) == 'Orgprof.php' ? 'bg-blue-700 shadow-md' : 'hover:bg-blue-700/50'; ?>">
                            <i class="fas fa-user-circle w-6"></i>
                            <span class="ml-3">Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Sponsorship Category -->
        <div class="mb-2 group">
            <div class="flex items-center px-3 py-2 cursor-pointer">
                <span class="uppercase text-xs font-semibold text-blue-300">Sponsorship</span>
                <i class="fas fa-chevron-down ml-auto text-xs text-blue-300 group-hover:rotate-180 transition-transform duration-300"></i>
            </div>
            <div class="overflow-hidden transition-all duration-300 max-h-0 group-hover:max-h-48">
                <ul class="mt-1 space-y-1">
                    <li>
                        <a href="eventform.php" class="flex items-center p-3 rounded-lg transition-all duration-200 
                           <?php echo basename($_SERVER['PHP_SELF']) == 'eventform.php' ? 'bg-blue-700 shadow-md' : 'hover:bg-blue-700/50'; ?>">
                            <i class="fas fa-calendar-plus w-6"></i>
                            <span class="ml-3">Seek Sponsorship</span>
                        </a>
                    </li>
                    <li>
                        <a href="Matched.php" class="flex items-center p-3 rounded-lg transition-all duration-200 
                           <?php echo basename($_SERVER['PHP_SELF']) == 'Matched.php' ? 'bg-blue-700 shadow-md' : 'hover:bg-blue-700/50'; ?>">
                            <i class="fas fa-handshake w-6"></i>
                            <span class="ml-3">Matched Sponsors</span>
                        </a>
                    </li>
                    <li>
                        <a href="AcceptedSponsers.php" class="flex items-center p-3 rounded-lg transition-all duration-200 
                           <?php echo basename($_SERVER['PHP_SELF']) == 'AcceptedSponsers.php' ? 'bg-blue-700 shadow-md' : 'hover:bg-blue-700/50'; ?>">
                            <i class="fas fa-check-circle w-6"></i>
                            <span class="ml-3">Accepted Sponsorship</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Reports Category -->
        <div class="mb-2 group">
            <div class="flex items-center px-3 py-2 cursor-pointer">
                <span class="uppercase text-xs font-semibold text-blue-300">Reports</span>
                <i class="fas fa-chevron-down ml-auto text-xs text-blue-300 group-hover:rotate-180 transition-transform duration-300"></i>
            </div>
            <div class="overflow-hidden transition-all duration-300 max-h-0 group-hover:max-h-32">
                <ul class="mt-1 space-y-1">
                    <li>
                        <a href="RequestHistory.php" class="flex items-center p-3 rounded-lg transition-all duration-200 
                           <?php echo basename($_SERVER['PHP_SELF']) == 'RequestHistory.php' ? 'bg-blue-700 shadow-md' : 'hover:bg-blue-700/50'; ?>">
                            <i class="fas fa-history w-6"></i>
                            <span class="ml-3">Request History</span>
                        </a>
                    </li>
                    <li>
                        <a href="eventRevenues.php" class="flex items-center p-3 rounded-lg transition-all duration-200 
                           <?php echo basename($_SERVER['PHP_SELF']) == 'eventRevenues.php' ? 'bg-blue-700 shadow-md' : 'hover:bg-blue-700/50'; ?>">
                            <i class="fas fa-chart-line w-6"></i>
                            <span class="ml-3">Event Revenues</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Border Line -->
    <div class="border-t border-blue-700 my-4"></div>
    
    <!-- Support & Logout Section -->
    <div class="mt-auto space-y-2">
        <a href="Help.php" class="flex items-center p-3 rounded-lg bg-blue-700/30 hover:bg-blue-700/50 transition-colors">
            <i class="fas fa-question-circle w-6"></i>
            <span class="ml-3">Help</span>
        </a>
        <a href="../../Controller/Logout.php" class="flex items-center p-3 rounded-lg bg-red-600 hover:bg-red-700 transition-colors">
            <i class="fas fa-sign-out-alt w-6"></i>
            <span class="ml-3">Log Out</span>
        </a>
    </div>
    
    <!-- App Version -->
    <div class="mt-3 text-center text-xs text-blue-300 opacity-50">
        <p>SponsMe v1.0.2</p>
    </div>
</div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Accepted Sponsorship</h3>

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
