<?php
session_start();
include '../../Model/db.php';

// Check if the user is logged in
if (!isset($_SESSION['userEmail'])) {
    header('Location: ../Login.php');
    exit();
}

$userEmail = $_SESSION['userEmail'];

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

try {

    $tableCheck = "SHOW COLUMNS FROM `sponsors` LIKE 'profile_picture'";
    $profilePictureExists = $conn->query($tableCheck)->num_rows > 0;

    if ($profilePictureExists) {
        $sql = "SELECT `id`, `company_name`, `unit`, `company_registration_code`, `address`, `emp_id`, 
                `sponsor_events`, `email`, `mobile_no`, `facebook`, `instagram`, `password`, 
                `company_status`, `created_at`, `profile_picture` 
                FROM `sponsors` WHERE `email` = ?";
    } else {
        $sql = "SELECT `id`, `company_name`, `unit`, `company_registration_code`, `address`, `emp_id`, 
                `sponsor_events`, `email`, `mobile_no`, `facebook`, `instagram`, `password`, 
                `company_status`, `created_at` 
                FROM `sponsors` WHERE `email` = ?";
    }

    $stmt = $conn->prepare($sql);

    // Check if prepare was successful
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sponsor = $result->fetch_assoc();
    } else {
        echo "<script>alert('Sponsor data not found! Redirecting to login.'); window.location.href = '../Login.php';</script>";
        exit();
    }
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

// Determine if user can edit company name based on company_status
$canEditCompanyName = ($sponsor['company_status'] === 'Main');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsor Profile</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
<!-- Sidebar/Navbar -->
<div class="bg-gray-900 text-white w-full md:w-1/4 p-6 flex flex-col items-center">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-center bg-gradient-to-r from-blue-400 to-indigo-500 bg-clip-text text-transparent">SponsMe</h2>
    </div>
    
    <nav class="flex flex-col gap-4 w-full">
        <a href="../home.php" class="group transition-all duration-300 hover:bg-indigo-700 bg-gray-800 p-4 rounded-lg text-center flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Home
        </a>
        <a href="#" class="group transition-all duration-300 bg-indigo-600 p-4 rounded-lg text-center flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile
        </a>
        <a href="Request.php" class="group transition-all duration-300 hover:bg-indigo-700 bg-gray-800 p-4 rounded-lg text-center flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Sponsorship Requests
        </a>
        <a href="BudgetManagement.php" class="group transition-all duration-300 hover:bg-indigo-700 bg-gray-800 p-4 rounded-lg text-center flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Budget Management
        </a>
    </nav>
    
    <div class="mt-auto w-full pt-6 border-t border-gray-700 mt-8">
        <a href="../login.php" class="bg-red-700 hover:bg-red-800 transition-colors duration-300 p-4 rounded-lg text-center block flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Log Out
        </a>
    </div>
</div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Profile</h3>

            <!-- Profile Details -->
            <div class="flex flex-col sm:flex-row items-center gap-6 mb-6">
                <div class="bg-gray-300 w-24 h-24 rounded-full flex items-center justify-center overflow-hidden">
                    <?php if (isset($sponsor['profile_picture']) && !empty($sponsor['profile_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($sponsor['profile_picture']); ?>" alt="Profile Picture" class="object-cover w-full h-full">
                    <?php else: ?>
                        <span class="text-gray-600 text-2xl">👤</span>
                    <?php endif; ?>
                </div>
                <div>
                    <h4 class="text-xl font-semibold text-gray-800 text-center sm:text-left">Edit Your Profile</h4>
                    <p class="text-gray-600">Status: <?php echo htmlspecialchars($sponsor['company_status']); ?></p>
                    <?php if ($sponsor['company_status'] === 'Sub'): ?>
                        <p class="text-yellow-600 text-sm mt-1">Note: As a Sub status, you cannot edit Company Name</p>
                    <?php endif; ?>
                </div>
            </div>

            <form id="profileForm" action="../../Controller/Sponsor/UpdateSponsor.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="company_status" value="<?php echo htmlspecialchars($sponsor['company_status']); ?>">
                <input type="hidden" name="sponsor_id" value="<?php echo htmlspecialchars($sponsor['id']); ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Profile Picture</label>
                        <input type="file" id="profilePicture" name="profile_picture" class="bg-gray-100 p-3 rounded border border-gray-300 w-full">
                        <?php if (isset($sponsor['profile_picture']) && !empty($sponsor['profile_picture'])): ?>
                            <p class="text-sm text-gray-500 mt-1">Current image: <?php echo basename($sponsor['profile_picture']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Company Name</label>
                        <input id="company_name" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full<?php echo !$canEditCompanyName ? ' cursor-not-allowed' : ''; ?>"
                            name="company_name" value="<?php echo htmlspecialchars($sponsor['company_name']); ?>"
                            readonly data-can-edit="<?php echo $canEditCompanyName ? 'true' : 'false'; ?>">
                        <?php if (!$canEditCompanyName): ?>
                            <p class="text-sm text-red-500 mt-1">Company Name can only be edited by Main status accounts</p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Unit</label>
                        <input id="unit" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full"
                            name="unit" value="<?php echo htmlspecialchars($sponsor['unit']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Employee ID of unit incharge</label>
                        <input id="emp_id" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full"
                            name="emp_id" value="<?php echo htmlspecialchars($sponsor['emp_id']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Address</label>
                        <input id="address" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full"
                            name="address" value="<?php echo htmlspecialchars($sponsor['address']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Company Registration Code</label>
                        <input id="company_registration_code" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full"
                            name="company_registration_code" value="<?php echo htmlspecialchars($sponsor['company_registration_code']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input id="email" type="email" class="bg-gray-100 p-3 rounded border border-gray-300 w-full cursor-not-allowed"
                            name="email" value="<?php echo htmlspecialchars($sponsor['email']); ?>" readonly>
                        <p class="text-sm text-gray-500 mt-1">Email cannot be edited</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Contact No.</label>
                        <input id="mobile_no" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full"
                            name="mobile_no" value="<?php echo htmlspecialchars($sponsor['mobile_no']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Facebook</label>
                        <input id="facebook" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full"
                            name="facebook" value="<?php echo htmlspecialchars($sponsor['facebook']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Instagram</label>
                        <input id="instagram" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full"
                            name="instagram" value="<?php echo htmlspecialchars($sponsor['instagram']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Sponsored Events</label>
                        <input id="sponsor_events" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full"
                            name="sponsor_events" value="<?php echo htmlspecialchars($sponsor['sponsor_events']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Member Since</label>
                        <input type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full cursor-not-allowed"
                            value="<?php echo htmlspecialchars($sponsor['created_at']); ?>" readonly>
                        <p class="text-sm text-gray-500 mt-1">Membership date cannot be edited</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 mt-6">
                    <button id="editButton" type="button" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded w-full sm:w-1/3">Edit</button>
                    <button id="saveButton" type="submit" class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded w-full sm:w-1/3 hidden">Save Changes</button>
                    <button type="button" onclick="if(confirm('Are you sure you want to delete your profile?')) { window.location.href='../../Controller/Sponsor/DeleteSponsor.php'; }" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded w-full sm:w-1/3">Delete Profile</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const editButton = document.getElementById('editButton');
        const saveButton = document.getElementById('saveButton');
        const fields = document.querySelectorAll('#profileForm input');
        const profilePictureInput = document.getElementById('profilePicture');

        // Function to enable editable fields
        editButton.addEventListener('click', () => {
            fields.forEach(field => {
                // Skip email, hidden fields, and created_at fields which should never be editable
                if (field.id === 'email' || field.type === 'hidden' || field.name === 'created_at') {
                    return;
                }

                // Handle company_name field based on company_status
                if (field.id === 'company_name') {
                    const canEdit = field.getAttribute('data-can-edit') === 'true';
                    if (canEdit) {
                        field.removeAttribute('readonly');
                        field.classList.remove('bg-gray-100');
                    }
                } else {
                    // For all other fields that should be editable
                    field.removeAttribute('readonly');
                    field.classList.remove('bg-gray-100');
                    field.classList.add('bg-white');
                }
            });

            saveButton.classList.remove('hidden');
            editButton.classList.add('hidden');
        });

        profilePictureInput.addEventListener('change', () => {
            saveButton.classList.remove('hidden');
            editButton.classList.add('hidden');
        });

        document.getElementById('profileForm').addEventListener('submit', (e) => {
            const confirmed = confirm('Are you sure you want to save these changes?');
            if (!confirmed) e.preventDefault();
        });
    </script>
</body>

</html>