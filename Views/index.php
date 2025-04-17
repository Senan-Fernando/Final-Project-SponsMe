<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - SponsMe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }

        section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link {
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #93c5fd !important;
        }

        .cta-button {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .cta-button:hover {
            transform: translateY(-5px);
            background-color: #1d4ed8;
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .animated-underline {
            position: relative;
        }

        .animated-underline::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #93c5fd;
            transition: width 0.3s ease;
        }

        .animated-underline:hover::after {
            width: 100%;
        }
    </style>
</head>

<body class="bg-white">

    <!-- Navbar -->
    <nav class="bg-blue-800 p-4 flex justify-between items-center text-white sticky top-0 z-50 shadow-lg"
        data-aos="fade-down" data-aos-duration="800">
        <div>
            <a href="index.php" class="text-white hover:text-blue-300 transition">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-handshake me-2"></i>SponsMe
                </h1>
                <p class="text-blue-200 text-sm opacity-75 text-center md:text-left">Connecting Events with Sponsors</p>
            </a>
        </div>
        <div class="flex space-x-8">
            <a href="#home" class="nav-link text-white text-sm sm:text-base animated-underline">HOME</a>
            <a href="#about" class="nav-link text-white text-sm sm:text-base animated-underline">ABOUT</a>
            <a href="#how-it-works" class="nav-link text-white text-sm sm:text-base animated-underline">HOW IT WORKS</a>

            <!-- Dynamic Login/Logout Link -->
            <?php if (isset($_SESSION['userId'])): ?>
                <?php
                $role = $_SESSION['userRole'];
                $profileLink = "#";
                if ($role === "organizer") {
                    $profileLink = "../Views/Event Organizer/Orgprof.php";
                } elseif ($role === "sponsor") {
                    $profileLink = "../Views/Event Sponsor/Sponsorprof.php";
                } elseif ($role === "sponsor,organizer") {
                    $profileLink = "../Views/Common/Dashboard.php";
                }
                ?>
                <a href="<?= $profileLink ?>" class="nav-link text-white text-sm sm:text-base animated-underline">BACK TO
                    PROFILE</a>
                <a href="../Controller/Logout.php"
                    class="nav-link text-white text-sm sm:text-base animated-underline">LOGOUT</a>
            <?php else: ?>
                <a href="Login.php" class="nav-link text-white text-sm sm:text-base animated-underline">LOGIN</a>
            <?php endif; ?>

        </div>
    </nav>


    <!-- Home Section -->
    <section id="home" class="bg-gray-100 flex flex-col lg:flex-row items-center justify-between px-6 lg:px-16 py-12">
        <div class="lg:w-1/2 text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
            <h1 class="text-4xl sm:text-5xl font-bold" data-aos="fade-up" data-aos-delay="200">We Get</h1>
            <h1 class="text-4xl sm:text-5xl font-bold" data-aos="fade-up" data-aos-delay="400">Your Sponsorship</h1>
            <h1 class="text-4xl sm:text-5xl font-bold text-green-500" data-aos="fade-up" data-aos-delay="600">Easily
            </h1>

            <button onclick="window.location.href='Login.php'"
                class="mt-8 bg-blue-700 text-white px-6 py-3 rounded-lg hover:bg-blue-600 text-sm sm:text-base cta-button"
                data-aos="zoom-in" data-aos-delay="800">
                Find Sponsorships
            </button>
        </div>

        <!-- Image or Illustration -->
        <div class="lg:w-1/3 mt-8 lg:mt-0" data-aos="fade-left" data-aos-duration="1000">
            <img src="../Static Assets/images/home.png" alt="Sponsorship Illustration"
                class="rounded-lg float-animation">
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="bg-white">
        <div class="text-center px-4">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4 text-gray-800" data-aos="fade-up">About Us</h2>
            <p class="text-gray-700 text-sm sm:text-base max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                SponsMe is a platform designed to connect sponsors and organizers effortlessly. We streamline the
                sponsorship process, making it easier for you to find the right opportunities and partnerships.
            </p>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md" data-aos="flip-left" data-aos-delay="200"
                    data-aos-duration="800">
                    <i class="fas fa-users text-4xl text-blue-700 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">For Organizers</h3>
                    <p class="text-gray-700">Find the right sponsors for your events with ease.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md" data-aos="flip-left" data-aos-delay="400"
                    data-aos-duration="800">
                    <i class="fas fa-handshake text-4xl text-green-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">For Sponsors</h3>
                    <p class="text-gray-700">Discover events that align with your brand values.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md" data-aos="flip-left" data-aos-delay="600"
                    data-aos-duration="800">
                    <i class="fas fa-chart-line text-4xl text-purple-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Growth</h3>
                    <p class="text-gray-700">Grow your network and opportunities with SponsMe.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- How It Works Section -->
    <section id="how-it-works" class="bg-gray-100">
        <div class="container mx-auto px-4 py-16">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-gray-800 text-center" data-aos="fade-up">How It Works
            </h2>

            <div class="flex flex-col lg:flex-row gap-8 items-center">
                <div class="w-full lg:w-1/2" data-aos="fade-right">
                    <div id="step-image-container" class="relative overflow-hidden rounded-lg p-6">
                        <img id="step-image" src="../Static Assets/images/carosel/1.png"
                            class="w-full h-auto mx-auto transition-all duration-500" alt="Step 1">
                    </div>
                </div>

                <div class="w-full lg:w-1/2 mt-8 lg:mt-0" data-aos="fade-left">
                    <div class="step-content pl-6 py-4">
                        <h3 class="text-2xl font-bold mb-4 text-blue-700" id="step-title">Sign Up</h3>
                        <p class="text-gray-700 mb-8 leading-relaxed" id="step-description">Create your account in just
                            a few simple steps. Choose whether you're an event organizer looking for sponsors or a
                            sponsor searching for events to support.</p>

                        <div class="w-full bg-gray-200 rounded-full h-1.5 mb-6">
                            <div class="bg-blue-700 h-1.5 rounded-full transition-all duration-700" id="progress-bar"
                                style="width: 11%;"></div>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-6">
                            <div class="step-button cursor-pointer py-2 px-4 transition-all hover:text-blue-700 text-center"
                                data-step="0">
                                <p class="text-sm font-semibold">Sign Up</p>
                            </div>
                            <div class="step-button cursor-pointer py-2 px-4 transition-all hover:text-blue-700 text-center"
                                data-step="1">
                                <p class="text-sm font-semibold text-gray-500">Choose the role</p>
                            </div>
                            <div class="step-button cursor-pointer py-2 px-4 transition-all hover:text-blue-700 text-center"
                                data-step="2">
                                <p class="text-sm font-semibold text-gray-500">Sponsor Profile</p>
                            </div>
                            <div class="step-button cursor-pointer py-2 px-4 transition-all hover:text-blue-700 text-center"
                                data-step="3">
                                <p class="text-sm font-semibold text-gray-500">Sponorship document</p>
                            </div>
                            <div class="step-button cursor-pointer py-2 px-4 transition-all hover:text-blue-700 text-center"
                                data-step="4">
                                <p class="text-sm font-semibold text-gray-500">Organizer Profile</p>
                            </div>
                            <div class="step-button cursor-pointer py-2 px-4 transition-all hover:text-blue-700 text-center"
                                data-step="5">
                                <p class="text-sm font-semibold text-gray-500">Event details</p>
                            </div>
                            <div class="step-button cursor-pointer py-2 px-4 transition-all hover:text-blue-700 text-center"
                                data-step="6">
                                <p class="text-sm font-semibold text-gray-500">Sponsorship request form</p>
                            </div>
                            <div class="step-button cursor-pointer py-2 px-4 transition-all hover:text-blue-700 text-center"
                                data-step="7">
                                <p class="text-sm font-semibold text-gray-500">Accepted sponsorships</p>
                            </div>
                            <div class="step-button cursor-pointer py-2 px-4 transition-all hover:text-blue-700 text-center"
                                data-step="8">
                                <p class="text-sm font-semibold text-gray-500">Reports for organizer</p>
                            </div>
                        </div>

                        <!-- Navigation Controls -->
                        <div class="flex justify-between mt-6">
                            <button id="prev-step"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md transition-colors duration-300 flex items-center border border-gray-300">
                                <i class="fas fa-chevron-left mr-2"></i> Previous
                            </button>
                            <button id="next-step"
                                class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md transition-colors duration-300 flex items-center">
                                Next <i class="fas fa-chevron-right ml-2"></i>
                            </button>
                        </div>

                        <button onclick="window.location.href='Login.php'"
                            class="mt-8 bg-blue-700 text-white w-full px-6 py-3 rounded-lg hover:bg-blue-600 text-sm sm:text-base cta-button">
                            Get Registered for Free
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Include -->
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
</body>
<script>
    AOS.init({
        duration: 1000,
        once: false,
        mirror: true
    });

    document.addEventListener('DOMContentLoaded', function () {
        const steps = [{
            title: "Sign Up",
            description: "If you don't have an account create your account in just a few simple steps. and login to your account. ",
            icon: "fas fa-user-plus"
        },
        {
            title: "Choose the role",
            description: "Choose whether you're an event organizer looking for sponsors or a Choose whether you're an event organizer looking for sponsors.",
            icon: "fas fa-id-card"
        },
        {
            title: "Sponsor Profile",
            description: "In the side bar you can have a look about the budget you have to sponsor and the sponsor requests that you have recieved.",
            icon: "fas fa-search"
        },
        {
            title: "Sponsorship document",
            description: "This form is to fill and adjust the relavent document that the company gives to the organizer as a proof for the sponsorship..",
            icon: "fas fa-puzzle-piece"
        },
        {
            title: "Organizer Profile",
            description: "By using the sponsorship tab in the side bar organizer can seek sponsorships and see the accepted sponsorships.",
            icon: "fas fa-comments"
        },
        {
            title: "Event deatils",
            description: "Fill the form and click on the find matching sponsors button to get match with sponsors for your event.",
            icon: "fas fa-handshake"
        },
        {
            title: "Sponsorship request form",
            description: "Fill the needed amount for the event and add a note if you need and click on the send request button.",
            icon: "fas fa-tasks"
        },
        {
            title: "Accepted sponsorships",
            description: "This page shows the accepted sponsorships and by clicking on the view document button you can view the relavent document that the sponsor sent.",
            icon: "fas fa-check-circle"
        },
        {
            title: "Reports for organizer",
            description: "By using the report tab in the side bar you can have a look of the histroty of your requested sponsorships and the budget that you have recieved for your events.",
            icon: "fas fa-chart-line"
        }
        ];

        const stepButtons = document.querySelectorAll('.step-button');
        const stepImage = document.getElementById('step-image');
        const stepTitle = document.getElementById('step-title');
        const stepDescription = document.getElementById('step-description');
        const progressBar = document.getElementById('progress-bar');
        const prevButton = document.getElementById('prev-step');
        const nextButton = document.getElementById('next-step');

        let currentIndex = 0;
        const totalSteps = steps.length;

        // Function to update the displayed step
        function updateStep(index) {
            // Update progress bar
            const progressPercentage = ((index + 1) / totalSteps) * 100;
            progressBar.style.width = `${progressPercentage}%`;

            // Fade out image then change it
            stepImage.style.opacity = '0';
            stepImage.style.transform = 'scale(0.95)';

            setTimeout(() => {
                // Update image
                stepImage.src = `../Static Assets/images/carosel/${index + 1}.png`;
                stepImage.alt = `Step ${index + 1}`;

                // Add animation to image
                stepImage.style.opacity = '1';
                stepImage.style.transform = 'scale(1)';

                // Update content with animation
                stepTitle.textContent = steps[index].title;
                stepTitle.classList.add('animate-fade-in');

                stepDescription.textContent = steps[index].description;
                stepDescription.classList.add('animate-fade-in');

                // Update buttons styling
                stepButtons.forEach((button, i) => {
                    const text = button.querySelector('p');

                    if (i === index) {
                        text.className = 'text-sm font-semibold text-blue-700';
                        button.classList.add('active', 'border-b-2', 'border-blue-700');
                    } else {
                        text.className = 'text-sm font-semibold text-gray-500';
                        button.classList.remove('active', 'border-b-2', 'border-blue-700');
                    }
                });

                // Update current index
                currentIndex = index;

                // Update navigation buttons
                prevButton.disabled = index === 0;
                prevButton.style.opacity = index === 0 ? '0.5' : '1';
                nextButton.disabled = index === totalSteps - 1;
                nextButton.textContent = index === totalSteps - 1 ? 'Steps Completed' : 'Next';
                nextButton.innerHTML = index === totalSteps - 1 ?
                    'Steps Completed' :
                    'Next <i class="fas fa-chevron-right ml-2"></i>';



                // Remove animation classes after animation completes
                setTimeout(() => {
                    stepTitle.classList.remove('animate-fade-in');
                    stepDescription.classList.remove('animate-fade-in');
                }, 500);
            }, 300);
        }

        // Initialize with first step
        updateStep(0);

        // Event listeners for step buttons
        stepButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                updateStep(index);
            });
        });

        // Event listeners for navigation buttons
        prevButton.addEventListener('click', () => {
            if (currentIndex > 0) {
                updateStep(currentIndex - 1);
            }
        });

        nextButton.addEventListener('click', () => {
            if (currentIndex < totalSteps - 1) {
                updateStep(currentIndex + 1);
            } else {
                // On the last step, redirect to login
                window.location.href = 'login.php';
            }
        });

        // Auto advance every 8 seconds
        let autoAdvanceInterval = setInterval(() => {
            const nextIndex = (currentIndex + 1) % totalSteps;
            updateStep(nextIndex);
        }, 8000);

        // Clear interval when user interacts
        const clearAutoAdvance = () => {
            clearInterval(autoAdvanceInterval);
            // Restart after 30 seconds of inactivity
            setTimeout(() => {
                autoAdvanceInterval = setInterval(() => {
                    const nextIndex = (currentIndex + 1) % totalSteps;
                    updateStep(nextIndex);
                }, 8000);
            }, 30000);
        };

        // Add event listeners to clear auto-advance
        stepButtons.forEach(button => {
            button.addEventListener('click', clearAutoAdvance);
        });
        prevButton.addEventListener('click', clearAutoAdvance);
        nextButton.addEventListener('click', clearAutoAdvance);
    });

    // Refresh AOS animations when scrolling
    window.addEventListener('scroll', function () {
        AOS.refresh();
    });

    // Add custom animation classes
    document.head.insertAdjacentHTML('beforeend', `
        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .animate-fade-in {
                animation: fadeIn 0.5s ease forwards;
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            
            .pulse-on-hover:hover {
                animation: pulse 1s infinite;
            }
        </style>
    `);

    // Add pulse animation to all buttons
    document.querySelectorAll('button').forEach(button => {
        button.classList.add('pulse-on-hover');
    });
</script>

</html>
