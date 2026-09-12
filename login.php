<?php
// login.php - Customer & User Login Page
$pageTitle = "Login";
require_once __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$flash = getFlash('auth_error') ?? getFlash('auth_success');
?>

    <div class="container py-5 my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm p-4 p-md-5 rounded">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle fa-3x text-primary mb-2"></i>
                        <h2 class="fw-bold">Welcome Back</h2>
                        <p class="text-muted">Sign in to your Ck Shop168 account</p>
                    </div>

                    <?php if ($flash): ?>
                        <div class="alert alert-<?= htmlspecialchars($flash['type'] ?? 'danger') ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($flash['message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="api/auth-action.php" method="POST">
                        <input type="hidden" name="action" value="login">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control py-3" placeholder="name@example.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control py-3" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold text-uppercase shadow-sm">
                            Sign In
                        </button>
                    </form>

                    <div class="text-center mt-4 border-top pt-3">
                        <p class="text-muted mb-1">Don't have an account yet?</p>
                        <a href="register.php" class="text-primary fw-bold">Create Account →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
