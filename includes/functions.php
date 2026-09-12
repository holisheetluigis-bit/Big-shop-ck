<?php
// includes/functions.php - Global Helper Functions

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function getDB() {
    return Database::getConnection();
}

// -------------------------------------------------------------
// Cart Management
// -------------------------------------------------------------

function initCart() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function getCart() {
    initCart();
    return $_SESSION['cart'];
}

function addToCart($productId, $quantity = 1) {
    initCart();
    $productId = (int)$productId;
    $quantity = max(1, (int)$quantity);

    $db = getDB();
    $stmt = $db->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product) {
        return false;
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'id'       => $product['id'],
            'name'     => $product['name'],
            'price'    => (float)$product['price'],
            'image'    => $product['image'],
            'category' => $product['category_name'],
            'quantity' => $quantity
        ];
    }
    return true;
}

function updateCart($productId, $quantity) {
    initCart();
    $productId = (int)$productId;
    $quantity = (int)$quantity;

    if ($quantity <= 0) {
        unset($_SESSION['cart'][$productId]);
    } elseif (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
    return true;
}

function removeFromCart($productId) {
    initCart();
    $productId = (int)$productId;
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        return true;
    }
    return false;
}

function clearCart() {
    $_SESSION['cart'] = [];
}

function getCartCount() {
    initCart();
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

function getCartTotal() {
    initCart();
    $total = 0.0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// -------------------------------------------------------------
// Authentication
// -------------------------------------------------------------

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function currentUser() {
    if (!isLoggedIn()) return null;
    return [
        'id'       => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'email'    => $_SESSION['user_email'] ?? '',
        'role'     => $_SESSION['user_role'] ?? 'customer'
    ];
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ../admin/login.php');
        exit;
    }
}

// -------------------------------------------------------------
// Flash Messages & Helpers
// -------------------------------------------------------------

function setFlash($name, $message, $type = 'success') {
    $_SESSION['flash'][$name] = [
        'message' => $message,
        'type'    => $type
    ];
}

function getFlash($name) {
    if (isset($_SESSION['flash'][$name])) {
        $flash = $_SESSION['flash'][$name];
        unset($_SESSION['flash'][$name]);
        return $flash;
    }
    return null;
}

function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

function formatPrice($price) {
    return '$' . number_format((float)$price, 2);
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'item' : $text;
}
