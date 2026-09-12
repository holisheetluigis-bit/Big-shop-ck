<?php
// contact.php - Customer Support & Contact Page
$pageTitle = "Contact Us";
require_once __DIR__ . '/includes/header.php';

$flash = getFlash('contact_success') ?? getFlash('contact_error');
?>

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5 bg-dark mb-5">
        <h1 class="text-center text-white display-6">Contact Us</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
            <li class="breadcrumb-item active text-primary">Contact</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Contact Start -->
    <div class="container-fluid contact py-4">
        <div class="container py-3">
            <div class="p-5 bg-light rounded">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="text-center mx-auto" style="max-width: 700px;">
                            <h1 class="text-primary">Get in touch</h1>
                            <p class="mb-4">Have a question regarding an order, product warranty, or tech specifications? Send us a message and our support staff will assist you within 24 hours.</p>
                        </div>

                        <?php if ($flash): ?>
                            <div class="alert alert-<?= htmlspecialchars($flash['type'] ?? 'info') ?> alert-dismissible fade show max-w-700 mx-auto mb-4" role="alert">
                                <?= htmlspecialchars($flash['message']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-12">
                        <div class="h-100 rounded mb-4">
                            <iframe class="rounded w-100" style="height: 350px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15635.795293291583!2d104.8887!3d11.5564!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x310951add5e2cd81%3A0x171e0b69c7c6f7d3!2sPhnom%20Penh%2C%20Cambodia!5e0!3m2!1sen!2skh!4v1694259649153!5m2!1sen!2skh" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <form action="api/contact-action.php" method="POST">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <input type="text" name="name" class="w-100 form-control border-0 py-3 px-4" placeholder="Your Name *" required>
                                </div>
                                <div class="col-lg-6">
                                    <input type="email" name="email" class="w-100 form-control border-0 py-3 px-4" placeholder="Enter Your Email *" required>
                                </div>
                                <div class="col-lg-6">
                                    <input type="tel" name="phone" class="w-100 form-control border-0 py-3 px-4" placeholder="Your Phone (Optional)">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="project" class="w-100 form-control border-0 py-3 px-4" placeholder="Order Number or Topic">
                                </div>
                                <div class="col-12">
                                    <input type="text" name="subject" class="w-100 form-control border-0 py-3 px-4" placeholder="Subject *" required>
                                </div>
                                <div class="col-12">
                                    <textarea name="message" class="w-100 form-control border-0 py-3 px-4" rows="5" placeholder="Your Message *" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="w-100 btn form-control border-secondary py-3 bg-white text-primary fw-bold" type="submit">Send Message Now</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-5">
                        <div class="d-flex p-4 rounded mb-4 bg-white shadow-sm">
                            <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Address</h4>
                                <p class="mb-2 text-muted">Phnom Penh, Cambodia</p>
                            </div>
                        </div>
                        <div class="d-flex p-4 rounded mb-4 bg-white shadow-sm">
                            <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Email Us</h4>
                                <p class="mb-2 text-muted">holisheetluigis@gmail.com</p>
                            </div>
                        </div>
                        <div class="d-flex p-4 rounded bg-white shadow-sm">
                            <i class="fa fa-phone-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Telephone</h4>
                                <p class="mb-2 text-muted">0765040211</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
