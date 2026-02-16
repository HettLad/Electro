<?php
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        if ($user['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login - Electro</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center">

    <div class="flex w-full min-h-screen">

        <!-- LEFT BRAND PANEL -->
        <div class="hidden lg:flex lg:w-1/2 bg-indigo-600 text-white relative">

            <div class="p-12 flex flex-col justify-between w-full">

                <h1 class="text-3xl font-bold">
                    Electro
                </h1>

                <div>
                    <h2 class="text-5xl font-bold leading-tight mb-6">
                        Premium Shopping Experience
                    </h2>

                    <p class="text-white/80">
                        Sign in to access your orders, cart, and exclusive deals.
                    </p>
                </div>

                <p class="text-white/60 text-sm">
                    © <?= date('Y') ?> Electro
                </p>

            </div>

        </div>

        <!-- RIGHT LOGIN FORM -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">

            <div class="w-full max-w-md">

                <h2 class="text-3xl font-bold mb-2">
                    Welcome Back
                </h2>

                <p class="text-slate-500 mb-8">
                    Login to your account
                </p>

                <!-- ERROR -->
                <?php if (isset($error)): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-lg">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            required
                            placeholder="name@example.com"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <!-- BUTTON -->
                    <button
                        type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">

                        Sign In

                    </button>

                </form>

                <!-- SIGNUP -->
                <p class="mt-8 text-sm text-center text-slate-500">

                    Don’t have an account?

                    <a href="signup.php" class="text-indigo-600 font-semibold">
                        Sign Up
                    </a>

                </p>

            </div>

        </div>

    </div>

</body>

</html>