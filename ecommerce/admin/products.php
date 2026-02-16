<?php
include("../config/db.php");

if (!isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

$result = $conn->query("
SELECT p.*, c.name as category_name 
FROM products p 
LEFT JOIN categories c ON p.category_id = c.id
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products - Material Dashboard</title>

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
        }

        .btn-add {
            background: #5a67ff;
        }

        .btn-edit {
            background: #f59e0b;
        }

        .btn-delete {
            background: #ef4444;
        }

        .btn-add:hover {
            background: #4c51bf;
        }

        .btn-edit:hover {
            background: #d97706;
        }

        .btn-delete:hover {
            background: #dc2626;
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

        <a href="products.php" class="active">
            <i class="fa fa-box"></i> Products
        </a>

        <a href="orders.php">
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

            <h2>Products</h2>

            <a href="add_product.php" class="btn btn-add">
                <i class="fa fa-plus"></i> Add Product
            </a>

        </div>


        <!-- CONTENT -->
        <div style="padding:30px;">


            <div class="card">

                <table class="table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>Price</th>

                            <th>Category</th>

                            <th>Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($row = $result->fetch_assoc()): ?>

                            <tr>

                                <td><?= $row['id'] ?></td>

                                <td><?= htmlspecialchars($row['name']) ?></td>

                                <td>₹<?= number_format($row['price'], 2) ?></td>

                                <td><?= htmlspecialchars($row['category_name']) ?></td>

                                <td>

                                    <a href="edit_product.php?id=<?= $row['id'] ?>"
                                        class="btn btn-edit"
                                        style="margin-right:8px;">

                                        Edit

                                    </a>

                                    <a href="delete_product.php?id=<?= $row['id'] ?>"
                                        class="btn btn-delete"
                                        onclick="return confirm('Delete this product?')">

                                        Delete

                                    </a>

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