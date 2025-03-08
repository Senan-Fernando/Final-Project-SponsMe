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

// Get request and organizer details
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
            <a onclick="window.location.href='../home.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">Home</a>
            <a href="#" onclick="window.location.href='Sponsorprof.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">Profile</a>
            <a onclick="window.location.href='Request.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">Sponsorship Requests</a>
        </nav>
        <div class="mt-auto">
            <a href="../login.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <div class="bg-[#1F509A] rounded-lg shadow-lg p-6">
            <!-- Page Header -->
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-white">Sponsorship Documentation</h3>
                <p class="text-white mt-2">Complete the sponsorship details for the event: <strong><?php echo $requestData['event_topic']; ?></strong></p>
            </div>

            <!-- Event Summary -->
            <div class="bg-blue-800 rounded-lg p-4 mb-6">
                <h4 class="text-xl font-semibold text-white mb-3">Event Summary</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white">
                    <div>
                        <p class="mb-1"><i class="bi bi-calendar-event me-2"></i> <strong>Event:</strong> <?php echo $requestData["event_topic"]; ?></p>
                        <p class="mb-1"><i class="bi bi-people me-2"></i> <strong>Crew Name:</strong> <?php echo $requestData["crew_name"]; ?></p>
                        <p class="mb-1"><i class="bi bi-person me-2"></i> <strong>Organizer:</strong> <?php echo $requestData["first_name"] . " " . $requestData["last_name"]; ?></p>
                    </div>
                    <div>
                        <p class="mb-1"><i class="bi bi-geo-alt me-2"></i> <strong>Location:</strong> <?php echo $requestData["location"]; ?></p>
                        <p class="mb-1"><i class="bi bi-tag me-2"></i> <strong>Type:</strong> <?php echo $requestData["event_type"]; ?></p>
                        <p class="mb-1"><i class="bi bi-calendar me-2"></i> <strong>Request Date:</strong> <?php echo $requestDate; ?></p>
                    </div>
                </div>
            </div>

            <!-- Sponsorship Form -->
            <div class="bg-blue-800 rounded-lg p-4 mb-6">
                <h4 class="text-xl font-semibold text-white mb-3">Sponsorship Details</h4>
                <form id="sponsorshipForm" action="../../Controller/Sponsor/process_sponsorship.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                    
                    <div class="mb-3">
                        <label for="amount" class="block text-white font-medium mb-1">Sponsorship Amount (in TND)</label>
                        <input type="number" class="form-control" id="amount" name="amount" required min="0" step="0.01">
                    </div>
                    
                    <div class="mb-3">
                        <label for="document" class="block text-white font-medium mb-1">Attach Document (PDF or Image)</label>
                        <input type="file" class="form-control" id="document" name="document" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-white opacity-75">Please attach the sponsorship contract or agreement (Max 5MB)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="block text-white font-medium mb-1">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
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
    
    <!-- Form Validation Script -->
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
    </script>
</body>
</html>