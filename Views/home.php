<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-blue-800 p-4 flex justify-between items-center text-white">
        <h1 class="text-2xl font-bold">SponsMe</h1>
        <div class="flex space-x-4">
            <a href="home.php" class="text-white hover:underline text-sm sm:text-base">home</a>
            <a href="login.php" class="text-white hover:underline text-sm sm:text-base">Login</a>
            <a href="#" class="text-white hover:underline text-sm sm:text-base">Profile</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex flex-col items-center justify-center text-center flex-grow px-4">
        <div class="text-white">
            <h1 class="text-4xl sm:text-5xl font-bold">We Get</h1>
            <h1 class="text-4xl sm:text-5xl font-bold">Your Sponsorship</h1>
            <h1 class="text-4xl sm:text-5xl font-bold text-blue-300">Easily</h1>
        </div>

        <!-- Button -->
        <button onclick="window.location.href='login.php'" class="mt-8 bg-blue-700 text-white px-6 py-3 rounded-lg hover:bg-blue-600 text-sm sm:text-base">
            Find Sponsorships
        </button>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>