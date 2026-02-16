<?php
include("../config/db.php");

if (!isLoggedIn()) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
SELECT c.id, c.quantity, p.name, p.price, p.image 
FROM cart c 
JOIN products p ON c.product_id = p.id 
WHERE c.user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Shopping Cart - Electro</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

</head>

<body class="bg-slate-50 min-h-screen text-slate-900">

    <!-- HEADER -->
    <header class="bg-white border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <h1 class="text-2xl font-bold text-indigo-600">
                Electro
            </h1>

            <a href="../index.php" class="font-semibold hover:text-indigo-600">
                Continue Shopping
            </a>
            <a href="profile.php" class="hidden md:block">
                <div class="h-10 w-10 shrink-0 rounded-full bg-cover bg-center bg-no-repeat" style='background-image: url("../assets/images/WhatsApp Image 2026-02-16 at 4.08.03 PM.jpeg");'></div>
            </a>

        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-10">

        <h1 class="text-3xl font-bold mb-8">
            Shopping Cart
        </h1>

        <?php if ($result->num_rows > 0): ?>

            <div class="grid lg:grid-cols-12 gap-8">

                <!-- CART ITEMS -->
                <div class="lg:col-span-8 space-y-6">

                    <?php while ($row = $result->fetch_assoc()):

                        $item_total = $row['price'] * $row['quantity'];
                        $total += $item_total;
                    ?>

                        <div class="bg-white p-6 rounded-3xl shadow-sm border flex flex-col sm:flex-row gap-6">

                            <!-- IMAGE -->
                            <div class="w-full sm:w-32 h-32 bg-slate-100 rounded-2xl overflow-hidden">

                                <img
                                    src="../assets/images/<?= htmlspecialchars($row['image']) ?>"
                                    class="w-full h-full object-cover">

                            </div>

                            <!-- INFO -->
                            <div class="flex-grow flex flex-col justify-between">

                                <div class="flex justify-between">

                                    <div>
                                        <h3 class="font-bold text-lg">
                                            <?= htmlspecialchars($row['name']) ?>
                                        </h3>

                                        <p class="text-sm text-slate-500 mt-1">
                                            Price: ₹<?= number_format($row['price'], 2) ?>
                                        </p>
                                    </div>

                                    <a
                                        href="remove.php?id=<?= $row['id'] ?>"
                                        class="text-red-500 font-semibold text-sm">
                                        Remove
                                    </a>

                                </div>

                                <!-- QUANTITY -->
                                <div class="flex justify-between items-end mt-6">

                                    <form
                                        action="update_quantity.php"
                                        method="POST"
                                        class="flex items-center gap-3">

                                        <input
                                            type="hidden"
                                            name="cart_id"
                                            value="<?= $row['id'] ?>">

                                        <input
                                            type="number"
                                            name="quantity"
                                            value="<?= $row['quantity'] ?>"
                                            min="1"
                                            class="w-20 rounded-xl border-slate-200">

                                        <button
                                            class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                                            Update
                                        </button>

                                    </form>

                                    <div class="text-right">

                                        <div class="text-xl font-bold text-indigo-600">
                                            ₹<?= number_format($item_total, 2) ?>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                    <?php endwhile; ?>

                </div>

                <!-- SUMMARY -->
                <div class="lg:col-span-4">

                    <div class="bg-white p-8 rounded-3xl shadow border sticky top-28">

                        <h2 class="text-2xl font-bold mb-6">
                            Order Summary
                        </h2>

                        <div class="flex justify-between mb-4">

                            <span class="text-slate-500">
                                Subtotal
                            </span>

                            <span class="font-semibold">
                                ₹<?= number_format($total, 2) ?>
                            </span>

                        </div>

                        <div class="flex justify-between mb-6">

                            <span class="text-slate-500">
                                Shipping
                            </span>

                            <span class="text-green-600 font-semibold">
                                Free
                            </span>

                        </div>

                        <div class="border-t pt-4 flex justify-between text-lg font-bold">

                            <span>Total</span>

                            <span class="text-indigo-600">
                                ₹<?= number_format($total, 2) ?>
                            </span>

                        </div>

                        <a
                            href="../order/checkout.php"
                            class="block mt-8 w-full bg-indigo-600 text-white text-center py-4 rounded-2xl font-bold hover:brightness-110">

                            Proceed to Checkout

                        </a>

                    </div>

                </div>

            </div>

        <?php else: ?>

            <!-- EMPTY CART -->

            <div class="bg-white p-10 rounded-3xl shadow text-center">

                <h3 class="text-xl font-bold mb-3">
                    Your cart is empty
                </h3>

                <a
                    href="../index.php"
                    class="text-indigo-600 font-semibold">

                    Continue shopping →

                </a>

            </div>

        <?php endif; ?>

    </main>

</body>

</html>