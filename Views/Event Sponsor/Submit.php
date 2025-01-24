<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsorship Submition</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <div class="bg-blue-900 text-white w-full md:w-1/4 p-6">
        <h2 class="text-2xl font-bold mb-6 text-center md:text-left">SponsMe</h2>
        <nav class="flex flex-col gap-4">
            <a href="#" onclick="window.location.href='../home.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">home</a>
            <a href="#" onclick="window.location.href='Sponsorprof.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">Profile</a>
        </nav>
        <div class="mt-auto">
            <a href="login.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Sponsorship Submition</h3>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4 flex flex-col md:flex-row">
            <li class="nav-item">
                <a class="nav-link text-dark" href="Request.php">Sponsorship Requests</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="Submit.php">Submit Forms</a>
            </li>
        </ul>

        <!-- Requests Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Request Card Example -->
            <div class="bg-gray-100 rounded-lg shadow-lg p-4">
                <div class="h-32 bg-gray-300 rounded mb-4 relative">
                </div>
                <div class="flex items-center justify-center">
                    <button class="btn btn-success w-14 flex items-center justify-center" style="background-color: rgb(3, 3, 189);" onclick="acceptAction()">
                        <i class="bi bi-upload"></i>
                    </button>
                </div>
            </div>

            <!-- Repeat Cards as Needed -->
            <div class="bg-gray-100 rounded-lg shadow-lg p-4">
                <div class="h-32 bg-gray-300 rounded mb-4 relative">
                </div>
                <div class="flex items-center justify-center">
                    <button class="btn btn-success w-14 flex items-center justify-center" style="background-color: rgb(3, 3, 189);" onclick="acceptAction()">
                        <i class="bi bi-upload"></i>
                    </button>
                </div>
            </div>

            <!-- Add More Cards if Needed -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
