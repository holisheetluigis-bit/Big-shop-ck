<?php
// admin/products.php - Manage Products CRUD
$adminTitle = "Products Management";
require_once __DIR__ . '/header.php';

$action = $_GET['action'] ?? 'list';
$msg = '';
$err = '';

// Handle Delete
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: products.php?msg=deleted');
    exit;
}

// Handle Form Submission (Create or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = sanitize($_POST['name'] ?? '');
    $categoryId  = (int)($_POST['category_id'] ?? 0);
    $price       = (float)($_POST['price'] ?? 0);
    $oldPrice    = !empty($_POST['old_price']) ? (float)$_POST['old_price'] : null;
    $stock       = (int)($_POST['stock'] ?? 0);
    $description = sanitize($_POST['description'] ?? '');
    $isFeatured  = isset($_POST['is_featured']) ? 1 : 0;
    $slug        = slugify($name);
    $productId   = (int)($_POST['product_id'] ?? 0);

    // Image handling
    $imagePath = $_POST['current_image'] ?? 'img/product-1.png';
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../img/';
        $fileName = 'prod_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = 'img/' . $fileName;
        }
    } elseif (!empty($_POST['existing_image'])) {
        $imagePath = sanitize($_POST['existing_image']);
    }

    if ($name && $categoryId && $price > 0) {
        if ($productId > 0) {
            // Update
            $update = $db->prepare("
                UPDATE products 
                SET category_id = ?, name = ?, slug = ?, description = ?, price = ?, old_price = ?, stock = ?, is_featured = ?, image = ?
                WHERE id = ?
            ");
            $update->execute([$categoryId, $name, $slug, $description, $price, $oldPrice, $stock, $isFeatured, $imagePath, $productId]);
            header('Location: products.php?msg=updated');
            exit;
        } else {
            // Insert
            $insert = $db->prepare("
                INSERT INTO products (category_id, name, slug, description, price, old_price, stock, is_featured, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert->execute([$categoryId, $name, $slug, $description, $price, $oldPrice, $stock, $isFeatured, $imagePath]);
            header('Location: products.php?msg=created');
            exit;
        }
    } else {
        $err = 'Please enter a valid product name, category, and price.';
    }
}

// Fetch categories
$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// If Edit Action, fetch existing product
$editingProduct = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $editingProduct = $stmt->fetch();
}

// Fetch products list
$products = $db->query("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    ORDER BY p.id DESC
")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Products Management</h2>
        <p class="text-muted mb-0">Add, edit, or delete items in your catalog</p>
    </div>
    <?php if ($action === 'list'): ?>
        <a href="products.php?action=create" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-1"></i> Add Product</a>
    <?php else: ?>
        <a href="products.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back to Product List</a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Product was successfully <?= htmlspecialchars($_GET['msg']) ?>!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($err): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($err) ?></div>
<?php endif; ?>

<?php if ($action === 'create' || $action === 'edit'): ?>
    <!-- Create / Edit Form -->
    <div class="card border-0 shadow-sm p-4 bg-white rounded" style="max-width: 800px;">
        <h4 class="fw-bold mb-3"><?= $action === 'edit' ? 'Edit Product' : 'Add New Product' ?></h4>
        <form action="products.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?= $editingProduct['id'] ?? 0 ?>">
            <input type="hidden" name="current_image" value="<?= $editingProduct['image'] ?? '' ?>">

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Product Name *</label>
                    <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($editingProduct['name'] ?? '') ?>" placeholder="e.g. Apple iPhone 13 Pro">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Category *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($editingProduct['category_id']) && $editingProduct['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Price ($) *</label>
                    <input type="number" step="0.01" name="price" class="form-control" required value="<?= $editingProduct['price'] ?? '' ?>" placeholder="199.99">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Old / Discount Price ($)</label>
                    <input type="number" step="0.01" name="old_price" class="form-control" value="<?= $editingProduct['old_price'] ?? '' ?>" placeholder="Optional">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Stock Quantity</label>
                    <input type="number" name="stock" class="form-control" value="<?= $editingProduct['stock'] ?? 50 ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($editingProduct['description'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Upload Product Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Or Select Existing Image</label>
                    <select name="existing_image" class="form-select">
                        <option value="">Choose existing...</option>
                        <?php for ($i = 1; $i <= 18; $i++): ?>
                            <option value="img/product-<?= $i ?>.png" <?= (isset($editingProduct['image']) && $editingProduct['image'] === "img/product-$i.png") ? 'selected' : '' ?>>
                                img/product-<?= $i ?>.png
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <?php if (!empty($editingProduct['image'])): ?>
                    <div class="col-12">
                        <label class="form-label d-block text-muted small">Current Image:</label>
                        <img src="../<?= htmlspecialchars($editingProduct['image']) ?>" class="rounded border p-1" style="width: 90px; height: 90px; object-fit: contain;">
                    </div>
                <?php endif; ?>

                <div class="col-12">
                    <div class="form-check my-2">
                        <input type="checkbox" name="is_featured" class="form-check-input" id="isFeatured" value="1" <?= (!empty($editingProduct['is_featured'])) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="isFeatured">Featured Product (Show on Homepage)</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Save Product</button>
                    <a href="products.php" class="btn btn-light px-4 ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<?php else: ?>
    <!-- Products Table -->
    <div class="card border-0 shadow-sm p-4 bg-white rounded">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Featured</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <img src="../<?= htmlspecialchars($p['image']) ?>" class="rounded border p-1" style="width: 50px; height: 50px; object-fit: contain;">
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($p['name']) ?></strong>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['category_name']) ?></span></td>
                            <td>
                                <strong class="text-primary"><?= formatPrice($p['price']) ?></strong>
                                <?php if ($p['old_price']): ?>
                                    <small class="text-muted text-decoration-line-through d-block"><?= formatPrice($p['old_price']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $p['stock'] > 10 ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <?= $p['stock'] ?> in stock
                                </span>
                            </td>
                            <td>
                                <?= $p['is_featured'] ? '<span class="text-success"><i class="fas fa-check-circle"></i> Yes</span>' : '<span class="text-muted">No</span>' ?>
                            </td>
                            <td class="text-end">
                                <a href="products.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="products.php?action=delete&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?');" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
