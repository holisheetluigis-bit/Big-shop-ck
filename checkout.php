<?php
// checkout.php - Dynamic Checkout Page
$pageTitle = "Checkout";
require_once __DIR__ . '/includes/header.php';

$cart = getCart();
if (empty($cart)) {
    setFlash('checkout_error', 'Your cart is empty. Please add products before checking out.', 'warning');
    header('Location: cart.php');
    exit;
}

$subtotal = getCartTotal();
$flash = getFlash('checkout_error');
$currentUser = currentUser();
?>

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5 bg-dark mb-5">
        <h1 class="text-center text-white display-6">Checkout</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
            <li class="breadcrumb-item"><a href="cart.php" class="text-white">Cart</a></li>
            <li class="breadcrumb-item active text-primary">Checkout</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Checkout Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-3">
            <h1 class="mb-4">Billing & Order Details</h1>

            <?php if ($flash): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="api/order-action.php" method="POST">
                <div class="row g-5">
                    <!-- Billing Information Form -->
                    <div class="col-md-12 col-lg-6 col-xl-7">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-2 fw-bold">First Name<sup>*</sup></label>
                                    <input type="text" name="first_name" class="form-control" required value="<?= $currentUser ? htmlspecialchars($currentUser['username']) : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-2 fw-bold">Last Name<sup>*</sup></label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-item w-100">
                                    <label class="form-label my-2">Company Name (Optional)</label>
                                    <input type="text" name="company" class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-item w-100">
                                    <label class="form-label my-2 fw-bold">Street Address <sup>*</sup></label>
                                    <input type="text" name="address" class="form-control" placeholder="House number and street name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-2 fw-bold">Town / City<sup>*</sup></label>
                                    <input type="text" name="city" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-2 fw-bold">Country<sup>*</sup></label>
                                    <input type="text" name="country" class="form-control" value="Cambodia" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-2 fw-bold">Postcode / ZIP<sup>*</sup></label>
                                    <input type="text" name="postcode" class="form-control" placeholder="12000" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-2 fw-bold">Mobile Phone<sup>*</sup></label>
                                    <input type="tel" name="phone" class="form-control" placeholder="0765040211" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-item w-100">
                                    <label class="form-label my-2 fw-bold">Email Address<sup>*</sup></label>
                                    <input type="email" name="email" class="form-control" value="<?= $currentUser ? htmlspecialchars($currentUser['email']) : '' ?>" required>
                                </div>
                            </div>

                            <?php if (!$currentUser): ?>
                                <div class="col-12">
                                    <div class="form-check my-2">
                                        <input type="checkbox" class="form-check-input" id="createAccount" name="create_account" value="1">
                                        <label class="form-check-label" for="createAccount">Create an account for faster future checkout?</label>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="col-12">
                                <div class="form-item w-100">
                                    <label class="form-label my-2">Order Notes (Optional)</label>
                                    <textarea name="notes" class="form-control" rows="4" placeholder="Special notes about delivery, apartment code, etc."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="col-md-12 col-lg-6 col-xl-5">
                        <div class="bg-light rounded p-4 shadow-sm">
                            <h3 class="mb-4 border-bottom pb-2">Your Order Summary</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Product</th>
                                            <th scope="col" class="text-center">Qty</th>
                                            <th scope="col" class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cart as $item): ?>
                                            <?php $itemTotal = $item['price'] * $item['quantity']; ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= htmlspecialchars($item['image']) ?>" class="rounded me-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                        <div>
                                                            <span class="small fw-bold d-block"><?= htmlspecialchars($item['name']) ?></span>
                                                            <small class="text-muted"><?= formatPrice($item['price']) ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle"><?= $item['quantity'] ?></td>
                                                <td class="text-end align-middle fw-bold"><?= formatPrice($itemTotal) ?></td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <tr class="border-top">
                                            <td colspan="2" class="fw-bold">Cart Subtotal</td>
                                            <td class="text-end fw-bold" id="checkoutSubtotal" data-subtotal="<?= $subtotal ?>">
                                                <?= formatPrice($subtotal) ?>
                                            </td>
                                        </tr>

                                        <!-- Shipping Options -->
                                        <tr>
                                            <td colspan="3">
                                                <label class="fw-bold mb-2">Shipping Method</label>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input shipping-radio" type="radio" name="shipping_method" id="shipFree" value="free" data-cost="0" checked>
                                                    <label class="form-check-label d-flex justify-content-between" for="shipFree">
                                                        <span>Free Delivery (3-5 days)</span>
                                                        <strong>$0.00</strong>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input shipping-radio" type="radio" name="shipping_method" id="shipFlat" value="flat" data-cost="15">
                                                    <label class="form-check-label d-flex justify-content-between" for="shipFlat">
                                                        <span>Express Courier</span>
                                                        <strong>$15.00</strong>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input shipping-radio" type="radio" name="shipping_method" id="shipPickup" value="pickup" data-cost="8">
                                                    <label class="form-check-label d-flex justify-content-between" for="shipPickup">
                                                        <span>Local Warehouse Pickup</span>
                                                        <strong>$8.00</strong>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Order Grand Total -->
                                        <tr class="table-primary border-top border-2">
                                            <td colspan="2" class="fs-5 fw-bold text-dark">Order Total:</td>
                                            <td class="text-end fs-4 fw-bold text-primary" id="checkoutGrandTotal">
                                                <?= formatPrice($subtotal) ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Payment Methods -->
                            <div class="mt-4 border-top pt-3">
                                <h5 class="mb-3">Payment Method</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payCod" value="Cash On Delivery" checked>
                                    <label class="form-check-label fw-bold" for="payCod">
                                        <i class="fas fa-money-bill-wave text-success me-2"></i>Cash On Delivery
                                    </label>
                                    <p class="text-muted small mb-0 ms-4">Pay in cash when your order is delivered to your doorstep.</p>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payBank" value="Direct Bank Transfer">
                                    <label class="form-check-label fw-bold" for="payBank">
                                        <i class="fas fa-university text-primary me-2"></i>Direct Bank Transfer / ABA Pay
                                    </label>
                                    <p class="text-muted small mb-0 ms-4">Make payment directly into our bank account using Order ID as reference.</p>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payPaypal" value="PayPal">
                                    <label class="form-check-label fw-bold" for="payPaypal">
                                        <i class="fab fa-paypal text-info me-2"></i>PayPal
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-pill py-3 px-4 w-100 text-uppercase fw-bold mt-3 shadow">
                                <i class="fas fa-lock me-2"></i>Place Order Now
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Checkout Page End -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('.shipping-radio');
        const subtotal = parseFloat(document.getElementById('checkoutSubtotal').dataset.subtotal);
        const grandTotalElem = document.getElementById('checkoutGrandTotal');

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                const cost = parseFloat(this.dataset.cost);
                const grand = subtotal + cost;
                grandTotalElem.textContent = '$' + grand.toFixed(2);
            });
        });
    });
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
