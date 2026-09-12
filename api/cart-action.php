<?php
// api/cart-action.php - Cart API Handler
require_once __DIR__ . '/../includes/functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || isset($_POST['ajax']);

$response = [
    'status'     => 'error',
    'message'    => 'Invalid action',
    'cart_count' => getCartCount(),
    'cart_total' => getCartTotal()
];

switch ($action) {
    case 'add':
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = max(1, (int)($_POST['quantity'] ?? 1));
        if ($productId > 0 && addToCart($productId, $quantity)) {
            $response = [
                'status'     => 'success',
                'message'    => 'Item added to your shopping cart!',
                'cart_count' => getCartCount(),
                'cart_total' => getCartTotal()
            ];
            setFlash('cart_success', 'Item successfully added to cart!');
        } else {
            $response['message'] = 'Product not found or unavailable.';
            setFlash('cart_error', 'Product not found or unavailable.', 'danger');
        }
        break;

    case 'update':
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        updateCart($productId, $quantity);
        $response = [
            'status'     => 'success',
            'message'    => 'Cart updated successfully!',
            'cart_count' => getCartCount(),
            'cart_total' => getCartTotal()
        ];
        setFlash('cart_success', 'Cart updated successfully!');
        break;

    case 'remove':
        $productId = (int)($_POST['product_id'] ?? $_GET['product_id'] ?? 0);
        if (removeFromCart($productId)) {
            $response = [
                'status'     => 'success',
                'message'    => 'Item removed from your cart.',
                'cart_count' => getCartCount(),
                'cart_total' => getCartTotal()
            ];
            setFlash('cart_success', 'Item removed from cart.');
        } else {
            $response['message'] = 'Item could not be removed.';
        }
        break;

    case 'clear':
        clearCart();
        $response = [
            'status'     => 'success',
            'message'    => 'Cart cleared.',
            'cart_count' => 0,
            'cart_total' => 0
        ];
        setFlash('cart_success', 'Cart cleared.');
        break;

    case 'get':
        $response = [
            'status'     => 'success',
            'items'      => getCart(),
            'cart_count' => getCartCount(),
            'cart_total' => getCartTotal()
        ];
        break;
}

if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
    exit;
}

// If form submission, redirect back to referer or cart.php
$redirect = $_SERVER['HTTP_REFERER'] ?? '../cart.php';
header("Location: $redirect");
exit;
