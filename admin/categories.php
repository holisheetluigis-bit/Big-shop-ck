<?php
// admin/categories.php - Manage Categories CRUD
$adminTitle = "Categories";
require_once __DIR__ . '/header.php';

$action = $_GET['action'] ?? 'list';
$msg = '';
$err = '';

// Handle Delete
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: categories.php?msg=deleted');
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $icon = sanitize($_POST['icon'] ?? 'fa-laptop');
    $slug = slugify($name);
    $catId = (int)($_POST['category_id'] ?? 0);

    if ($name) {
        if ($catId > 0) {
            $stmt = $db->prepare("UPDATE categories SET name = ?, slug = ?, icon = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $icon, $catId]);
            header('Location: categories.php?msg=updated');
            exit;
        } else {
            $stmt = $db->prepare("INSERT INTO categories (name, slug, icon) VALUES (?, ?, ?)");
            $stmt->execute([$name, $slug, $icon]);
            header('Location: categories.php?msg=created');
            exit;
        }
    } else {
        $err = 'Category name is required.';
    }
}

// Edit Category
$editingCat = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $editingCat = $stmt->fetch();
}

// List Categories
$categories = $db->query("
    SELECT c.*, COUNT(p.id) AS product_count 
    FROM categories c 
    LEFT JOIN products p ON c.id = p.category_id 
    GROUP BY c.id 
    ORDER BY c.name ASC
")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Categories Management</h2>
        <p class="text-muted mb-0">Organize products into store categories</p>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Category successfully <?= htmlspecialchars($_GET['msg']) ?>!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($err): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($err) ?></div>
<?php endif; ?>

<div class="row g-4">
    <!-- Category Form -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm p-4 bg-white rounded">
            <h4 class="fw-bold mb-3"><?= $editingCat ? 'Edit Category' : 'Add New Category' ?></h4>
            <form action="categories.php" method="POST">
                <input type="hidden" name="category_id" value="<?= $editingCat['id'] ?? 0 ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold">Category Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editingCat['name'] ?? '') ?>" placeholder="e.g. Gaming Gear" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Icon Class (FontAwesome)</label>
                    <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($editingCat['icon'] ?? 'fa-laptop') ?>" placeholder="e.g. fa-gamepad">
                    <small class="text-muted">Example: <code>fa-mobile-alt</code>, <code>fa-headphones</code>, <code>fa-camera</code></small>
                </div>
                <button type="submit" class="btn btn-primary px-4 fw-bold">
                    <?= $editingCat ? 'Update Category' : 'Save Category' ?>
                </button>
                <?php if ($editingCat): ?>
                    <a href="categories.php" class="btn btn-light ms-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Category List Table -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm p-4 bg-white rounded">
            <h4 class="fw-bold mb-3">All Categories (<?= count($categories) ?>)</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Products</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><i class="fas <?= htmlspecialchars($cat['icon']) ?> fa-lg text-primary"></i></td>
                                <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                                <td><span class="badge bg-light text-dark border"><?= $cat['product_count'] ?> products</span></td>
                                <td class="text-end">
                                    <a href="categories.php?action=edit&id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="categories.php?action=delete&id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category? Associated products may be affected.');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
