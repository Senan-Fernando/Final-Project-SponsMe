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
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />

    <style>
        .hover-up { transition: transform 0.3s ease; }
        .hover-up:hover { transform: translateY(-5px); }
        .custom { color: black; }
        
        /* Added animation styles */
        .input-focus-effect:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0px rgba(59, 130, 246, 0.2); }
            70% { box-shadow: 0 0 0 5px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0px rgba(59, 130, 246, 0); }
        }
        
        .btn-animate {
            position: relative;
            overflow: hidden;
        }
        
        .btn-animate:after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: all 0.5s;
        }
        
        .btn-animate:hover:after {
            left: 100%;
        }
        
        /* Modal animation */
        .modal-content {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .custom {
            color: #4a6cf7;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <!-- Logo in Top-Left Corner -->
    <div class="absolute top-4 left-4" data-aos="fade-right" data-aos-duration="1000">
        <h1 class="text-2xl font-bold">
            <i class="fas fa-handshake me-2 custom"></i>SponsMe
        </h1>
        <p class="text-gray-800 text-sm opacity-75 text-center md:text-left">Connecting Events with Sponsors</p>
    </div>

    <!-- Container for Login Form and Image -->
    <div class="container mx-auto px-4 mt-20">
        <div class="row justify-content-center align-items-center">
            <!-- Login Form -->
            <div class="col-md-6 col-lg-5" data-aos="fade-right" data-aos-duration="1000">
                <div class="bg-white rounded-lg shadow-2xl p-7 hover-up">
                    <h2 class="text-3xl font-bold text-center text-gray-800 mb-8" data-aos="fade-down" data-aos-delay="200">Log In</h2>

                    <form id="loginForm">
                        <div class="mb-6" data-aos="fade-up" data-aos-delay="300">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-300 input-focus-effect"
                                placeholder="Enter your email" required>
                        </div>
                        <div class="mb-6" data-aos="fade-up" data-aos-delay="400">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-300 input-focus-effect"
                                placeholder="Enter your password" required>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300 btn-animate" data-aos="fade-up" data-aos-delay="500">
                            Log In
                        </button>

                        <div class="mt-6 text-center" data-aos="fade-up" data-aos-delay="600">
                            <a href="#" class="text-blue-600 hover:text-blue-800 transition duration-300">Forgot Password?</a>
                            <span class="text-gray-500 mx-2">|</span>
                            <a href="SignupCategory.php" class="text-red-600 hover:text-red-800 transition duration-300">Sign Up</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Image -->
            <div class="col-md-6 col-lg-5" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                <img src="../Static Assets/images/login.png" alt="Sponsorship Illustration" class="rounded-lg w-full">
            </div>
        </div>
    </div>

    <!-- Phone Number Modal -->
    <div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phoneModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="phoneForm">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Enter your phone number</label>
                            <input type="text" class="form-control input-focus-effect" id="phone" name="phone" placeholder="94711234567" required>
                            <input type="hidden" id="resetEmail" name="email">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-animate">Send OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- OTP Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">Verify OTP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="otpForm">
                        <div class="mb-3">
                            <label for="otp" class="form-label">Enter the OTP sent to your phone</label>
                            <input type="text" class="form-control input-focus-effect" id="otp" name="otp" required>
                            <input type="hidden" id="otpEmail" name="email">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-animate">Verify OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- New Password Modal -->
    <div class="modal fade" id="newPasswordModal" tabindex="-1" aria-labelledby="newPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPasswordModalLabel">Set New Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newPasswordForm">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control input-focus-effect" id="newPassword" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control input-focus-effect" id="confirmPassword" name="confirm_password" required>
                        </div>
                        <input type="hidden" id="passwordEmail" name="email">
                        <button type="submit" class="btn btn-primary w-100 btn-animate">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS animation library
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true, // whether animation should happen only once - while scrolling down
                mirror: false, // whether elements should animate out while scrolling past them
                duration: 800 // default duration
            });
        });

        let userEmail = ''; // To store email for the reset process

        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission
            let formData = new FormData(this);

            // Add button loading animation
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Logging in...';
            button.disabled = true;

            fetch("../Controller/LoginController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text()) // Read response as text
            .then(text => {
                try {
                    const data = JSON.parse(text); // Try to parse JSON
                    console.log("Server Response:", data);

                    // Reset button state
                    button.innerHTML = originalText;
                    button.disabled = false;

                    Swal.fire({
                        icon: data.icon,
                        title: data.title,
                        text: data.text,
                        showCancelButton: data.icon === 'error', // Show cancel button only on error
                        confirmButtonText: data.icon === 'error' ? 'Reset Password' : 'OK',
                        cancelButtonText: 'Try Again',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    }).then((result) => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else if (result.isConfirmed && data.icon === 'error') {
                            userEmail = document.getElementById('email').value; // Store email
                            document.getElementById('resetEmail').value = userEmail;
                            new bootstrap.Modal(document.getElementById('phoneModal')).show();
                        }
                    });
                } catch (error) {
                    console.error("Invalid JSON:", text);
                    throw new Error("Invalid JSON response from server");
                }
            })
            .catch(error => {
                // Reset button state
                button.innerHTML = originalText;
                button.disabled = false;

                console.error("Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again!',
                    confirmButtonText: 'OK'
                });
            });
        });

        // Phone Form Submission
        document.getElementById("phoneForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            
            // Add loading animation to button
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...';
            button.disabled = true;
            
            fetch("../Controller/SendOTPController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                
                Swal.fire({
                    icon: data.icon,
                    title: data.title,
                    text: data.text,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then(() => {
                    if (data.icon === 'success') {
                        document.getElementById('otpEmail').value = userEmail;
                        bootstrap.Modal.getInstance(document.getElementById('phoneModal')).hide();
                        new bootstrap.Modal(document.getElementById('otpModal')).show();
                    }
                });
            })
            .catch(error => {
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                
                console.error("Error:", error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong!' });
            });
        });

        // OTP Form Submission
        document.getElementById("otpForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            
            // Add loading animation
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Verifying...';
            button.disabled = true;
            
            fetch("../Controller/VerifyOTPController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                
                Swal.fire({
                    icon: data.icon,
                    title: data.title,
                    text: data.text,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then(() => {
                    if (data.icon === 'success') {
                        document.getElementById('passwordEmail').value = userEmail;
                        bootstrap.Modal.getInstance(document.getElementById('otpModal')).hide();
                        new bootstrap.Modal(document.getElementById('newPasswordModal')).show();
                    }
                });
            })
            .catch(error => {
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                
                console.error("Error:", error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong!' });
            });
        });

        // New Password Form Submission
        document.getElementById("newPasswordForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            
            // Add loading animation
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Resetting...';
            button.disabled = true;
            
            fetch("../Controller/ResetPasswordController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                
                Swal.fire({
                    icon: data.icon,
                    title: data.title,
                    text: data.text,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then(() => {
                    if (data.icon === 'success') {
                        bootstrap.Modal.getInstance(document.getElementById('newPasswordModal')).hide();
                    }
                });
            })
            .catch(error => {
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                
                console.error("Error:", error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong!' });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>