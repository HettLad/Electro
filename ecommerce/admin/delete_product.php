<?php
include("../config/db.php");

if (!isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
} else {
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    $product = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Product</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Delete Product</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <p>Are you sure you want to delete this product?</p>
        <p><strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
        <form method="POST">
            <button type="submit" class="btn">Yes, Delete</button>
            <a href="products.php">
                <button type="button" class="btn">No, Cancel</button>
            </a>
        </form>
    </div>
</body>
</html>
