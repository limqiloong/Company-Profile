<?php
// Database configuration (if needed)

static $pdo = null;

if ($pdo === null) {
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT') ?: '3306';
    $db   = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');

    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT            => 5,
        // Avoid persistent connections in many serverless setups unless you know your DB supports it well
        PDO::ATTR_PERSISTENT         => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);
}

// Paths & URLs
define('BASE_PATH', dirname(__DIR__));          // filesystem path to project root
define('ASSETS_PATH', BASE_PATH . '/assets');   // filesystem path to assets

// Derive base URL from the current script location so it works when the site
// runs from a subfolder (e.g., /frontend) or from the domain root (Vercel).
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseUrl === '/' || $baseUrl === '\\' || $baseUrl === '.') {
    $baseUrl = '';
}
define('BASE_URL', $baseUrl);
define('ASSETS_URL', $baseUrl . '/../assets');  // resolves to correct root assets

// Site configuration
define('SITE_NAME', 'Golden Prosperous Group of Companies');
define('SITE_NAME_CHINESE', '鑫鴻集團');
define('SITE_EMAIL', 'gerrardlau@goldenprosperousrsb.com');
define('SITE_PHONE', ' 09-505 4433 ');
define('SITE_ADDRESS', 'B-46, Jalan Semambu Baru 2, 25300 Kuantan, Pahang Darul Makmur.');
define('SITE_WEBSITE', 'goldenprosperousrsb.com');
define('SITE_FACEBOOK', 'https://www.facebook.com/goldenprosperousgoc/');
define('SITE_GOOGLE_MAPS', 'https://maps.app.goo.gl/6Z5Wr25K8CATNgG18');

// Social Media Links (optional - leave empty if not used)
// define('SITE_TWITTER', '');
// define('SITE_LINKEDIN', '');
// define('SITE_INSTAGRAM', '');

// Timezone
date_default_timezone_set('Asia/Kuala_Lumpur');


?>

