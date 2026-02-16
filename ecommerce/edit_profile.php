<?php
include("./config/db.php");

if (!isLoggedIn()) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =========================
   FETCH USER INFO
========================= */
$stmt = $conn->prepare("
SELECT name, email
FROM users
WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* =========================
   UPDATE PROFILE
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name  = $_POST['name'];
    $email = $_POST['email'];

    $update = $conn->prepare("
    UPDATE users
    SET name = ?, email = ?
    WHERE id = ?
    ");

    $update->bind_param("ssi", $name, $email, $user_id);

    if ($update->execute()) {
        $success = "Profile updated successfully.";

        // Refresh data
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Edit Profile - Electro</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

</head>

<body class="bg-slate-100 min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <h1 class="text-2xl font-bold text-indigo-600">
            Electro
        </h1>

        <div class="flex gap-6 text-sm font-semibold">
            <a href="../index.php" class="hover:text-indigo-600">Home</a>
            <a href="profile.php" class="hover:text-indigo-600">Profile</a>
            <a href="../auth/logout.php" class="text-red-500">Logout</a>
        </div>

    </nav>

    <main class="max-w-4xl mx-auto p-8">

        <!-- CARD -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <!-- HEADER -->
            <div class="p-6 border-b flex items-center justify-between">

                <h2 class="text-xl font-bold">
                    Edit Profile
                </h2>

                <a href="profile.php"
                    class="text-sm text-indigo-600 font-semibold hover:underline">
                    ← Back to Profile
                </a>

            </div>

            <!-- BODY -->
            <div class="p-8">

                <?php if (isset($success)): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        <?= $success ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">

                    <!-- NAME -->
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="<?= htmlspecialchars($user['name']) ?>"
                            required
                            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars($user['email']) ?>"
                            required
                            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <!-- PASSWORD (OPTIONAL UI ONLY) -->
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            New Password (optional)
                        </label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Leave blank to keep current password"
                            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <!-- BUTTON -->
                    <div class="pt-4">

                        <button
                            type="submit"
                            class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">

                            Save Changes

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>

</body>

</html>