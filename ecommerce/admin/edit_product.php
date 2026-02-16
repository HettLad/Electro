<?php
include("../config/db.php");

if (!isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

$categories = $conn->query("SELECT * FROM categories");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];

    $image = $_FILES['image']['name'];

    if ($image) {

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../assets/images/" . $image
        );

        $stmt = $conn->prepare("
        UPDATE products
        SET name=?, price=?, description=?, category_id=?, image=?
        WHERE id=?
        ");

        $stmt->bind_param(
            "sdsisi",
            $name,
            $price,
            $description,
            $category_id,
            $image,
            $id
        );
    } else {

        $stmt = $conn->prepare("
        UPDATE products
        SET name=?, price=?, description=?, category_id=?
        WHERE id=?
        ");

        $stmt->bind_param(
            "sdsii",
            $name,
            $price,
            $description,
            $category_id,
            $id
        );
    }

    if ($stmt->execute()) {

        header("Location: products.php");
        exit();
    } else {

        $error = $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Edit Product - Dashboard</title>

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
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            max-width: 700px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {

            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .btn {
            background: #5a67ff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn:hover {
            background: #4c51bf;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .product-img {
            width: 120px;
            margin-top: 10px;
            border-radius: 8px;
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

            <h3>Edit Product</h3>

        </div>


        <!-- CONTENT -->
        <div style="padding:30px;">


            <div class="card">

                <?php if (isset($error)): ?>

                    <div class="error">

                        <?= $error ?>

                    </div>

                <?php endif; ?>


                <form method="POST" enctype="multipart/form-data">


                    <div class="form-group">

                        <label>Name</label>

                        <input
                            name="name"
                            value="<?= htmlspecialchars($product['name']) ?>"
                            required>

                    </div>


                    <div class="form-group">

                        <label>Price</label>

                        <input
                            name="price"
                            type="number"
                            step="0.01"
                            value="<?= $product['price'] ?>"
                            required>

                    </div>


                    <div class="form-group">

                        <label>Description</label>

                        <textarea
                            name="description"
                            rows="4"><?= htmlspecialchars($product['description']) ?></textarea>

                    </div>


                    <div class="form-group">

                        <label>Category</label>

                        <select name="category_id" required>

                            <?php while ($cat = $categories->fetch_assoc()): ?>

                                <option
                                    value="<?= $cat['id'] ?>"
                                    <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>

                                    <?= htmlspecialchars($cat['name']) ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Current Image</label><br>

                        <img
                            src="../assets/images/<?= $product['image'] ?>"
                            class="product-img">

                    </div>


                    <div class="form-group">

                        <label>Change Image</label>

                        <input type="file" name="image">

                    </div>


                    <button class="btn">

                        Update Product

                    </button>


                </form>


            </div>


        </div>


    </div>


</body>

</html>