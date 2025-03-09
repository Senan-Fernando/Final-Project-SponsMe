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

// Fetch sponsor details from the database - UPDATED query to include additional fields
$query = "SELECT `id`, `company_name`, `unit`, `email`, `mobile_no`, `facebook`, `instagram`, `company_status` FROM `sponsors` WHERE `id` = ?";
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

// Dummy rating for demonstration (in a real app, this would come from a ratings table)
$sponsor_rating = rand(3, 5); // Random rating between 3 and 5 stars

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
    $insert_stmt->bind_param(
        "iisssssss",
        $organizer_id,
        $sponsor_id,
        $unit,
        $location,
        $event_type,
        $event_topic,
        $sponsorship_type,
        $target_audience,
        $message
    );

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

        .star-rating {
            color: #FFD700;
        }

        .star-empty {
            color: #e2e8f0;
        }

        .contact-info {
            background-color: #f8fafc;
            border-radius: 0.5rem;
            border-left: 4px solid #3b82f6;
        }

        .social-icon {
            transition: transform 0.2s;
        }

        .social-icon:hover {
            transform: scale(1.2);
        }
    </style>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

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
                            <a href="../home.php" class="flex items-center p-3 rounded-lg transition-all duration-200 
                           <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'bg-blue-700 shadow-md' : 'hover:bg-blue-700/50'; ?>">
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
                    <!-- Sponsor Profile Card -->
                    <div class="mb-8 p-4 border rounded-lg shadow-sm">
                        <div class="flex justify-between items-start">
                            <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($sponsor['company_name']); ?></h3>

                            <!-- Star Rating -->
                            <div class="flex items-center">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $sponsor_rating): ?>
                                        <i class="fas fa-star star-rating"></i>
                                    <?php else: ?>
                                        <i class="fas fa-star star-empty"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span class="ml-2 text-gray-600 text-sm">(<?php echo rand(10, 50); ?> reviews)</span>
                            </div>
                        </div>

                        <!-- Company Status -->
                        <div class="mt-2">
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                                <i class="fas fa-building mr-1"></i> <?php echo htmlspecialchars($sponsor['company_status']); ?>
                            </span>
                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-sm ml-2">
                                <i class="fas fa-landmark mr-1"></i> <?php echo htmlspecialchars($sponsor['unit']); ?>
                            </span>
                        </div>

                        <!-- Contact Information -->
                        <div class="mt-4 p-4 contact-info">
                            <h4 class="text-md font-semibold text-gray-700 mb-3">Contact Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <!-- Email -->
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="text-gray-800"><?php echo htmlspecialchars($sponsor['email']); ?></p>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-500">Phone</p>
                                        <p class="text-gray-800"><?php echo htmlspecialchars($sponsor['mobile_no']); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="mt-4 flex space-x-4">
                                <?php if (!empty($sponsor['facebook'])): ?>
                                    <a href="<?php echo htmlspecialchars($sponsor['facebook']); ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="fab fa-facebook fa-lg social-icon"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($sponsor['instagram'])): ?>
                                    <a href="<?php echo htmlspecialchars($sponsor['instagram']); ?>" target="_blank" class="text-pink-600 hover:text-pink-800">
                                        <i class="fab fa-instagram fa-lg social-icon"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="">
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