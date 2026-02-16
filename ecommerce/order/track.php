<?php
include("../config/db.php");

if (!isLoggedIn()) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Order Tracking - Electro</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }
    </style>

</head>

<body class="bg-[#f6f7f8] min-h-screen">

    <!-- NAV -->
    <nav class="bg-white border-b px-6 py-4 flex justify-between">

        <div class="text-2xl font-bold text-blue-600">
            Electro
        </div>

        <a href="../index.php" class="font-semibold hover:text-blue-600">
            Home
        </a>
        <a href="profile.php" class="hidden md:block">
            <div class="h-10 w-10 shrink-0 rounded-full bg-cover bg-center bg-no-repeat" style='background-image: url("../assets/images/WhatsApp Image 2026-02-16 at 4.08.03 PM.jpeg");'></div>
        </a>

    </nav>


    <main class="max-w-7xl mx-auto px-6 py-10">

        <?php if ($order_id): ?>

            <?php

            $stmt = $conn->prepare("
SELECT *
FROM orders
WHERE id=? AND user_id=?
");

            $stmt->bind_param("ii", $order_id, $user_id);
            $stmt->execute();

            $order = $stmt->get_result()->fetch_assoc();

            ?>

            <?php if ($order): ?>


                <!-- HEADER -->

                <div class="mb-10">

                    <h1 class="text-3xl font-bold">
                        Order #<?= $order['id'] ?>
                    </h1>

                    <p class="text-gray-500">
                        Placed on <?= date("F j, Y", strtotime($order['created_at'])) ?>
                    </p>

                </div>


                <?php

                /* STATUS STEP LOGIC */

                $status = $order['status'];

                $currentStep = 0;

                if ($status == "pending" || $status == "paid")
                    $currentStep = 1;

                if ($status == "shipped")
                    $currentStep = 2;

                if ($status == "delivered")
                    $currentStep = 4;

                $steps = [
                    "Confirmed",
                    "Shipped",
                    "Out for Delivery",
                    "Delivered"
                ];

                ?>


                <!-- TIMELINE -->

                <div class="bg-white rounded-xl shadow border p-8 mb-8">

                    <div class="flex justify-between items-center relative">

                        <!-- line -->
                        <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200"></div>

                        <div class="absolute top-1/2 left-0 h-1 bg-blue-600"
                            style="width:<?= ($currentStep - 1) / (count($steps) - 1) * 100 ?>%">
                        </div>


                        <?php foreach ($steps as $index => $step): ?>

                            <?php
                            $stepNumber = $index + 1;

                            $isCompleted = $stepNumber <= $currentStep;
                            ?>

                            <div class="relative z-10 flex flex-col items-center">

                                <div class="w-12 h-12 rounded-full flex items-center justify-center
<?= $isCompleted ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-400' ?>">

                                    <?php if ($isCompleted): ?>
                                        <span class="material-icons">check</span>
                                    <?php else: ?>
                                        <span class="material-icons">schedule</span>
                                    <?php endif; ?>

                                </div>

                                <p class="text-xs font-bold mt-2
<?= $isCompleted ? 'text-blue-600' : 'text-gray-400' ?>">

                                    <?= $step ?>

                                </p>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>



                <div class="grid lg:grid-cols-3 gap-8">

                    <!-- LEFT -->

                    <div class="lg:col-span-2">

                        <div class="bg-white rounded-xl shadow border p-6">

                            <h3 class="font-bold mb-6">
                                Shipment Items
                            </h3>


                            <?php

                            $item_stmt = $conn->prepare("
SELECT oi.*,p.name,p.image
FROM order_items oi
JOIN products p ON oi.product_id=p.id
WHERE order_id=?
");

                            $item_stmt->bind_param("i", $order_id);
                            $item_stmt->execute();

                            $items = $item_stmt->get_result();

                            ?>

                            <?php while ($item = $items->fetch_assoc()): ?>

                                <div class="flex gap-4 mb-4">

                                    <img src="../assets/images/<?= $item['image'] ?>"
                                        class="w-16 h-16 rounded object-cover">

                                    <div>

                                        <div class="font-bold">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            Qty: <?= $item['quantity'] ?>
                                        </div>

                                        <div class="font-semibold">
                                            ₹<?= number_format($item['price'], 2) ?>
                                        </div>

                                    </div>

                                </div>

                            <?php endwhile; ?>


                        </div>

                    </div>


                    <!-- RIGHT -->

                    <div class="space-y-6">

                        <div class="bg-white rounded-xl shadow border p-6">

                            <h3 class="font-bold mb-4">
                                Delivery Address
                            </h3>

                            <p class="text-sm text-gray-600">
                                <?= nl2br(htmlspecialchars($order['address'])) ?>
                            </p>

                        </div>


                        <div class="bg-white rounded-xl shadow border p-6">

                            <h3 class="font-bold mb-4">
                                Order Summary
                            </h3>

                            <div class="flex justify-between">

                                <span>Status</span>

                                <span class="font-bold text-blue-600">
                                    <?= ucfirst($order['status']) ?>
                                </span>

                            </div>

                            <div class="flex justify-between text-lg font-bold mt-2">

                                <span>Total</span>

                                <span>
                                    ₹<?= number_format($order['total'], 2) ?>
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                <a href="track.php"
                    class="inline-block mt-8 bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold">

                    Back to Orders

                </a>



            <?php else: ?>

                <div class="bg-red-100 text-red-600 p-4 rounded">

                    Order not found

                </div>

            <?php endif; ?>


        <?php else: ?>



            <!-- ORDER LIST -->

            <h2 class="text-2xl font-bold mb-6">

                My Orders

            </h2>


            <?php

            $stmt = $conn->prepare("
SELECT *
FROM orders
WHERE user_id=?
ORDER BY created_at DESC
");

            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $orders = $stmt->get_result();

            ?>


            <div class="space-y-4">

                <?php while ($order = $orders->fetch_assoc()): ?>

                    <div class="bg-white rounded-xl shadow border p-6 flex justify-between">

                        <div>

                            <div class="font-bold">
                                Order #<?= $order['id'] ?>
                            </div>

                            <div class="text-gray-500">
                                <?= date("F j,Y", strtotime($order['created_at'])) ?>
                            </div>

                            <div class="text-blue-600 font-bold">
                                ₹<?= number_format($order['total'], 2) ?>
                            </div>

                        </div>


                        <a href="?order_id=<?= $order['id'] ?>"
                            class="bg-blue-600 text-white px-3 py-6 rounded-lg">

                            View

                        </a>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php endif; ?>


    </main>

</body>

</html>