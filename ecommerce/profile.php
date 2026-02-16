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
$user_stmt = $conn->prepare("
SELECT name, email, created_at
FROM users
WHERE id = ?
");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

/* =========================
   ORDER COUNT
========================= */
$count_stmt = $conn->prepare("
SELECT COUNT(*) AS total_orders
FROM orders
WHERE user_id = ?
");
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$order_count = $count_stmt->get_result()->fetch_assoc()['total_orders'];

/* =========================
   LAST ORDER
========================= */
$order_stmt = $conn->prepare("
SELECT id, total, created_at
FROM orders
WHERE user_id = ?
ORDER BY id DESC
LIMIT 1
");
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$last_order = $order_stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>User Profile - Electro</title>

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
            <a href="./index.php" class="hover:text-indigo-600">Home</a>
            <a href="./order/track.php" class="hover:text-indigo-600">Orders</a>
            <a href="./auth/logout.php" class="text-red-500">Logout</a>
        </div>
        <a href="profile.php" class="hidden md:block">
            <div class="h-10 w-10 shrink-0 rounded-full bg-cover bg-center bg-no-repeat" style='background-image: url("./assets/images/WhatsApp Image 2026-02-16 at 4.08.03 PM.jpeg");'></div>
        </a>

    </nav>

    <main class="max-w-7xl mx-auto p-8 space-y-8">

        <!-- PROFILE HEADER -->
        <div class="bg-white p-8 rounded-xl shadow flex justify-between items-center">

            <div class="flex items-center gap-6">

                <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold text-indigo-600">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>

                <div>
                    <h1 class="text-2xl font-bold">
                        <?= htmlspecialchars($user['name']) ?>
                    </h1>

                    <p class="text-slate-500">
                        <?= htmlspecialchars($user['email']) ?>
                    </p>
                </div>

            </div>

            <a href="edit_profile.php"><button class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm">
                    Edit Profile
                </button></a>

        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white p-6 rounded-xl shadow">
                <div class="text-3xl font-bold">
                    <?= $order_count ?>
                </div>
                <div class="text-sm text-slate-500">
                    Total Orders
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <div class="text-3xl font-bold">0</div>
                <div class="text-sm text-slate-500">
                    Wishlist Items
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <div class="text-3xl font-bold">0</div>
                <div class="text-sm text-slate-500">
                    Reward Points
                </div>
            </div>

        </div>

        <!-- ACCOUNT INFO -->
        <div class="bg-white rounded-xl shadow">

            <div class="p-6 border-b font-bold text-lg">
                Account Information
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="text-xs text-slate-400">Full Name</label>
                    <p class="font-medium">
                        <?= htmlspecialchars($user['name']) ?>
                    </p>
                </div>

                <div>
                    <label class="text-xs text-slate-400">Email Address</label>
                    <p class="font-medium">
                        <?= htmlspecialchars($user['email']) ?>
                    </p>
                </div>

                <div>
                    <label class="text-xs text-slate-400">Member Since</label>
                    <p class="font-medium">
                        <?= isset($user['created_at'])
                            ? date("F Y", strtotime($user['created_at']))
                            : "Recently Joined"; ?>
                    </p>
                </div>

                <div>
                    <label class="text-xs text-slate-400">Account Status</label>
                    <p class="font-medium text-green-600">
                        Active
                    </p>
                </div>

            </div>

        </div>

        <!-- LAST ORDER -->
        <?php if ($last_order): ?>

            <div class="bg-white rounded-xl shadow">

                <div class="p-6 border-b font-bold text-lg">
                    Recent Order
                </div>

                <div class="p-6 flex justify-between items-center">

                    <div>

                        <h3 class="font-bold">
                            Order #<?= $last_order['id'] ?>
                        </h3>

                        <p class="text-sm text-slate-500">
                            Placed on
                            <?= date("F j, Y", strtotime($last_order['created_at'])) ?>
                        </p>

                        <p class="font-bold mt-1">
                            ₹<?= number_format($last_order['total'], 2) ?>
                        </p>

                    </div>

                    <a
                        href="../order/track.php?order_id=<?= $last_order['id'] ?>"
                        class="border px-4 py-2 rounded-lg hover:bg-indigo-50 font-semibold">

                        Track Order

                    </a>

                </div>

            </div>

        <?php endif; ?>

    </main>

</body>

</html>