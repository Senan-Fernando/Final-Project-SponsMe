<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsor Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex items-center justify-center p-4">
    <div class="absolute top-4 left-4 text-white text-xl font-bold">
        <a href='../index.php' class='text-black hover:text'>SponsMe</a>
    </div>

    <!-- Registration Form Container -->
    <div class="bg-[#1F509A] rounded-lg shadow-lg p-6 w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center text-white mb-6">Register Profile</h2>

        <!-- Checkbox Selection with Color Change -->
        <div class="flex justify-center space-x-6 mb-6">
            <button id="existingCompany" class="px-6 py-3 border border-white rounded-lg text-white cursor-pointer transition duration-300" onclick="window.location.href='RegExistingCompany.php'" >
                Existing Company
            </button>
            <button id="newCompany" class="px-6 py-3 border border-white rounded-lg text-white cursor-pointer transition duration-300 bg-blue-500" >
                New Company
            </button>
        </div>

        <!-- Input Fields -->
        <form id="registrationForm" action="../../Controller/Sponsor/RegSponsController.php" method="POST" class="grid gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input name="companyname" type="text" placeholder="Company Name" class="form-control" required>
                <input name="unit" type="text" placeholder="Unit" class="form-control" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input name="company_registration_code" type="text" placeholder="Company Registration Code" class="form-control" required>
                <input name="address" type="text" placeholder="Address" class="form-control" required>
            </div>
            

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input name="emp_id" type="text" placeholder="Emp ID of the unit incharge" class="form-control" required>
                <select name="sponsor_events" class="form-select" required>
                    <option selected disabled>Sponsor Events</option>
                    <option value="Concerts">Concerts</option>
                    <option value="Charity">Charity</option>
                    <option value="Sports">Sports</option>
                    <option value="Gaming">Gaming</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input name="email" type="email" placeholder="Email" class="form-control" required>
                <input name="mobile_no" type="text" placeholder="Contact No." class="form-control" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input name="facebook" type="text" placeholder="Facebook Profile Link" class="form-control">
                <input name="instagram" type="text" placeholder="Instagram Profile Link" class="form-control">
            </div>

            <!-- Password Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input id="password" name="password" type="password" placeholder="Password" class="form-control" required>
                <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm Password" class="form-control" required>
            </div>

            <!-- Password Match Message -->
            <div id="passwordMessage" class="text-center text-sm mt-2"></div>

            <!-- Submit Button -->
            <button type="button" onclick="validatePasswords()" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg">Sign Up</button>
        </form>

        <!-- Login Link -->
        <p class="text-center text-gray-400 mt-4">
            Or <a href="../Login.php" class="text-blue-400 hover:underline">Log In</a>
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    

    <script>
        document.getElementById("registrationForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            let formData = new FormData(this);

            // Validate password match before sending data
            if (formData.get("password") !== formData.get("confirm_password")) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match!',
                    confirmButtonText: 'Try Again'
                });
                return;
            }

            fetch("../../Controller/Sponsor/RegSponsController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json()) // Process JSON response
            .then(data => {
                console.log("Server Response:", data); // Log response to console
                
                Swal.fire({
                    icon: data.icon,
                    title: data.title,
                    text: data.text
                });
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
    
    <!-- JavaScript for Checkbox Selection Color -->
    <script>
        function selectCompany(type) {
            const existingButton = document.getElementById("existingCompany");
            const newButton = document.getElementById("newCompany");

            if (type === "existing") {
                existingButton.classList.add("bg-green-500", "text-white");
                existingButton.classList.remove("border-white");

                newButton.classList.remove("bg-green-500", "text-white");
                newButton.classList.add("border-white");
            } else {
                newButton.classList.add("bg-green-500", "text-white");
                newButton.classList.remove("border-white");

                existingButton.classList.remove("bg-green-500", "text-white");
                existingButton.classList.add("border-white");
            }
        }

        function validatePasswords() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const passwordMessage = document.getElementById('passwordMessage');

            if (password === confirmPassword) {
                passwordMessage.textContent = 'Passwords match!';
                passwordMessage.className = 'text-green-600';
                document.getElementById('registrationForm').submit();
            } else {
                passwordMessage.textContent = 'Passwords do not match.';
                passwordMessage.className = 'text-red-600';
            }
        }
    </script>
</body>

</html>
