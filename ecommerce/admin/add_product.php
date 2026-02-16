<?php
include("../config/db.php");

if (!isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];

    /* HANDLE NEW CATEGORY */

    if ($category_id == "other") {

        $new_category = trim($_POST['new_category']);

        if (!empty($new_category)) {

            $stmt = $conn->prepare("
            INSERT INTO categories (name)
            VALUES (?)
            ");

            $stmt->bind_param("s", $new_category);
            $stmt->execute();

            $category_id = $stmt->insert_id;
        }
    }

    /* IMAGE UPLOAD */

    $image = $_FILES['image']['name'];

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        "../assets/images/" . $image
    );

    /* INSERT PRODUCT */

    $stmt = $conn->prepare("
    INSERT INTO products
    (name, price, description, category_id, image)
    VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sdsis",
        $name,
        $price,
        $description,
        $category_id,
        $image
    );

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

    <title>Add Product - Material Dashboard</title>

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


        <div class="header">

            <h3>Add Product</h3>

        </div>


        <div style="padding:30px;">


            <div class="card">


                <?php if (isset($error)): ?>

                    <div class="error">
                        <?= $error ?>
                    </div>

                <?php endif; ?>


                <form method="POST" enctype="multipart/form-data">


                    <div class="form-group">

                        <label>Product Name</label>

                        <input name="name" required>

                    </div>


                    <div class="form-group">

                        <label>Price</label>

                        <input
                            type="number"
                            step="0.01"
                            name="price"
                            required>

                    </div>


                    <div class="form-group">

                        <label>Description</label>

                        <textarea name="description" rows="4"></textarea>

                    </div>


                    <div class="form-group">

                        <label>Category</label>

                        <select
                            name="category_id"
                            id="category_select"
                            required>

                            <option value="">
                                Select Category
                            </option>

                            <?php
                            $categories = $conn->query("SELECT * FROM categories");
                            while ($cat = $categories->fetch_assoc()):
                            ?>

                                <option value="<?= $cat['id'] ?>">

                                    <?= htmlspecialchars($cat['name']) ?>

                                </option>

                            <?php endwhile; ?>

                            <option value="other">

                                Other

                            </option>

                        </select>

                    </div>


                    <div class="form-group"
                        id="new_category_box"
                        style="display:none;">

                        <label>New Category Name</label>

                        <input
                            type="text"
                            name="new_category"
                            placeholder="Enter new category">

                    </div>


                    <div class="form-group">

                        <label>Product Image</label>

                        <input
                            type="file"
                            name="image"
                            required>

                    </div>


                    <button class="btn">

                        Add Product

                    </button>


                </form>


            </div>


        </div>


    </div>


    <script>
        document
            .getElementById("category_select")
            .addEventListener("change", function() {

                if (this.value == "other") {

                    document
                        .getElementById("new_category_box")
                        .style.display = "block";

                } else {

                    document
                        .getElementById("new_category_box")
                        .style.display = "none";

                }

            });
    </script>


</body>

</html>