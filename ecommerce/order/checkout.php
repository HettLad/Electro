<?php
include("../config/db.php");

if (!isLoggedIn()) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* FETCH USER INFO */
$user_stmt = $conn->prepare("
SELECT name, email
FROM users
WHERE id = ?
");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

/* FETCH LAST ORDER ADDRESS */
$address_stmt = $conn->prepare("
SELECT address
FROM orders
WHERE user_id = ?
ORDER BY id DESC
LIMIT 1
");
$address_stmt->bind_param("i", $user_id);
$address_stmt->execute();

$address_result = $address_stmt->get_result();
$last_address = "";

if ($address_result->num_rows > 0) {
    $last_address = $address_result->fetch_assoc()['address'];
}

/* FETCH CART ITEMS */
$stmt = $conn->prepare("
SELECT c.id, c.quantity, p.name, p.price, p.image
FROM cart c
JOIN products p ON c.product_id = p.id
WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: ../cart/view.php");
    exit();
}

/* CALCULATE TOTAL */
$total = 0;
$cart_items = [];

while ($row = $result->fetch_assoc()) {
    $item_total = $row['price'] * $row['quantity'];
    $total += $item_total;
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout - Electro</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet" />

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
        }
    </style>

</head>

<body class="text-slate-900">

    <!-- HEADER -->
    <header class="sticky top-0 bg-white border-b z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <a href="../index.php" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white">🛍️</div>
                <span class="text-2xl font-bold">Electro</span>
            </a>

            <a href="../cart/view.php" class="font-semibold">Cart</a>

        </div>
    </header>

    <!-- MAIN -->
    <main class="max-w-7xl mx-auto px-6 py-10">

        <!-- Breadcrumb -->
        <div class="text-sm text-slate-500 mb-8">
            <a href="../index.php" class="hover:underline">Home</a>
            <span class="mx-2">/</span>
            Checkout
        </div>
        <a href="profile.php" class="hidden md:block">
            <div class="h-10 w-10 shrink-0 rounded-full bg-cover bg-center bg-no-repeat" style='background-image: url("../assets/images/WhatsApp Image 2026-02-16 at 4.08.03 PM.jpeg");'></div>
        </a>
        <div class="grid lg:grid-cols-2 gap-10">

            <!-- BILLING -->
            <div class="bg-white rounded-3xl shadow p-8">

                <h2 class="text-xl font-bold mb-6">
                    Billing Details
                </h2>

                <label class="text-sm font-semibold">Name</label>
                <input
                    type="text"
                    value="<?= htmlspecialchars($user['name']) ?>"
                    readonly
                    class="w-full mb-4 rounded-xl border-slate-200 bg-slate-50">

                <label class="text-sm font-semibold">Email</label>
                <input
                    type="email"
                    value="<?= htmlspecialchars($user['email']) ?>"
                    readonly
                    class="w-full mb-4 rounded-xl border-slate-200 bg-slate-50">

                <label class="text-sm font-semibold">
                    Delivery Address
                </label>

                <textarea
                    id="address"
                    rows="4"
                    required
                    class="w-full rounded-xl border-slate-200"><?= htmlspecialchars($last_address) ?></textarea>

            </div>

            <!-- ORDER SUMMARY -->
            <div class="bg-white rounded-3xl shadow p-8 h-fit">

                <h2 class="text-xl font-bold mb-6">
                    Order Summary
                </h2>

                <div class="space-y-4 mb-6">

                    <?php foreach ($cart_items as $item):

                        $item_total = $item['price'] * $item['quantity'];
                    ?>

                        <div class="flex justify-between items-center">

                            <div class="flex items-center gap-3">

                                <img
                                    src="../assets/images/<?= htmlspecialchars($item['image']) ?>"
                                    class="w-14 h-14 object-cover rounded-xl">

                                <div>

                                    <div class="font-semibold text-sm">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </div>

                                    <div class="text-slate-500 text-xs">
                                        Qty: <?= $item['quantity'] ?>
                                    </div>

                                </div>

                            </div>

                            <div class="font-semibold">
                                ₹<?= number_format($item_total, 2) ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

                <hr class="mb-6">

                <div class="flex justify-between text-lg font-bold mb-6">

                    <span>Total</span>

                    <span class="text-blue-600">
                        ₹<?= number_format($total, 2) ?>
                    </span>

                </div>

                <button
                    id="pay-btn"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:brightness-110 transition">

                    Pay with Razorpay

                </button>

            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t py-10 mt-16">
        <div class="max-w-7xl mx-auto px-6 text-center text-slate-500">

            <h4 class="font-bold text-slate-800 mb-2">
                Electro Store
            </h4>

            <p>© <?= date('Y') ?> All Rights Reserved</p>

        </div>
    </footer>

    <!-- RAZORPAY SCRIPT (UNCHANGED) -->
    <script>
        document.getElementById('pay-btn').onclick = function(e) {

            var address = document.getElementById('address').value;

            if (address.trim() == "") {
                alert("Please enter address");
                return;
            }

            var options = {

                "key": "rzp_test_SGjKjwWB1Rn4sC",

                "amount": "<?= $total * 100 ?>",

                "currency": "INR",

                "name": "Electro",

                "description": "Order Payment",

                "handler": function(response) {

                    var address =
                        document.getElementById("address").value;

                    window.location.href =
                        "place_order.php?payment_id=" +
                        response.razorpay_payment_id +
                        "&amount=<?= $total ?>" +
                        "&address=" + encodeURIComponent(address);
                },

                "prefill": {
                    "name": "<?= htmlspecialchars($user['name']) ?>",
                    "email": "<?= htmlspecialchars($user['email']) ?>"
                },

                "theme": {
                    "color": "#2563EB"
                }

            };

            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
        };
    </script>

</body>

</html>