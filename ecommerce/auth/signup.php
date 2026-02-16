<?php
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO users (name, email, password)
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Sign Up - Electro</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .form-input-focus:focus {
            border-color: #2463eb;
            box-shadow: 0 0 0 2px rgba(36, 99, 235, .1);
            outline: none;
        }
    </style>

</head>

<body class="bg-slate-100 min-h-screen">

    <main class="flex min-h-screen">

        <!-- LEFT BRAND PANEL -->
        <section class="hidden lg:flex lg:w-1/2 relative bg-indigo-600 text-white">

            <div class="relative z-10 flex flex-col justify-between p-12 w-full">

                <div class="flex items-center gap-2">
                    <div class="bg-white p-2 rounded-lg">
                    </div>
                    <span class="text-2xl font-bold">
                        Electro
                    </span>
                </div>

                <div class="max-w-md">

                    <h1 class="text-5xl font-bold mb-6">
                        Join the Premium Shopping Experience
                    </h1>

                    <p class="text-white/80">
                        Create your account to explore exclusive deals and seamless ordering.
                    </p>

                </div>

                <p class="text-white/60 text-sm">
                    © <?= date('Y') ?> Electro
                </p>

            </div>

        </section>

        <!-- RIGHT FORM -->
        <section class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">

            <div class="w-full max-w-md">

                <h2 class="text-3xl font-bold mb-2">
                    Create Account
                </h2>

                <p class="text-slate-500 mb-8">
                    Enter your details to get started
                </p>

                <!-- ERROR -->
                <?php if (isset($error)): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-lg">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">

                    <!-- USERNAME -->
                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            required
                            placeholder="John Doe"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 form-input-focus">

                    </div>

                    <!-- EMAIL -->
                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            required
                            placeholder="john@example.com"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 form-input-focus">

                    </div>

                    <!-- PASSWORD -->
                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 form-input-focus">

                    </div>

                    <!-- TERMS (visual only) -->
                    <div class="flex items-center gap-2 text-sm text-slate-500">

                        <input type="checkbox" class="rounded border-slate-300">

                        <span>
                            I agree to Terms & Privacy Policy
                        </span>

                    </div>

                    <!-- SUBMIT -->
                    <button
                        type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-lg transition">

                        Create Account

                    </button>

                </form>

                <!-- LOGIN LINK -->
                <p class="mt-8 text-center text-slate-500 text-sm">

                    Already have an account?

                    <a href="login.php" class="text-indigo-600 font-bold">
                        Sign In
                    </a>

                </p>

            </div>

        </section>

    </main>

</body>

</html>