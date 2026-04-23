<?php
$db_path = __DIR__ . '/../database.sqlite';

try {
    $pdo = new PDO("sqlite:" . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create tables
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

    // Set AI Settings
    $stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (key_name, key_value) VALUES ('ai_provider', 'nvidia')");
    $stmt->execute();
    $stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (key_name, key_value) VALUES ('ai_api_key', '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')");
    $stmt->execute();

    // Insert default admin if not exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES ('admin', ?)");
        $stmt->execute([$hash]);
    }

    // Insert default chatbot knowledge if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM chatbot_knowledge");
    if ($stmt->fetchColumn() == 0) {
        $defaults = [
            ['halo', 'Halo! Ada yang bisa saya bantu?'],
            ['hai', 'Hai! Senang melihat Anda di sini.'],
            ['siapa', 'Saya adalah asisten virtual Stormbreaker. Saya di sini untuk membantu Anda menjelajahi portfolio ini.'],
            ['layanan', 'Kami menawarkan jasa UI/UX Design, Web Development (PHP, JS), dan konsultasi keamanan web.'],
            ['harga', 'Untuk informasi harga, silakan hubungi kami melalui email di bagian bawah halaman atau klik "Get In Touch".'],
            ['kontak', 'Anda bisa menghubungi kami via email atau melalui media sosial yang ada di bagian bawah.'],
            ['proyek', 'Anda bisa melihat proyek terbaru kami di bagian "Design Projects" dan "Web Projects" di atas.'],
            ['skil', 'Pemilik portfolio ini ahli dalam PHP, SQL, JavaScript, UI/UX Design, dan Web Security.'],
            ['bantu', 'Tentu! Anda bisa bertanya tentang layanan, proyek, atau cara menghubungi kami.'],
            ['terima kasih', 'Sama-sama! Senang bisa membantu.'],
        ];
        $stmt = $pdo->prepare("INSERT INTO chatbot_knowledge (keyword, response) VALUES (?, ?)");
        foreach ($defaults as $d) {
            $stmt->execute($d);
        }
    }
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
