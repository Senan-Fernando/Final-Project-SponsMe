<?php
// session_start();
// include '../../Model/db.php';

// Check if the user is logged in
// if (!isset($_SESSION['userEmail'])) {
//     header('Location: ../Login.php');
//     exit();
// }

// $userEmail = $_SESSION['userEmail'];
// $sql = "SELECT `id`, `first_name`, `last_name`, `email`, `address`, `sponsor_events`, `mobile_no`, `facebook`, `instagram`, `whatsapp`, `youtube`, `profile_picture`, `password`, `created_at` FROM `sponsors` WHERE `email` = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("s", $userEmail);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows > 0) {
//     $sponsor = $result->fetch_assoc();
// } else {
//     echo "<script>alert('Sponsor data not found! Redirecting to login.'); window.location.href = '../Login.php';</script>";
//     exit();
// }
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
    <div class="bg-blue-900 text-white w-full md:w-1/4 p-6 flex flex-col items-center">
        <h2 class="text-2xl font-bold mb-6 text-center">SponsMe</h2>
        <nav class="flex flex-col gap-4 w-full">
            <a href="../home.php" class="bg-blue-700 p-3 rounded text-center">home</a>
            <a href="#" class="bg-blue-700 p-3 rounded text-center">Profile</a>
            <a href="Request.php" class="bg-blue-700 p-3 rounded text-center">Sponsorship Requests</a>
        </nav>
        <div class="mt-auto w-full">
            <a href="../login.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <div class="bg-[#1F509A] rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Profile</h3>

            <!-- Profile Details -->
            <div class="flex flex-col sm:flex-row items-center gap-6 mb-6">
                <div class="bg-gray-300 w-24 h-24 rounded-full flex items-center justify-center overflow-hidden">
                    <?php if (!empty($sponsor['profile_picture'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo $sponsor['profile_picture']; ?>" alt="Profile Picture" class="object-cover w-full h-full">
                    <?php else: ?>
                        <span class="text-gray-600 text-2xl">👤</span>
                    <?php endif; ?>
                </div>
                <div>
                    <h4 class="text-xl font-semibold text-gray-800 text-center sm:text-left">Edit Your Profile</h4>
                </div>
            </div>

            <form id="profileForm" action="../../Controller/Sponsor/UpdateSponsor.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Profile Picture</label>
                        <input type="file" id="profilePicture" name="profile_picture" class="bg-gray-100 p-3 rounded border border-gray-300 w-full">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Company Name</label>
                        <input id="name" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full" name="name" value="<?php echo htmlspecialchars($sponsor['first_name'] . ' ' . $sponsor['last_name']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Unit</label>
                        <input type="name" class="bg-gray-100 p-3 rounded border border-gray-300 w-full" name="email" value="<?php echo htmlspecialchars($sponsor['email']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Employee ID of unit incharge</label>
                        <input id="Emp_ID" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full" name="address" value="<?php echo htmlspecialchars($sponsor['address']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Address</label>
                        <input id="address" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full" name="mobile_no" value="<?php echo htmlspecialchars($sponsor['mobile_no']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Company Registration code</label>
                        <input id="code" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full" name="whatsapp" value="<?php echo htmlspecialchars($sponsor['whatsapp']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input id="Email" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full" name="facebook" value="<?php echo htmlspecialchars($sponsor['facebook']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Contact No.</label>
                        <input id="contact_no" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full" name="instagram" value="<?php echo htmlspecialchars($sponsor['instagram']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Facebook</label>
                        <input id="facebook" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full" name="youtube" value="<?php echo htmlspecialchars($sponsor['youtube']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Instagram</label>
                        <input id="instagram" type="text" class="bg-gray-100 p-3 rounded border border-gray-300 w-full" name="youtube" value="<?php echo htmlspecialchars($sponsor['youtube']); ?>" readonly>
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

        editButton.addEventListener('click', () => {
            fields.forEach(field => {
                if (field.id !== 'email') {
                    field.removeAttribute('readonly');
                    field.classList.remove('bg-gray-100');
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
