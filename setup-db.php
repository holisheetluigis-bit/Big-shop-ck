<?php
// setup-db.php - Automated Setup and Database Seeder

$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$pass = '';

header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><title>Ck Shop168 - Database Setup</title>";
echo "<style>body{font-family:sans-serif;padding:30px;background:#f8f9fa;color:#333;} .card{background:#fff;padding:25px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.05);max-width:600px;margin:auto;} .success{color:#198754;font-weight:bold;} .btn{display:inline-block;padding:10px 20px;background:#f28b00;color:#fff;text-decoration:none;border-radius:5px;margin-top:15px;}</style></head><body>";
echo "<div class='card'><h2>🚀 Ck Shop168 - Database Setup</h2>";

try {
    // 1. Connect without database to ensure DB creation
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "<p class='success'>✔ Connected to MySQL server successfully.</p>";

    // 2. Read database.sql
    $sqlFile = __DIR__ . '/database.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("database.sql file not found at " . $sqlFile);
    }
    $sql = file_get_contents($sqlFile);

    // 3. Execute multi-query script
    $pdo->exec($sql);
    echo "<p class='success'>✔ Database <code>ck_shop</code> and all tables created successfully.</p>";
    echo "<p class='success'>✔ Seeded categories, products, and default admin account.</p>";

    echo "<hr>";
    echo "<h3>Default Admin Account</h3>";
    echo "<ul>";
    echo "<li><strong>Username:</strong> maroza</li>";
    echo "<li><strong>Password:</strong> password30112007</li>";
    echo "<li><strong>Admin URL:</strong> <a href='admin/login.php'>admin/login.php</a></li>";
    echo "</ul>";

    echo "<a class='btn' href='index.php'>Go to Ck Shop168 Storefront →</a>";

} catch (Exception $e) {
    echo "<p style='color:#dc3545;font-weight:bold;'>❌ Setup Failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</div></body></html>";
