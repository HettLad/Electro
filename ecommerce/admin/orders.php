<?php
include("../config/db.php");

if (!isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

$result = $conn->query("
SELECT o.*, u.name 
FROM orders o
JOIN users u ON o.user_id = u.id
ORDER BY o.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Orders - Material Dashboard</title>

    <link rel="stylesheet" href="style/assets/css/index.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f4f7fe;
            font-family: Inter, sans-serif;
        }

        .sidebar {
            width: 260px;
            background: #fff;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            border-right: 1px solid #eee;
        }

        .sidebar .logo {
            font-size: 20px;
            font-weight: 700;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            padding: 14px 20px;
            color: #444;
            text-decoration: none;
        }

        .sidebar a.active {
            background: #5a67ff;
            color: #fff;
            border-radius: 8px;
            margin: 5px 10px;
        }

        .main {
            margin-left: 260px;
        }

        .header {
            background: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
            color: #666;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .btn-update {
            background: #5a67ff;
            margin-left: 8px;
        }

        .btn-update:hover {
            background: #4c51bf;
        }

        select {
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status.paid {
            background: #dbeafe;
            color: #1e40af;
        }

        .status.shipped {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status.delivered {
            background: #dcfce7;
            color: #166534;
        }

        .status.cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>

</head>

<body>


    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="logo">
            Material Dashboard
        </div>

        <a href="dashboard.php">
            <i class="fa fa-chart-line"></i> Dashboard
        </a>

        <a href="products.php">
            <i class="fa fa-box"></i> Products
        </a>

        <a href="orders.php" class="active">
            <i class="fa fa-shopping-cart"></i> Orders
        </a>

        <a href="users.php">
            <i class="fa fa-users"></i> Users
        </a>

        <a href="../auth/logout.php">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>

    </div>


    <!-- MAIN -->
    <div class="main">


        <!-- HEADER -->
        <div class="header">

            <h2>Manage Orders</h2>

        </div>


        <!-- CONTENT -->
        <div style="padding:30px;">


            <div class="card">

                <table class="table">

                    <thead>

                        <tr>

                            <th>Order ID</th>

                            <th>Customer</th>

                            <th>Total</th>

                            <th>Status</th>

                            <th>Date</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($order = $result->fetch_assoc()): ?>

                            <tr>

                                <td>#<?= $order['id'] ?></td>

                                <td><?= htmlspecialchars($order['name']) ?></td>

                                <td>₹<?= number_format($order['total'], 2) ?></td>

                                <td>

                                    <span class="status <?= strtolower($order['status']) ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>

                                </td>

                                <td><?= date("d M Y", strtotime($order['created_at'])) ?></td>

                                <td>

                                    <form action="update_order_status.php" method="POST">

                                        <input type="hidden"
                                            name="order_id"
                                            value="<?= $order['id'] ?>">

                                        <select name="status">

                                            <option value="pending"
                                                <?= $order['status'] == "pending" ? 'selected' : '' ?>>
                                                Pending
                                            </option>

                                            <option value="paid"
                                                <?= $order['status'] == "paid" ? 'selected' : '' ?>>
                                                Paid
                                            </option>

                                            <option value="shipped"
                                                <?= $order['status'] == "shipped" ? 'selected' : '' ?>>
                                                Shipped
                                            </option>

                                            <option value="delivered"
                                                <?= $order['status'] == "delivered" ? 'selected' : '' ?>>
                                                Delivered
                                            </option>

                                            <option value="cancelled"
                                                <?= $order['status'] == "cancelled" ? 'selected' : '' ?>>
                                                Cancelled
                                            </option>

                                        </select>

                                        <button type="submit"
                                            class="btn btn-update">

                                            Update

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>


        </div>


    </div>


</body>

</html>