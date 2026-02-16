<?php
include("../config/db.php");

if (!isLoggedIn()) {
    header("Location: ../auth/login.php");
    exit();
}

$cart_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $cart_id, $user_id);
$stmt->execute();

header("Location: view.php");
exit();
?>