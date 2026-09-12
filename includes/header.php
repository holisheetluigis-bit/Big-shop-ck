<?php
// includes/header.php - Global Header & Navigation
require_once __DIR__ . '/functions.php';

$db = getDB();

// Fetch categories with product counts
$catStmt = $db->query("
    SELECT c.*, COUNT(p.id) AS product_count 
    FROM categories c 
    LEFT JOIN products p ON c.id = p.category_id 
    GROUP BY c.id 
    ORDER BY c.name ASC
");
$headerCategories = $catStmt->fetchAll();

$currentPage = basename($_SERVER['PHP_SELF']);
$cartCount = getCartCount();
$cartTotal = getCartTotal();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?>Ck Shop168 - Electronics Store</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="electronics, computer, mobile, laptop, smart tv, gadgets" name="keywords">
    <meta content="Ck Shop168 - Your modern online electronics store" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background-color: #f92400;
            color: #fff;
            border-radius: 50%;
            padding: 2px 7px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .toast-notification {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 999999;
            min-width: 280px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
    </style>
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    <div class="container-fluid px-5 d-none border-bottom d-lg-block">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-4 text-center text-lg-start mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a href="contact.php" class="text-muted me-2"> Help</a><small> / </small>
                    <a href="contact.php" class="text-muted mx-2"> Support</a><small> / </small>
                    <a href="contact.php" class="text-muted ms-2"> Contact</a>
                </div>
            </div>
            <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
                <small class="text-dark">Call Us:</small>
                <a href="tel:0765040211" class="text-muted ms-1">0765040211</a>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle text-muted ms-2" data-bs-toggle="dropdown"><small><i class="fa fa-user me-2"></i> 
                            <?= $user ? htmlspecialchars($user['username']) : 'My Account' ?>
                        </small></a>
                        <div class="dropdown-menu rounded">
                            <?php if ($user): ?>
                                <span class="dropdown-item-text text-muted small">Signed in as <strong><?= htmlspecialchars($user['username']) ?></strong></span>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <a href="admin/index.php" class="dropdown-item text-primary"><i class="fa fa-tachometer-alt me-1"></i> Admin Panel</a>
                                <?php endif; ?>
                                <div class="dropdown-divider"></div>
                                <a href="cart.php" class="dropdown-item"><i class="fa fa-shopping-cart me-1"></i> My Cart</a>
                                <a href="checkout.php" class="dropdown-item"><i class="fa fa-credit-card me-1"></i> Checkout</a>
                                <div class="dropdown-divider"></div>
                                <a href="api/auth-action.php?action=logout" class="dropdown-item text-danger"><i class="fa fa-sign-out-alt me-1"></i> Log Out</a>
                            <?php else: ?>
                                <a href="login.php" class="dropdown-item"><i class="fa fa-sign-in-alt me-1"></i> Login</a>
                                <a href="register.php" class="dropdown-item"><i class="fa fa-user-plus me-1"></i> Register</a>
                                <div class="dropdown-divider"></div>
                                <a href="admin/login.php" class="dropdown-item text-muted"><i class="fa fa-lock me-1"></i> Admin Login</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header & Search Bar Start -->
    <div class="container-fluid px-5 py-4 d-none d-lg-block">
        <div class="row gx-0 align-items-center text-center">
            <div class="col-md-4 col-lg-3 text-center text-lg-start">
                <div class="d-inline-flex align-items-center">
                    <a href="index.php" class="navbar-brand p-0">
                        <h1 class="display-5 text-primary m-0"><i class="fas fa-shopping-bag text-secondary me-2"></i>Ck Shop168</h1>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-lg-6 text-center">
                <div class="position-relative ps-4">
                    <form action="shop.php" method="GET" class="d-flex border rounded-pill">
                        <input class="form-control border-0 rounded-pill w-100 py-3 ps-4" type="text" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" placeholder="Search looking for?">
                        <select name="category" class="form-select text-dark border-0 border-start rounded-0 p-3" style="width: 220px;">
                            <option value="">All Categories</option>
                            <?php foreach ($headerCategories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary rounded-pill py-3 px-5" style="border: 0;"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 text-center text-lg-end">
                <div class="d-inline-flex align-items-center">
                    <a href="shop.php" class="text-muted d-flex align-items-center justify-content-center me-3" title="Browse Products"><span class="rounded-circle btn-md-square border"><i class="fas fa-th-large"></i></span></a>
                    <a href="cart.php" class="text-muted d-flex align-items-center justify-content-center position-relative">
                        <span class="rounded-circle btn-md-square border"><i class="fas fa-shopping-cart"></i></span>
                        <span class="cart-badge header-cart-count"><?= $cartCount ?></span>
                        <span class="text-dark ms-2 header-cart-total"><?= formatPrice($cartTotal) ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar & Categories Start -->
    <div class="container-fluid nav-bar p-0">
        <div class="row gx-0 bg-primary px-5 align-items-center">
            <div class="col-lg-3 d-none d-lg-block">
                <nav class="navbar navbar-light position-relative" style="width: 250px;">
                    <button class="navbar-toggler border-0 fs-4 w-100 px-0 text-start text-white" type="button" data-bs-toggle="collapse" data-bs-target="#allCat">
                        <h4 class="m-0 text-white"><i class="fa fa-bars me-2"></i>All Categories</h4>
                    </button>
                    <div class="collapse navbar-collapse rounded-bottom bg-white shadow-sm" id="allCat">
                        <div class="navbar-nav ms-auto py-0 w-100">
                            <ul class="list-unstyled categories-bars m-0 p-3">
                                <?php foreach ($headerCategories as $cat): ?>
                                    <li class="py-2 border-bottom">
                                        <div class="categories-bars-item d-flex justify-content-between align-items-center">
                                            <a href="shop.php?category=<?= $cat['id'] ?>" class="text-dark">
                                                <i class="fa <?= htmlspecialchars($cat['icon']) ?> me-2 text-primary"></i><?= htmlspecialchars($cat['name']) ?>
                                            </a>
                                            <span class="badge bg-light text-muted">(<?= $cat['product_count'] ?>)</span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-12 col-lg-9">
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-3 py-lg-0">
                    <a href="index.php" class="navbar-brand d-block d-lg-none">
                        <h1 class="display-5 text-white m-0"><i class="fas fa-shopping-bag text-secondary me-2"></i>Ck Shop168</h1>
                    </a>
                    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars fa-1x"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav ms-auto py-0">
                            <a href="index.php" class="nav-item nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
                            <a href="shop.php" class="nav-item nav-link <?= $currentPage === 'shop.php' ? 'active' : '' ?>">Shop</a>
                            <a href="cart.php" class="nav-item nav-link <?= $currentPage === 'cart.php' ? 'active' : '' ?>">Cart (<?= $cartCount ?>)</a>
                            <a href="checkout.php" class="nav-item nav-link <?= $currentPage === 'checkout.php' ? 'active' : '' ?>">Checkout</a>
                            <a href="contact.php" class="nav-item nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>">Contact</a>
                        </div>
                        <div class="d-none d-xl-flex flex-shrink-0 ps-4">
                            <a href="tel:0765040211" class="btn btn-light btn-lg-square rounded-circle position-relative wow tada" data-wow-delay=".9s">
                                <i class="fa fa-phone-alt fa-2x text-primary"></i>
                            </a>
                            <div class="d-flex flex-column ms-3">
                                <span class="text-white-50">24/7 Support</span>
                                <a href="tel:0765040211" class="text-white">0765040211</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->
