<?php
// Database configuration (if needed)

$charset = 'utf8mb4';

// Prefer environment variables (for Vercel/production), fall back to local defaults.
$parsedUrl = getenv('DATABASE_URL') ? parse_url(getenv('DATABASE_URL')) : null;

if ($parsedUrl && isset($parsedUrl['host'])) {
    $host = $parsedUrl['host'] ?? '127.0.0.1';
    $port = $parsedUrl['port'] ?? '3306';
    $db   = isset($parsedUrl['path']) ? ltrim($parsedUrl['path'], '/') : 'company';
    $user = $parsedUrl['user'] ?? 'root';
    $pass = $parsedUrl['pass'] ?? '';
} else {
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $db   = getenv('DB_NAME') ?: 'company';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
}

$socket = getenv('DB_SOCKET');

if ($socket) {
    $dsn = "mysql:unix_socket={$socket};dbname={$db};charset={$charset}";
} else {
    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
}

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // show errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch as array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // better security
    PDO::ATTR_TIMEOUT            => 5,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Connected successfully"; // you can test with this
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
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

