<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magnifying Glass Effect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .magnify {
            position: relative;
            cursor: pointer;
        }

        .magnify:hover .magnify-lens {
            display: flex;
        }

        .magnify-lens {
            display: none;
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: white;
            border: 4px solid black;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: black;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        .magnify-lens:before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            background: black;
            border-radius: 50%;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
        }

        .magnify-lens:after {
            content: '';
            position: absolute;
            width: 15px;
            height: 70px;
            background: black;
            bottom: -70px;
            left: 55%;
            transform: rotate(45deg);
            border-radius: 5px;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex items-center justify-center">
    <!-- Navbar -->
    <div class="absolute top-4 left-4 text-white text-xl font-bold">
        <a href="index.php" class="hover:underline">SponsMe</a>
    </div>

    <!-- Login Form Container -->
    <div class="container mx-auto px-4 flex items-center justify-center">
        <div class="bg-white shadow-lg rounded-lg p-8 space-y-6 max-w-sm w-full">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Select Your Role</h2>

            <div class="magnify">
                <a href="./Event Sponsor/RegSpons.php"
                    class="block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 text-center">
                    Sponsors
                </a>
                <div class="magnify-lens">Sponsors</div>
            </div>

            <div class="magnify">
                <a href="./Event Organizer/RegOrg.php"
                    class="block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-300 text-center">
                    Organizers
                </a>
                <div class="magnify-lens">Organizers</div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
