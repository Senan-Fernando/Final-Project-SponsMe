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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .content-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        @media (max-width: 768px) {
            .form-container {
                padding: 1rem;
                margin: 0.5rem;
            }
            .form-grid {
                gap: 0.5rem;
            }
            .input-field {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="fixed top-0 left-0 m-3 z-10">
        <h1 class="text-xl font-bold sm:text-2xl">
            <i class="fas fa-handshake me-2 custom"></i>SponsMe
        </h1>
        <p class="text-gray-800 text-xs sm:text-sm opacity-75">Connecting Events with Sponsors</p>
    </div>

    <!-- Split Screen Container -->
    <div class="container mx-auto px-4 mt-2">
        <div class="row justify-content-center align-items-center">
        <!-- Left Side - Image (hidden on very small screens) -->
        <div class="md:w-1/2 flex items-center justify-center p-2 hidden sm:flex">
            <img src="../../Static Assets/images/spons.png" alt="Sponsorship" class="max-w-full max-h-full object-cover rounded-lg" />
        </div>
        
        <!-- Right Side - Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center py-2 px-3">
            <!-- Registration Form Container -->
            <div class="bg-white rounded-lg shadow-lg p-4 w-full max-w-md form-container my-4">
                <h2 class="text-xl font-bold text-center text-black mb-3">Register Profile</h2>

                <!-- Checkbox Selection -->
                <div class="flex justify-center space-x-4 mb-4">
                    <button id="existingCompany" class="px-4 py-2 text-sm border border-black rounded-lg text-black cursor-pointer transition duration-300" onclick="window.location.href='RegExistingCompany.php'">
                        Existing Company
                    </button>
                    <button id="newCompany" class="px-4 py-2 text-sm border border-white rounded-lg text-gray-200 cursor-pointer transition duration-300 bg-blue-600">
                        New Company
                    </button>
                </div>

                <!-- Input Fields -->
                <form id="registrationForm" class="grid gap-3 form-grid">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input name="companyname" type="text" placeholder="Company Name" class="form-control input-field text-sm py-2" required>
                        <input name="unit" type="text" placeholder="Unit" class="form-control input-field text-sm py-2" required>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input name="company_registration_code" type="text" placeholder="Company Registration Code" class="form-control input-field text-sm py-2" required>
                        <input name="address" type="text" placeholder="Address" class="form-control input-field text-sm py-2" required>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input name="emp_id" type="text" placeholder="Emp ID" class="form-control input-field text-sm py-2" required>
                        <select name="sponsor_events" class="form-select input-field text-sm py-2" required>
                            <option selected disabled>Sponsor Events</option>
                            <option value="Concerts">Concerts</option>
                            <option value="Charity">Charity</option>
                            <option value="Sports">Sports</option>
                            <option value="Gaming">Gaming</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input name="email" type="email" placeholder="Email" class="form-control input-field text-sm py-2" required>
                        <input name="mobile_no" type="text" placeholder="Contact No." class="form-control input-field text-sm py-2" required>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input name="facebook" type="text" placeholder="Facebook Link" class="form-control input-field text-sm py-2">
                        <input name="instagram" type="text" placeholder="Instagram Link" class="form-control input-field text-sm py-2">
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input id="password" name="password" type="password" placeholder="Password" class="form-control input-field text-sm py-2" required>
                        <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm" class="form-control input-field text-sm py-2" required>
                    </div>

                    <!-- Password Match Message -->
                    <div id="passwordMessage" class="text-center text-xs mt-1"></div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm">Sign Up</button>
                </form>

                <!-- Login Link -->
                <p class="text-center text-gray-400 mt-2 text-xs">
                    Or <a href="../Login.php" class="text-blue-400 hover:underline">Log In</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById("registrationForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            // Client-side password validation
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match!',
                    confirmButtonText: 'Try Again'
                });
                return false;
            }

            // Create FormData object
            let formData = new FormData(this);

            // Show loading state
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we process your registration.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form data via AJAX
            fetch("../../Controller/Sponsor/RegSponsController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log("Server Response:", data);
                
                if (data.status) {
                    // Success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful',
                        text: data.message,
                        confirmButtonText: 'Login Now'
                    }).then((result) => {
                        if (result.isConfirmed && data.redirect) {
                            window.location.href = data.redirect;
                        }
                    });
                } else {
                    // Error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: data.message,
                        confirmButtonText: 'Try Again'
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: 'Something went wrong. Please try again!',
                    confirmButtonText: 'OK'
                });
            });
        });

        // Real-time password validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const passwordMessage = document.getElementById('passwordMessage');

        function checkPasswords() {
            if (confirmPasswordInput.value === '') {
                passwordMessage.textContent = '';
                return;
            }
            
            if (passwordInput.value === confirmPasswordInput.value) {
                passwordMessage.textContent = 'Passwords match!';
                passwordMessage.className = 'text-center text-xs mt-1 text-green-600';
            } else {
                passwordMessage.textContent = 'Passwords do not match.';
                passwordMessage.className = 'text-center text-xs mt-1 text-red-600';
            }
        }

        passwordInput.addEventListener('keyup', checkPasswords);
        confirmPasswordInput.addEventListener('keyup', checkPasswords);
    </script>
</body>
</html>