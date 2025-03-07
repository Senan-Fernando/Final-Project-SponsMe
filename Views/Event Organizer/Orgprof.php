<?php
include '../../Model/db.php';
session_start();

// Check if the session is set for the organizer
if (!isset($_SESSION['userId']) || $_SESSION['userRole'] !== 'organizer') {
    header("Location: ../login.php");
    exit();
}

$organizer_id = $_SESSION['userId'];

// Fetch organizer details
$query = "SELECT `id`, `crew_name`, `leader_nic`, `first_name`, `last_name`, `email`, `mobile`, `whatsapp`, `password`, `created_at` FROM `organizers` WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$result = $stmt->get_result();
$organizer = $result->fetch_assoc();

// Fetch image if exists
$query = "SELECT image_path FROM image_uploads WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$image = $stmt->get_result()->fetch_assoc();

// Store alert message in a variable
$alert_message = '';
$alert_type = '';

// Check if there's a message in the session
if (isset($_SESSION['message']) && isset($_SESSION['alert_type'])) {
    $alert_message = $_SESSION['message'];
    $alert_type = $_SESSION['alert_type'];
    // Clear the session message so it doesn't appear again on refresh
    unset($_SESSION['message']);
    unset($_SESSION['alert_type']);
}

// Check if the form is submitted to update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the updated data from the form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $crew_name = $_POST['crew_name'];
    $leader_nic = $_POST['leader_nic'];
    $mobile = $_POST['mobile'];
    $whatsapp = $_POST['whatsapp'];

    // Update organizer details in the database
    $update_query = "UPDATE `organizers` SET `first_name` = ?, `last_name` = ?, `crew_name` = ?, `leader_nic` = ?, `mobile` = ?, `whatsapp` = ? WHERE `id` = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $first_name, $last_name, $crew_name, $leader_nic, $mobile, $whatsapp, $organizer_id);

    if ($stmt->execute()) {
        // Data updated successfully - store in session
        $_SESSION['message'] = "Profile updated successfully.";
        $_SESSION['alert_type'] = "success";
    } else {
        // Error updating the profile - store in session
        $_SESSION['message'] = "Error updating profile.";
        $_SESSION['alert_type'] = "error";
    }
    
    // Redirect to prevent form resubmission on refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Close connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.min.js"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <div class="bg-blue-900 text-white w-full md:w-1/4 p-6 flex flex-col">
        <h2 class="text-2xl font-bold mb-6 text-center md:text-left">SponsMe</h2>
        <nav class="flex flex-col gap-4">
            <a href="../home.php" class="bg-blue-700 p-3 rounded text-center md:text-left">Home</a>
            <a href="#" class="bg-blue-700 p-3 rounded text-center md:text-left">Profile</a>
            <a href="eventform.php" class="bg-blue-700 p-3 rounded text-center md:text-left">Seek Sponsorship</a>
        </nav>
        <div class="mt-auto">
            <a href="../../Controller/Logout.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <div class="bg-[#1F509A] rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold text-white mb-6">Profile</h3>

            <!-- Profile Details -->
            <div class="flex flex-col sm:flex-row items-center gap-6 mb-6">
                <div class="relative">
                    <img src="<?php echo !empty($image['image_path']) ? '../../' . htmlspecialchars($image['image_path']) : 'default-avatar.png'; ?>" class="bg-gray-300 w-24 h-24 rounded-full object-cover">
                </div>

                <form action="../../Controller/Organizer/uploadImage.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_image" accept="image/*" class="form-control mb-2" required>
                    <input type="hidden" name="user_id" value="<?php echo $organizer_id; ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($organizer['email']); ?>">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>

            <form>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-white font-medium mb-2">Crew Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($organizer['crew_name'] ?? "N/A"); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-white font-medium mb-2">Crew Leader NIC Number</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($organizer['leader_nic'] ?? "N/A"); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-white font-medium mb-2">First Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($organizer['first_name'] ?? "N/A"); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-white font-medium mb-2">Last Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($organizer['last_name'] ?? "N/A"); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-white font-medium mb-2">Email</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($organizer['email'] ?? "N/A"); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-white font-medium mb-2">Mobile No.</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($organizer['mobile'] ?? "N/A"); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-white font-medium mb-2">WhatsApp</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($organizer['whatsapp'] ?? "N/A"); ?>" readonly>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 mt-6">
                    <button type="button" onclick="openModal()" class="btn btn-primary w-full sm:w-1/3">Edit Profile</button>
                    <button type="button" onclick="confirmDelete()" class="btn btn-danger w-full sm:w-1/3">Delete Profile</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg max-h-[90vh] overflow-y-auto">
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Edit Profile</h2>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $organizer_id; ?>">

                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="border-b pb-4">
                        <h3 class="font-semibold text-lg text-gray-700">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700">First Name</label>
                                <input type="text" name="first_name" class="w-full p-2 border rounded"
                                    value="<?php echo htmlspecialchars($organizer['first_name'] ?? ""); ?>" required>
                            </div>
                            <div>
                                <label class="block text-gray-700">Last Name</label>
                                <input type="text" name="last_name" class="w-full p-2 border rounded"
                                    value="<?php echo htmlspecialchars($organizer['last_name'] ?? ""); ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Crew Details -->
                    <div class="border-b pb-4">
                        <h3 class="font-semibold text-lg text-gray-700">Crew Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700">Crew Name</label>
                                <input type="text" name="crew_name" class="w-full p-2 border rounded"
                                    value="<?php echo htmlspecialchars($organizer['crew_name'] ?? ""); ?>" required>
                            </div>
                            <div>
                                <label class="block text-gray-700">Crew Leader NIC</label>
                                <input type="text" name="leader_nic" class="w-full p-2 border rounded"
                                    value="<?php echo htmlspecialchars($organizer['leader_nic'] ?? ""); ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="border-b pb-4">
                        <h3 class="font-semibold text-lg text-gray-700">Contact Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700">Mobile Number</label>
                                <input type="text" name="mobile" class="w-full p-2 border rounded"
                                    value="<?php echo htmlspecialchars($organizer['mobile'] ?? ""); ?>" required>
                            </div>
                            <div>
                                <label class="block text-gray-700">WhatsApp</label>
                                <input type="text" name="whatsapp" class="w-full p-2 border rounded"
                                    value="<?php echo htmlspecialchars($organizer['whatsapp'] ?? ""); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col md:flex-row justify-between gap-2">
                        <button type="submit" class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700 w-full md:w-auto">
                            Save Changes
                        </button>
                        <button type="button" onclick="closeModal()" class="bg-gray-500 text-white p-2 rounded hover:bg-gray-600 w-full md:w-auto">
                            Close
                        </button>
                    </div>
                </div>
            </form>

            <!-- Change Password Section -->
            <div class="border-t pt-6 mt-6">
                <h3 class="font-semibold text-lg text-gray-700">Change Password</h3>
                <form action="../../Controller/Organizer/UpdatePassword.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="hidden" name="user_id" value="<?php echo $organizer_id; ?>">
                    <div>
                        <label class="block text-gray-700">Current Password</label>
                        <input type="password" name="current_password" class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">New Password</label>
                        <input type="password" name="new_password" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700">Confirm Password</label>
                        <input type="password" name="confirm_password" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="md:col-span-2 text-center">
                        <button type="submit" class="bg-red-600 text-white p-2 rounded hover:bg-red-700 w-full md:w-auto">
                            Update Password
                        </button>
                    </div>
                </form>
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

        function openModal() {
            document.getElementById("editProfileModal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("editProfileModal").classList.add("hidden");
        }

        function confirmDelete() {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to delete your profile? This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'deleteOrganizer.php';
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>