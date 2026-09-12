<?php
// order-success.php - Order Confirmation & Receipt
$pageTitle = "Order Received";
require_once __DIR__ . '/includes/header.php';

$orderNumber = sanitize($_GET['order'] ?? '');
$db = getDB();

$order = null;
$orderItems = [];

if ($orderNumber) {
    $stmt = $db->prepare("SELECT * FROM orders WHERE order_number = ?");
    $stmt->execute([$orderNumber]);
    $order = $stmt->fetch();

    if ($order) {
        $itemStmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $itemStmt->execute([$order['id']]);
        $orderItems = $itemStmt->fetchAll();
    }
}
?>

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5 bg-dark mb-5">
        <h1 class="text-center text-white display-6">Order Confirmation</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
            <li class="breadcrumb-item active text-primary">Order Placed</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <div class="container py-5">
        <?php if (!$order): ?>
            <div class="text-center py-5">
                <i class="fas fa-exclamation-circle fa-4x text-warning mb-3"></i>
                <h2>Order Not Found</h2>
                <p class="text-muted">We could not find the requested order details.</p>
                <a href="shop.php" class="btn btn-primary rounded-pill px-4 py-2 mt-2">Go to Shop</a>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm p-4 p-md-5 max-w-800 mx-auto" style="max-width: 800px;">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-check fa-3x"></i>
                    </div>
                    <h2 class="text-success mb-1">Thank You For Your Order!</h2>
                    <p class="text-muted">Your order has been received and is being processed by our team.</p>
                    <div class="badge bg-light text-dark fs-6 py-2 px-3 border">
                        Order Number: <strong class="text-primary"><?= htmlspecialchars($order['order_number']) ?></strong>
                    </div>
                </div>

                <div class="row g-4 my-3 bg-light rounded p-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Order Date:</small>
                        <strong><?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Order Status:</small>
                        <span class="badge bg-warning text-dark text-capitalize"><?= htmlspecialchars($order['status']) ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Shipping Address:</small>
                        <strong><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></strong><br>
                        <?= htmlspecialchars($order['address']) ?>, <?= htmlspecialchars($order['city']) ?><br>
                        <?= htmlspecialchars($order['country']) ?> (<?= htmlspecialchars($order['postcode']) ?>)<br>
                        Tel: <?= htmlspecialchars($order['phone']) ?>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Payment & Shipping Method:</small>
                        <strong><?= htmlspecialchars($order['payment_method']) ?></strong><br>
                        <span class="text-muted"><?= htmlspecialchars($order['shipping_method']) ?></span>
                    </div>
                </div>

                <h4 class="mt-4 mb-3">Ordered Items</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($item['product_name']) ?></strong></td>
                                    <td class="text-center"><?= formatPrice($item['price']) ?></td>
                                    <td class="text-center"><?= $item['quantity'] ?></td>
                                    <td class="text-end fw-bold"><?= formatPrice($item['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="border-top">
                                <td colspan="3" class="text-end">Subtotal:</td>
                                <td class="text-end fw-bold"><?= formatPrice($order['subtotal']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">Shipping Fee:</td>
                                <td class="text-end"><?= formatPrice($order['shipping_cost']) ?></td>
                            </tr>
                            <tr class="table-primary border-top border-2">
                                <td colspan="3" class="text-end fs-5 fw-bold">Total Paid / Due:</td>
                                <td class="text-end fs-5 fw-bold text-primary"><?= formatPrice($order['total_amount']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="shop.php" class="btn btn-primary rounded-pill px-4 py-2">
                        ← Continue Shopping
                    </a>
                    <button onclick="window.print();" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
