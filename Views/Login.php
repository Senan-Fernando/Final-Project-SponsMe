<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex items-center justify-center">
    <div class="absolute top-4 left-4 text-black text-xl font-bold">
        <a href="index.php" class="text-black hover:text">SponsMe</a>
    </div>

    <div class="w-full max-w-lg bg-[#1F509A] rounded-lg shadow-lg p-8">
        <h2 class="text-3xl font-bold text-center text-white mb-6">Log In</h2>

        <form id="loginForm">
            <div class="mb-5">
                <input type="email" name="email" id="email"
                    class="form-control border-2 border-blue-200 focus:border-blue-700 focus:outline-none rounded-lg p-3 text-gray-800 w-full"
                    placeholder="Email" required>
            </div>
            <div class="mb-5">
                <input type="password" name="password" id="password"
                    class="form-control border-2 border-blue-200 focus:border-blue-700 focus:outline-none rounded-lg p-3 text-gray-800 w-full"
                    placeholder="Password" required>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg text-lg">
                Log In
            </button>

            <p class="text-center mt-5 text-white">
                <a href="#" class="text-white hover:underline">Forget Password?</a>
                <span> or </span>
                <a href="SignupCategory.php" class="text-red-500 hover:underline">Sign Up</a>
            </p>
        </form>
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


</body>

</html>