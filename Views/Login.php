<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex items-center justify-center">
    <!-- Navbar -->
    <div class="absolute top-4 left-4 text-black text-xl font-bold">
        <a href="home.php" class="hover:underline">SponsMe</a>
    </div>

    <!-- Login Form Container -->
    <div class="container mx-auto px-4">
        <div class="bg-blue-900 rounded-lg shadow-lg p-6 max-w-md mx-auto">
            <h2 class="text-2xl font-bold text-center text-white mb-6">Log In</h2>

            <form id="loginForm" action="../Controller/LoginController.php" method="POST">
                <!-- Input Fields -->
                <div class="mb-3">
                <input type="email" name="email" id="email" 
                    class="form-control border-2 border-black focus:border-blue-700 focus:outline-none rounded-lg p-3 text-gray-800 w-full" 
                    placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" id="password" 
                    class="form-control border-2 border-black focus:border-blue-700 focus:outline-none rounded-lg p-3 text-gray-800 w-full" 
                    placeholder="Password" required>
            </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg">
                    Log In
                </button>


                <!-- Additional Links -->
                <p class="text-center mt-3">
                    <a href="#" class="text-white">Forget Password? or</a>
                    <span></span>
                    <a href="SignupCategory.php" class="text-red-500">Sign Up</a>
                </p>
            </form>
        </div>
    </div>
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
