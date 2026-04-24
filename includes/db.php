<?php
require_once __DIR__ . '/supabase.php';

// Supabase Configuration from Environment
define('SB_PROJECT_REF', getenv('SB_PROJECT_REF') ?: 'abrxshzkgshklgmaztlp');
define('SB_API_KEY', getenv('SB_API_KEY') ?: '');

$db_path = __DIR__ . '/../database.sqlite';

try {
    // 1. Initialize SQLite (Fallback & Local Sync)
    $pdo = new PDO("sqlite:" . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 2. Initialize Supabase
    $supabase = new SupabaseClient(SB_PROJECT_REF, SB_API_KEY);

    // Create tables (SQLite)
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS banners (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        image_path TEXT NOT NULL,
        title TEXT,
        subtitle TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS designs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        image_path TEXT NOT NULL,
        title TEXT NOT NULL,
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS websites (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        image_path TEXT NOT NULL,
        title TEXT NOT NULL,
        url TEXT,
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS certificates (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        image_path TEXT NOT NULL,
        title TEXT NOT NULL,
        description TEXT,
        platform TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS skills (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        icon_path TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS chatbot_knowledge (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        keyword TEXT NOT NULL,
        response TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS partners (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        logo_path TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        role TEXT NOT NULL,
        text TEXT NOT NULL,
        image_path TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        key_name TEXT PRIMARY KEY,
        key_value TEXT
    )");

    // Set AI Settings (SQLite)
    $stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (key_name, key_value) VALUES ('ai_provider', 'nvidia')");
    $stmt->execute();
    $stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (key_name, key_value) VALUES ('ai_api_key', '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')");
    $stmt->execute();

    // Insert default admin if not exists (SQLite)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES ('admin', ?)");
        $stmt->execute([$hash]);
    }

} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

/**
 * Global Helper to Fetch Data (Prioritize Supabase)
 */
function sb_get($table, $order = 'id.desc') {
    global $supabase, $pdo;
    $data = $supabase->select($table, $order);
    if ($data !== false) return $data;
    
    // Fallback to SQLite
    $sql_table = $table;
    $sql_order = str_replace('.', ' ', $order);
    return $pdo->query("SELECT * FROM $sql_table ORDER BY $sql_order")->fetchAll();
}

function sb_query_single($table, $column, $value) {
    global $supabase, $pdo;
    $data = $supabase->querySingle($table, $column, $value);
    if ($data !== null) return $data;
    
    // Fallback to SQLite
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE $column = ? LIMIT 1");
    $stmt->execute([$value]);
    return $stmt->fetch();
}
?>
?>
