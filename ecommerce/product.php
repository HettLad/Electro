<?php
include("config/db.php");

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: index.php");
    exit();
}

/* HANDLE REVIEW SUBMISSION */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rating'])) {

    if (isLoggedIn()) {

        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $user_id = $_SESSION['user_id'];

        $insert_stmt = $conn->prepare("
        INSERT INTO reviews (product_id, user_id, rating, comment)
        VALUES (?, ?, ?, ?)");

        $insert_stmt->bind_param("iiis", $id, $user_id, $rating, $comment);
        $insert_stmt->execute();

        header("Location: product.php?id=" . $id);
        exit();
    }
}

/* FETCH REVIEWS */
$reviews_stmt = $conn->prepare("
SELECT r.*, u.name
FROM reviews r
JOIN users u ON r.user_id=u.id
WHERE product_id=?
ORDER BY created_at DESC
");

$reviews_stmt->bind_param("i", $id);
$reviews_stmt->execute();
$reviews = $reviews_stmt->get_result();

/* FETCH AVERAGE RATING */
$avg_stmt = $conn->prepare("
SELECT AVG(rating) as avg_rating,
COUNT(*) as total_reviews
FROM reviews
WHERE product_id=?");

$avg_stmt->bind_param("i", $id);
$avg_stmt->execute();

$avg_data = $avg_stmt->get_result()->fetch_assoc();
$avg_rating = round($avg_data['avg_rating']);
$total_reviews = $avg_data['total_reviews'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?= htmlspecialchars($product['name']) ?> - Electro</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

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
    <header class="sticky top-0 bg-white border-b z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <a href="index.php" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white">🛍️</div>
                <span class="text-2xl font-bold">Electro</span>
            </a>

            <a href="cart/view.php" class="font-semibold">Cart</a>

        </div>
    </header>

    <!-- MAIN -->
    <main class="max-w-7xl mx-auto px-6 py-10">

        <!-- Breadcrumb -->
        <div class="text-sm text-slate-500 mb-6">
            <a href="index.php" class="hover:underline">Home</a>
            <span class="mx-2">/</span>
            <span><?= htmlspecialchars($product['name']) ?></span>
        </div>
        <a href="profile.php" class="hidden md:block">
            <div class="h-10 w-10 shrink-0 rounded-full bg-cover bg-center bg-no-repeat" style='background-image: url("./assets/images/WhatsApp Image 2026-02-16 at 4.08.03 PM.jpeg");'></div>
        </a>

        <div class="grid lg:grid-cols-2 gap-12">

            <!-- IMAGE -->
            <div>

                <div class="bg-white rounded-3xl p-6 shadow" style="height: 400px; width: 400px;">

                    <img id="mainImage"
                        src="assets/images/<?= htmlspecialchars($product['image']) ?>"
                        class="w-full rounded-2xl" style="height: 400px; width: 400px;">

                </div>

                <!-- Thumbnails -->
                <!-- <div class="flex gap-3 mt-4">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <img
                            src="/assets/images/<?= htmlspecialchars($product['image']) ?>"
                            onclick="changeImage(this.src)"
                            class="w-20 h-20 object-cover rounded-xl cursor-pointer border">
                    <?php endfor; ?>
                </div> -->

            </div>

            <!-- INFO -->
            <div>

                <h1 class="text-3xl font-bold mb-3">
                    <?= htmlspecialchars($product['name']) ?>
                </h1>

                <!-- Rating -->
                <div class="flex items-center gap-2 mb-4">

                    <div class="text-amber-400 text-lg">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= $avg_rating ? "★" : "☆" ?>
                        <?php endfor; ?>
                    </div>

                    <span class="text-slate-500 text-sm">
                        (<?= $total_reviews ?> reviews)
                    </span>

                </div>

                <!-- Price -->
                <div class="mb-4">

                    <span class="text-3xl font-bold text-blue-600">
                        ₹<?= htmlspecialchars($product['price']) ?>
                    </span>

                    <span class="line-through text-slate-400 ml-3">
                        ₹<?= htmlspecialchars($product['price'] * 1.5) ?>
                    </span>

                </div>

                <!-- Description -->
                <p class="text-slate-600 mb-6 leading-relaxed">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </p>

                <!-- Cart -->
                <div class="flex gap-4">

                    <input type="number" value="1" min="1"
                        class="w-24 rounded-xl border-slate-200">

                    <a href="cart/add.php?id=<?= $product['id'] ?>"
                        class="flex-1 bg-blue-600 text-white text-center py-3 rounded-xl font-semibold hover:brightness-110">

                        Add To Cart

                    </a>

                </div>

            </div>

        </div>

        <!-- REVIEWS -->
        <section class="mt-16 max-w-3xl">

            <h2 class="text-2xl font-bold mb-6">
                Customer Reviews
            </h2>

            <!-- Add Review -->
            <?php if (isLoggedIn()): ?>

                <form method="POST" class="bg-white p-6 rounded-2xl shadow mb-8">

                    <div class="flex gap-2 text-2xl mb-4">

                        <?php for ($i = 5; $i >= 1; $i--): ?>

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="rating"
                                    value="<?= $i ?>"
                                    required
                                    class="hidden">

                                <span class="text-amber-400">★</span>

                            </label>

                        <?php endfor; ?>

                    </div>

                    <textarea name="comment"
                        required
                        placeholder="Write review..."
                        class="w-full rounded-xl border-slate-200 mb-4"></textarea>

                    <button class="bg-blue-600 text-white px-6 py-2 rounded-xl font-semibold">
                        Submit Review
                    </button>

                </form>

            <?php endif; ?>

            <!-- Review List -->
            <div class="space-y-6">

                <?php while ($review = $reviews->fetch_assoc()): ?>

                    <div class="bg-white p-6 rounded-2xl shadow">

                        <div class="font-bold mb-1">
                            <?= htmlspecialchars($review['name']) ?>
                        </div>

                        <div class="text-amber-400 mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?= $i <= $review['rating'] ? "★" : "☆" ?>
                            <?php endfor; ?>
                        </div>

                        <p class="text-slate-600">
                            <?= nl2br(htmlspecialchars($review['comment'])) ?>
                        </p>

                    </div>

                <?php endwhile; ?>

            </div>

        </section>

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t py-10 mt-16">
        <div class="max-w-7xl mx-auto px-6 text-center text-slate-500">

            <h4 class="font-bold text-slate-800 mb-2">Electro Store</h4>
            <p>© <?= date('Y') ?> All Rights Reserved</p>

        </div>
    </footer>

    <script>
        function changeImage(src) {
            document.getElementById("mainImage").src = src;
        }
    </script>

</body>

</html>