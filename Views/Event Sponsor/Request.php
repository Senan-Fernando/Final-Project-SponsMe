<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsorship Requests</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-hover:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <div class="bg-blue-900 text-white w-full md:w-1/4 p-6">
        <h2 class="text-2xl font-bold mb-6 text-center md:text-left">SponsMe</h2>
        <nav class="flex flex-col gap-4">
            <a onclick="window.location.href='../home.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">home</a>
            <a href="#" onclick="window.location.href='Sponsorprof.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">Profile</a>
            <a onclick="window.location.href='request.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">Sponsorship Requests</a>
        </nav>
        <div class="mt-auto">
            <a href="../login.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Sponsorship Requests</h3>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4 flex flex-col md:flex-row">
            <li class="nav-item">
                <a class="nav-link active" href="Request.php">Sponsorship Requests</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="Submit.php">Submit Forms</a>
            </li>
        </ul>

        <!-- Requests Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Request Card Example -->
            <div class="bg-[#1F509A] rounded-lg shadow-lg p-4 card-hover"  onclick="window.location.href='ViewEventform.php'">
                <div class="h-32 bg-gray-300 rounded mb-4 relative">
                    <button class="btn btn-primary w-10 bg-blue-600"  onclick="downloadAction()">
                        <i class="bi bi-download"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between">
                    <button class="btn btn-success w-14" style="cursor: pointer;" onclick="acceptAction()">
                        <i class="bi bi-check-circle"></i>
                    </button>
                    <button class="btn btn-danger w-14" style="cursor: pointer;" onclick="rejectAction()">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </div>

            <!-- Repeat Cards as Needed -->
            <div class="bg-[#1F509A] rounded-lg shadow-lg p-4 card-hover" onclick="window.location.href='ViewEventform.php'">
                <div class="h-32 bg-gray-300 rounded mb-4 relative">
                    <button class="btn btn-primary w-10 bg-blue-600"  onclick="downloadAction()">
                        <i class="bi bi-download"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between">
                    <button class="btn btn-success w-14" style="cursor: pointer;" onclick="acceptAction()">
                        <i class="bi bi-check-circle"></i>
                    </button>
                    <button class="btn btn-danger w-14" style="cursor: pointer;" onclick="rejectAction()">
                        <i class="bi bi-x-circle"></i>
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
