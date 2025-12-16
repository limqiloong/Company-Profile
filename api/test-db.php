<?php
// Simple DB connectivity check for Vercel environment.
// Supports env vars: DATABASE_URL or DB_HOST/DB_PORT/DB_NAME/DB_USER/DB_PASS, optional DB_SOCKET.

declare(strict_types=1);

header('Content-Type: application/json');

try {
    // Reuse connection in a warm function instance.
    static $pdo = null;

    if ($pdo === null) {
        $parsedUrl = getenv('DATABASE_URL') ? parse_url(getenv('DATABASE_URL')) : null;

        if ($parsedUrl && isset($parsedUrl['host'])) {
            $host = $parsedUrl['host'] ?? '127.0.0.1';
            $port = $parsedUrl['port'] ?? '3306';
            $db   = isset($parsedUrl['path']) ? ltrim($parsedUrl['path'], '/') : '';
            $user = $parsedUrl['user'] ?? '';
            $pass = $parsedUrl['pass'] ?? '';
        } else {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $port = getenv('DB_PORT') ?: '3306';
            $db   = getenv('DB_NAME') ?: '';
            $user = getenv('DB_USER') ?: '';
            $pass = getenv('DB_PASS') ?: '';
        }

        $socket = getenv('DB_SOCKET');

        if ($db === '' || $user === '') {
            throw new RuntimeException('Missing DB_NAME/DB_USER or DATABASE_URL');
        }

        $dsn = $socket
            ? "mysql:unix_socket={$socket};dbname={$db};charset=utf8mb4"
            : "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT            => 5,
            PDO::ATTR_PERSISTENT         => false, // safer for serverless
        ];

        $pdo = new PDO($dsn, $user, $pass, $options);
    }

    // Example query – adjust to your table.
    $stmt = $pdo->query('SELECT * FROM project LIMIT 50');
    $rows = $stmt->fetchAll();

    echo json_encode([
        'ok'   => true,
        'rows' => $rows,
        'info' => [
            'host'   => $host ?? null,
            'port'   => $port ?? null,
            'db'     => $db ?? null,
            'socket' => $socket ?? null,
        ],
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok'      => false,
        'message' => $e->getMessage(),
        'code'    => $e->getCode(),
    ]);
}

