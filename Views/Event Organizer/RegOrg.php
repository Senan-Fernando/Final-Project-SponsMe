<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex items-center justify-center p-4">
    <div class="absolute top-4 left-4 text-white text-xl font-bold">
        <a href="index.php" class="hover:underline">SponsMe</a>
    </div>

    <!-- Registration Form Container -->
    <div class="bg-gray-100 rounded-lg shadow-lg p-6 w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Register Profile</h2>

        <!-- Input Fields -->
        <form id="registrationForm" action="../../Controller/Organizer/RegOrgController.php" method="POST" class="grid gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input name="first_name" type="text" placeholder="First Name" class="form-control" required>
                <input name="last_name" type="text" placeholder="Last Name" class="form-control" required>
            </div>
            <div>
                <input name="email" type="email" placeholder="Email" class="form-control" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input name="mobile_no" type="text" placeholder="Mobile No." class="form-control" required>
                <input name="whatsapp" type="text" placeholder="WhatsApp" class="form-control" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input id="password" name="password" type="password" placeholder="Password" class="form-control" required>
                <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm Password" class="form-control" required>
            </div>

            <!-- Password Match Message -->
            <div id="passwordMessage" class="text-sm mt-1 text-center"></div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="button" onclick="validatePasswords()" class="btn btn-primary w-full">Sign Up</button>
            </div>
        </form>

        <!-- Login Link -->
        <p class="text-center text-gray-600 mt-4">
            <span>or</span>
        </p>
        <p class="text-center text-blue-600">
            <a href="../Login.php" class="text-blue-600 hover:underline">Log In</a>
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validatePasswords(event) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;
            const passwordMessage = document.getElementById("passwordMessage");

            if (password === confirmPassword && password.length > 0) {
                passwordMessage.textContent = "Passwords are matching!";
                passwordMessage.className = "text-green-600 text-sm mt-1";
                // Allow form submission only when the user clicks "Sign Up"
                return true;
            } else {
                passwordMessage.textContent = "Passwords do not match.";
                passwordMessage.className = "text-red-600 text-sm mt-1";
                event.preventDefault(); // Prevent form submission
                return false;
            }
        }

        // Attach the event listener to the button
        document.querySelector("button[type='button']").addEventListener("click", function(event) {
            if (validatePasswords(event)) {
                document.getElementById("registrationForm").submit();
            }
        });


        // Add event listeners for real-time password validation
        document.getElementById("password").addEventListener("input", validatePasswords);
        document.getElementById("confirm_password").addEventListener("input", validatePasswords);
    </script>
</body>

</html>