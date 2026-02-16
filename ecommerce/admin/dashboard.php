<?php
include("../config/db.php");

if (!isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

$users = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$products = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
$orders = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
$sales = $conn->query("SELECT SUM(total) as c FROM orders WHERE status='paid' or status='shipped' or status='delivered'")->fetch_assoc()['c'];

/* FETCH MONTHLY SALES */

$sales_chart = $conn->query("
SELECT 
DATE_FORMAT(created_at, '%Y-%m') as month,
SUM(total) as total_sales
FROM orders
WHERE status IN ('paid','shipped','delivered')
GROUP BY month
ORDER BY month
");

$chart_labels = [];
$chart_values = [];

while ($row = $sales_chart->fetch_assoc()) {

    $chart_labels[] = $row['month'];
    $chart_values[] = $row['total_sales'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="style/assets/css/index.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .chart-card {
            margin-top: 20px;
        }
    </style>

</head>

<body>


    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="logo">

            Admin Dashboard

        </div>

        <a href="dashboard.php" class="active">
            <i class="fa fa-chart-line"></i> Dashboard
        </a>

        <a href="products.php">
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

            <h2>Dashboard</h2>

            <div>

                <i class="fa fa-bell"></i>

            </div>

        </div>



        <!-- CONTENT -->
        <div style="padding:30px;">


            <div class="grid">


                <div class="card">

                    <h4>Total Users</h4>

                    <h2><?= $users ?></h2>

                </div>


                <div class="card">

                    <h4>Total Products</h4>

                    <h2><?= $products ?></h2>

                </div>


                <div class="card">

                    <h4>Total Orders</h4>

                    <h2><?= $orders ?></h2>

                </div>


                <div class="card">

                    <h4>Total Sales</h4>

                    <h2>₹<?= number_format($sales, 2) ?></h2>

                </div>


            </div>



            <div class="card chart-card">

                <h4>Sales Overview</h4>

                <canvas id="chart"></canvas>

            </div>



        </div>


    </div>



    <script>
        var labels = <?= json_encode($chart_labels) ?>;
        var values = <?= json_encode($chart_values) ?>;

        new Chart(document.getElementById("chart"), {

            type: "line",

            data: {

                labels: labels,

                datasets: [{

                    label: "Monthly Sales",

                    data: values,

                    borderColor: "#5a67ff",

                    backgroundColor: "rgba(90,103,255,0.1)",

                    fill: true,

                    tension: 0.4

                }]

            },

            options: {

                responsive: true,

                plugins: {

                    legend: {

                        display: true

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true

                    }

                }

            }

        });
    </script>



</body>

</html>