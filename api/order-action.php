<?php
// api/order-action.php - Order Placement & Checkout Processing
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../checkout.php');
    exit;
}

$cart = getCart();
if (empty($cart)) {
    setFlash('checkout_error', 'Your shopping cart is currently empty.', 'danger');
    header('Location: ../cart.php');
    exit;
}

// Sanitize inputs
$firstName = sanitize($_POST['first_name'] ?? '');
$lastName  = sanitize($_POST['last_name'] ?? '');
$company   = sanitize($_POST['company'] ?? '');
$address   = sanitize($_POST['address'] ?? '');
$city      = sanitize($_POST['city'] ?? '');
$country   = sanitize($_POST['country'] ?? '');
$postcode  = sanitize($_POST['postcode'] ?? '');
$phone     = sanitize($_POST['phone'] ?? '');
$email     = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$notes     = sanitize($_POST['notes'] ?? '');

$shippingOption = $_POST['shipping_method'] ?? 'free';
$shippingCost = 0.00;
$shippingName = 'Free Shipping';
if ($shippingOption === 'flat') {
    $shippingCost = 15.00;
    $shippingName = 'Flat rate ($15.00)';
} elseif ($shippingOption === 'pickup') {
    $shippingCost = 8.00;
    $shippingName = 'Local Pickup ($8.00)';
}

$paymentMethod = sanitize($_POST['payment_method'] ?? 'Cash On Delivery');

// Validation
if (!$firstName || !$lastName || !$address || !$city || !$country || !$postcode || !$phone || !$email) {
    setFlash('checkout_error', 'Please fill in all required billing fields marked with (*).', 'danger');
    header('Location: ../checkout.php');
    exit;
}

$db = getDB();
$db->beginTransaction();

try {
    $subtotal = getCartTotal();
    $totalAmount = $subtotal + $shippingCost;
    $orderNumber = 'CK-' . strtoupper(substr(uniqid(), -6)) . '-' . rand(100, 999);
    $userId = $_SESSION['user_id'] ?? null;

    // Optional account creation on checkout
    if (!empty($_POST['create_account']) && !$userId) {
        $checkUser = $db->prepare("SELECT id FROM users WHERE email = ?");
        $checkUser->execute([$email]);
        if (!$checkUser->fetch()) {
            $defaultPassword = password_hash('Customer123!', PASSWORD_BCRYPT);
            $username = explode('@', $email)[0] . rand(10, 99);
            $userStmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
            $userStmt->execute([$username, $email, $defaultPassword]);
            $userId = $db->lastInsertId();
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'customer';
        }
    }

    // Insert order
    $orderStmt = $db->prepare("
        INSERT INTO orders (
            user_id, order_number, first_name, last_name, company, address, city, country, postcode,
            phone, email, notes, shipping_method, shipping_cost, payment_method, subtotal, total_amount, status
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending'
        )
    ");

    $orderStmt->execute([
        $userId, $orderNumber, $firstName, $lastName, $company, $address, $city, $country, $postcode,
        $phone, $email, $notes, $shippingName, $shippingCost, $paymentMethod, $subtotal, $totalAmount
    ]);

    $orderId = $db->lastInsertId();

    // Insert order items
    $itemStmt = $db->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, price, quantity, total)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($cart as $item) {
        $itemTotal = $item['price'] * $item['quantity'];
        $itemStmt->execute([
            $orderId,
            $item['id'],
            $item['name'],
            $item['price'],
            $item['quantity'],
            $itemTotal
        ]);

        // Optional: reduce product stock
        $stockStmt = $db->prepare("UPDATE products SET stock = GREATEST(0, stock - ?) WHERE id = ?");
        $stockStmt->execute([$item['quantity'], $item['id']]);
    }

    $db->commit();

    // Clear cart after successful placement
    clearCart();

    // Redirect to success page
    header("Location: ../order-success.php?order=" . urlencode($orderNumber));
    exit;

} catch (Exception $e) {
    $db->rollBack();
    setFlash('checkout_error', 'Order failed to process: ' . htmlspecialchars($e->getMessage()), 'danger');
    header('Location: ../checkout.php');
    exit;
}
