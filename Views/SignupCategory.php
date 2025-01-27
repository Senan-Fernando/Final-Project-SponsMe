<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hover-up {
            transition: transform 0.3s ease;
        }

        .hover-up:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex items-center justify-center">
    <!-- Navbar -->
    <div class="absolute top-4 left-4 text-white text-xl font-bold">
        <a href='home.php' class='text-black hover:text'>SponsMe</a>
    </div>

    <div class="container mx-auto px-4 flex items-center justify-center">
        <div style="background-color: #001A6E;" class="shadow-lg rounded-lg p-8 space-y-6 max-w-sm w-full">
            <h2 class="text-2xl font-bold text-center text-white mb-6">Select Your Role</h2>

            <a href="./Event Sponsor/RegSpons.php" class="hover-up block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 text-center">
                Sponsors
            </a>
            <a href="./Event Organizer/RegOrg.php" class="hover-up block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-300 text-center">
                Organizers
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
