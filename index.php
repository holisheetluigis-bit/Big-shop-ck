<?php
// index.php - Dynamic Homepage
$pageTitle = "Home";
require_once __DIR__ . '/includes/header.php';

$db = getDB();

// Fetch featured products
$featuredStmt = $db->query("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    WHERE p.is_featured = 1 
    ORDER BY p.id ASC 
    LIMIT 8
");
$featuredProducts = $featuredStmt->fetchAll();

// Fetch all products for the catalog tabs
$allProductsStmt = $db->query("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    ORDER BY p.id ASC
");
$allProducts = $allProductsStmt->fetchAll();

$flash = getFlash('auth_success') ?? getFlash('cart_success');
?>

    <?php if ($flash): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Carousel & Hero Start -->
    <div class="container-fluid py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-12 col-lg-7">
                    <h4 class="mb-3 text-secondary">100% Authentic Electronics</h4>
                    <h1 class="mb-5 display-3 text-primary">Discover Next-Gen Tech & Gadgets</h1>
                    <div class="position-relative mx-auto">
                        <form action="shop.php" method="GET">
                            <input class="form-control border-2 border-secondary w-75 py-3 px-4 rounded-pill" type="text" name="search" placeholder="What gadget are you looking for?">
                            <button type="submit" class="btn btn-primary border-2 border-secondary py-3 px-4 position-absolute rounded-pill text-white h-100" style="top: 0; right: 25%;">Shop Now</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-12 col-lg-5">
                    <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active rounded">
                                <img src="img/carousel-1.png" class="img-fluid w-100 h-100 bg-secondary rounded" alt="Smartphones">
                                <a href="shop.php?category=4" class="btn px-4 py-2 text-white rounded">Smartphones</a>
                            </div>
                            <div class="carousel-item rounded">
                                <img src="img/carousel-2.png" class="img-fluid w-100 h-100 rounded" alt="Laptops">
                                <a href="shop.php?category=3" class="btn px-4 py-2 text-white rounded">Laptops</a>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Features Section Start -->
    <div class="container-fluid featurs py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-car-side fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Free Shipping</h5>
                            <p class="mb-0">Free on orders over $300</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-user-shield fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Security Payment</h5>
                            <p class="mb-0">100% secure checkout</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-exchange-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>30 Day Return</h5>
                            <p class="mb-0">30 day money guarantee</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fa fa-phone-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>24/7 Support</h5>
                            <p class="mb-0">Dedicated instant support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features Section End -->

    <!-- Featured Products Section Start -->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <div class="tab-class text-center">
                <div class="row g-4">
                    <div class="col-lg-4 text-start">
                        <h1>Our Popular Products</h1>
                    </div>
                    <div class="col-lg-8 text-end">
                        <ul class="nav nav-pills d-inline-flex text-center mb-5">
                            <li class="nav-item">
                                <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-all">
                                    <span class="text-dark" style="width: 130px;">All Products</span>
                                </a>
                            </li>
                            <?php foreach ($headerCategories as $index => $cat): ?>
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-cat-<?= $cat['id'] ?>">
                                        <span class="text-dark" style="width: 140px;"><?= htmlspecialchars($cat['name']) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <!-- Tab: All Products -->
                    <div id="tab-all" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <?php foreach ($featuredProducts as $prod): ?>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="rounded position-relative fruite-item border border-secondary shadow-sm h-100 d-flex flex-column">
                                        <div class="fruite-img p-4 text-center">
                                            <img src="<?= htmlspecialchars($prod['image']) ?>" class="img-fluid rounded-top" alt="<?= htmlspecialchars($prod['name']) ?>" style="max-height: 180px; object-fit: contain;">
                                        </div>
                                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                                            <?= htmlspecialchars($prod['category_name']) ?>
                                        </div>
                                        <div class="p-4 border-top border-secondary flex-grow-1 d-flex flex-column justify-content-between">
                                            <div>
                                                <h5><?= htmlspecialchars($prod['name']) ?></h5>
                                                <p class="text-muted small"><?= htmlspecialchars(substr($prod['description'], 0, 75)) ?>...</p>
                                                <div class="d-flex my-2">
                                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                                        <i class="fas fa-star text-primary"></i>
                                                    <?php endfor; ?>
                                                    <span class="ms-2 text-muted small">(<?= $prod['reviews_count'] ?>)</span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between flex-lg-wrap align-items-center mt-3">
                                                <div>
                                                    <span class="text-dark fs-5 fw-bold"><?= formatPrice($prod['price']) ?></span>
                                                    <?php if (!empty($prod['old_price'])): ?>
                                                        <span class="text-muted text-decoration-line-through ms-1 small"><?= formatPrice($prod['old_price']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <button class="btn border border-secondary rounded-pill px-3 text-primary ajax-add-to-cart" data-product-id="<?= $prod['id'] ?>">
                                                    <i class="fa fa-shopping-bag me-1 text-primary"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Tabs by Category -->
                    <?php foreach ($headerCategories as $cat): ?>
                        <div id="tab-cat-<?= $cat['id'] ?>" class="tab-pane fade p-0">
                            <div class="row g-4">
                                <?php
                                $catProducts = array_filter($allProducts, function($p) use ($cat) {
                                    return $p['category_id'] == $cat['id'];
                                });
                                ?>
                                <?php if (empty($catProducts)): ?>
                                    <div class="col-12 text-center py-5">
                                        <p class="text-muted fs-5">No products found in this category.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($catProducts as $prod): ?>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="rounded position-relative fruite-item border border-secondary shadow-sm h-100 d-flex flex-column">
                                                <div class="fruite-img p-4 text-center">
                                                    <img src="<?= htmlspecialchars($prod['image']) ?>" class="img-fluid rounded-top" alt="<?= htmlspecialchars($prod['name']) ?>" style="max-height: 180px; object-fit: contain;">
                                                </div>
                                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                                                    <?= htmlspecialchars($prod['category_name']) ?>
                                                </div>
                                                <div class="p-4 border-top border-secondary flex-grow-1 d-flex flex-column justify-content-between">
                                                    <div>
                                                        <h5><?= htmlspecialchars($prod['name']) ?></h5>
                                                        <p class="text-muted small"><?= htmlspecialchars(substr($prod['description'], 0, 75)) ?>...</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between flex-lg-wrap align-items-center mt-3">
                                                        <span class="text-dark fs-5 fw-bold"><?= formatPrice($prod['price']) ?></span>
                                                        <button class="btn border border-secondary rounded-pill px-3 text-primary ajax-add-to-cart" data-product-id="<?= $prod['id'] ?>">
                                                            <i class="fa fa-shopping-bag me-1 text-primary"></i> Add
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Featured Products Section End -->

    <!-- Banner Section Start -->
    <div class="container-fluid banner bg-secondary my-5">
        <div class="container py-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="py-4">
                        <h1 class="display-3 text-white">Latest Electronics Sale</h1>
                        <p class="fw-normal display-5 text-dark mb-4">in our Store</p>
                        <p class="mb-4 text-white">Upgrade your lifestyle today with top-tier gadgets, ultrabooks, smartphones and 4K displays with warranty and full after-sale support.</p>
                        <a href="shop.php" class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">BUY NOW</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative text-center">
                        <img src="img/product-banner.jpg" class="img-fluid w-100 rounded" alt="Promotional Banner">
                        <div class="d-flex align-items-center justify-content-center bg-white rounded-circle position-absolute" style="width: 140px; height: 140px; top: 0; left: 0;">
                            <h1 style="font-size: 80px;">1</h1>
                            <div class="d-flex flex-column">
                                <span class="h2 mb-0">50$</span>
                                <span class="h4 text-muted mb-0">OFF</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Section End -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
