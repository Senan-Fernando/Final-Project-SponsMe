<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .hover-up {
            transition: transform 0.3s ease;
        }

        .hover-up:hover {
            transform: translateY(-5px);
        }

        .custom {
            color: black;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <!-- Logo in Top-Left Corner -->
    <div class="absolute top-4 left-4">
        <h1 class="text-2xl font-bold">
            <i class="fas fa-handshake me-2 custom"></i>SponsMe
        </h1>
        <p class="text-gray-800 text-sm opacity-75 text-center md:text-left">Connecting Events with Sponsors</p>
    </div>

    <!-- Container for Login Form and Image -->
    <div class="container mx-auto px-4 mt-20">
        <div class="row justify-content-center align-items-center">
            <!-- Login Form -->
            <div class="col-md-6 col-lg-5">
                <div class="bg-white rounded-lg shadow-2xl p-7">
                    <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Log In</h2>

                    <form id="loginForm">
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-300"
                                placeholder="Enter your email" required>
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-300"
                                placeholder="Enter your password" required>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300">
                            Log In
                        </button>

                        <div class="mt-6 text-center">
                            <a href="#" class="text-blue-600 hover:text-blue-800 transition duration-300">Forgot Password?</a>
                            <span class="text-gray-500 mx-2">|</span>
                            <a href="SignupCategory.php" class="text-red-600 hover:text-red-800 transition duration-300">Sign Up</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Image -->
            <div class="col-md-6 col-lg-5">
                <img src="../Static Assets/images/login.png" alt="Sponsorship Illustration" class="rounded-lg w-full">
            </div>
        </div>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            let formData = new FormData(this);

            fetch("../Controller/LoginController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text()) // Read response as text
            .then(text => {
                try {
                    const data = JSON.parse(text); // Try to parse JSON
                    console.log("Server Response:", data);

                    Swal.fire({
                        icon: data.icon,
                        title: data.title,
                        text: data.text
                    }).then(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    });

                } catch (error) {
                    console.error("Invalid JSON:", text);
                    throw new Error("Invalid JSON response from server");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again!',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>