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
        section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-blue-800 p-4 flex justify-between items-center text-white sticky top-0 z-50">
        <h1 class="text-2xl font-bold">SponsMe</h1>
        <div class="flex space-x-4">
            <a href="#home" class="text-white hover:underline text-sm sm:text-base">Home</a>
            <a href="#about" class="text-white hover:underline text-sm sm:text-base">About</a>
            <a href="#how-it-works" class="text-white hover:underline text-sm sm:text-base">How It Works</a>
        </div>
    </nav>

    <!-- Home Section -->
    <section id="home" class="bg-gradient-to-r from-blue-500 to-gray-400">
        <div class="flex flex-col items-center justify-center text-center px-4">
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
    </section>

    <!-- About Section -->
    <section id="about" class="bg-gray-100">
        <div class="text-center px-4">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4 text-gray-800">About Us</h2>
            <p class="text-gray-700 text-sm sm:text-base max-w-2xl mx-auto">
                SponsMe is a platform designed to connect sponsors and organizers effortlessly. We streamline the sponsorship process, making it easier for you to find the right opportunities and partnerships.
            </p>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="bg-white">
        <div class="text-center px-4">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4 text-gray-800">How It Works</h2>
            <p class="text-gray-700 text-sm sm:text-base max-w-2xl mx-auto">
                Simply sign up, create your profile, and start exploring sponsorship opportunities tailored to your needs. Whether you're an organizer or a sponsor, our platform is here to help you succeed.
            </p>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
