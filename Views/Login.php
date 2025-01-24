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
    <div class="absolute top-4 left-4 text-white text-xl font-bold">
        <a href="index.php" class="hover:underline">SponsMe</a>
    </div>

    <!-- Login Form Container -->
    <div class="container mx-auto px-4">
        <div class="bg-gray-100 rounded-lg shadow-lg p-6 max-w-md mx-auto">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Log In</h2>

            <form id="loginForm" action="../Controller/LoginController.php" method="POST">
                <!-- Input Fields -->
                <div class="mb-3">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-full">Log In</button>

                <!-- Additional Links -->
                <p class="text-center mt-3">
                    <a href="#" class="text-decoration-none">Forget Password?</a>
                    <span> or </span>
                    <a href="SignupCategory.php" class="text-blue-600">Sign Up</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
