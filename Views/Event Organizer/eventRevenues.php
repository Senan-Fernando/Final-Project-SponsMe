<?php
include '../../Model/db.php';
session_start();

// Check if the session is set for the organizer
if (!isset($_SESSION['userId']) || $_SESSION['userRole'] !== 'organizer') {
    header("Location: ../login.php");
    exit();
}

$organizer_id = $_SESSION['userId'];

// Fetch all sponsorship requests for this organizer with requested_amount included
$query = "SELECT sr.*, sr.requested_amount as initial_requested, s.company_name, s.unit as sponsor_unit 
          FROM sponsorship_requests sr
          JOIN sponsors s ON sr.sponsor_id = s.id
          WHERE sr.organizer_id = ?
          ORDER BY sr.request_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$requests = $stmt->get_result();

// Store alert message in a variable
$alert_message = '';
$alert_type = '';

// Check if there's a message in the session
if (isset($_SESSION['message']) && isset($_SESSION['alert_type'])) {
    $alert_message = $_SESSION['message'];
    $alert_type = $_SESSION['alert_type'];
    // Clear the session message
    unset($_SESSION['message']);
    unset($_SESSION['alert_type']);
}

// Collect data for chart
$chartLabels = [];
$requestedAmounts = [];
$receivedAmounts = [];
$totalRequested = 0;
$totalReceived = 0;

// Process data for chart and summary statistics
if ($requests->num_rows > 0) {
    // Need to reconnect for the loop since we'll be fetching additional data
    $conn_loop = new mysqli($servername, $username, $password, $database);
    
    while ($row = $requests->fetch_assoc()) {
        // Fetch associated sponsorship details
        $details_query = "SELECT amount FROM sponsorship_details WHERE request_id = ?";
        $stmt_details = $conn_loop->prepare($details_query);
        $stmt_details->bind_param("i", $row['id']);
        $stmt_details->execute();
        $details_result = $stmt_details->get_result();
        $details = $details_result->fetch_assoc();
        
        // Fetch actual received amount from sponsorship_budget
        $budget_query = "SELECT SUM(amount) as total_budget FROM sponsorship_budget 
                         WHERE sponsor_id = ? AND unit = ?";
        $stmt_budget = $conn_loop->prepare($budget_query);
        $stmt_budget->bind_param("is", $row['sponsor_id'], $row['sponsor_unit']);
        $stmt_budget->execute();
        $budget_result = $stmt_budget->get_result();
        $budget = $budget_result->fetch_assoc();
        
        // Use initial_requested from sponsorship_requests
        $requested_amount = floatval($row['initial_requested']);
        
        // Use amount from sponsorship_details if available, otherwise use details amount
        $details_amount = isset($details['amount']) ? floatval($details['amount']) : 0;
        
        // Use received amount from sponsorship_budget
        $received_budget = isset($budget['total_budget']) ? floatval($budget['total_budget']) : 0;
        
        // Add to totals for summary
        if ($requested_amount > 0) {
            $totalRequested += $requested_amount;
        }
        if ($received_budget > 0) {
            $totalReceived += $received_budget;
        }
        
        // Add data for chart if we have meaningful data
        if ($row['event_topic'] && ($requested_amount > 0 || $received_budget > 0)) {
            $chartLabels[] = $row['event_topic'];
            $requestedAmounts[] = $requested_amount;
            $receivedAmounts[] = $received_budget;
        }
        
        // Calculate the difference (could be used for updating the budget table if needed)
        $difference = $requested_amount - $received_budget;
        
        $stmt_details->close();
        $stmt_budget->close();
    }
    
    $conn_loop->close();
}

// Close the main connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Revenues - Organizer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
        <!-- Chart Summary Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Sponsorship Summary</h3>
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/2">
                    <canvas id="sponsorshipChart" height="250"></canvas>
                </div>
                <div class="md:w-1/2 flex flex-col justify-center">
                    <div class="stats-summary space-y-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700">Total Requested</h4>
                            <p class="text-2xl font-bold text-blue-600" id="totalRequested">Calculating...</p>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700">Total Received</h4>
                            <p class="text-2xl font-bold text-green-600" id="totalReceived">Calculating...</p>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700">Funding Gap</h4>
                            <p class="text-2xl font-bold text-red-600" id="fundingGap">Calculating...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#1F509A] rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold text-white mb-6">Event Revenues from Sponsors</h3>

            <!-- Events and Revenue Table -->
            <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-gray-700">Event</th>
                            <th class="px-4 py-3 text-left text-gray-700">Company</th>
                            <th class="px-4 py-3 text-left text-gray-700">Unit</th>
                            <th class="px-4 py-3 text-left text-gray-700">Requested</th>
                            <th class="px-4 py-3 text-left text-gray-700">Received</th>
                            <th class="px-4 py-3 text-left text-gray-700">Difference</th>
                            <th class="px-4 py-3 text-left text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left text-gray-700">Documents</th>
                            <th class="px-4 py-3 text-left text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        // Reconnect to fetch data for the table
                        $conn = new mysqli($servername, $username, $password, $database);
                        
                        // Re-run the query to reset the result set
                        $query = "SELECT sr.*, sr.requested_amount as initial_requested, s.company_name, s.unit as sponsor_unit 
                                  FROM sponsorship_requests sr
                                  JOIN sponsors s ON sr.sponsor_id = s.id
                                  WHERE sr.organizer_id = ?
                                  ORDER BY sr.request_date DESC";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $organizer_id);
                        $stmt->execute();
                        $requests = $stmt->get_result();
                        
                        if ($requests->num_rows > 0) {
                            while ($row = $requests->fetch_assoc()) {
                                // Fetch associated sponsorship details
                                $details_query = "SELECT * FROM sponsorship_details WHERE request_id = ?";
                                $stmt_details = $conn->prepare($details_query);
                                $stmt_details->bind_param("i", $row['id']);
                                $stmt_details->execute();
                                $details = $stmt_details->get_result()->fetch_assoc();
                                
                                // Calculate total budget received for this sponsor's unit
                                $budget_query = "SELECT SUM(amount) as total_budget FROM sponsorship_budget 
                                                 WHERE sponsor_id = ? AND unit = ?";
                                $stmt_budget = $conn->prepare($budget_query);
                                $stmt_budget->bind_param("is", $row['sponsor_id'], $row['sponsor_unit']);
                                $stmt_budget->execute();
                                $budget = $stmt_budget->get_result()->fetch_assoc();
                                
                                // Use initial_requested from the request table
                                $requested_amount = floatval($row['initial_requested']);
                                $received_budget = isset($budget['total_budget']) ? floatval($budget['total_budget']) : 0;
                                $difference = $requested_amount - $received_budget;
                                $percentage = ($requested_amount > 0) ? min(100, max(0, ($received_budget / $requested_amount) * 100)) : 0;
                                
                                $document = isset($details['document_path']) ? $details['document_path'] : "None";
                                $notes = isset($details['notes']) ? $details['notes'] : "No notes";
                                
                                // Format amounts for display
                                $requested_display = $requested_amount > 0 ? "$" . number_format($requested_amount, 2) : "Pending";
                                $received_display = $received_budget > 0 ? "$" . number_format($received_budget, 2) : "$0.00";
                                $difference_display = $requested_amount > 0 ? "$" . number_format($difference, 2) : "N/A";
                                
                                echo "<tr>";
                                echo "<td class='px-4 py-3'>" . htmlspecialchars($row['event_topic']) . " (" . htmlspecialchars($row['event_type']) . ")</td>";
                                echo "<td class='px-4 py-3'>" . htmlspecialchars($row['company_name']) . "</td>";
                                echo "<td class='px-4 py-3'>" . htmlspecialchars($row['sponsor_unit']) . "</td>";
                                echo "<td class='px-4 py-3'>" . $requested_display . "</td>";
                                echo "<td class='px-4 py-3'>" . $received_display . "</td>";
                                
                                // Difference with progress bar
                                echo "<td class='px-4 py-3'>";
                                if($requested_amount > 0) {
                                    echo "<div class='flex flex-col'>";
                                    echo "<span class='" . ($difference < 0 ? "text-green-600" : "text-red-600") . "'>" . $difference_display . "</span>";
                                    echo "<div class='w-full bg-gray-200 rounded-full h-2.5 mt-1'>";
                                    echo "<div class='bg-blue-600 h-2.5 rounded-full' style='width: {$percentage}%'></div>";
                                    echo "</div>";
                                    echo "</div>";
                                } else {
                                    echo "N/A";
                                }
                                echo "</td>";
                                
                                // Status with color coding
                                $status_class = '';
                                switch ($row['status']) {
                                    case 'pending':
                                        $status_class = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'approved':
                                        $status_class = 'bg-green-100 text-green-800';
                                        break;
                                    case 'declined':
                                        $status_class = 'bg-red-100 text-red-800';
                                        break;
                                    default:
                                        $status_class = 'bg-gray-100 text-gray-800';
                                }
                                
                                echo "<td class='px-4 py-3'><span class='px-2 py-1 rounded-full text-xs font-medium $status_class'>" . ucfirst(htmlspecialchars($row['status'])) . "</span></td>";
                                
                                // Document section
                                if ($document !== "None") {
                                    echo "<td class='px-4 py-3'><a href='../../" . htmlspecialchars($document) . "' class='text-blue-600 hover:underline' target='_blank'>View Document</a></td>";
                                } else {
                                    echo "<td class='px-4 py-3'>No document submitted</td>";
                                }
                                
                                // Actions
                                echo "<td class='px-4 py-3'>";
                                echo "<button type='button' onclick='viewDetails(" . json_encode([
                                    "event" => $row['event_topic'],
                                    "company" => $row['company_name'],
                                    "unit" => $row['sponsor_unit'],
                                    "requestedAmount" => $requested_display,
                                    "receivedBudget" => $received_display,
                                    "difference" => $difference_display,
                                    "percentage" => round($percentage),
                                    "sponsorshipType" => $row['sponsorship_type'],
                                    "location" => $row['location'],
                                    "audience" => $row['target_audience'],
                                    "message" => $row['message'],
                                    "requestDate" => $row['request_date'],
                                    "status" => $row['status'],
                                    "notes" => $notes
                                ]) . ")' class='bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700'>Details</button>";
                                echo "</td>";
                                
                                echo "</tr>";
                                
                                $stmt_details->close();
                                $stmt_budget->close();
                            }
                        } else {
                            echo "<tr><td colspan='9' class='px-4 py-3 text-center text-gray-500'>No sponsorship requests found</td></tr>";
                        }
                        
                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg max-h-[90vh] overflow-y-auto">
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Sponsorship Details</h2>
            
            <div id="detailsContent" class="space-y-4">
                <!-- Content will be dynamically populated -->
            </div>
            
            <div class="mt-6 text-center">
                <button type="button" onclick="closeDetailsModal()" class="bg-gray-500 text-white p-2 rounded hover:bg-gray-600">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        // Show the SweetAlert if message exists
        <?php if (!empty($alert_message)): ?>
            Swal.fire({
                title: "<?php echo $alert_type == 'success' ? 'Success!' : 'Error!'; ?>",
                text: "<?php echo $alert_message; ?>",
                icon: "<?php echo $alert_type; ?>",
                confirmButtonText: "Okay"
            });
        <?php endif; ?>

        // Update totals in the summary section
        document.getElementById('totalRequested').textContent = '$<?php echo number_format($totalRequested, 2); ?>';
        document.getElementById('totalReceived').textContent = '$<?php echo number_format($totalReceived, 2); ?>';
        document.getElementById('fundingGap').textContent = '$<?php echo number_format($totalRequested - $totalReceived, 2); ?>';

        // Set up the chart
        const ctx = document.getElementById('sponsorshipChart').getContext('2d');
        const sponsorshipChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [
                    {
                        label: 'Requested Amount',
                        data: <?php echo json_encode($requestedAmounts); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Received Amount',
                        data: <?php echo json_encode($receivedAmounts); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount ($)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Events'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        function viewDetails(details) {
            const detailsContent = document.getElementById('detailsContent');
            
            // Calculate funding status class and message
            let fundingStatusClass = 'bg-yellow-100 text-yellow-800';
            let fundingStatusMsg = 'Partially Funded';
            
            if (details.percentage >= 100) {
                fundingStatusClass = 'bg-green-100 text-green-800';
                fundingStatusMsg = 'Fully Funded';
            } else if (details.percentage <= 0) {
                fundingStatusClass = 'bg-red-100 text-red-800';
                fundingStatusMsg = 'Not Funded';
            }
            
            // Format the details content with nice styling
            detailsContent.innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 bg-blue-50 p-3 rounded-lg">
                        <h3 class="font-bold text-blue-800 text-lg">${details.event}</h3>
                        <p class="text-gray-600">Location: ${details.location}</p>
                    </div>
                    
                    <div class="border-r pr-4">
                        <p class="font-semibold text-gray-700">Company</p>
                        <p>${details.company}</p>
                    </div>
                    
                    <div>
                        <p class="font-semibold text-gray-700">Unit</p>
                        <p>${details.unit}</p>
                    </div>
                    
                    <div class="col-span-2 bg-gray-50 p-3 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <p class="font-semibold text-gray-700">Funding Progress</p>
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${fundingStatusClass}">${fundingStatusMsg}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-blue-600 h-4 rounded-full text-xs text-white text-center leading-4" style="width: ${details.percentage}%">
                                ${details.percentage}%
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-r pr-4">
                        <p class="font-semibold text-gray-700">Requested Amount</p>
                        <p class="text-blue-600 font-medium">${details.requestedAmount}</p>
                    </div>
                    
                    <div>
                        <p class="font-semibold text-gray-700">Received Budget</p>
                        <p class="text-green-600 font-medium">${details.receivedBudget}</p>
                    </div>
                    
                    <div class="col-span-2">
                        <p class="font-semibold text-gray-700">Difference</p>
                        <p class="${parseFloat(details.difference.replace(/[^0-9.-]+/g, '')) < 0 ? 'text-green-600' : 'text-red-600'} font-medium">${details.difference}</p>
                    </div>
                    
                    <div class="border-r pr-4">
                        <p class="font-semibold text-gray-700">Sponsorship Type</p>
                        <p>${details.sponsorshipType}</p>
                    </div>
                    
                    <div>
                        <p class="font-semibold text-gray-700">Target Audience</p>
                        <p>${details.audience}</p>
                    </div>
                    
                    <div class="border-r pr-4">
                        <p class="font-semibold text-gray-700">Request Date</p>
                        <p>${details.requestDate}</p>
                    </div>
                    
                    <div>
                        <p class="font-semibold text-gray-700">Status</p>
                        <p class="font-medium ${details.status === 'approved' ? 'text-green-600' : details.status === 'declined' ? 'text-red-600' : 'text-yellow-600'}">${details.status.charAt(0).toUpperCase() + details.status.slice(1)}</p>
                    </div>
                    
                    <div class="col-span-2">
                        <p class="font-semibold text-gray-700">Message</p>
                        <p class="bg-gray-50 p-2 rounded">${details.message}</p>
                    </div>
                    
                    ${details.notes !== "No notes" ? `
                    <div class="col-span-2">
                        <p class="font-semibold text-gray-700">Sponsor Notes</p>
                        <p class="bg-gray-50 p-2 rounded">${details.notes}</p>
                    </div>
                    ` : ''}
                </div>
            `;
            
            // Show the modal
            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>