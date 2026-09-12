<?php
// shop.php - Dynamic Shop / Product Catalog
$pageTitle = "Shop Products";
require_once __DIR__ . '/includes/header.php';

$db = getDB();

// Filters & Sorting
$categoryId = isset($_GET['category']) && is_numeric($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = $_GET['sort'] ?? 'newest';

// Build SQL Query
$where = ["1=1"];
$params = [];

if ($categoryId) {
    $where[] = "p.category_id = ?";
    $params[] = $categoryId;
}

if ($search !== '') {
    $where[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$orderBy = "p.id DESC";
if ($sort === 'price_asc') {
    $orderBy = "p.price ASC";
} elseif ($sort === 'price_desc') {
    $orderBy = "p.price DESC";
} elseif ($sort === 'rating') {
    $orderBy = "p.rating DESC";
}

$whereClause = implode(" AND ", $where);

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 9;
$offset = ($page - 1) * $limit;

// Count total
$countStmt = $db->prepare("SELECT COUNT(*) FROM products p WHERE $whereClause");
$countStmt->execute($params);
$totalProducts = $countStmt->fetchColumn();
$totalPages = ceil($totalProducts / $limit);

// Fetch products
$sql = "
    SELECT p.*, c.name AS category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    WHERE $whereClause 
    ORDER BY $orderBy 
    LIMIT $limit OFFSET $offset
";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5 bg-dark mb-5">
        <h1 class="text-center text-white display-6">Shop Catalog</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
            <li class="breadcrumb-item active text-primary">Shop</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-4">
        <div class="container py-3">
            <h1 class="mb-4">All Electronics Products</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <!-- Search & Sort Top Toolbar -->
                    <div class="row g-4 mb-4 align-items-center">
                        <div class="col-xl-4">
                            <form action="shop.php" method="GET" class="input-group w-100 mx-auto d-flex">
                                <?php if ($categoryId): ?>
                                    <input type="hidden" name="category" value="<?= $categoryId ?>">
                                <?php endif; ?>
                                <input type="search" name="search" class="form-control p-3" placeholder="keywords" value="<?= htmlspecialchars($search) ?>" aria-describedby="search-icon-1">
                                <button type="submit" id="search-icon-1" class="input-group-text p-3 bg-primary text-white border-0"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                        <div class="col-xl-4 text-center text-xl-start">
                            <span class="text-muted">Showing <?= count($products) ?> of <?= $totalProducts ?> products</span>
                        </div>
                        <div class="col-xl-4">
                            <div class="bg-light ps-3 py-2 rounded d-flex justify-content-between align-items-center">
                                <label for="sortSelector" class="me-2 mb-0 fw-bold">Sort By:</label>
                                <select id="sortSelector" class="form-select border-0 bg-light" onchange="location = this.value;">
                                    <option value="shop.php?<?= http_build_query(array_merge($_GET, ['sort' => 'newest'])) ?>" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
                                    <option value="shop.php?<?= http_build_query(array_merge($_GET, ['sort' => 'price_asc'])) ?>" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                                    <option value="shop.php?<?= http_build_query(array_merge($_GET, ['sort' => 'price_desc'])) ?>" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                                    <option value="shop.php?<?= http_build_query(array_merge($_GET, ['sort' => 'rating'])) ?>" <?= $sort === 'rating' ? 'selected' : '' ?>>Highest Rated</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Sidebar Filters -->
                        <div class="col-lg-3">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="mb-3 p-3 bg-light rounded shadow-sm">
                                        <h4 class="mb-3 border-bottom pb-2">Categories</h4>
                                        <ul class="list-unstyled fruite-categorie m-0">
                                            <li class="py-1">
                                                <div class="d-flex justify-content-between fruite-name">
                                                    <a href="shop.php" class="<?= !$categoryId ? 'fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-th-large me-2"></i>All Products</a>
                                                    <span>(<?= $totalProducts ?>)</span>
                                                </div>
                                            </li>
                                            <?php foreach ($headerCategories as $cat): ?>
                                                <li class="py-1">
                                                    <div class="d-flex justify-content-between fruite-name">
                                                        <a href="shop.php?category=<?= $cat['id'] ?>" class="<?= $categoryId == $cat['id'] ? 'fw-bold text-primary' : 'text-dark' ?>">
                                                            <i class="fas <?= htmlspecialchars($cat['icon']) ?> me-2 text-primary"></i><?= htmlspecialchars($cat['name']) ?>
                                                        </a>
                                                        <span>(<?= $cat['product_count'] ?>)</span>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="position-relative rounded overflow-hidden">
                                        <img src="img/product-banner-2.jpg" class="img-fluid w-100 rounded" alt="Special Promo">
                                        <div class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%);">
                                            <h3 class="text-secondary fw-bold">Gadget <br> Fresh <br> Deals</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="col-lg-9">
                            <div class="row g-4 justify-content-start">
                                <?php if (empty($products)): ?>
                                    <div class="col-12 text-center py-5">
                                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                        <h3>No products found</h3>
                                        <p class="text-muted">Try adjusting your category filter or search terms.</p>
                                        <a href="shop.php" class="btn btn-primary rounded-pill px-4 py-2 mt-2">Clear Filters</a>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($products as $prod): ?>
                                        <div class="col-md-6 col-lg-6 col-xl-4">
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
                                                        <p class="text-muted small"><?= htmlspecialchars(substr($prod['description'], 0, 80)) ?>...</p>
                                                        <div class="d-flex my-2">
                                                            <?php for ($i = 0; $i < 5; $i++): ?>
                                                                <i class="fas fa-star text-primary"></i>
                                                            <?php endfor; ?>
                                                            <span class="ms-2 text-muted small">(<?= $prod['reviews_count'] ?>)</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-3">
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
                                <?php endif; ?>

                                <!-- Pagination -->
                                <?php if ($totalPages > 1): ?>
                                    <div class="col-12">
                                        <div class="pagination d-flex justify-content-center mt-5">
                                            <?php if ($page > 1): ?>
                                                <a href="shop.php?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="rounded">&laquo;</a>
                                            <?php endif; ?>
                                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                <a href="shop.php?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="rounded <?= $i === $page ? 'active' : '' ?>">
                                                    <?= $i ?>
                                                </a>
                                            <?php endfor; ?>
                                            <?php if ($page < $totalPages): ?>
                                                <a href="shop.php?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="rounded">&raquo;</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fruits Shop End-->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
