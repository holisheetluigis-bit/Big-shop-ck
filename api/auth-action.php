<?php
// api/auth-action.php - User Login, Registration, and Logout
require_once __DIR__ . '/../includes/functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$db = getDB();

if ($action === 'login') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        setFlash('auth_error', 'Please provide both email and password.', 'danger');
        header('Location: ../login.php');
        exit;
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: ../index.php');
        }
        exit;
    } else {
        setFlash('auth_error', 'Invalid email address or password.', 'danger');
        header('Location: ../login.php');
        exit;
    }
}

if ($action === 'register') {
    $username = sanitize($_POST['username'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!$username || !$email || !$password) {
        setFlash('auth_error', 'All fields are required.', 'danger');
        header('Location: ../register.php');
        exit;
    }

    if (strlen($password) < 6) {
        setFlash('auth_error', 'Password must be at least 6 characters long.', 'danger');
        header('Location: ../register.php');
        exit;
    }

    if ($password !== $confirmPassword) {
        setFlash('auth_error', 'Passwords do not match.', 'danger');
        header('Location: ../register.php');
        exit;
    }

    // Check existing email or username
    $check = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $check->execute([$email, $username]);
    if ($check->fetch()) {
        setFlash('auth_error', 'Username or email is already registered.', 'danger');
        header('Location: ../register.php');
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $insert = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
    $insert->execute([$username, $email, $hashedPassword]);

    $userId = $db->lastInsertId();
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = 'customer';

    setFlash('auth_success', 'Account created successfully! Welcome to Ck Shop168.');
    header('Location: ../index.php');
    exit;
}

if ($action === 'logout') {
    unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['user_email'], $_SESSION['user_role']);
    setFlash('auth_success', 'You have been logged out.');
    header('Location: ../index.php');
    exit;
}

header('Location: ../index.php');
exit;
