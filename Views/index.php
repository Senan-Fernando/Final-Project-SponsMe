<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - SponsMe</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .nav-link {
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #93c5fd !important;
        }
        .cta-button {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-5px);
            background-color: #1d4ed8;
        }
    </style>
</head>
<body class="bg-white">

    <!-- Navbar -->
    <nav class="bg-blue-800 p-4 flex justify-between items-center text-white sticky top-0 z-50 shadow-lg">
        <div>
        <a href="index.php" class="text-white hover:text-blue-300 transition">
            <h1 class="text-2xl font-bold">
                <i class="fas fa-handshake me-2"></i>SponsMe
            </h1>
            <p class="text-blue-200 text-sm opacity-75 text-center md:text-left">Connecting Events with Sponsors</p>
        </div>
        <div class="flex space-x-8">
            <a href="#home" class="nav-link text-white text-sm sm:text-base">HOME</a>
            <a href="#about" class="nav-link text-white text-sm sm:text-base">ABOUT</a>
            <a href="#how-it-works" class="nav-link text-white text-sm sm:text-base">HOW IT WORKS</a>
            <a href="Login.php" class="nav-link text-white text-sm sm:text-base">LOGIN</a>
        </div>
    </nav>

    <!-- Home Section -->
    <section id="home" class="bg-gray-100 flex flex-col lg:flex-row items-center justify-between px-6 lg:px-16 py-12">
        <!-- Text Content -->
        <div class="lg:w-1/2 text-center lg:text-left">
            <h1 class="text-4xl sm:text-5xl font-bold">We Get</h1>
            <h1 class="text-4xl sm:text-5xl font-bold">Your Sponsorship</h1>
            <h1 class="text-4xl sm:text-5xl font-bold text-green-500">Easily</h1>

            <button onclick="window.location.href='login.php'" 
                class="mt-8 bg-blue-700 text-white px-6 py-3 rounded-lg hover:bg-blue-600 text-sm sm:text-base cta-button">
                Find Sponsorships
            </button>
        </div>

        <!-- Image or Illustration -->
        <div class="lg:w-1/3 mt-8 lg:mt-0">
            <img src="../Static Assets/images/home.png" alt="Sponsorship Illustration" class="rounded-lg ">
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="bg-white">
        <div class="text-center px-4">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4 text-gray-800">About Us</h2>
            <p class="text-gray-700 text-sm sm:text-base max-w-2xl mx-auto">
                SponsMe is a platform designed to connect sponsors and organizers effortlessly. We streamline the sponsorship process, making it easier for you to find the right opportunities and partnerships.
            </p>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <i class="fas fa-users text-4xl text-blue-700 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">For Organizers</h3>
                    <p class="text-gray-700">Find the right sponsors for your events with ease.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <i class="fas fa-handshake text-4xl text-green-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">For Sponsors</h3>
                    <p class="text-gray-700">Discover events that align with your brand values.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <i class="fas fa-chart-line text-4xl text-purple-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Growth</h3>
                    <p class="text-gray-700">Grow your network and opportunities with SponsMe.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="bg-gray-100">
        <div class="text-center px-4">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4 text-gray-800">How It Works</h2>
            <p class="text-gray-700 text-sm sm:text-base max-w-2xl mx-auto">
                Simply sign up, create your profile, and start exploring sponsorship opportunities tailored to your needs. Whether you're an organizer or a sponsor, our platform is here to help you succeed.
            </p>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                    <i class="fas fa-user-plus text-4xl text-blue-700 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Sign Up</h3>
                    <p class="text-gray-700">Create your account in just a few steps.</p>
                </div>
                <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                    <i class="fas fa-search text-4xl text-green-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Explore</h3>
                    <p class="text-gray-700">Find events or sponsors that match your goals.</p>
                </div>
                <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                    <i class="fas fa-handshake text-4xl text-purple-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Connect</h3>
                    <p class="text-gray-700">Build meaningful partnerships effortlessly.</p>
                </div>
            </div>
            <button onclick="window.location.href='login.php'" class="mt-8 bg-blue-700 text-white px-6 py-3 rounded-lg hover:bg-blue-600 text-sm sm:text-base cta-button">
                Get Registered for Free
            </button>
        </div>
    </section>
  <!-- Footer Include -->
  <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>