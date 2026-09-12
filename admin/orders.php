<?php
// admin/orders.php - Order Processing & Management
$adminTitle = "Orders Management";
require_once __DIR__ . '/header.php';

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = sanitize($_POST['status']);
    if (in_array($newStatus, ['pending', 'processing', 'completed', 'cancelled'])) {
        $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $orderId]);
        header("Location: orders.php?order_id=$orderId&msg=status_updated");
        exit;
    }
}

// Handle Order Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $orderId = (int)$_GET['id'];
    $stmt = $db->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    header("Location: orders.php?msg=deleted");
    exit;
}

// Specific Order Detail View
$selectedOrder = null;
$selectedOrderItems = [];
if (isset($_GET['order_id'])) {
    $orderId = (int)$_GET['order_id'];
    $stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $selectedOrder = $stmt->fetch();

    if ($selectedOrder) {
        $stmtItems = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmtItems->execute([$orderId]);
        $selectedOrderItems = $stmtItems->fetchAll();
    }
}

// Fetch all orders with status filter
$statusFilter = $_GET['status'] ?? '';
$where = "1=1";
$params = [];
if ($statusFilter && in_array($statusFilter, ['pending', 'processing', 'completed', 'cancelled'])) {
    $where = "status = ?";
    $params[] = $statusFilter;
}

$stmtOrders = $db->prepare("SELECT * FROM orders WHERE $where ORDER BY id DESC");
$stmtOrders->execute($params);
$orders = $stmtOrders->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Orders Management</h2>
        <p class="text-muted mb-0">Track customer orders, inspect items, and update fulfillment status</p>
    </div>
    <div class="btn-group">
        <a href="orders.php" class="btn btn-sm btn-outline-secondary <?= empty($statusFilter) ? 'active' : '' ?>">All</a>
        <a href="orders.php?status=pending" class="btn btn-sm btn-outline-warning <?= $statusFilter === 'pending' ? 'active' : '' ?>">Pending</a>
        <a href="orders.php?status=processing" class="btn btn-sm btn-outline-info <?= $statusFilter === 'processing' ? 'active' : '' ?>">Processing</a>
        <a href="orders.php?status=completed" class="btn btn-sm btn-outline-success <?= $statusFilter === 'completed' ? 'active' : '' ?>">Completed</a>
        <a href="orders.php?status=cancelled" class="btn btn-sm btn-outline-danger <?= $statusFilter === 'cancelled' ? 'active' : '' ?>">Cancelled</a>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Order successfully <?= htmlspecialchars($_GET['msg']) ?>!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($selectedOrder): ?>
    <!-- Detailed Order Inspector Card -->
    <div class="card border-0 shadow-sm p-4 bg-white rounded mb-5 border-start border-4 border-primary">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
                <h4 class="fw-bold mb-1">Order #<?= htmlspecialchars($selectedOrder['order_number']) ?></h4>
                <small class="text-muted">Placed on <?= date('F j, Y \a\t g:i a', strtotime($selectedOrder['created_at'])) ?></small>
            </div>
            <div>
                <form action="orders.php" method="POST" class="d-inline-flex align-items-center">
                    <input type="hidden" name="update_status" value="1">
                    <input type="hidden" name="order_id" value="<?= $selectedOrder['id'] ?>">
                    <label class="me-2 fw-bold small">Status:</label>
                    <select name="status" class="form-select form-select-sm me-2" style="width: 140px;" onchange="this.form.submit()">
                        <option value="pending" <?= $selectedOrder['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $selectedOrder['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="completed" <?= $selectedOrder['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $selectedOrder['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </form>
                <a href="orders.php" class="btn btn-sm btn-light border ms-2">Close</a>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <span class="text-muted small d-block">Customer Details:</span>
                <strong><?= htmlspecialchars($selectedOrder['first_name'] . ' ' . $selectedOrder['last_name']) ?></strong><br>
                <small class="text-muted"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($selectedOrder['email']) ?></small><br>
                <small class="text-muted"><i class="fas fa-phone me-1"></i><?= htmlspecialchars($selectedOrder['phone']) ?></small>
            </div>
            <div class="col-md-4">
                <span class="text-muted small d-block">Shipping Address:</span>
                <?= htmlspecialchars($selectedOrder['address']) ?><br>
                <?= htmlspecialchars($selectedOrder['city']) ?>, <?= htmlspecialchars($selectedOrder['country']) ?> (<?= htmlspecialchars($selectedOrder['postcode']) ?>)<br>
                <span class="badge bg-light text-dark border mt-1"><?= htmlspecialchars($selectedOrder['shipping_method']) ?></span>
            </div>
            <div class="col-md-4">
                <span class="text-muted small d-block">Payment Method:</span>
                <strong><?= htmlspecialchars($selectedOrder['payment_method']) ?></strong><br>
                <?php if (!empty($selectedOrder['notes'])): ?>
                    <small class="text-muted d-block mt-2"><strong>Notes:</strong> <?= htmlspecialchars($selectedOrder['notes']) ?></small>
                <?php endif; ?>
            </div>
        </div>

        <h5 class="fw-bold mb-3">Itemized Items</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selectedOrderItems as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td class="text-center"><?= formatPrice($item['price']) ?></td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-end fw-bold"><?= formatPrice($item['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                        <td class="text-end"><?= formatPrice($selectedOrder['subtotal']) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end">Shipping Cost:</td>
                        <td class="text-end"><?= formatPrice($selectedOrder['shipping_cost']) ?></td>
                    </tr>
                    <tr class="table-light">
                        <td colspan="3" class="text-end fs-5 fw-bold">Total Amount:</td>
                        <td class="text-end fs-5 fw-bold text-primary"><?= formatPrice($selectedOrder['total_amount']) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Orders List Table -->
<div class="card border-0 shadow-sm p-4 bg-white rounded">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No orders found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $ord): ?>
                        <tr>
                            <td><strong class="text-primary"><?= htmlspecialchars($ord['order_number']) ?></strong></td>
                            <td>
                                <div><?= htmlspecialchars($ord['first_name'] . ' ' . $ord['last_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($ord['email']) ?></small>
                            </td>
                            <td><small><?= date('M j, Y', strtotime($ord['created_at'])) ?></small></td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($ord['payment_method']) ?></span></td>
                            <td><strong><?= formatPrice($ord['total_amount']) ?></strong></td>
                            <td>
                                <?php
                                $statusClass = [
                                    'pending'    => 'bg-warning text-dark',
                                    'processing' => 'bg-info text-dark',
                                    'completed'  => 'bg-success text-white',
                                    'cancelled'  => 'bg-danger text-white',
                                ][$ord['status']] ?? 'bg-secondary text-white';
                                ?>
                                <span class="badge <?= $statusClass ?> text-capitalize"><?= htmlspecialchars($ord['status']) ?></span>
                            </td>
                            <td class="text-end">
                                <a href="orders.php?order_id=<?= $ord['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Inspect">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="orders.php?action=delete&id=<?= $ord['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this order?');" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
