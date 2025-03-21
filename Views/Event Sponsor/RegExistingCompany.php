<?php
// Start session
session_start();
?>

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
    <!-- SweetAlert2 for better alerts -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                padding: 0.5rem;
                margin: 0.25rem;
            }
            .form-grid {
                gap: 0.25rem;
            }
            .input-field {
                padding: 0.25rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
<div class="position-absolute top-0 start-0 m-4">
        <h1 class="text-2xl font-bold">
        <i class="fas fa-handshake me-2 custom"></i>SponsMe
    </h1>
    <p class="text-gray-800 text-sm opacity-75">Connecting Events with Sponsors</p>
</div>
    <!-- Display session messages if any -->
    <?php
    if (isset($_SESSION['error'])) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{$_SESSION['error']}',
                confirmButtonColor: '#3085d6'
            });
        </script>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{$_SESSION['success']}',
                confirmButtonColor: '#3085d6'
            });
        </script>";
        unset($_SESSION['success']);
    }
    ?>
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
            <div class="bg-white rounded-lg shadow-lg p-4 w-full max-w-sm form-container">
                <h2 class="text-xl font-bold text-center text-black mb-3">Register Profile</h2>

        <!-- Company Type Selection -->
        <div class="flex justify-center space-x-4 mb-3">
            <button id="existingCompany" class="px-3 py-2 border border-black rounded-lg text-gray-200 cursor-pointer transition duration-300 bg-blue-600">
                Existing Company
            </button>
            <button id="newCompany" class="px-3 py-2 border border-black rounded-lg text-gray-800 cursor-pointer transition duration-300"onclick="window.location.href='RegNewCompany.php'">
                New Company
            </button>
        </div>

                <!-- Input Fields -->
        <form id="registrationForm" action="../../Controller/Sponsor/ExistRegSponsController.php" method="POST" class="grid gap-2">
            <!-- Company Registration Code with Fetch Button -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-center">
                <!-- Company Registration Code Input -->
                <div class="col-span-2">
                    <input id="companyRegistrationCode" name="company_registration_code" type="text"
                        placeholder="Company Registration Code"
                        class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm" required>
                </div>

                <!-- Check Company Button -->
                <div class="col-span-1 flex justify-center sm:justify-start">
                    <button type="button" onclick="fetchCompanyName()"
                        class="bg-green-600 hover:bg-green-700 text-white px-2 py-2 rounded-md text-xs">
                        Check Company
                    </button>
                </div>
            </div>

            <!-- Company Name & Unit Name Fields -->
            <input id="companyName" name="companyname" type="text" placeholder="Company Name" class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm" required readonly>
            <input id="unitname" name="unitname" type="text" placeholder="Unit Name" class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm" required>

            <!-- Address Field -->
            <div class="grid grid-cols-1">
                <input id="address" name="address" type="text" placeholder="Address" class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <input name="emp_id" type="text" placeholder="Emp ID of the unit incharge" class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm" required>
                <select name="sponsor_events" class="form-select h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm" required>
                    <option selected disabled>Sponsor Events</option>
                    <option value="Concerts">Concerts</option>
                    <option value="Charity">Charity</option>
                    <option value="Sports">Sports</option>
                    <option value="Gaming">Gaming</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <input name="email" type="email" placeholder="Email" class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm" required>
                <input name="mobile_no" type="text" placeholder="Contact No." class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <input name="facebook" type="text" placeholder="Facebook Profile Link" class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm">
                <input name="instagram" type="text" placeholder="Instagram Profile Link" class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm">
            </div>

            <!-- Password Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <input id="password" name="password" type="password" placeholder="Password" class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm" required>
                <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm Password" class="form-control h-9 px-2 py-1 border border-gray-300 rounded-md w-full text-sm" required>
            </div>

            <!-- Password Match Message -->
            <div id="passwordMessage" class="text-center text-xs mt-1"></div>

            <!-- Submit Button -->
            <button type="button" onclick="validatePasswords()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm">Sign Up</button>
        </form>

                <!-- Login Link -->
                <p class="text-center text-gray-400 mt-2 ">
                    Or <a href="../Login.php" class="text-blue-400 hover:underline">Log In</a>
                </p>
            </div>
        </div>
    </div>
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript -->
    <script>
        function fetchCompanyName() {
            const registrationCode = document.getElementById("companyRegistrationCode").value;
            
            if (!registrationCode) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Input Required',
                    text: 'Please enter a Company Registration Code.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            
            // Show loading indicator
            Swal.fire({
                title: 'Checking...',
                text: 'Looking up company information',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Fetch from the backend - Make sure this points to the correct controller endpoint
            fetch(`../../Controller/Sponsor/ExistRegSponsController.php?action=fetchCompany&code=${registrationCode}`)
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    
                    if (data.status === 'success') {
                        // Fill in the company details
                        document.getElementById("companyName").value = data.company_name;
                        document.getElementById("unitname").value = data.unit || '';
                        document.getElementById("address").value = data.address || '';
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Company Found',
                            text: 'Company details have been loaded.',
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        // Company not found
                        document.getElementById("companyName").value = '';
                        document.getElementById("unitname").value = '';
                        document.getElementById("address").value = '';
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Company Not Found',
                            text: data.message || 'No company found with that registration code.',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'There was a problem connecting to the server. Please try again.',
                        confirmButtonColor: '#3085d6'
                    });
                });
        }

        function validatePasswords() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const passwordMessage = document.getElementById('passwordMessage');

            if (password === confirmPassword) {
                // Check if company name is filled (meaning a valid company was found)
                const companyName = document.getElementById('companyName').value;
                if (!companyName) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Company Not Verified',
                        text: 'Please verify a company by clicking the "Check Company" button first.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                passwordMessage.textContent = 'Passwords match!';
                passwordMessage.className = 'text-green-600';
                document.getElementById('registrationForm').submit();
            } else {
                passwordMessage.textContent = 'Passwords do not match.';
                passwordMessage.className = 'text-red-600';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'The passwords you entered do not match. Please try again.',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    </script>
</body>

</html>