<?php
include("../config/db.php");

if (!isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

/* HANDLE ACTIONS */

if (isset($_GET['block'])) {

    $id = intval($_GET['block']);

    $stmt = $conn->prepare("UPDATE users SET status='blocked' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

if (isset($_GET['unblock'])) {

    $id = intval($_GET['unblock']);

    $stmt = $conn->prepare("UPDATE users SET status='active' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

/* FETCH USERS */

$result = $conn->query("
SELECT id,name,email,role,status,created_at 
FROM users
ORDER BY created_at DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Users - Material Dashboard</title>

    <link rel="stylesheet" href="style/assets/css/index.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-blocked {
            color: red;
            font-weight: bold;
        }

        .btn-action {
            margin-right: 5px;
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

        <a href="orders.php">
            <i class="fa fa-shopping-cart"></i> Orders
        </a>

        <a href="users.php" class="active">
            <i class="fa fa-users"></i> Users
        </a>

        <a href="../auth/logout.php">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>

    </div>


    <!-- MAIN -->
    <div class="main">

        <div class="header">

            <h3>Manage Users</h3>

        </div>


        <div style="padding:30px;">


            <div class="card">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>Email</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th>Date</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($user = $result->fetch_assoc()): ?>

                            <tr>

                                <td><?= $user['id'] ?></td>

                                <td><?= htmlspecialchars($user['name']) ?></td>

                                <td><?= htmlspecialchars($user['email']) ?></td>

                                <td><?= $user['role'] ?></td>

                                <td>

                                    <span class="status-<?= $user['status'] ?>">

                                        <?= ucfirst($user['status']) ?>

                                    </span>

                                </td>

                                <td>

                                    <?= date("d M Y", strtotime($user['created_at'])) ?>

                                </td>

                                <td>

                                    <?php if ($user['status'] == "active"): ?>

                                        <button
                                            class="btn btn-warning btn-sm btn-action"
                                            onclick="confirmAction('block',<?= $user['id'] ?>)">

                                            Block

                                        </button>

                                    <?php else: ?>

                                        <button
                                            class="btn btn-success btn-sm btn-action"
                                            onclick="confirmAction('unblock',<?= $user['id'] ?>)">

                                            Unblock

                                        </button>

                                    <?php endif; ?>

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="confirmAction('delete',<?= $user['id'] ?>)">

                                        Delete

                                    </button>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- CONFIRM MODAL -->

    <div class="modal fade" id="confirmModal">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Confirm Action</h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    Are you sure you want to continue?

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <a id="confirmBtn"
                        class="btn btn-danger">

                        Confirm

                    </a>

                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmAction(action, id) {

            var modal = new bootstrap.Modal(document.getElementById('confirmModal'));

            document.getElementById('confirmBtn').href =
                "users.php?" + action + "=" + id;

            modal.show();

        }
    </script>


</body>

</html>