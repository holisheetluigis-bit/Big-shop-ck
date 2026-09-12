<?php
// cart.php - Shopping Cart Page
$pageTitle = "Shopping Cart";
require_once __DIR__ . '/includes/header.php';

$cart = getCart();
$subtotal = getCartTotal();

$flash = getFlash('cart_success') ?? getFlash('cart_error') ?? getFlash('checkout_error');
?>

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5 bg-dark mb-5">
        <h1 class="text-center text-white display-6">Shopping Cart</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
            <li class="breadcrumb-item"><a href="shop.php" class="text-white">Shop</a></li>
            <li class="breadcrumb-item active text-primary">Cart</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-3">
            <?php if ($flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type'] ?? 'info') ?> alert-dismissible fade show mb-4" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($cart)): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-shopping-cart fa-5x text-muted"></i>
                    </div>
                    <h2>Your Shopping Cart is Empty</h2>
                    <p class="text-muted">Looks like you haven't added any products to your cart yet.</p>
                    <a href="shop.php" class="btn btn-primary rounded-pill px-5 py-3 mt-3">Start Shopping Now →</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col">Products</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $item): ?>
                                <?php $itemTotal = $item['price'] * $item['quantity']; ?>
                                <tr>
                                    <th scope="row">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= htmlspecialchars($item['image']) ?>" class="img-fluid me-3 rounded" style="width: 80px; height: 80px; object-fit: contain;" alt="<?= htmlspecialchars($item['name']) ?>">
                                        </div>
                                    </th>
                                    <td>
                                        <p class="mb-0 fw-bold"><?= htmlspecialchars($item['name']) ?></p>
                                        <small class="text-muted"><?= htmlspecialchars($item['category']) ?></small>
                                    </td>
                                    <td>
                                        <p class="mb-0"><?= formatPrice($item['price']) ?></p>
                                    </td>
                                    <td>
                                        <div class="input-group quantity" style="width: 120px;">
                                            <form action="api/cart-action.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                                <input type="hidden" name="quantity" value="<?= max(0, $item['quantity'] - 1) ?>">
                                                <button type="submit" class="btn btn-sm btn-minus rounded-circle bg-light border">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </form>
                                            <input type="text" class="form-control form-control-sm text-center border-0" value="<?= $item['quantity'] ?>" readonly>
                                            <form action="api/cart-action.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                                <input type="hidden" name="quantity" value="<?= $item['quantity'] + 1 ?>">
                                                <button type="submit" class="btn btn-sm btn-plus rounded-circle bg-light border">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-bold"><?= formatPrice($itemTotal) ?></p>
                                    </td>
                                    <td>
                                        <form action="api/cart-action.php" method="POST">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                            <button type="submit" class="btn btn-md rounded-circle bg-light border" title="Remove Item">
                                                <i class="fa fa-times text-danger"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row g-4 justify-content-between my-5">
                    <div class="col-lg-5">
                        <div class="d-flex">
                            <input type="text" class="border-0 border-bottom rounded me-3 py-3 px-2 w-100" placeholder="Coupon Code (e.g. DISCOUNT10)">
                            <button class="btn btn-outline-primary rounded-pill px-4" type="button">Apply</button>
                        </div>
                        <div class="mt-4">
                            <form action="api/cart-action.php" method="POST" onsubmit="return confirm('Clear all items from your cart?');">
                                <input type="hidden" name="action" value="clear">
                                <button type="submit" class="btn btn-outline-danger rounded-pill px-4"><i class="fa fa-trash me-2"></i>Clear Entire Cart</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                        <div class="bg-light rounded p-4 shadow-sm">
                            <h2 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h2>
                            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                <h5 class="mb-0 me-4">Subtotal:</h5>
                                <p class="mb-0 fs-5"><?= formatPrice($subtotal) ?></p>
                            </div>
                            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                <h5 class="mb-0 me-4">Shipping:</h5>
                                <div class="text-end">
                                    <p class="mb-0 text-success fw-bold">Free on Standard Orders</p>
                                    <small class="text-muted">Calculated at checkout</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-4 border-top border-2 pt-3">
                                <h5 class="mb-0 me-4">Total Amount:</h5>
                                <p class="mb-0 fs-4 fw-bold text-primary"><?= formatPrice($subtotal) ?></p>
                            </div>
                            <a href="checkout.php" class="btn btn-primary rounded-pill px-4 py-3 text-uppercase mb-2 w-100">
                                Proceed to Checkout →
                            </a>
                            <a href="shop.php" class="btn btn-outline-secondary rounded-pill px-4 py-2 text-uppercase w-100">
                                ← Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Cart Page End -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
