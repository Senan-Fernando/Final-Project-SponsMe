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
    <title>Event Form | SponsMe</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .hover-scale {
            transition: transform 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.02);
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
        <div class="bg-blue-800 rounded-lg p-4 mb-6 flex items-center hover-scale shadow-md">
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
    <div class="flex-1 p-6 md:p-8 flex justify-center items-center">
        <div class="max-w-4xl w-full">
            <!-- Page Title and Description -->
            <div class="mb-8 text-center fade-in">
                <h1 class="text-3xl font-bold text-blue-900 mb-2">Create Sponsorship Request</h1>
                <p class="text-gray-600">Fill in the details below to find matching sponsors for your event</p>
            </div>
            
            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden fade-in">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-blue-800 to-blue-600 p-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-calendar-plus mr-3"></i> Event Details
                    </h3>
                    <p class="text-blue-100 text-sm mt-2">All fields are required to match you with the best sponsors</p>
                </div>
                
                <!-- Form Body -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Location Field -->
                        <div class="hover-scale">
                            <label for="location" class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i> Event Location
                            </label>
                            <div class="relative">
                                <input type="text" id="location" name="location" 
                                    class="form-control pl-10 py-3 bg-gray-50 border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 transition-all" 
                                    placeholder="Enter city or venue" required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Event Type Field -->
                        <div class="hover-scale">
                            <label for="eventType" class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-tag text-blue-600 mr-2"></i> Event Type
                            </label>
                            <div class="relative">
                                <select id="eventType" name="eventType" 
                                    class="form-select pl-10 py-3 bg-gray-50 border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 transition-all" 
                                    required>
                                    <option selected disabled value="">Select event category</option>
                                    <option value="Concert">Concert</option>
                                    <option value="Raves">Raves</option>
                                    <option value="Gaming">Gaming</option>
                                    <option value="Sports">Sports</option>
                                    <option value="Charity">Charity</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-list text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Event Topic Field -->
                        <div class="hover-scale">
                            <label for="eventTopic" class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-lightbulb text-blue-600 mr-2"></i> Event Topic
                            </label>
                            <div class="relative">
                                <input type="text" id="eventTopic" name="eventTopic" 
                                    class="form-control pl-10 py-3 bg-gray-50 border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 transition-all" 
                                    placeholder="Main theme or subject" required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-bookmark text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sponsorship Type Field -->
                        <div class="hover-scale">
                            <label for="sponsorshipType" class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-award text-blue-600 mr-2"></i> Sponsorship Level
                            </label>
                            <div class="relative">
                                <select id="sponsorshipType" name="sponsorshipType" 
                                    class="form-select pl-10 py-3 bg-gray-50 border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 transition-all" 
                                    required>
                                    <option selected disabled value="">Select sponsorship tier</option>
                                    <option value="Platinum">Platinum</option>
                                    <option value="Gold">Gold</option>
                                    <option value="Silver">Silver</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-trophy text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Target Audience Field -->
                    <div class="mb-6 hover-scale">
                        <label for="targetAudience" class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-users text-blue-600 mr-2"></i> Target Audience
                        </label>
                        <div class="relative">
                            <input type="text" id="targetAudience" name="targetAudience" 
                                class="form-control pl-10 py-3 bg-gray-50 border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 transition-all" 
                                placeholder="Age group, demographics, interests" required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-friends text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="text-center mt-8">
                        <button type="submit" class="btn px-8 py-3 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-bold rounded-lg shadow-lg hover-scale transition-all duration-300 flex items-center justify-center mx-auto">
                            <i class="fas fa-search mr-2"></i> Find Matching Sponsors
                        </button>
                    </div>
                </form>
                
                <!-- Form Footer -->
                <div class="bg-gray-50 p-4 border-t border-gray-200 text-center text-sm text-gray-600">
                    <p>Need help finding sponsors? <a href="Help.php" class="text-blue-600 hover:underline">View our sponsor matching guide</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript for form enhancements -->
    <script>
        // Add animation to form elements on page load
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.hover-scale');
            formElements.forEach((element, index) => {
                setTimeout(() => {
                    element.classList.add('fade-in');
                }, index * 100);
            });
            
            // Form validation visual feedback
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('invalid', function() {
                    this.classList.add('border-red-500');
                });
                
                input.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                });
            });
        });
    </script>
</body>
</html>