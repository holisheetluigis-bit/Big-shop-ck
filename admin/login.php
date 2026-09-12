<?php
// admin/login.php - Administrator Login Portal
require_once __DIR__ . '/../includes/functions.php';

if (isAdmin()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usernameOrEmail && $password) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND role = 'admin'");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['user_email'] = $admin['email'];
            $_SESSION['user_role'] = 'admin';
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid administrative credentials.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Login - Ck Shop168</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <style>
        body {
            background: #2b3035;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Open Sans', sans-serif;
        }
        .admin-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            padding: 35px;
        }
    </style>
</head>
<body>
    <div class="admin-card">
        <div class="text-center mb-4">
            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="fas fa-user-shield fa-2x"></i>
            </div>
            <h3 class="fw-bold mb-1">Admin Portal</h3>
            <p class="text-muted small">Ck Shop168 Management System</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small" role="alert">
                <i class="fas fa-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Username or Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="admin" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold text-uppercase shadow-sm">
                Login to Dashboard
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="../index.php" class="text-muted small text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i> Return to Storefront
            </a>
        </div>
    </div>
</body>
</html>
