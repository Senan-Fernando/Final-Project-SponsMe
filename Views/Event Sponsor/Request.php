<?php
session_start();
include '../../Model/db.php';

// Check if sponsor is logged in
if (!isset($_SESSION['userEmail'])) {
    header('Location: ../Login.php');
    exit();
}

// Get the sponsor_id based on the logged-in user's email
$userEmail = $_SESSION['userEmail'];
$sponsorQuery = "SELECT id FROM sponsors WHERE email = '$userEmail'";
$sponsorResult = $conn->query($sponsorQuery);

if ($sponsorResult && $sponsorResult->num_rows > 0) {
    $sponsorRow = $sponsorResult->fetch_assoc();
    $sponsor_id = $sponsorRow['id'];
} else {
    // Handle case where sponsor is not found
    echo "Error: Sponsor not found for this email.";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsor Requests</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col md:flex-row">

       <!-- Sidebar -->
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
                        <a href="Sponsorprof.php" class="flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-blue-700 <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'bg-blue-700 shadow-md' : ''; ?>">
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
                        <a href="#" class="flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-blue-700 <?php echo basename($_SERVER['PHP_SELF']) == 'Request.php' ? 'bg-blue-700 shadow-md' : ''; ?>">
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
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Sponsorship Requests</h3>

        <!-- Filter Controls -->
        <div class="bg-blue-50 p-4 rounded-lg shadow mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="filter-status" class="form-label">Filter by Status</label>
                    <select id="filter-status" class="form-select">
                        <option value="all">All Requests</option>
                        <option value="pending">Pending</option>
                        <option value="accepted">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label for="sort-by" class="form-label">Sort By</label>
                    <select id="sort-by" class="form-select">
                        <option value="date-desc">Newest First</option>
                        <option value="date-asc">Oldest First</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Requests Grid with PHP Integration -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            // Query to get sponsorship requests for this sponsor with organizer details
            if (isset($sponsor_id) && $sponsor_id > 0) {
                $sql = "SELECT r.id, r.organizer_id, r.event_type, r.event_topic, 
                       r.sponsorship_type, r.target_audience, r.message, r.request_date, 
                       r.status, r.location, s.company_name, s.unit,
                       o.crew_name, o.first_name, o.last_name
                       FROM sponsorship_requests r
                       JOIN sponsors s ON r.sponsor_id = s.id
                       JOIN organizers o ON r.organizer_id = o.id
                       WHERE r.sponsor_id = $sponsor_id
                       ORDER BY r.request_date DESC";

                $result = $conn->query($sql);

                // Debug connection issues if needed
                if (!$result) {
                    echo '<div class="col-12 text-center p-5 bg-white rounded shadow">';
                    echo '<h4 class="mt-3">Database Error</h4>';
                    echo '<p class="text-muted">Error: ' . $conn->error . '</p>';
                    echo '</div>';
                } elseif ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Determine status class
                        $statusClass = "";
                        switch ($row["status"]) {
                            case "pending":
                                $statusClass = "status-pending";
                                break;
                            case "accepted":
                                $statusClass = "status-accepted";
                                break;
                            case "rejected":
                                $statusClass = "status-rejected";
                                break;
                            default:
                                $statusClass = "status-pending";
                        }

                        // Format date
                        $requestDate = date("M d, Y", strtotime($row["request_date"]));
            ?>
                        <div class="bg-[#1F509A] rounded-lg shadow-lg p-4 card-hover request-card"
                            data-status="<?php echo $row['status']; ?>"
                            data-event-type="<?php echo $row['event_type']; ?>">
                            <div class="request-details text-white mb-4">
                                <h5 class="font-bold mb-2"><?php echo $row["event_topic"]; ?></h5>
                                <p class="text-sm mb-1"><i class="bi bi-people me-2"></i> <?php echo $row["crew_name"]; ?></p>
                                <p class="text-sm mb-1"><i class="bi bi-person me-2"></i> <?php echo $row["first_name"] . " " . $row["last_name"]; ?></p>
                                <p class="text-sm mb-1"><i class="bi bi-geo-alt me-2"></i> <?php echo $row["location"]; ?></p>
                                <p class="text-sm mb-1"><i class="bi bi-calendar-event me-2"></i> <?php echo $requestDate; ?></p>
                                <div class="mt-2">
                                    <span class="badge <?php echo $statusClass; ?> px-3 py-2">
                                        <?php echo ucfirst($row["status"]); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-auto">
                                <a href="ViewEventform.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                                <?php if ($row["status"] == "pending"): ?>
                                    <div class="flex gap-2">
                                        <button class="btn btn-success" onclick="updateStatus(<?php echo $row['id']; ?>, 'accepted')">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button class="btn btn-danger" onclick="updateStatus(<?php echo $row['id']; ?>, 'rejected')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
            <?php
                    }
                } else {
                    echo '<div class="col-12 text-center p-5 bg-white rounded shadow">';
                    echo '<i class="bi bi-inbox-fill text-secondary" style="font-size: 3rem;"></i>';
                    echo '<h4 class="mt-3">No sponsorship requests found</h4>';
                    echo '<p class="text-muted">You don\'t have any sponsorship requests at the moment.</p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-12 text-center p-5 bg-white rounded shadow">';
                echo '<h4 class="mt-3">Account Error</h4>';
                echo '<p class="text-muted">Unable to retrieve your sponsor information. Please contact support.</p>';
                echo '</div>';
            }

            $conn->close();
            ?>
        </div>

        <!-- Pagination (if needed) -->
        <nav class="mt-6" aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Filter & Sort JavaScript -->
    <script>
        // Function to handle status updates
        function updateStatus(requestId, status) {
    if (confirm("Are you sure you want to " + (status === 'accepted' ? 'accepted' : 'reject') + " this request?")) {
        // Send AJAX request to update status
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../../Controller/Sponsor/update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                // Reload page or update UI
                window.location.reload();
            }
        };
        xhr.send("request_id=" + requestId + "&status=" + status);
    }
}

        // Filter functionality
        document.getElementById('filter-status').addEventListener('change', filterRequests);
        document.getElementById('sort-by').addEventListener('change', sortRequests);

        function filterRequests() {
            const statusFilter = document.getElementById('filter-status').value;
            const requestCards = document.querySelectorAll('.request-card');

            requestCards.forEach(card => {
                let showCard = true;

                // Status filtering
                if (statusFilter !== 'all' && card.dataset.status !== statusFilter) {
                    showCard = false;
                }

                card.style.display = showCard ? 'flex' : 'none';
            });
        }

        function sortRequests() {
            const sortBy = document.getElementById('sort-by').value;
            const container = document.querySelector('.grid');
            const requestCards = Array.from(document.querySelectorAll('.request-card'));

            // Sort based on date
            requestCards.sort((a, b) => {
                const dateA = new Date(a.querySelector('.bi-calendar-event').nextSibling.textContent.trim());
                const dateB = new Date(b.querySelector('.bi-calendar-event').nextSibling.textContent.trim());

                return sortBy === 'date-asc' ? dateA - dateB : dateB - dateA;
            });

            // Reorder in DOM
            requestCards.forEach(card => {
                container.appendChild(card);
            });
        }
    </script>
</body>

</html>