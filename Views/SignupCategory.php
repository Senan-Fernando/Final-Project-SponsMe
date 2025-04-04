<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Selection Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animation.css Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .hover-up {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-up:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .logo-animate:hover {
            animation: pulse 1s;
        }
        .sponsor-btn:hover {
            animation: pulse 0.5s;
        }
        .organizer-btn:hover {
            animation: pulse 0.5s;
        }
        .custom {
            color: #4a6cf7;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">
    <!-- Logo in Top-Left Corner -->
    <div class="absolute top-4 left-4 animate__animated animate__fadeInLeft">
        <a href="index.php" class="text-black hover:text-blue-300 transition logo-animate">
            <h1 class="text-2xl font-bold">
                <i class="fas fa-handshake me-2 custom"></i>SponsMe
            </h1>
            <p class="text-gray-800 text-sm opacity-75 text-center md:text-left">Connecting Events with Sponsors</p>
        </a>
    </div>
    
    <!-- Container for Role Selection Form and Image -->
    <div class="container mx-auto px-4 mt-20 animate__animated animate__fadeIn animate__delay-1s">
        <div class="row justify-content-center align-items-center g-5">
            <!-- Role Selection Form -->
            <div class="col-12 col-md-6 col-lg-5 animate__animated animate__fadeIn animate__delay-1s">
                <div class="bg-white shadow-2xl rounded-lg p-8 space-y-6">
                    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6 animate__animated animate__fadeInDown animate__delay-1s">Select Your Role</h2>
                    <a href="./Event Sponsor/RegExistingCompany.php" class="hover-up block bg-blue-600 text-white px-6 py-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-center font-semibold text-lg transition duration-300 animate__animated animate__fadeInUp animate__delay-1s sponsor-btn">
                        Sponsors
                    </a>
                    <a href="./Event Organizer/RegOrg.php" class="hover-up block bg-green-600 text-white px-6 py-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-center font-semibold text-lg transition duration-300 animate__animated animate__fadeInUp animate__delay-2s organizer-btn">
                        Organizers
                    </a>
                </div>
            </div>
            
            <!-- Image -->
            <div class="col-12 col-md-6 col-lg-5 d-flex justify-content-center animate__animated animate__fadeInRight animate__delay-1s">
                <img src="../Static Assets/images/role.png" alt="Sponsorship Illustration" class="rounded-lg w-3/4 animate__animated animate__pulse animate__infinite animate__slow">
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // JavaScript to trigger animations when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation classes to elements when they come into view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__animated', 'animate__fadeIn');
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            // Buttons hover animation
            const buttons = document.querySelectorAll('.hover-up');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.classList.add('animate__animated', 'animate__pulse');
                });
                
                button.addEventListener('mouseleave', function() {
                    this.classList.remove('animate__animated', 'animate__pulse');
                });
            });
        });
    </script>
</body>
</html>