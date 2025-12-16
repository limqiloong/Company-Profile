<?php
// Simple DB connectivity check for Vercel environment.
// Requires env vars: DB_HOST, DB_PORT (optional, defaults 3306), DB_NAME, DB_USER, DB_PASS.

declare(strict_types=1);

header('Content-Type: application/json');

try {
    // Reuse connection in a warm function instance.
    static $pdo = null;

    if ($pdo === null) {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $db   = getenv('DB_NAME') ?: '';
        $user = getenv('DB_USER') ?: '';
        $pass = getenv('DB_PASS') ?: '';

        if ($db === '' || $user === '') {
            throw new RuntimeException('Missing DB_NAME or DB_USER environment variables');
        }

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT            => 5,
            PDO::ATTR_PERSISTENT         => false, // safer for serverless
        ];

        $pdo = new PDO($dsn, $user, $pass, $options);
    }

    // Example query – adjust the table name as needed.
    $stmt = $pdo->query('SELECT * FROM your_table LIMIT 50');
    $rows = $stmt->fetchAll();

    echo json_encode([
        'ok'   => true,
        'rows' => $rows,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok'      => false,
        'message' => $e->getMessage(),
    ]);
}

