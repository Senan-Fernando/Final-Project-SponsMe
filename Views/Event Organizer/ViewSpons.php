<?php
include '../../Model/db.php';
session_start();

// Check if the session is set for the organizer
if (!isset($_SESSION['userId']) || $_SESSION['userRole'] !== 'organizer') {
    header("Location: ../login.php");
    exit();
}

// Check if sponsor_id is provided in the URL
if (!isset($_GET['sponsor_id']) || empty($_GET['sponsor_id'])) {
    header("Location: Matched.php");
    exit();
}

$sponsor_id = $_GET['sponsor_id'];
$organizer_id = $_SESSION['userId'];

// Fetch sponsor details from the database
$query = "SELECT `id`, `company_name`, `unit` FROM `sponsors` WHERE `id` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $sponsor_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if sponsor exists
if ($result->num_rows === 0) {
    header("Location: Matched.php");
    exit();
}

$sponsor = $result->fetch_assoc();
$stmt->close();

// Get event data from session
if (!isset($_SESSION['event_data'])) {
    // Redirect to the event form if no event data is found
    header("Location: eventform.php");
    exit();
}

$event_data = $_SESSION['event_data'];

// Process form submission
$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $message = trim($_POST['message']);
    
    // Get event data from session
    $location = $event_data['location'];
    $event_type = $event_data['event_type'];
    $event_topic = $event_data['event_topic'];
    $sponsorship_type = $event_data['sponsorship_type'];
    $target_audience = $event_data['target_audience'];
    $unit = $sponsor['unit'];

    // Insert the request into the database
$insert_query = "INSERT INTO `sponsorship_requests` 
                (`organizer_id`, `sponsor_id`, `unit`, `location`, `event_type`, 
                 `event_topic`, `sponsorship_type`, `target_audience`, `message`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("iisssssss", 
                        $organizer_id, 
                        $sponsor_id, 
                        $unit,
                        $location, 
                        $event_type, 
                        $event_topic, 
                        $sponsorship_type, 
                        $target_audience,
                        $message);
    
    if ($insert_stmt->execute()) {
        $success_message = "Your sponsorship request has been sent successfully!";
    } else {
        $error_message = "Failed to send request. Please try again.";
    }
    
    $insert_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Sponsorship</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-header {
            background: linear-gradient(to right, #1a365d, #2d3748);
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .form-body {
            background-color: white;
            border-radius: 0 0 0.5rem 0.5rem;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <div class="bg-blue-900 text-white w-full md:w-1/4 p-6 shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center md:text-left">SponsMe</h2>
        <nav class="flex flex-col gap-4">
            <a href="../home.php" class="bg-blue-700 hover:bg-blue-600 p-3 rounded text-center md:text-left transition-colors">Home</a>
            <a href="Orgprof.php" class="bg-blue-700 hover:bg-blue-600 p-3 rounded text-center md:text-left transition-colors">Profile</a>
            <a href="eventform.php" class="bg-blue-700 hover:bg-blue-600 p-3 rounded text-center md:text-left transition-colors">Seek Sponsorship</a>
        </nav>
        <div class="mt-auto">
            <a href="../../Controller/Logout.php" class="bg-blue-700 hover:bg-blue-600 p-3 rounded text-center block mt-6 transition-colors">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <div class="max-w-3xl mx-auto">
            <!-- Status Messages -->
            <?php if (!empty($success_message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p class="font-bold">Success!</p>
                <p><?php echo $success_message; ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Error!</p>
                <p><?php echo $error_message; ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <!-- Form Header -->
                <div class="form-header p-6 text-white">
                    <h2 class="text-2xl font-bold">Request Sponsorship</h2>
                    <p class="text-blue-200">Send a sponsorship request to <?php echo htmlspecialchars($sponsor['company_name']); ?></p>
                </div>

                <!-- Form Body -->
                <div class="form-body p-6">
                    <form method="POST" action="">
                        <!-- Sponsor Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-3">Sponsor Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Company Name</label>
                                    <input type="text" class="form-control bg-gray-100" value="<?php echo htmlspecialchars($sponsor['company_name']); ?>" readonly>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Unit</label>
                                    <input type="text" class="form-control bg-gray-100" value="<?php echo htmlspecialchars($sponsor['unit']); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Event Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-3">Event Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Location</label>
                                    <input type="text" class="form-control bg-gray-100" value="<?php echo htmlspecialchars($event_data['location']); ?>" readonly>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Event Type</label>
                                    <input type="text" class="form-control bg-gray-100" value="<?php echo htmlspecialchars($event_data['event_type']); ?>" readonly>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Event Topic</label>
                                    <input type="text" class="form-control bg-gray-100" value="<?php echo htmlspecialchars($event_data['event_topic']); ?>" readonly>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Sponsorship Type</label>
                                    <input type="text" class="form-control bg-gray-100" value="<?php echo htmlspecialchars($event_data['sponsorship_type']); ?>" readonly>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-gray-700 font-medium mb-2">Target Audience</label>
                                    <input type="text" class="form-control bg-gray-100" value="<?php echo htmlspecialchars($event_data['target_audience']); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Message to Sponsor -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-3">Message to Sponsor</h3>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Your Message</label>
                                <textarea name="message" rows="5" class="form-control w-full" placeholder="Introduce yourself and provide more details about your event and how the sponsor could benefit..." required></textarea>
                                <p class="text-sm text-gray-500 mt-1">Be specific about what you're looking for and how the sponsor will benefit from supporting your event.</p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-between items-center mt-8">
                            <a href="ViewSpons.php?sponsor_id=<?php echo $sponsor_id; ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded font-medium transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </a>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded font-bold transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i> Send Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (!empty($success_message)): ?>
            <div class="mt-6 flex justify-center">
                <a href="Matched.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded font-medium transition-colors">
                    <i class="fas fa-list mr-2"></i> Back to Matched Sponsors
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>