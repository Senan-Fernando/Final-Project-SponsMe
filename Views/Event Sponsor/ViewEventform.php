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
$sql = "SELECT r.*, o.crew_name, o.first_name, o.last_name, o.email, o.mobile, o.whatsapp 
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

// Format date
$requestDate = date("M d, Y", strtotime($requestData["request_date"]));

// Determine status class
$statusClass = "";
switch ($requestData["status"]) {
    case "pending":
        $statusClass = "bg-yellow-500";
        break;
    case "accepted":
        $statusClass = "bg-green-500";
        break;
    case "rejected":
        $statusClass = "bg-red-500";
        break;
    default:
        $statusClass = "bg-yellow-500";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Event Request Form</title>
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
            <!-- Page Header with Status Badge -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <h3 class="text-2xl font-bold text-white">Event Request Details</h3>
                <span class="text-white px-4 py-2 rounded mt-2 md:mt-0 <?php echo $statusClass; ?>">
                    <?php echo ucfirst($requestData["status"]); ?>
                </span>
            </div>

            <!-- Organizer Profile Details -->
            <div class="bg-blue-800 rounded-lg p-4 mb-6">
                <h4 class="text-xl font-semibold text-white mb-3">Organizer Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white">
                    <div>
                        <p class="mb-1"><i class="bi bi-people me-2"></i> <strong>Crew Name:</strong> <?php echo $requestData["crew_name"]; ?></p>
                        <p class="mb-1"><i class="bi bi-person me-2"></i> <strong>Organizer:</strong> <?php echo $requestData["first_name"] . " " . $requestData["last_name"]; ?></p>
                        <p class="mb-1"><i class="bi bi-envelope me-2"></i> <strong>Email:</strong> <?php echo $requestData["email"]; ?></p>
                    </div>
                    <div>
                        <p class="mb-1"><i class="bi bi-phone me-2"></i> <strong>Mobile:</strong> <?php echo $requestData["mobile"]; ?></p>
                        <p class="mb-1"><i class="bi bi-whatsapp me-2"></i> <strong>WhatsApp:</strong> <?php echo $requestData["whatsapp"]; ?></p>
                        <p class="mb-1"><i class="bi bi-calendar me-2"></i> <strong>Request Date:</strong> <?php echo $requestDate; ?></p>
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div class="bg-blue-800 rounded-lg p-4 mb-6">
                <h4 class="text-xl font-semibold text-white mb-3">Event Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-3">
                        <label class="block text-white font-medium mb-1">Event Topic</label>
                        <input type="text" class="form-control" value="<?php echo $requestData['event_topic']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="block text-white font-medium mb-1">Event Type</label>
                        <input type="text" class="form-control" value="<?php echo $requestData['event_type']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="block text-white font-medium mb-1">Location</label>
                        <input type="text" class="form-control" value="<?php echo $requestData['location']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="block text-white font-medium mb-1">Unit/Department</label>
                        <input type="text" class="form-control" value="<?php echo $requestData['unit']; ?>" readonly>
                    </div>
                </div>
            </div>

<!-- Sponsorship Details -->
<div class="bg-blue-800 rounded-lg p-4 mb-6">
    <h4 class="text-xl font-semibold text-white mb-3">Sponsorship Details</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="mb-3">
            <label class="block text-white font-medium mb-1">Sponsorship Type</label>
            <input type="text" class="form-control" value="<?php echo $requestData['sponsorship_type']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="block text-white font-medium mb-1">Target Audience</label>
            <input type="text" class="form-control" value="<?php echo $requestData['target_audience']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="block text-white font-medium mb-1">Requested Amount</label>
            <input type="text" class="form-control" value="<?php echo number_format($requestData['requested_amount'], 2); ?>" readonly>
        </div>
    </div>
    <div class="mb-3">
        <label class="block text-white font-medium mb-1">Message</label>
        <textarea class="form-control" rows="4" readonly><?php echo $requestData['message']; ?></textarea>
    </div>
</div>

            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <a href="Request.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Requests
                </a>
                <div class="flex gap-2">
                    <?php if ($requestData["status"] == "pending"): ?>
                        <button class="btn btn-success" onclick="updateStatus(<?php echo $requestData['id']; ?>, 'accepted')">
                            <i class="bi bi-check-circle me-1"></i> Approve
                        </button>
                        <button class="btn btn-danger" onclick="updateStatus(<?php echo $requestData['id']; ?>, 'rejected')">
                            <i class="bi bi-x-circle me-1"></i> Reject
                        </button>
                    <?php else: ?>
                        <button class="btn btn-primary" onclick="location.href='mailto:<?php echo $requestData['email']; ?>'">
                            <i class="bi bi-envelope me-1"></i> Contact Organizer
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Status Update Script -->
    <script>
function updateStatus(requestId, status) {
    if (confirm("Are you sure you want to " + (status === 'accepted' ? 'accept' : 'reject') + " this request?")) {
        // Send AJAX request to update status
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../../Controller/Sponsor/update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                try {
                    const response = JSON.parse(this.responseText);
                    if (response.status === "success") {
                        if (response.redirect) {
                            // Redirect to the Sponzingdocs page if status is accepted
                            window.location.href = response.redirect;
                        } else {
                            // Redirect back to requests page for other statuses
                            window.location.href = 'Request.php';
                        }
                    } else {
                        alert("Error: " + response.message);
                    }
                } catch (e) {
                    // Fallback for backward compatibility
                    window.location.href = 'Request.php';
                }
            }
        };
        xhr.send("request_id=" + requestId + "&status=" + status);
    }
}
    </script>
</body>
</html>