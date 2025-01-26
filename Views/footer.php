<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ensure the footer stays at the bottom */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1; /* Allow the main content to grow and push the footer down */
        }
    </style>
</head>

<body>

    <!-- Footer -->
    <footer class="bg-blue-800 text-white text-center py-4">
        <div class="container">
            <!-- Footer Text -->
            <p class="text-sm sm:text-base">
                © 2025 SponsMe. All rights reserved.
            </p>

            <!-- Navigation Links -->
            <div class="flex justify-center space-x-4 mt-2">
                <a href="#" class="hover:underline text-white">Privacy Policy</a>
                <a href="#" class="hover:underline text-white">Terms of Service</a>
                <a href="#" class="hover:underline text-white">Contact Us</a>
            </div>

            <!-- Social Media Links -->
            <div class="mt-4">
                <p class="text-xs sm:text-sm">Follow us:</p>
                <div class="flex justify-center space-x-3">
                    <a href="#" class="text-white hover:text-blue-300"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white hover:text-blue-300"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white hover:text-blue-300"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white hover:text-blue-300"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
