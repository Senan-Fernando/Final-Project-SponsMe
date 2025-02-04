<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Organizer Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-gray-400 flex items-center justify-center min-h-screen p-4">

    <div class="absolute top-4 left-4 text-black text-xl font-bold">
        <a href="home.php" class="text-black hover:text">SponsMe</a>
    </div>

    <!-- Edit Profile Form Container -->
    <div class="bg-[#1F509A] rounded-lg shadow-lg p-6 w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center text-white mb-6">Edit Profile</h2>

        <form>
            <!-- Input Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="firstName" class="block text-white font-medium mb-2">Crew Name</label>
                    <input type="text" id="crewname" class="form-control" placeholder="Crew Name" required>
                </div>
                <div>
                    <label for="lastName" class="block text-white  font-medium mb-2">Crew Leader NIC number</label>
                    <input type="text" id="NIC" class="form-control" placeholder="Crew leader NIC" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-white  font-medium mb-2">Email</label>
                <input type="email" id="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="number" class="block text-white  font-medium mb-2">Mobile No.</label>
                    <input type="text" id="number" class="form-control" placeholder="Mobile No." required>
                </div>
                <div>
                    <label for="number" class="block text-white  font-medium mb-2">Whatsapp</label>
                    <input type="text" id="whatsapp" class="form-control" placeholder="Whatsapp" required>
                </div>
                <div>
                    <label for="password" class="block text-white  font-medium mb-2">New Password</label>
                    <input type="password" id="password" class="form-control" placeholder="New Password" required>
                </div>
                <div>
                    <label for="confirmPassword" class="block text-white  font-medium mb-2">Confirm Password</label>
                    <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" onclick="window.location.href='Orgprof.php'" class="btn btn-primary w-full bg-green-500">Save changes</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
