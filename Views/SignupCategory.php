<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Selection Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hover-up {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-up:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">
    <!-- Logo in Top-Left Corner -->
    <div class="absolute top-4 left-4">
    <a href="index.php" class="text-black hover:text-blue-300 transition">
        <h1 class="text-2xl font-bold">
            <i class="fas fa-handshake me-2 custom"></i>SponsMe
        </h1>
        <p class="text-gray-800 text-sm opacity-75 text-center md:text-left">Connecting Events with Sponsors</p>
    </div>

    <!-- Container for Role Selection Form and Image -->
    <div class="container mx-auto px-4 mt-20"> <!-- Added mt-20 for gap -->
        <div class="row justify-content-center align-items-center g-5"> <!-- Use Bootstrap grid system -->
            <!-- Role Selection Form -->
            <div class="col-12 col-md-6 col-lg-5"> <!-- Form takes 6 columns on medium screens, 5 on large -->
                <div class="bg-white shadow-2xl rounded-lg p-8 space-y-6">
                    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Select Your Role</h2>

                    <a href="./Event Sponsor/RegExistingCompany.php" class="hover-up block bg-blue-600 text-white px-6 py-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-center font-semibold text-lg transition duration-300">
                        Sponsors
                    </a>
                    <a href="./Event Organizer/RegOrg.php" class="hover-up block bg-green-600 text-white px-6 py-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-center font-semibold text-lg transition duration-300">
                        Organizers
                    </a>
                </div>
            </div>

            <!-- Image -->
            <div class="col-12 col-md-6 col-lg-5 d-flex justify-content-center"> <!-- Image takes 6 columns on medium screens, 5 on large -->
                <img src="../Static Assets/images/role.png" alt="Sponsorship Illustration" class="rounded-lg w-3/4"> <!-- Smaller image (75% width) -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>