<?php
include("../config/db.php");

if (!isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

// Add a new coupon
if (isset($_POST['add_coupon'])) {
    $code = $_POST['code'];
    $discount = $_POST['discount'];
    $stmt = $conn->prepare("INSERT INTO coupons (code, discount) VALUES (?, ?)");
    $stmt->bind_param("sd", $code, $discount);
    $stmt->execute();
}

// Delete a coupon
if (isset($_GET['delete_coupon'])) {
    $id = $_GET['delete_coupon'];
    $stmt = $conn->prepare("DELETE FROM coupons WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

$coupons = $conn->query("SELECT * FROM coupons");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Coupons</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Manage Coupons</h2>
        <form method="POST">
            <input type="text" name="code" placeholder="Coupon Code" required>
            <input type="number" name="discount" step="0.01" placeholder="Discount (%)" required>
            <button type="submit" name="add_coupon" class="btn">Add Coupon</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($coupon = $coupons->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $coupon['id']; ?></td>
                        <td><?php echo htmlspecialchars($coupon['code']); ?></td>
                        <td><?php echo htmlspecialchars($coupon['discount']); ?>%</td>
                        <td><?php echo $coupon['is_active'] ? 'Yes' : 'No'; ?></td>
                        <td>
                            <a href="?delete_coupon=<?php echo $coupon['id']; ?>" class="btn">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
