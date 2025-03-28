<?php
include '../../Model/db.php';
session_start();

// Check if the session is set for the organizer
if (!isset($_SESSION['userId']) || $_SESSION['userRole'] !== 'organizer') {
    header("Location: ../login.php");
    exit();
}

$organizer_id = $_SESSION['userId']; // Get the organizer's ID from the session

// Fetch accepted sponsorship requests with the document path, location_details, and sponsor's profile image
$query = "SELECT sr.id AS request_id, sr.sponsor_id, sr.unit, sr.location, sr.event_type, sr.event_topic, 
                 sr.sponsorship_type, sr.target_audience, sr.message, sr.request_date, sr.status, 
                 sr.location_details, s.company_name, s.profile_picture, sd.document_path,
                 (SELECT iu.image_path 
                  FROM image_uploads iu 
                  WHERE iu.user_id = s.id 
                  ORDER BY iu.uploaded_at DESC 
                  LIMIT 1) AS uploaded_image
          FROM sponsorship_requests sr
          JOIN sponsors s ON sr.sponsor_id = s.id
          LEFT JOIN sponsorship_details sd ON sr.id = sd.request_id
          WHERE sr.organizer_id = ? AND sr.status = 'accepted'";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $organizer_id);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}
$result = $stmt->get_result();

// Store accepted sponsorships in an array
$accepted_sponsorships = [];
$base_url = '/Final-Project-SponsMe-main'; // Base URL of the project
$default_image = $base_url . '../Static Assets/images/noProfile.png'; // Path to a default profile image
while ($row = $result->fetch_assoc()) {
    // Adjust the document path to be relative to the web root
    if (!empty($row['document_path'])) {
        $row['document_path'] = rtrim($base_url, '/') . '/' . ltrim(str_replace('\\', '/', $row['document_path']), '/');
    }
    // Determine the profile image to use
    if (!empty($row['uploaded_image'])) {
        // Replace relative path segments and ensure it’s clean
        $clean_path = str_replace('../', '', $row['uploaded_image']);
        $row['profile_image'] = $base_url . '/uploads/sponsor_profiles/' . basename($clean_path);
    } elseif (!empty($row['profile_picture'])) {
        $clean_path = str_replace('../', '', $row['profile_picture']);
        $row['profile_image'] = $base_url . '/uploads/sponsor_profiles/' . basename($clean_path);
    } else {
        $row['profile_image'] = $default_image;
    }
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
    <style>
        .card-hover:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }
        .document-message {
            color: #ffffff;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        .location-icon {
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .location-icon:hover {
            color: #34d399;  
        }
        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;  
            border-radius: 0.5rem;  
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col md:flex-row">

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
                        <div class="bg-[#1F509A] rounded-lg shadow-md p-4 flex flex-col items-center card-hover">
                            <div class="bg-blue-300 w-full h-32 rounded mb-4 flex items-center justify-center">
                                <img src="<?php echo htmlspecialchars($sponsorship['profile_image']);  ?>" 
                                     alt="<?php echo htmlspecialchars($sponsorship['company_name']); ?> Profile Image" 
                                     class="profile-image">
                            </div>
                            <h4 class="text-white font-semibold"><?php echo htmlspecialchars($sponsorship['company_name']); ?></h4>
                            <p class="text-white"><?php echo htmlspecialchars($sponsorship['unit']); ?></p>
                            <p class="text-white text-sm">Event: <?php echo htmlspecialchars($sponsorship['event_topic']); ?></p>
                            <!-- Location Icon -->
                            <?php if (!empty($sponsorship['location_details'])): ?>
                                <i class="bi bi-geo-alt-fill text-white text-lg mt-2 location-icon" 
                                   onclick="openLocation('<?php echo htmlspecialchars($sponsorship['location_details']); ?>')"></i>
                            <?php else: ?>
                                <p class="document-message mt-2">No location available</p>
                            <?php endif; ?>
                            <!-- Document Button -->
                            <?php if (!empty($sponsorship['document_path'])): ?>
                                <a href="<?php echo htmlspecialchars($sponsorship['document_path']); ?>" 
   class="btn btn-primary w-full mt-3" 
   target="_blank">
    <i class="bi bi-eye mr-1"></i> View Document
</a>

                            <?php else: ?>
                                <p class="document-message mt-3">No document available</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-gray-600">No accepted sponsorships found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewDocument(docPath) {
            // Open the document in a new tab
            window.open(docPath, '_blank');
        }

        function openLocation(locationDetails) {
            // Extract the Google Maps link from locationDetails
            // The format is: "https://maps.google.com/?q=latitude,longitude (Lat: ..., Lon: ..., Accuracy: ...)"
            const match = locationDetails.match(/https:\/\/maps\.google\.com\/\?q=[0-9.-]+,[0-9.-]+/);
            if (match) {
                const googleMapsLink = match[0];
                window.open(googleMapsLink, '_blank');
            } else {
                alert("Invalid Google Maps link in location details.");
            }
        }
    </script>
</body>

</html>