<?php
session_start();
include '../../Model/db.php';

// Check if sponsor is logged in
if (!isset($_SESSION['userEmail'])) {
    header('Location: ../Login.php');
    exit();
}

// Get the request ID from the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Error: No request ID provided.";
    exit();
}

$request_id = $_GET['id'];

// Get request and organizer details, including location_details
$sql = "SELECT r.*, o.crew_name, o.first_name, o.last_name, o.email 
        FROM sponsorship_requests r
        JOIN organizers o ON r.organizer_id = o.id
        WHERE r.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Error: Request not found.";
    exit();
}

$requestData = $result->fetch_assoc();

// Check if the request is already in accepted status
if ($requestData["status"] != "accepted") {
    echo "Error: This request is not in accepted status.";
    exit();
}

// Format date
$requestDate = date("M d, Y", strtotime($requestData["request_date"]));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsorship Documentation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for the button hover effect */
        #fetchLocation {
            transition: all 0.3s ease;
        }
        #fetchLocation:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col md:flex-row">

 <!-- Sidebar/Navbar -->
<div class="bg-gradient-to-b from-blue-900 to-blue-800 text-white w-full md:w-1/4 p-6 shadow-2xl flex flex-col h-screen fixed md:sticky top-0">
    <!-- Logo and Brand -->
    <div class="mb-8 border-b border-blue-700 pb-4">
        <h2 class="text-3xl font-bold mb-2 text-center md:text-left">
            <i class="fas fa-handshake me-2"></i>SponsMe
        </h2>
        <p class="text-blue-200 text-sm opacity-75 text-center md:text-left">Connecting Events with Sponsors</p>
    </div>
    
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
                        <a href="../index.php" class="flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-blue-700 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-blue-700 shadow-md' : ''; ?>">
                            <i class="fas fa-home w-6"></i>
                            <span class="ml-3">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.php" class="flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-blue-700 <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'bg-blue-700 shadow-md' : ''; ?>">
                            <i class="fas fa-user-circle w-6"></i>
                            <span class="ml-3">Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Management Category -->
        <div class="mb-2 group">
            <div class="flex items-center px-3 py-2 cursor-pointer">
                <span class="uppercase text-xs font-semibold text-blue-300">Management</span>
                <i class="fas fa-chevron-down ml-auto text-xs text-blue-300 group-hover:rotate-180 transition-transform duration-300"></i>
            </div>
            <div class="overflow-hidden transition-all duration-300 max-h-0 group-hover:max-h-48">
                <ul class="mt-1 space-y-1">
                    <li>
                        <a href="Request.php" class="flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-blue-700 <?php echo basename($_SERVER['PHP_SELF']) == 'Request.php' ? 'bg-blue-700 shadow-md' : ''; ?>">
                            <i class="fas fa-calendar-plus w-6"></i>
                            <span class="ml-3">Sponsorship Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="BudgetManagement.php" class="flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-blue-700 <?php echo basename($_SERVER['PHP_SELF']) == 'BudgetManagement.php' ? 'bg-blue-700 shadow-md' : ''; ?>">
                            <i class="fas fa-handshake w-6"></i>
                            <span class="ml-3">Budget Management</span>
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
        <a href="Help.php" class="flex items-center p-3 rounded-lg bg-blue-700/30 hover:bg-blue-700 transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'Help.php' ? 'bg-blue-700 shadow-md' : ''; ?>">
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
            <!-- Page Header -->
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-black">Sponsorship Documentation</h3>
                <p class="text-black mt-2">Complete the sponsorship details for the event: <strong><?php echo htmlspecialchars($requestData['event_topic']); ?></strong></p>
            </div>

            <!-- Event Summary -->
            <div class="bg-blue-100 rounded-lg p-4 mb-6">
                <h4 class="text-xl font-semibold text-black mb-3">Event Summary</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-black">
                    <div>
                        <p class="mb-1"><i class="bi bi-calendar-event me-2"></i> <strong>Event:</strong> <?php echo htmlspecialchars($requestData["event_topic"]); ?></p>
                        <p class="mb-1"><i class="bi bi-people me-2"></i> <strong>Crew Name:</strong> <?php echo htmlspecialchars($requestData["crew_name"]); ?></p>
                        <p class="mb-1"><i class="bi bi-person me-2"></i> <strong>Organizer:</strong> <?php echo htmlspecialchars($requestData["first_name"] . " " . $requestData["last_name"]); ?></p>
                    </div>
                    <div>
                        <p class="mb-1"><i class="bi bi-geo-alt me-2"></i> <strong>Location:</strong> <?php echo htmlspecialchars($requestData["location"]); ?></p>
                        <p class="mb-1"><i class="bi bi-tag me-2"></i> <strong>Type:</strong> <?php echo htmlspecialchars($requestData["event_type"]); ?></p>
                        <p class="mb-1"><i class="bi bi-calendar me-2"></i> <strong>Request Date:</strong> <?php echo htmlspecialchars($requestDate); ?></p>
                    </div>
                </div>
            </div>

            <!-- Sponsorship Form -->
            <div class="bg-blue-100 rounded-lg p-4 mb-6">
                <h4 class="text-xl font-semibold text-black mb-3">Sponsorship Details</h4>
                <form id="sponsorshipForm" action="../../Controller/Sponsor/process_sponsorship.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                    
                    <div class="mb-3">
                        <label for="amount" class="block text-black font-medium mb-1">Sponsorship Amount (in TND)</label>
                        <input type="number" class="form-control" id="amount" name="amount" required min="0" step="0.01">
                    </div>
                    
                    <div class="mb-3">
                        <label for="document" class="block text-black font-medium mb-1">Attach Document (PDF or Image)</label>
                        <input type="file" class="form-control" id="document" name="document" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-black opacity-75">Please attach the sponsorship contract or agreement (Max 5MB)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="block text-black font-medium mb-1">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo htmlspecialchars($requestData['notes'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="location_details" class="block text-black font-medium mb-1">Location Details (Optional)</label>
                        <div class="input-group">
                            <textarea class="form-control" id="location_details" name="location_details" rows="3" placeholder="Paste a Google Maps link or fetch your current location"><?php echo htmlspecialchars($requestData['location_details'] ?? ''); ?></textarea>
                            <button type="button" class="btn text-black font-semibold bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600 rounded-lg px-4 py-2" id="fetchLocation">
                                <i class="bi bi-geo-alt me-1"></i> Fetch Current Location
                            </button>
                        </div>
                        <small class="text-black opacity-75">Paste a Google Maps link or click the button to fetch your current location with accuracy details.</small>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between gap-4 mt-5">
                        <a href="Request.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Requests
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Submit Sponsorship Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Form Validation and Geolocation Script -->
    <script>
    document.getElementById('sponsorshipForm').addEventListener('submit', function(event) {
        const amount = document.getElementById('amount').value;
        const document = document.getElementById('document').files[0];
        
        let isValid = true;
        let errorMessage = '';
        
        // Validate amount
        if (!amount || amount <= 0) {
            errorMessage += 'Please enter a valid sponsorship amount.\n';
            isValid = false;
        }
        
        // Validate document
        if (!document) {
            errorMessage += 'Please attach a document.\n';
            isValid = false;
        } else {
            // Check file size (5MB max)
            if (document.size > 5 * 1024 * 1024) {
                errorMessage += 'Document size should be less than 5MB.\n';
                isValid = false;
            }
            
            // Check file type
            const fileType = document.type;
            const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(fileType)) {
                errorMessage += 'Please upload only PDF or image files (jpg, jpeg, png).\n';
                isValid = false;
            }
        }
        
        if (!isValid) {
            event.preventDefault();
            alert(errorMessage);
        }
    });

    // Fetch current location with high accuracy and include accuracy details
    document.getElementById('fetchLocation').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    const accuracy = position.coords.accuracy; // Accuracy in meters
                    const googleMapsLink = `https://maps.google.com/?q=${latitude},${longitude}`;
                    const locationDetails = `${googleMapsLink} (Lat: ${latitude.toFixed(6)}, Lon: ${longitude.toFixed(6)}, Accuracy: ${accuracy.toFixed(1)} meters)`;
                    document.getElementById('location_details').value = locationDetails;
                },
                function(error) {
                    let errorMessage = '';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = "User denied the request for Geolocation.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = "Location information is unavailable.";
                            break;
                        case error.TIMEOUT:
                            errorMessage = "The request to get user location timed out.";
                            break;
                        case error.UNKNOWN_ERROR:
                            errorMessage = "An unknown error occurred.";
                            break;
                    }
                    alert(errorMessage + "\nPlease enter a Google Maps link manually.");
                },
                {
                    enableHighAccuracy: true, // Enable high accuracy mode
                    timeout: 10000, // Wait up to 10 seconds
                    maximumAge: 0 // Do not use cached location
                }
            );
        } else {
            alert("Geolocation is not supported by this browser. Please enter a Google Maps link manually.");
        }
    });
    </script>
</body>
</html>