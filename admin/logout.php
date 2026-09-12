<?php
// admin/logout.php - Admin Logout
require_once __DIR__ . '/../includes/functions.php';

unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['user_email'], $_SESSION['user_role']);
header('Location: login.php');
exit;
