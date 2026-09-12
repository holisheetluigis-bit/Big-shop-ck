<?php
// includes/footer.php - Global Footer Include
?>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
        <div class="container py-5">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <a href="index.php">
                            <h1 class="text-primary mb-0"><i class="fas fa-shopping-bag text-secondary me-2"></i>Ck Shop168</h1>
                            <p class="text-secondary mb-0">Electronics & Gadgets</p>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative mx-auto">
                            <form action="shop.php" method="GET">
                                <input class="form-control border-0 w-100 py-3 px-4 rounded-pill" type="text" name="search" placeholder="Search gadgets, smartphones, laptops...">
                                <button type="submit" class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white" style="top: 0; right: 0;">Search Now</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-end pt-3">
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href="#"><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-secondary btn-md-square rounded-circle" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="text-primary mb-4">About Ck Shop168</h4>
                        <p class="mb-3">We provide authentic, high-quality electronics, computer hardware, smart devices, and camera equipment at unbeatable prices.</p>
                        <p><i class="fa fa-map-marker-alt me-2 text-primary"></i>Phnom Penh, Cambodia</p>
                        <p><i class="fa fa-phone-alt me-2 text-primary"></i>0765040211</p>
                        <p><i class="fa fa-envelope me-2 text-primary"></i>holisheetluigis@gmail.com</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="text-primary mb-4">Quick Links</h4>
                        <a href="index.php" class="text-white-50 mb-2"><i class="fas fa-angle-right me-2"></i> Home</a>
                        <a href="shop.php" class="text-white-50 mb-2"><i class="fas fa-angle-right me-2"></i> All Products</a>
                        <a href="cart.php" class="text-white-50 mb-2"><i class="fas fa-angle-right me-2"></i> Shopping Cart</a>
                        <a href="checkout.php" class="text-white-50 mb-2"><i class="fas fa-angle-right me-2"></i> Checkout</a>
                        <a href="contact.php" class="text-white-50 mb-2"><i class="fas fa-angle-right me-2"></i> Contact Us</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="text-primary mb-4">Customer Care</h4>
                        <a href="contact.php" class="text-white-50 mb-2"><i class="fas fa-angle-right me-2"></i> Help & Support</a>
                        <a href="#" class="text-white-50 mb-2"><i class="fas fa-angle-right me-2"></i> Privacy Policy</a>
                        <a href="#" class="text-white-50 mb-2"><i class="fas fa-angle-right me-2"></i> Terms & Conditions</a>
                        <a href="#" class="text-white-50 mb-2"><i class="fas fa-angle-right me-2"></i> Warranty & Returns</a>
                        <a href="admin/login.php" class="text-white-50 mb-2"><i class="fas fa-angle-right me-2"></i> Staff / Admin Portal</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="text-primary mb-4">Payment & Security</h4>
                        <p class="mb-3">We support safe, secure and fast transactions across multiple payment options.</p>
                        <div class="d-flex flex-wrap gap-2 text-white fs-4">
                            <i class="fab fa-cc-visa text-primary me-2"></i>
                            <i class="fab fa-cc-mastercard text-danger me-2"></i>
                            <i class="fab fa-cc-paypal text-info me-2"></i>
                            <i class="fas fa-money-bill-wave text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright py-4 bg-secondary text-white">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6 text-center text-md-start mb-md-0">
                    <span><i class="fas fa-copyright text-light me-2"></i><?= date('Y') ?> <strong>Ck Shop168</strong>. All rights reserved.</span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span class="small text-white-50">Powered by PHP & MySQL</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-notification"></div>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <!-- Cart AJAX & Toast Helper -->
    <script>
    function showNotification(message, type = 'success') {
        const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
        const toast = $(`
            <div class="toast align-items-center text-white ${bgClass} border-0 show mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fs-6 fw-bold">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} me-2"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);
        $('#toast-container').append(toast);
        setTimeout(() => {
            toast.fadeOut(400, function() { $(this).remove(); });
        }, 3000);
    }

    $(document).on('click', '.ajax-add-to-cart', function(e) {
        e.preventDefault();
        const btn = $(this);
        const productId = btn.data('product-id');
        const quantity = btn.data('quantity') || 1;

        btn.prop('disabled', true);
        $.ajax({
            url: 'api/cart-action.php',
            method: 'POST',
            data: {
                action: 'add',
                product_id: productId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(res) {
                btn.prop('disabled', false);
                if (res.status === 'success') {
                    $('.header-cart-count').text(res.cart_count);
                    $('.header-cart-total').text('$' + parseFloat(res.cart_total).toFixed(2));
                    showNotification(res.message || 'Product added to cart!');
                } else {
                    showNotification(res.message || 'Could not add to cart', 'error');
                }
            },
            error: function() {
                btn.prop('disabled', false);
                showNotification('Network error. Please try again.', 'error');
            }
        });
    });
    </script>
</body>
</html>
