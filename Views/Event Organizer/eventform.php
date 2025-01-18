<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <div class="bg-blue-900 text-white w-full md:w-1/4 p-6 flex flex-col items-center md:items-start">
        <h2 class="text-2xl font-bold mb-6">SponsMe</h2>
        <nav class="flex flex-col gap-4 w-full">
            <a href="#" onclick="window.location.href='index.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">index</a>
            <a href="#" onclick="window.location.href='Orgprof.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">Profile</a>
            <a href="#" onclick="window.location.href='eventform.php'" class="bg-blue-700 p-3 rounded text-center md:text-left">Seek Sponsorship</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex justify-center items-center p-4">
        <div class="bg-gray-100 rounded-lg shadow-lg p-6 w-full md:w-3/4">
            <h3 class="text-2xl font-bold text-center text-gray-800 mb-6">Event Form</h3>

            <!-- Event Form Container -->
            <form>
                <!-- Input Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="location" class="block text-gray-700 font-medium mb-2">Location</label>
                        <input type="text" id="location" class="form-control" placeholder="Enter Location" required>
                    </div>
                    <div>
                        <label for="eventType" class="block text-gray-700 font-medium mb-2">Event Type</label>
                        <select id="eventType" class="form-select" required>
                            <option selected disabled>Select Event Type</option>
                            <option value="1">Concert</option>
                            <option value="2">Raves</option>
                            <option value="5">Gaming</option>
                            <option value="4">Sports</option>
                            <option value="3">Charity</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="eventTopic" class="block text-gray-700 font-medium mb-2">Event Topic</label>
                        <input type="text" id="eventTopic" class="form-control" placeholder="Enter Event Topic" required>
                    </div>
                    <div>
                        <label for="sponsorshipType" class="block text-gray-700 font-medium mb-2">Sponsorship Type</label>
                        <select id="sponsorshipType" class="form-select" required>
                            <option selected disabled>Select Sponsorship Type</option>
                            <option value="1">Platinum</option>
                            <option value="2">Gold</option>
                            <option value="3">Silver</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="targetAudience" class="block text-gray-700 font-medium mb-2">Target Audience</label>
                    <input type="text" id="targetAudience" class="form-control" placeholder="Enter Target Audience" required>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" onclick="window.location.href='Matched.php'" class="btn btn-primary px-6">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
