<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsor Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex items-center justify-center p-4">
    <div class="absolute top-4 left-4 text-white text-xl font-bold">
        <a href='index.php' class='hover:underline'>SponsMe</a>
    </div>

    <!-- Registration Form Container -->
    <div class="bg-gray-100 rounded-lg shadow-lg p-6 w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Register Profile</h2>

        <!-- Input Fields -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <input type="text" placeholder="First Name" class="form-control" required>
            <input type="text" placeholder="Last Name" class="form-control" required>
        </div>
        <div class="mb-4">
            <input type="email" placeholder="Email" class="form-control" required>
        </div>
        <div class="mb-4">
            <input type="address" placeholder="Address" class="form-control" required>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <select class="form-select" required>
                <option selected disabled>Sponsor Events</option>
                <option value="1">Concerts</option>
                <option value="2">Charity</option>
                <option value="3">Sports</option>
                <option value="4">Gaming</option>
            </select>
            <input type="text" placeholder="Mobile No." class="form-control" required>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <input type="text" placeholder="Facebook Profile Link" class="form-control" required>
            <input type="text" placeholder="Instagram Profile Link" class="form-control" required>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <input type="text" placeholder="Whatsapp" class="form-control" required>
            <input type="text" placeholder="Youtube channel Link" class="form-control" required>
        </div>

        <!-- Submit Button -->
        <div class="text-center mb-4">
            <button type="submit" onclick="window.location.href='login.php'" class="btn btn-primary w-full">Sign Up</button>
        </div>

        <!-- Login Link -->
        <p class="text-center text-gray-600">
            Or <a href="../Login.php" class="text-blue-600 hover:underline">Log In</a>
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
