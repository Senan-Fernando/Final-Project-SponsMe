<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Sponsorship</title>
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
            <a href="#" onclick="window.location.href='../home.php'" class="bg-blue-700 p-3 rounded text-center">home</a>
            <a href="#" onclick="window.location.href='Sponsorprof.php'" class="bg-blue-700 p-3 rounded text-center">Profile</a>
        </nav>
        <div class="mt-auto">
            <a href="login.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Accepted Sponsorship</h3>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4 flex flex-col md:flex-row">
            <li class="nav-item">
                <a class="nav-link text-dark" href="Matched.php">Matched Sponsorship</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="Accepted.php">Accepted Sponsorships</a>
            </li>
        </ul>

        <!-- Requests Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Request Card Example -->
            <div class="bg-[#1F509A] rounded-lg shadow-md hover:shadow-lg p-4 flex flex-col items-center transform hover:scale-105 transition duration-300 cursor-pointer">
                <div class="bg-blue-300 w-full h-32 rounded mb-4 flex items-center justify-center">
                    <button class="btn btn-success w-10" style="cursor: pointer;" onclick="acceptAction()">
                        <i class="bi bi-check-circle"></i>
                    </button>
                </div>
                <button class="btn btn-primary w-full">View and download</button>
            </div>

            <!-- Repeat similar cards as needed -->
            <div class="bg-[#1F509A] rounded-lg shadow-md hover:shadow-lg p-4 flex flex-col items-center transform hover:scale-105 transition duration-300 cursor-pointer">
                <div class="bg-blue-300 w-full h-32 rounded mb-4 flex items-center justify-center">
                    <button class="btn btn-success w-10" style="cursor: pointer;" onclick="acceptAction()">
                        <i class="bi bi-check-circle"></i>
                    </button>
                </div>
                <button class="btn btn-primary w-full">View and download</button>
            </div>

            <div class="bg-[#1F509A] rounded-lg shadow-md hover:shadow-lg p-4 flex flex-col items-center transform hover:scale-105 transition duration-300 cursor-pointer">
                <div class="bg-blue-300 w-full h-32 rounded mb-4 flex items-center justify-center">
                    <button class="btn btn-success w-10" style="cursor: pointer;" onclick="acceptAction()">
                        <i class="bi bi-check-circle"></i>
                    </button>
                </div>
                <button class="btn btn-primary w-full">View and download</button>
            </div>
            <div class="bg-[#1F509A] rounded-lg shadow-md hover:shadow-lg p-4 flex flex-col items-center transform hover:scale-105 transition duration-300 cursor-pointer">
                <div class="bg-blue-300 w-full h-32 rounded mb-4 flex items-center justify-center">
                    <button class="btn btn-success w-10" style="cursor: pointer;" onclick="acceptAction()">
                        <i class="bi bi-check-circle"></i>
                    </button>
                </div>
                <button class="btn btn-primary w-full">View and download</button>
            </div>
            <div class="bg-[#1F509A] rounded-lg shadow-md hover:shadow-lg p-4 flex flex-col items-center transform hover:scale-105 transition duration-300 cursor-pointer">
                <div class="bg-blue-300 w-full h-32 rounded mb-4 flex items-center justify-center">
                    <button class="btn btn-success w-10" style="cursor: pointer;" onclick="acceptAction()">
                        <i class="bi bi-check-circle"></i>
                    </button>
                </div>
                <button class="btn btn-primary w-full">View and download</button>
            </div>
            <div class="bg-[#1F509A] rounded-lg shadow-md hover:shadow-lg p-4 flex flex-col items-center transform hover:scale-105 transition duration-300 cursor-pointer">
                <div class="bg-blue-300 w-full h-32 rounded mb-4 flex items-center justify-center">
                    <button class="btn btn-success w-10" style="cursor: pointer;" onclick="acceptAction()">
                        <i class="bi bi-check-circle"></i>
                    </button>
                </div>
                <button class="btn btn-primary w-full">View and download</button>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
