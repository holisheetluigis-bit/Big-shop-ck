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
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Permanently Fixed Sidebar */
        .admin-sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            background: #212529;
            color: #cfd4da;
            z-index: 1040;
            overflow-y: auto;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .admin-sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: background 0.15s ease, color 0.15s ease;
        }
        .admin-sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(242, 139, 0, 0.25);
        }
        .admin-sidebar .nav-link.active {
            color: #ffffff;
            background: #f28b00;
            font-weight: 600;
        }
        .admin-sidebar .nav-link i {
            width: 26px;
            font-size: 1.1rem;
        }

        /* Fixed offset main content */
        .admin-main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            background-color: #f4f6f9;
            padding: 35px 45px;
        }

        /* Fixed non-moving cards */
        .card-stat {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transform: none !important;
            transition: box-shadow 0.2s ease;
        }
        .card-stat:hover {
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
            transform: none !important;
        }

        /* Mobile Responsive */
        @media (max-width: 767.98px) {
            .admin-sidebar {
                position: relative;
                width: 100%;
                height: auto;
                box-shadow: none;
            }
            .admin-main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="admin-wrapper d-flex">
    <!-- Permanently Fixed Left Sidebar -->
    <aside class="admin-sidebar">
        <div class="text-center py-3 border-bottom border-secondary mb-3">
            <a href="index.php" class="text-white text-decoration-none">
                <h4 class="fw-bold mb-0 text-primary"><i class="fas fa-shopping-bag text-white me-2"></i>Ck Admin</h4>
            </a>
            <small class="text-muted d-block mt-1">Logged in as <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></small>
        </div>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="index.php" class="nav-link <?= $currentAdminPage === 'index.php' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt me-2"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="products.php" class="nav-link <?= $currentAdminPage === 'products.php' ? 'active' : '' ?>">
                    <i class="fas fa-box-open me-2"></i> <span>Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="categories.php" class="nav-link <?= $currentAdminPage === 'categories.php' ? 'active' : '' ?>">
                    <i class="fas fa-tags me-2"></i> <span>Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="orders.php" class="nav-link <?= $currentAdminPage === 'orders.php' ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart me-2"></i> <span>Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="messages.php" class="nav-link <?= $currentAdminPage === 'messages.php' ? 'active' : '' ?> d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-envelope me-2"></i> <span>Messages</span>
                    </div>
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
    </aside>

    <!-- Main Content Area -->
    <main class="admin-main-content">
