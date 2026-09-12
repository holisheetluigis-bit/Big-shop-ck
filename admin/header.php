<?php
// admin/header.php - Shared Admin Navigation & Layout
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$db = getDB();
$unreadCount = $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
$currentAdminPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= isset($adminTitle) ? htmlspecialchars($adminTitle) . ' - ' : '' ?>Ck Shop168 Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f4f6f9;
        }
        .admin-sidebar {
            min-height: 100vh;
            background: #212529;
            color: #cfd4da;
        }
        .admin-sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 4px;
            transition: all 0.2s;
        }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            color: #ffffff;
            background: #f28b00;
        }
        .admin-sidebar .nav-link i {
            width: 22px;
        }
        .card-stat {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 admin-sidebar p-3 d-flex flex-column">
            <div class="text-center py-3 border-bottom border-secondary mb-3">
                <a href="index.php" class="text-white text-decoration-none">
                    <h4 class="fw-bold mb-0 text-primary"><i class="fas fa-shopping-bag text-white me-2"></i>Ck Admin</h4>
                </a>
                <small class="text-muted">Logged in as <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></small>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?= $currentAdminPage === 'index.php' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="products.php" class="nav-link <?= $currentAdminPage === 'products.php' ? 'active' : '' ?>">
                        <i class="fas fa-box-open me-2"></i> Products
                    </a>
                </li>
                <li>
                    <a href="categories.php" class="nav-link <?= $currentAdminPage === 'categories.php' ? 'active' : '' ?>">
                        <i class="fas fa-tags me-2"></i> Categories
                    </a>
                </li>
                <li>
                    <a href="orders.php" class="nav-link <?= $currentAdminPage === 'orders.php' ? 'active' : '' ?>">
                        <i class="fas fa-shopping-cart me-2"></i> Orders
                    </a>
                </li>
                <li>
                    <a href="messages.php" class="nav-link <?= $currentAdminPage === 'messages.php' ? 'active' : '' ?> d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-envelope me-2"></i> Messages</span>
                        <?php if ($unreadCount > 0): ?>
                            <span class="badge bg-danger rounded-pill"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
            <div class="border-top border-secondary pt-3 mt-auto">
                <a href="../index.php" target="_blank" class="btn btn-outline-light btn-sm w-100 mb-2">
                    <i class="fas fa-external-link-alt me-1"></i> View Store
                </a>
                <a href="logout.php" class="btn btn-danger btn-sm w-100">
                    <i class="fas fa-sign-out-alt me-1"></i> Log Out
                </a>
            </div>
        </div>

        <!-- Main Content Body -->
        <div class="col-md-9 col-lg-10 p-4 p-md-5">
