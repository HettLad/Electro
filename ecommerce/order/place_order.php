<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include("../config/db.php");

if (
    !isLoggedIn() ||
    !isset($_GET['payment_id']) ||
    !isset($_GET['amount']) ||
    !isset($_GET['address'])
) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$payment_id = $_GET['payment_id'];
$total_amount = $_GET['amount'];
$address = $_GET['address'];

$conn->begin_transaction();

try {

    /* INSERT ORDER */
    $stmt = $conn->prepare("
    INSERT INTO orders (user_id, total, status, address)
    VALUES (?, ?, 'paid', ?)
    ");
    $stmt->bind_param("ids", $user_id, $total_amount, $address);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    /* ORDER ITEMS */
    $cart_stmt = $conn->prepare("
    SELECT cart.product_id, cart.quantity, products.price
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
    ");
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    $order_item_stmt = $conn->prepare("
    INSERT INTO order_items
    (order_id, product_id, quantity, price)
    VALUES (?, ?, ?, ?)
    ");

    while ($item = $cart_result->fetch_assoc()) {
        $order_item_stmt->bind_param(
            "iiid",
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        );
        $order_item_stmt->execute();
    }

    /* PAYMENT */
    $payment_stmt = $conn->prepare("
    INSERT INTO payments
    (order_id, payment_method, payment_status, transaction_id)
    VALUES (?, 'razorpay', 'success', ?)
    ");
    $payment_stmt->bind_param("is", $order_id, $payment_id);
    $payment_stmt->execute();

    /* CLEAR CART */
    $clear_stmt = $conn->prepare("
    DELETE FROM cart WHERE user_id=?
    ");
    $clear_stmt->bind_param("i", $user_id);
    $clear_stmt->execute();

    $conn->commit();
} catch (Exception $e) {

    $conn->rollback();
    die("REAL ERROR: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment Success</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Manrope', sans-serif;
            background: #f6f7f8;
        }
    </style>

</head>

<body class="flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-2xl shadow-xl p-10 text-center max-w-md">

        <div class="w-20 h-20 mx-auto mb-6 bg-green-100
rounded-full flex items-center justify-center">

            <span class="text-4xl">✔️</span>

        </div>

        <h1 class="text-2xl font-bold mb-2">
            Payment Successful
        </h1>

        <p class="text-slate-500 mb-6">
            Your order has been placed successfully.
            Transaction ID:
            <br>
            <span class="font-semibold"><?= htmlspecialchars($payment_id) ?></span>
        </p>

        <a href="track.php?order_id=<?= $order_id ?>"
            class="inline-block bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:brightness-110">

            Track Your Order

        </a>

    </div>

</body>

</html>