<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsor Profile Edit</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex justify-center items-center p-4">
    <div class="absolute top-4 left-4 text-white text-xl font-bold">
        <a href='../home.php' class='text-black hover:text'>SponsMe</a>
    </div>

    <!-- Edit Profile Form Container -->
    <div class="bg-[#1F509A] rounded-lg shadow-lg p-8 w-full max-w-2xl">
        <h3 class="text-2xl font-bold text-white mb-6 text-center">Edit Profile</h3>
        <form>
            <!-- Input Fields in Two Columns -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="comapanyname" class="block text-white font-medium mb-2">Company Name</label>
                    <input type="text" id="comapanyname" class="form-control" placeholder="" required>
                </div>
                <div>
                    <label for="unit" class="block text-white font-medium mb-2">Unit</label>
                    <input type="text" id="unit" class="form-control" placeholder="" required>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="text" class="block text-white font-medium mb-2">NIC no. of the unit incharge</label>
                    <input type="text" id="name" class="form-control" placeholder="" required>
                </div>
                <div>
                    <label for="address" class="block text-white font-medium mb-2">Address</label>
                    <input type="text" id="address" class="form-control" placeholder="" required>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="code" class="block text-white font-medium mb-2">Company registration code</label>
                    <input type="text" id="mobile" class="form-control" placeholder="" required>
                </div>
                <div>
                    <label for="sponsorEvents" class="block text-white font-medium mb-2">Sponsor Events</label>
                    <select id="sponsorEvents" class="form-select" required>
                        <option selected disabled>Select Events</option>
                        <option value="1">Concerts</option>
                        <option value="2">Charity</option>
                        <option value="3">Sports</option>
                        <option value="4">Gaming</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="email" class="block text-white font-medium mb-2">Email</label>
                    <input type="email" id="email" class="form-control" placeholder="" required>
                </div>
                <div>
                    <label for="mobile" class="block text-white font-medium mb-2">Mobile No</label>
                    <input type="text" id="mobile" class="form-control" placeholder="" required>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="Facebook" class="block text-white font-medium mb-2">Facebook</label>
                    <input type="text" id="Facebook" class="form-control" placeholder="" required>
                </div>
                <div>
                    <label for="Instagram" class="block text-white font-medium mb-2">Instagram</label>
                    <input type="text" id="Instagram" class="form-control" placeholder="" required>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="password" class="block text-white font-medium mb-2">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="" required>
                </div>
                <div>
                    <label for="confirmPassword" class="block text-white font-medium mb-2">Confirm Password</label>
                    <input type="password" id="confirmPassword" class="form-control" placeholder="" required>
                </div>
            </div>
            

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" onclick="window.location.href='Sponsorprof.php'" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg text-lg">Finished</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
