<?php
require_once __DIR__ . '/../includes/db.php';

// Data from SQLite to Sync to Supabase
$tables = [
    'users',
    'banners',
    'designs',
    'websites',
    'certificates',
    'skills',
    'chatbot_knowledge',
    'partners',
    'testimonials',
    'settings'
];

echo "<h2>Starting Sync to Supabase...</h2>";

foreach ($tables as $table) {
    try {
        echo "Processing table: <strong>$table</strong>... ";
        
        // 1. Get all data from SQLite
        $stmt = $pdo->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            echo "<span style='color:orange;'>Empty, skipping.</span><br>";
            continue;
        }

        // 2. Insert into Supabase
        $success_count = 0;
        foreach ($rows as $row) {
            // Remove 'id' because it's SERIAL/Auto-increment in Supabase
            // and we want Supabase to assign new IDs to avoid conflicts
            if (isset($row['id'])) unset($row['id']);
            
            $res = $supabase->insert($table, $row);
            if ($res) {
                $success_count++;
            } else {
                echo "<br><span style='color:red;'>Failed to insert a row in $table. Check if table exists in Supabase.</span>";
            }
        }
        
        echo "<span style='color:green;'>Success: $success_count rows synced.</span><br>";
        
    } catch (Exception $e) {
        echo "<span style='color:red;'>Error: " . $e->getMessage() . "</span><br>";
    }
}

echo "<br><strong>Sync Complete!</strong><br>";
?>
