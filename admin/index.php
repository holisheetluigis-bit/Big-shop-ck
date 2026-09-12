<?php
// admin/index.php - Executive Dashboard
$adminTitle = "Dashboard";
require_once __DIR__ . '/header.php';

$totalSales = $db->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();
$totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalProducts = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalCustomers = $db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();

// Recent orders
$recentOrders = $db->query("SELECT * FROM orders ORDER BY id DESC LIMIT 5")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Dashboard Overview</h2>
        <p class="text-muted mb-0">Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>! Here is what's happening today.</p>
    </div>
    <div>
        <a href="products.php?action=create" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-1"></i> Add New Product</a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Total Sales</span>
                    <h3 class="fw-bold mt-1 text-primary"><?= formatPrice($totalSales) ?></h3>
                </div>
                <div class="rounded-circle bg-light text-primary p-3">
                    <i class="fas fa-dollar-sign fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Total Orders</span>
                    <h3 class="fw-bold mt-1"><?= $totalOrders ?></h3>
                </div>
                <div class="rounded-circle bg-light text-info p-3">
                    <i class="fas fa-shopping-cart fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Live Products</span>
                    <h3 class="fw-bold mt-1 text-success"><?= $totalProducts ?></h3>
                </div>
                <div class="rounded-circle bg-light text-success p-3">
                    <i class="fas fa-box-open fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Unread Messages</span>
                    <h3 class="fw-bold mt-1 text-danger"><?= $unreadCount ?></h3>
                </div>
                <div class="rounded-circle bg-light text-danger p-3">
                    <i class="fas fa-envelope fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card border-0 shadow-sm p-4 bg-white rounded">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Recent Orders</h4>
        <a href="orders.php" class="text-primary text-decoration-none small fw-bold">View All Orders →</a>
    </div>

    <?php if (empty($recentOrders)): ?>
        <p class="text-muted text-center py-4 mb-0">No orders placed yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items / Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td><strong class="text-primary"><?= htmlspecialchars($order['order_number']) ?></strong></td>
                            <td>
                                <div><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($order['phone']) ?></small>
                            </td>
                            <td><strong><?= formatPrice($order['total_amount']) ?></strong></td>
                            <td><small class="badge bg-light text-dark border"><?= htmlspecialchars($order['payment_method']) ?></small></td>
                            <td>
                                <?php
                                $statusClass = [
                                    'pending'    => 'bg-warning text-dark',
                                    'processing' => 'bg-info text-dark',
                                    'completed'  => 'bg-success text-white',
                                    'cancelled'  => 'bg-danger text-white',
                                ][$order['status']] ?? 'bg-secondary text-white';
                                ?>
                                <span class="badge <?= $statusClass ?> text-capitalize"><?= htmlspecialchars($order['status']) ?></span>
                            </td>
                            <td><small class="text-muted"><?= date('M j, Y', strtotime($order['created_at'])) ?></small></td>
                            <td>
                                <a href="orders.php?order_id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">Manage</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
