<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-blue-800 p-4 flex justify-between items-center text-white">
        <h1 class="text-2xl font-bold">SponsMe</h1>
        <div class="flex space-x-4">
            <a href="#home" class="text-white hover:underline text-sm sm:text-base">Home</a>
            <a href="#about" class="text-white hover:underline text-sm sm:text-base">About</a>
            <a href="#how-it-works" class="text-white hover:underline text-sm sm:text-base">How It Works</a>
            <a href="login.php" class="text-white hover:underline text-sm sm:text-base">Login</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="home" class="flex flex-col items-center justify-center text-center flex-grow px-4">
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

    <!-- About Section -->
    <div id="about" class="bg-white py-12 px-6">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">About Us</h2>
        <p class="text-gray-600 text-center max-w-2xl mx-auto">SponsMe is a platform designed to connect event organizers with potential sponsors easily. Our mission is to simplify the sponsorship process and create opportunities for events of all sizes.</p>
    </div>

    <!-- How It Works Section -->
    <div id="how-it-works" class="bg-gray-100 py-12 px-6">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">How It Works</h2>
        <div class="text-gray-600 max-w-3xl mx-auto space-y-4">
            <p><strong>Step 1:</strong> Organizers register their events and provide sponsorship requirements.</p>
            <p><strong>Step 2:</strong> Sponsors browse events and find the perfect match for their brand.</p>
            <p><strong>Step 3:</strong> Both parties communicate and finalize the sponsorship details seamlessly.</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
