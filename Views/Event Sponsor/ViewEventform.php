<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Event Request form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex">

    <!-- Sidebar -->
    <div class="bg-blue-900 text-white w-1/4 p-6">
        <h2 class="text-2xl font-bold mb-6">SponsMe</h2>
        <nav class="flex flex-col gap-4">
            <a href="#" onclick="window.location.href='index.php'"class="bg-blue-700 p-3 rounded text-center">index</a>
            <a href="#" onclick="window.location.href='Sponsorprof.php'"class="bg-blue-700 p-3 rounded text-center">Profile</a>
            <a href="#" onclick="window.location.href='Request.php'"class="bg-blue-700 p-3 rounded text-center">Sponsorship Requests</a>
        </nav>
        <div class="mt-auto">
            <a href="login.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <div class="bg-white rounded-lg shadow-lg p-6">

            <!-- Profile Details -->
            <div class="flex items-center gap-6 mb-6">
                <div class="bg-gray-300 w-24 h-24 rounded-full flex items-center justify-center">
                    <!-- Placeholder for Profile Image -->
                    <span class="text-gray-600 text-2xl">👤</span>
                </div>
                <div>
                    <h4 class="text-xl font-semibold text-gray-800">Event Topic</h4>
                </div>
                <br>
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex gap-2 items-center align-">
                        <input type="text" class="form-control" value="Description" readonly>  
                    </div>
                </div>
            </div>

            <form>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Location</label>
                        <input type="text" class="form-control" value="" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Sponsorship Type</label>
                        <input type="email" class="form-control" value="" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Target Audience</label>
                        <input type="text" class="form-control" value="" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Mobile No.</label>
                        <input type="text" class="form-control" value="" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Whatsapp</label>
                        <input type="text" class="form-control" value="" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Message</label>
                        <input type="text" class="form-control" value="" readonly>
                        <br>
                        <div class="flex justify-between gap-2">
                            <button class="bg-blue-500 text-white px-4 py-1 rounded">Send</button>
                            <div class="flex gap-2">
                                <button class="bg-green-500 text-white px-4 py-1 rounded"  onclick="window.location.href='Request.php'">Accept</button>
                                <button class="bg-red-500 text-white px-4 py-1 rounded">Decline</button>
                            </div>
                        </div>
                    </div>
                </div>
            
                
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
