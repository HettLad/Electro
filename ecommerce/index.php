<?php
include("config/db.php");

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT * FROM products WHERE name LIKE ?";
$params = ["%$search%"];

if ($category != "") {
    $sql .= " AND category_id = ?";
    $params[] = $category;
}

$stmt = $conn->prepare($sql);
$types = str_repeat('s', count($params));
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Electro Store</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <!-- Fonts + Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
        }
    </style>
</head>

<body class="text-slate-900">

    <!-- HEADER -->
    <header class="sticky top-0 z-50 bg-white border-b">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between gap-6">

            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold">
                    🛍️
                </div>
                <span class="text-2xl font-bold">Electro</span>
            </div>

            <!-- Search -->
            <form class="flex-1 max-w-xl">
                <input
                    type="text"
                    name="search"
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Search products..."
                    class="w-full px-4 py-3 bg-slate-100 rounded-xl focus:ring-2 focus:ring-blue-600 border-0">
            </form>

            <!-- User / Cart -->
            <div class="flex items-center gap-6 text-sm">

                <a href="order/track.php" class="font-semibold">Orders</a>

                <a href="cart/view.php" class="relative font-semibold">
                    Cart
                </a>

                <?php if (isLoggedIn()): ?>
                    <span><?= $_SESSION['username'] ?></span>
                    <a href="auth/logout.php" class="text-red-500">Logout</a>
                <?php else: ?>
                    <a href="auth/login.php">Login</a>
                    <a href="auth/signup.php">Signup</a>
                <?php endif; ?>

            </div>
            <a href="profile.php" class="hidden md:block">
                <div class="h-10 w-10 shrink-0 rounded-full bg-cover bg-center bg-no-repeat" style='background-image: url("./assets/images/WhatsApp Image 2026-02-16 at 4.08.03 PM.jpeg");'></div>
            </a>

        </div>
    </header>

    <!-- CATEGORY NAV -->
    <nav class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-6 h-14 flex items-center gap-3 overflow-x-auto">

            <a href="index.php"
                class="px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap
<?= $category == '' ? 'bg-blue-600 text-white' : 'bg-slate-100' ?>">
                All Categories
            </a>

            <?php while ($cat = $categories->fetch_assoc()): ?>

                <a href="?category=<?= $cat['id'] ?>"
                    class="px-5 py-2 rounded-full text-sm whitespace-nowrap
<?= $category == $cat['id'] ? 'bg-blue-600 text-white' : 'bg-slate-100' ?>">

                    <?= htmlspecialchars($cat['name']) ?>

                </a>

            <?php endwhile; ?>

        </div>
    </nav>

    <!-- MAIN -->
    <main class="max-w-7xl mx-auto px-6 py-10">

        <h1 class="text-2xl font-bold mb-8">
            Products
        </h1>

        <!-- PRODUCTS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php while ($row = $result->fetch_assoc()): ?>

                <div class="bg-white rounded-3xl p-4 shadow hover:shadow-xl transition">

                    <!-- Image -->
                    <div class="aspect-square bg-slate-100 rounded-2xl overflow-hidden mb-4">
                        <img src="assets/images/<?= htmlspecialchars($row['image']) ?>"
                            class="w-full h-full object-cover">
                    </div>

                    <!-- Name -->
                    <h3 class="font-bold text-lg mb-1 line-clamp-1">
                        <?= htmlspecialchars($row['name']) ?>
                    </h3>

                    <!-- Price -->
                    <p class="text-blue-600 font-bold text-xl mb-4">
                        ₹<?= htmlspecialchars($row['price']) ?>
                    </p>

                    <!-- Buttons -->
                    <div class="flex gap-3">

                        <a href="product.php?id=<?= $row['id'] ?>"
                            class="flex-1 text-center py-2 rounded-xl bg-slate-100 font-semibold hover:bg-slate-200">
                            View
                        </a>

                        <a href="cart/add.php?id=<?= $row['id'] ?>"
                            class="flex-1 text-center py-2 rounded-xl bg-blue-600 text-white font-semibold hover:brightness-110">
                            Add
                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t py-10 mt-12">
        <div class="max-w-7xl mx-auto px-6 text-center text-slate-500">

            <h4 class="font-bold text-slate-800 mb-2">Electro Store</h4>

            <p>© <?= date('Y') ?> All Rights Reserved</p>

        </div>
    </footer>

</body>

</html>