<?php
include '../../Model/db.php';
session_start();

// Check if the session is set for the organizer
if (!isset($_SESSION['userId']) || $_SESSION['userRole'] !== 'organizer') {
    header("Location: ../login.php");
    exit();
}

$organizer_id = $_SESSION['userId'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support | SponsMe</title>
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
        
        .accordion-button:not(.collapsed) {
            background-color: #3b82f6;
            color: white;
        }
        
        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
            border-color: #3b82f6;
        }
        
        .help-card {
            transition: all 0.3s ease;
        }
        
        .help-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
            <a href="Help.php" class="flex items-center p-3 rounded-lg bg-blue-700 shadow-md transition-colors">
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
    <div class="flex-1 p-6 md:p-8 flex justify-center">
        <div class="max-w-5xl w-full">
            <!-- Page Title and Description -->
            <div class="mb-8 text-center fade-in">
                <h1 class="text-3xl font-bold text-blue-900 mb-2">Help & Support Center</h1>
                <p class="text-gray-600">Find answers to common questions and learn how to maximize your sponsorship opportunities</p>
            </div>
            
            <!-- Quick Help Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <!-- Sponsor Matching Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden help-card fade-in">
                    <div class="bg-gradient-to-r from-blue-700 to-blue-500 p-4 text-white">
                        <div class="rounded-full bg-white/20 w-12 h-12 flex items-center justify-center mb-2">
                            <i class="fas fa-handshake text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold">Sponsor Matching</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">Learn how our matching algorithm connects you with the right sponsors.</p>
                        <a href="#sponsor-matching" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                            Learn more <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Best Practices Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden help-card fade-in" style="animation-delay: 0.1s;">
                    <div class="bg-gradient-to-r from-green-700 to-green-500 p-4 text-white">
                        <div class="rounded-full bg-white/20 w-12 h-12 flex items-center justify-center mb-2">
                            <i class="fas fa-lightbulb text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold">Best Practices</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">Tips and strategies to create appealing sponsorship requests.</p>
                        <a href="#best-practices" class="text-green-600 hover:text-green-800 font-medium flex items-center">
                            View tips <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- FAQ Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden help-card fade-in" style="animation-delay: 0.2s;">
                    <div class="bg-gradient-to-r from-purple-700 to-purple-500 p-4 text-white">
                        <div class="rounded-full bg-white/20 w-12 h-12 flex items-center justify-center mb-2">
                            <i class="fas fa-question-circle text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold">Frequently Asked</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">Quick answers to common questions about using SponsMe.</p>
                        <a href="#faq" class="text-purple-600 hover:text-purple-800 font-medium flex items-center">
                            View FAQs <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Sponsor Matching Guide Section -->
            <div id="sponsor-matching" class="bg-white rounded-xl shadow-xl overflow-hidden mb-10 fade-in">
                <div class="bg-gradient-to-r from-blue-800 to-blue-600 p-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-handshake mr-3"></i> Sponsor Matching Guide
                    </h3>
                    <p class="text-blue-100 text-sm mt-2">How our algorithm finds the perfect sponsors for your events</p>
                </div>
                
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6 mb-6">
                        <div class="md:w-1/2">
                            <h4 class="text-xl font-semibold text-blue-800 mb-3">How Matching Works</h4>
                            <p class="text-gray-700 mb-4">
                                SponsMe uses a sophisticated algorithm that analyzes multiple factors to connect you with sponsors who are most likely to be interested in your event. Our system considers:
                            </p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-5">
                                <li><span class="font-medium text-blue-700">Event Type & Topic:</span> Matched with sponsors' industry and marketing objectives</li>
                                <li><span class="font-medium text-blue-700">Location:</span> Geographical relevance to sponsors' target markets</li>
                                <li><span class="font-medium text-blue-700">Target Audience:</span> Alignment with sponsors' customer demographics</li>
                                <li><span class="font-medium text-blue-700">Sponsorship Level:</span> Budget compatibility with sponsors' investment range</li>
                            </ul>
                        </div>
                        <div class="md:w-1/2 bg-blue-50 rounded-lg p-5">
                            <h4 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-2"></i> Matching Score Explained
                            </h4>
                            <p class="text-gray-700 mb-4">
                                When you see the matching score for potential sponsors, here's what the percentages mean:
                            </p>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-3">
                                        <div class="bg-red-500 h-2.5 rounded-full" style="width: 35%"></div>
                                    </div>
                                    <span class="text-sm text-gray-700"><strong>30-49%:</strong> Basic match, limited alignment</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-3">
                                        <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 65%"></div>
                                    </div>
                                    <span class="text-sm text-gray-700"><strong>50-74%:</strong> Good match, significant alignment</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-3">
                                        <div class="bg-green-500 h-2.5 rounded-full" style="width: 90%"></div>
                                    </div>
                                    <span class="text-sm text-gray-700"><strong>75-100%:</strong> Excellent match, strong alignment</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-5 border-l-4 border-blue-600">
                        <h4 class="text-lg font-semibold text-blue-800 mb-2">Pro Tip: Complete Information = Better Matches</h4>
                        <p class="text-gray-700">
                            The more detailed and specific you are when filling out your event form, the better our algorithm can match you with appropriate sponsors. Vague information may result in lower-quality matches.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Best Practices Section -->
            <div id="best-practices" class="bg-white rounded-xl shadow-xl overflow-hidden mb-10 fade-in">
                <div class="bg-gradient-to-r from-green-800 to-green-600 p-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-lightbulb mr-3"></i> Sponsorship Best Practices
                    </h3>
                    <p class="text-green-100 text-sm mt-2">Strategies to increase your sponsorship success rate</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-semibold text-green-800 mb-4 flex items-center">
                                <span class="flex items-center justify-center bg-green-100 text-green-700 rounded-full w-8 h-8 mr-3">1</span>
                                Crafting Appealing Requests
                            </h4>
                            <ul class="space-y-3 text-gray-700">
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span><strong>Be Specific:</strong> Clearly define your event's purpose, goals, and expected outcomes.</span>
                                </li>
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span><strong>Highlight Value:</strong> Explain what sponsors will gain (exposure, lead generation, brand alignment).</span>
                                </li>
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span><strong>Target Audience Details:</strong> Provide rich demographic information about your expected attendees.</span>
                                </li>
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span><strong>Show Track Record:</strong> Include past event successes and metrics if available.</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="text-xl font-semibold text-green-800 mb-4 flex items-center">
                                <span class="flex items-center justify-center bg-green-100 text-green-700 rounded-full w-8 h-8 mr-3">2</span>
                                After Matching
                            </h4>
                            <ul class="space-y-3 text-gray-700">
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span><strong>Quick Response:</strong> Respond promptly to sponsor inquiries to demonstrate professionalism.</span>
                                </li>
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span><strong>Personalize Communication:</strong> Research each sponsor and customize your approach.</span>
                                </li>
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span><strong>Be Flexible:</strong> Consider alternative sponsorship arrangements if proposed.</span>
                                </li>
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span><strong>Provide Details:</strong> Have a sponsorship package ready with specific benefits and deliverables.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-5 mt-6 border-l-4 border-green-600">
                        <h4 class="text-lg font-semibold text-green-800 mb-2 flex items-center">
                            <i class="fas fa-trophy text-yellow-500 mr-2"></i> Success Story
                        </h4>
                        <p class="text-gray-700 italic">
                            "By clearly defining our audience demographics and event goals, we achieved a 78% matching score with a major sponsor who ultimately contributed twice our initial expected budget. The key was being very specific about our target audience's purchasing habits and interests."
                        </p>
                        <p class="text-right text-sm text-gray-600 mt-2">— Music Festival Organizer</p>
                    </div>
                </div>
            </div>
            
<!-- FAQ Section -->
<div id="faq" class="bg-white rounded-xl shadow-xl overflow-hidden mb-10 fade-in">
    <div class="bg-gradient-to-r from-purple-800 to-purple-600 p-6">
        <h3 class="text-2xl font-bold text-white flex items-center">
            <i class="fas fa-question-circle mr-3"></i> Frequently Asked Questions
        </h3>
        <p class="text-purple-100 text-sm mt-2">Quick answers to common questions about using SponsMe</p>
    </div>
    
    <div class="p-6">
        <div class="accordion" id="faqAccordion">
            <!-- FAQ Item 1 -->
            <div class="accordion-item border-0 mb-3 rounded-lg shadow-sm">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button rounded-lg" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        How many sponsors will I be matched with?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-gray-50">
                        <p class="text-gray-700">
                            The number of matches depends on the specificity of your event details and the availability of suitable sponsors in our database. Typically, you can expect between 3-10 potential sponsors, with varying match scores. We prioritize quality matches over quantity.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 2 -->
            <div class="accordion-item border-0 mb-3 rounded-lg shadow-sm">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed rounded-lg" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        What does each sponsorship level (Platinum, Gold, Silver) mean?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-gray-50">
                        <p class="text-gray-700 mb-3">
                            Sponsorship levels indicate the tier of partnership and benefits:
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li><strong>Platinum:</strong> Premium positioning, highest visibility, exclusive benefits, typically largest financial contribution</li>
                            <li><strong>Gold:</strong> High visibility, prominent positioning, significant benefits, medium-high financial contribution</li>
                            <li><strong>Silver:</strong> Good visibility, standard benefits package, moderate financial contribution</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 3 -->
            <div class="accordion-item border-0 mb-3 rounded-lg shadow-sm">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed rounded-lg" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        How long does it take to receive sponsor matches?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-gray-50">
                        <p class="text-gray-700">
                            Sponsor matches are generated instantly after you submit your event details. Our algorithm processes your request and provides matches in real-time, allowing you to start reaching out to potential sponsors immediately.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 4 -->
            <div class="accordion-item border-0 mb-3 rounded-lg shadow-sm">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed rounded-lg" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Can I edit my event details after submitting?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-gray-50">
                        <p class="text-gray-700">
                            Yes, you can edit your event details by returning to the "Request History" section, finding your event, and selecting the "Edit" option. After updating your information, our system will regenerate sponsor matches based on your revised details.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 5 -->
            <div class="accordion-item border-0 rounded-lg shadow-sm">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed rounded-lg" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        What should I do if a sponsor accepts my request?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-gray-50">
                        <p class="text-gray-700">
                            When a sponsor accepts your request, you'll receive a notification, and they'll appear in your "Accepted Sponsorship" section. From there, you should:
                        </p>
                        <ol class="list-decimal list-inside mt-2 space-y-1 text-gray-700">
                            <li>Review their acceptance details and any notes they've provided</li>
                            <li>Contact them directly using the provided information</li>
                            <li>Send a formal agreement or contract outlining the terms of sponsorship</li>
                            <li>Maintain regular communication about event preparations and their specific involvement</li>
                            <li>Update your event details to reflect the secured sponsorship</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form submission handler
        document.getElementById('supportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const subject = document.getElementById('supportSubject').value;
            const message = document.getElementById('supportMessage').value;
            
            // Validate form
            if (!subject || !message) {
                alert('Please fill in all fields');
                return;
            }
            
            // Here you would normally send an AJAX request to your backend
            // For demo purposes, we'll just show a success message
            alert('Your support request has been submitted. Our team will respond within 24 hours.');
            
            // Reset form
            this.reset();
        });
    </script>
</body>
</html>
                                        