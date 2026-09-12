<?php
// register.php - User Registration Page
$pageTitle = "Register";
require_once __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$flash = getFlash('auth_error');
?>

    <div class="container py-5 my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm p-4 p-md-5 rounded">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-primary mb-2"></i>
                        <h2 class="fw-bold">Create Account</h2>
                        <p class="text-muted">Join Ck Shop168 for fast checkout & exclusive offers</p>
                    </div>

                    <?php if ($flash): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($flash['message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="api/auth-action.php" method="POST">
                        <input type="hidden" name="action" value="register">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control py-3" placeholder="john_doe" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control py-3" placeholder="name@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control py-3" placeholder="At least 6 characters" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control py-3" placeholder="Repeat your password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold text-uppercase shadow-sm">
                            Create My Account
                        </button>
                    </form>

                    <div class="text-center mt-4 border-top pt-3">
                        <p class="text-muted mb-1">Already registered?</p>
                        <a href="login.php" class="text-primary fw-bold">Sign In Here →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
