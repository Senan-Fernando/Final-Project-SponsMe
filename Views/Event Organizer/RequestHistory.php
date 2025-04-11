<?php
include '../../Model/db.php';
session_start();

// Check if the session is set for the organizer
if (!isset($_SESSION['userId']) || $_SESSION['userRole'] !== 'organizer') {
    header("Location: ../login.php");
    exit();
}

$organizer_id = $_SESSION['userId'];

// Fetch all sponsorship requests for this organizer
$query = "SELECT sr.*, s.company_name, s.unit as sponsor_unit 
          FROM sponsorship_requests sr
          JOIN sponsors s ON sr.sponsor_id = s.id
          WHERE sr.organizer_id = ?
          ORDER BY sr.request_date DESC";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $organizer_id);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}
$requests = $stmt->get_result();

// Collect data for charts and reports
$eventRequestsOverTime = []; 
$eventSponsorshipTypes = []; 
$eventStatusOverTime = []; 
$statusTypes = ['pending', 'accepted', 'rejected']; 
$eventTopics = []; 

// Data for period-specific charts
$dailyRequests = []; // Requests per event topic for today
$weeklyRequests = []; // Requests per event topic for this week
$monthlyRequests = []; // Requests per event topic for this month
$yearlyRequests = []; // Requests per event topic for this year

$totalRequests = 0;
$pendingRequests = 0;
$acceptedRequests = 0;

// Store requests for PDF generation
$allRequests = [];
if ($requests->num_rows > 0) {
    while ($row = $requests->fetch_assoc()) {
        $totalRequests++;
        $status = strtolower($row['status']);
        if ($status === 'pending') $pendingRequests++;
        if ($status === 'accepted') $acceptedRequests++;

        $event_topic = $row['event_topic'];
        $request_date = date('Y-m', strtotime($row['request_date'])); // Group by month for existing charts
        $sponsorship_type = $row['sponsorship_type'];

        // Store request data for PDF and period-specific charts
        $allRequests[] = [
            'event_topic' => $row['event_topic'],
            'event_type' => $row['event_type'],
            'company_name' => $row['company_name'],
            'sponsor_unit' => $row['sponsor_unit'],
            'request_date' => date('Y-m-d', strtotime($row['request_date'])),
            'status' => $row['status'],
        ];

        // Unique event topics
        if (!in_array($event_topic, $eventTopics)) {
            $eventTopics[] = $event_topic;
        }

        // Data for bar chart (requests per event over time)
        if (!isset($eventRequestsOverTime[$event_topic])) {
            $eventRequestsOverTime[$event_topic] = [];
        }
        if (!isset($eventRequestsOverTime[$event_topic][$request_date])) {
            $eventRequestsOverTime[$event_topic][$request_date] = 0;
        }
        $eventRequestsOverTime[$event_topic][$request_date]++;

        // Data for pie chart (sponsorship types per event)
        if (!isset($eventSponsorshipTypes[$event_topic])) {
            $eventSponsorshipTypes[$event_topic] = [];
        }
        if (!isset($eventSponsorshipTypes[$event_topic][$sponsorship_type])) {
            $eventSponsorshipTypes[$event_topic][$sponsorship_type] = 0;
        }
        $eventSponsorshipTypes[$event_topic][$sponsorship_type]++;

        // Data for stacked bar chart (status per event over time)
        if (!isset($eventStatusOverTime[$event_topic])) {
            $eventStatusOverTime[$event_topic] = [];
            foreach ($statusTypes as $status) {
                $eventStatusOverTime[$event_topic][$status] = [];
            }
        }
        if (!isset($eventStatusOverTime[$event_topic][$status][$request_date])) {
            $eventStatusOverTime[$event_topic][$status][$request_date] = 0;
        }
        if (in_array($status, $statusTypes)) {
            $eventStatusOverTime[$event_topic][$status][$request_date]++;
        }

        // Data for period-specific charts
        $requestDate = date('Y-m-d', strtotime($row['request_date']));
        $currentDate = date('Y-m-d');
        $currentYear = date('Y');
        $currentMonth = date('Y-m');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));

        // Daily requests
        if ($requestDate === $currentDate) {
            if (!isset($dailyRequests[$event_topic])) {
                $dailyRequests[$event_topic] = 0;
            }
            $dailyRequests[$event_topic]++;
        }

        // Weekly requests
        if ($requestDate >= $weekStart && $requestDate <= $weekEnd) {
            if (!isset($weeklyRequests[$event_topic])) {
                $weeklyRequests[$event_topic] = 0;
            }
            $weeklyRequests[$event_topic]++;
        }

        // Monthly requests
        if (date('Y-m', strtotime($requestDate)) === $currentMonth) {
            if (!isset($monthlyRequests[$event_topic])) {
                $monthlyRequests[$event_topic] = 0;
            }
            $monthlyRequests[$event_topic]++;
        }

        // Yearly requests
        if (date('Y', strtotime($requestDate)) === $currentYear) {
            if (!isset($yearlyRequests[$event_topic])) {
                $yearlyRequests[$event_topic] = 0;
            }
            $yearlyRequests[$event_topic]++;
        }
    }
}

// Prepare time labels (unique months)
$timeLabels = [];
foreach ($eventRequestsOverTime as $event => $months) {
    foreach (array_keys($months) as $month) {
        if (!in_array($month, $timeLabels)) {
            $timeLabels[] = $month;
        }
    }
}
sort($timeLabels);

// Prepare datasets for bar chart (requests per event over time)
$requestsOverTimeDatasets = [];
foreach ($eventTopics as $event) {
    $data = [];
    foreach ($timeLabels as $month) {
        $data[] = $eventRequestsOverTime[$event][$month] ?? 0;
    }
    $requestsOverTimeDatasets[] = [
        'label' => $event,
        'data' => $data,
        'backgroundColor' => sprintf('rgba(%d, %d, %d, 0.6)', rand(0, 255), rand(0, 255), rand(0, 255)),
        'borderColor' => sprintf('rgba(%d, %d, %d, 1)', rand(0, 255), rand(0, 255), rand(0, 255)),
        'borderWidth' => 1
    ];
}

// Prepare datasets for stacked bar chart (status per event over time)
$statusOverTimeDatasets = [];
foreach ($statusTypes as $status) {
    $dataByEvent = [];
    foreach ($eventTopics as $event) {
        $eventData = [];
        foreach ($timeLabels as $month) {
            $eventData[] = $eventStatusOverTime[$event][$status][$month] ?? 0;
        }
        $dataByEvent[] = $eventData;
    }
    $statusOverTimeDatasets[] = [
        'label' => ucfirst($status),
        'data' => array_merge(...$dataByEvent),
        'backgroundColor' => $status === 'pending' ? 'rgba(255, 206, 86, 0.6)' : ($status === 'accepted' ? 'rgba(75, 192, 192, 0.6)' : 'rgba(255, 99, 132, 0.6)'),
        'borderColor' => $status === 'pending' ? 'rgba(255, 206, 86, 1)' : ($status === 'accepted' ? 'rgba(75, 192, 192, 1)' : 'rgba(255, 99, 132, 1)'),
        'borderWidth' => 1
    ];
}

// Prepare labels for stacked bar chart (event-month combinations)
$stackedBarLabels = [];
foreach ($eventTopics as $event) {
    foreach ($timeLabels as $month) {
        $stackedBarLabels[] = "$event ($month)";
    }
}

// Prepare datasets for period-specific charts
$dailyDatasets = [];
foreach ($eventTopics as $event) {
    if (isset($dailyRequests[$event])) {
        $dailyDatasets[] = [
            'label' => $event,
            'data' => [$dailyRequests[$event]],
            'backgroundColor' => sprintf('rgba(%d, %d, %d, 0.6)', rand(0, 255), rand(0, 255), rand(0, 255)),
            'borderColor' => sprintf('rgba(%d, %d, %d, 1)', rand(0, 255), rand(0, 255), rand(0, 255)),
            'borderWidth' => 1
        ];
    }
}

$weeklyDatasets = [];
foreach ($eventTopics as $event) {
    if (isset($weeklyRequests[$event])) {
        $weeklyDatasets[] = [
            'label' => $event,
            'data' => [$weeklyRequests[$event]],
            'backgroundColor' => sprintf('rgba(%d, %d, %d, 0.6)', rand(0, 255), rand(0, 255), rand(0, 255)),
            'borderColor' => sprintf('rgba(%d, %d, %d, 1)', rand(0, 255), rand(0, 255), rand(0, 255)),
            'borderWidth' => 1
        ];
    }
}

$monthlyDatasets = [];
foreach ($eventTopics as $event) {
    if (isset($monthlyRequests[$event])) {
        $monthlyDatasets[] = [
            'label' => $event,
            'data' => [$monthlyRequests[$event]],
            'backgroundColor' => sprintf('rgba(%d, %d, %d, 0.6)', rand(0, 255), rand(0, 255), rand(0, 255)),
            'borderColor' => sprintf('rgba(%d, %d, %d, 1)', rand(0, 255), rand(0, 255), rand(0, 255)),
            'borderWidth' => 1
        ];
    }
}

$yearlyDatasets = [];
foreach ($eventTopics as $event) {
    if (isset($yearlyRequests[$event])) {
        $yearlyDatasets[] = [
            'label' => $event,
            'data' => [$yearlyRequests[$event]],
            'backgroundColor' => sprintf('rgba(%d, %d, %d, 0.6)', rand(0, 255), rand(0, 255), rand(0, 255)),
            'borderColor' => sprintf('rgba(%d, %d, %d, 1)', rand(0, 255), rand(0, 255), rand(0, 255)),
            'borderWidth' => 1
        ];
    }
}

// Close the connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request History - SponsMe</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- pdfMake -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <style>
        .chart-container { max-width: 600px; margin: 0 auto; }
        .main-content { margin-left: 0; }
        @media (min-width: 768px) {
            .main-content { margin-left: 25%; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col md:flex-row">
    <!-- Sidebar Navigation -->
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
                               <?php echo basename($_SERVER['PHP_SELF']) == 'Matched.php' ? 'bg-blue-7 shadow-md' : 'hover:bg-blue-700/50'; ?>">
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
            <h3 class="text-xl font-bold text-gray-800 mb-4">Request Summary</h3>
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/2">
                    <canvas id="requestChart" height="250"></canvas>
                </div>
                <div class="md:w-1/2 flex flex-col justify-center">
                    <div class="stats-summary space-y-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700">Total Requests</h4>
                            <p class="text-2xl font-bold text-blue-600"><?php echo $totalRequests; ?></p>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700">Pending Requests</h4>
                            <p class="text-2xl font-bold text-yellow-600"><?php echo $pendingRequests; ?></p>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700">Accepted Requests</h4>
                            <p class="text-2xl font-bold text-green-600"><?php echo $acceptedRequests; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Charts -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Event Analysis</h3>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="chart-container">
                        <canvas id="sponsorshipTypeChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="chart-container" style="max-width: 800px;">
                        <canvas id="statusOverTimeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Period-Specific Sections -->
        <div class="bg-[#1F509A] rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold text-white mb-6">Request History by Period</h3>

            <!-- Daily Section -->
            <div class="bg-white rounded-lg p-4 mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-gray-800">Daily Requests (<?php echo date('Y-m-d'); ?>)</h4>
                    <button onclick="generatePDF('Daily')" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Download PDF</button>
                </div>
                <div class="chart-container">
                    <canvas id="dailyChart" height="200"></canvas>
                </div>
            </div>

            <!-- Weekly Section -->
            <div class="bg-white rounded-lg p-4 mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-gray-800">Weekly Requests (<?php echo date('Y-m-d', strtotime('monday this week')); ?> to <?php echo date('Y-m-d', strtotime('sunday this week')); ?>)</h4>
                    <button onclick="generatePDF('Weekly')" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Download PDF</button>
                </div>
                <div class="chart-container">
                    <canvas id="weeklyChart" height="200"></canvas>
                </div>
            </div>

            <!-- Monthly Section -->
            <div class="bg-white rounded-lg p-4 mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-gray-800">Monthly Requests (<?php echo date('Y-m'); ?>)</h4>
                    <button onclick="generatePDF('Monthly')" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Download PDF</button>
                </div>
                <div class="chart-container">
                    <canvas id="monthlyChart" height="200"></canvas>
                </div>
            </div>

            <!-- Yearly Section -->
            <div class="bg-white rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-gray-800">Yearly Requests (<?php echo date('Y'); ?>)</h4>
                    <button onclick="generatePDF('Yearly')" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Download PDF</button>
                </div>
                <div class="chart-container">
                    <canvas id="yearlyChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        // Pass PHP data to JavaScript for PDF generation
        const allRequests = <?php echo json_encode($allRequests); ?>;
        const currentYear = new Date().getFullYear();
        const currentMonth = (new Date().getMonth() + 1).toString().padStart(2, '0');
        const currentYearMonth = `${currentYear}-${currentMonth}`;
        const currentDate = '<?php echo date('Y-m-d'); ?>';
        
        function getWeekRange() {
            const today = new Date();
            const firstDayOfWeek = new Date(today.setDate(today.getDate() - today.getDay() + 1)); // Monday
            const lastDayOfWeek = new Date(today.setDate(firstDayOfWeek.getDate() + 6)); // Sunday
            return {
                start: `${firstDayOfWeek.getFullYear()}-${(firstDayOfWeek.getMonth() + 1).toString().padStart(2, '0')}-${firstDayOfWeek.getDate().toString().padStart(2, '0')}`,
                end: `${lastDayOfWeek.getFullYear()}-${(lastDayOfWeek.getMonth() + 1).toString().padStart(2, '0')}-${lastDayOfWeek.getDate().toString().padStart(2, '0')}`
            };
        }

        // Initialize charts
        const requestChart = new Chart(document.getElementById('requestChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($timeLabels); ?>,
                datasets: <?php echo json_encode($requestsOverTimeDatasets); ?>
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: 'Month' } },
                    y: { title: { display: true, text: 'Number of Requests' }, beginAtZero: true }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        const sponsorshipTypeChart = new Chart(document.getElementById('sponsorshipTypeChart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_keys($eventSponsorshipTypes[$eventTopics[0] ?? []])); ?>,
                datasets: [{
                    label: 'Sponsorship Types',
                    data: <?php echo json_encode(array_values($eventSponsorshipTypes[$eventTopics[0] ?? []])); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'top' },
                    title: {
                        display: true,
                        text: 'Sponsorship Types for <?php echo $eventTopics[0] ?? "Event"; ?>'
                    }
                }
            }
        });

        const statusOverTimeChart = new Chart(document.getElementById('statusOverTimeChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($stackedBarLabels); ?>,
                datasets: <?php echo json_encode($statusOverTimeDatasets); ?>
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: 'Event (Month)' }, stacked: true },
                    y: { title: { display: true, text: 'Number of Requests' }, beginAtZero: true, stacked: true }
                }
            }
        });

        const dailyChart = new Chart(document.getElementById('dailyChart'), {
            type: 'bar',
            data: {
                labels: ['Today'],
                datasets: <?php echo json_encode($dailyDatasets); ?>
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: 'Period' } },
                    y: { title: { display: true, text: 'Number of Requests' }, beginAtZero: true }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        const weeklyChart = new Chart(document.getElementById('weeklyChart'), {
            type: 'bar',
            data: {
                labels: ['This Week'],
                datasets: <?php echo json_encode($weeklyDatasets); ?>
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: 'Period' } },
                    y: { title: { display: true, text: 'Number of Requests' }, beginAtZero: true }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        const monthlyChart = new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: ['This Month'],
                datasets: <?php echo json_encode($monthlyDatasets); ?>
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: 'Period' } },
                    y: { title: { display: true, text: 'Number of Requests' }, beginAtZero: true }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        const yearlyChart = new Chart(document.getElementById('yearlyChart'), {
            type: 'bar',
            data: {
                labels: ['This Year'],
                datasets: <?php echo json_encode($yearlyDatasets); ?>
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: 'Period' } },
                    y: { title: { display: true, text: 'Number of Requests' }, beginAtZero: true }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        function generatePDF(period) {
            const weekRange = getWeekRange();
            const filteredRequests = allRequests.filter(request => {
                const requestDate = new Date(request.request_date);
                if (period === 'Yearly') {
                    return requestDate.getFullYear() === currentYear;
                } else if (period === 'Monthly') {
                    return `${requestDate.getFullYear()}-${(requestDate.getMonth() + 1).toString().padStart(2, '0')}` === currentYearMonth;
                } else if (period === 'Weekly') {
                    return request.request_date >= weekRange.start && request.request_date <= weekRange.end;
                } else if (period === 'Daily') {
                    return request.request_date === currentDate;
                }
                return true;
            });

            // Get base64 images of the charts
            const requestChartImage = requestChart.toBase64Image();
            const sponsorshipTypeChartImage = sponsorshipTypeChart.toBase64Image();
            const statusOverTimeChartImage = statusOverTimeChart.toBase64Image();
            const dailyChartImage = dailyChart.toBase64Image();
            const weeklyChartImage = weeklyChart.toBase64Image();
            const monthlyChartImage = monthlyChart.toBase64Image();
            const yearlyChartImage = yearlyChart.toBase64Image();

            // Select the period-specific chart based on the period
            let periodChartImage;
            let periodChartTitle;
            switch (period) {
                case 'Daily':
                    periodChartImage = dailyChartImage;
                    periodChartTitle = `Daily Requests (${currentDate})`;
                    break;
                case 'Weekly':
                    periodChartImage = weeklyChartImage;
                    periodChartTitle = `Weekly Requests (${weekRange.start} to ${weekRange.end})`;
                    break;
                case 'Monthly':
                    periodChartImage = monthlyChartImage;
                    periodChartTitle = `Monthly Requests (${currentYearMonth})`;
                    break;
                case 'Yearly':
                    periodChartImage = yearlyChartImage;
                    periodChartTitle = `Yearly Requests (${currentYear})`;
                    break;
            }

            const docDefinition = {
                content: [
                    {
                        text: `Sponsorship Request History - ${period} Report`,
                        style: 'header',
                        alignment: 'center',
                        margin: [0, 0, 0, 20]
                    },
                    {
                        text: `Total Requests: ${filteredRequests.length}`,
                        style: 'subheader',
                        margin: [0, 0, 0, 10]
                    },
                    {
                        table: {
                            headerRows: 1,
                            widths: ['*', '*', '*', '*', '*', '*'],
                            body: [
                                [
                                    { text: 'Event Topic', style: 'tableHeader' },
                                    { text: 'Event Type', style: 'tableHeader' },
                                    { text: 'Sponsor', style: 'tableHeader' },
                                    { text: 'Unit', style: 'tableHeader' },
                                    { text: 'Request Date', style: 'tableHeader' },
                                    { text: 'Status', style: 'tableHeader' }
                                ],
                                ...filteredRequests.map(request => [
                                    request.event_topic,
                                    request.event_type,
                                    request.company_name,
                                    request.sponsor_unit,
                                    request.request_date,
                                    request.status
                                ])
                            ]
                        },
                        margin: [0, 0, 0, 20]
                    },
                    {
                        text: periodChartTitle,
                        style: 'subheader',
                        margin: [0, 20, 0, 10],
                        pageBreak: 'before'
                    },
                    {
                        image: periodChartImage,
                        width: 500,
                        margin: [0, 0, 0, 20]
                    },
                    {
                        text: 'Requests per Event Over Time',
                        style: 'subheader',
                        margin: [0, 20, 0, 10],
                        pageBreak: 'before'
                    },
                    {
                        image: requestChartImage,
                        width: 500,
                        margin: [0, 0, 0, 20]
                    },
                    {
                        text: 'Sponsorship Types for <?php echo $eventTopics[0] ?? "Event"; ?>',
                        style: 'subheader',
                        margin: [0, 20, 0, 10],
                        pageBreak: 'before'
                    },
                    {
                        image: sponsorshipTypeChartImage,
                        width: 400,
                        margin: [0, 0, 0, 20]
                    },
                    {
                        text: 'Status per Event Over Time',
                        style: 'subheader',
                        margin: [0, 20, 0, 10],
                        pageBreak: 'before'
                    },
                    {
                        image: statusOverTimeChartImage,
                        width: 500,
                        margin: [0, 0, 0, 20]
                    }
                ],
                styles: {
                    header: {
                        fontSize: 18,
                        bold: true,
                        color: '#1F509A'
                    },
                    subheader: {
                        fontSize: 14,
                        bold: true,
                        color: '#333'
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 12,
                        color: 'white',
                        fillColor: '#1F509A', 
                        alignment: 'center'
                    }
                },
                defaultStyle: {
                    fontSize: 10,
                    color: '#333'
                }
            };

            pdfMake.createPdf(docDefinition).download(`request_history_${period.toLowerCase()}_report_${new Date().toISOString().slice(0, 10)}.pdf`);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>