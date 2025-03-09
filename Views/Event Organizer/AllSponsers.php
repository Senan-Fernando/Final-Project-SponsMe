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

// Extract event data for display
$location = $event_data['location'];
$event_type = $event_data['event_type'];
$event_topic = $event_data['event_topic'];
$sponsorship_type = $event_data['sponsorship_type'];
$target_audience = $event_data['target_audience'];

// Get all available sponsors without matching criteria
$query = "SELECT `id`, `company_name`, `unit`, `company_registration_code`, `address`, 
          `emp_id`, `sponsor_events`, `email`, `mobile_no`, `facebook`, `instagram`,
          `company_status`, `profile_picture` 
          FROM `sponsors`";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Store all sponsors
$all_sponsors = [];
while ($row = $result->fetch_assoc()) {
    $all_sponsors[] = $row;
}

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Available Sponsorships</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

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
        <h3 class="text-2xl font-bold text-gray-800 mb-4">All Available Sponsorships</h3>

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
                <a class="nav-link text-dark" href="Matched.php">Matched Sponsorship</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="AllSponsers.php">All Available Sponsorships</a>
            </li>
        </ul>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" action="" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="Search by company name or event type" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <?php if (isset($_GET['search'])): ?>
                <a href="AllSponsers.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors text-center">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Sponsorship Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php 
            // Filter sponsors based on search query if provided
            $filtered_sponsors = $all_sponsors;
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = strtolower($_GET['search']);
                $filtered_sponsors = array_filter($all_sponsors, function($sponsor) use ($search) {
                    return (
                        strpos(strtolower($sponsor['company_name']), $search) !== false || 
                        strpos(strtolower($sponsor['sponsor_events']), $search) !== false
                    );
                });
            }
            
            if (count($filtered_sponsors) > 0): 
            ?>
                <?php foreach ($filtered_sponsors as $sponsor): ?>
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

                        <!-- Event Type Match Indicator -->
                        <?php if (strpos(strtolower($sponsor['sponsor_events']), strtolower($event_type)) !== false): ?>
                            <div class="bg-green-600 text-white text-xs font-bold py-1 px-3 rounded-full mb-3">
                                <i class="fas fa-check-circle mr-1"></i> Matches Your Event Type
                            </div>
                        <?php endif; ?>

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
                    <h4 class="text-xl font-bold mb-2">No Sponsors Found</h4>
                    <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                        <p class="text-gray-600 mb-4">We couldn't find any sponsors matching your search term "<?php echo htmlspecialchars($_GET['search']); ?>".</p>
                        <a href="AllSponsers.php" class="btn btn-primary">Clear Search</a>
                    <?php else: ?>
                        <p class="text-gray-600 mb-4">There are currently no active sponsors in our database.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>