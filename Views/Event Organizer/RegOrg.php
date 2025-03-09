<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex items-center justify-center p-4">
    <div class="absolute top-4 left-4 text-black text-xl font-bold">
        <a href='../index.php' class='text-black hover:text'>SponsMe</a>
    </div>
   
    <div style="background-color: #1F509A;" class="rounded-lg shadow-lg p-6 w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center text-white mb-6">Register Profile</h2>

        <form id="registerForm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <input type="text" name="crew_name" placeholder="Crew name" class="form-control" required>
                <input type="text" name="leader_nic" placeholder="Crew Leader NIC Number" class="form-control" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <input type="text" name="first_name" placeholder="Enter first name" class="form-control" required>
                <input type="text" name="last_name" placeholder="Enter last name" class="form-control" required>
            </div>
            <div class="mb-4">
                <input type="email" name="email" placeholder="Email" class="form-control" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <input type="text" name="mobile" placeholder="Mobile No." class="form-control" required>
                <input type="text" name="whatsapp" placeholder="Whatsapp" class="form-control" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <input type="password" name="password" placeholder="Password" class="form-control" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" required>
            </div>

            <div class="text-center mb-4">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg">Sign Up</button>
            </div>
        </form>

        <p class="text-center text-gray-400">
            <span> or </span>
        </p>
        <p class="text-center text-blue-600">
            <a href="../Login.php" class="text-blue-400 hover:underline">Log In</a>
        </p>
    </div>

    <script>
        document.getElementById("registerForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);
            let password = formData.get("password");
            let confirmPassword = formData.get("confirm_password");

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match!',
                    confirmButtonText: 'Try Again'
                });
                return;
            }

            fetch("../../Controller/Organizer/RegOrgController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: data.icon,
                    title: data.title,
                    text: data.text,
                    confirmButtonText: "OK"
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                });
            })
            .catch(error => {
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
