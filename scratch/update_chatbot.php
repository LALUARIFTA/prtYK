<?php
require_once __DIR__ . '/../includes/db.php';

// Prepare more comprehensive data based on the website content
$new_knowledge = [
    // Branding & Identity
    ['stormbreaker', 'Stormbreaker adalah studio desain dan pengembangan digital yang fokus pada kualitas premium dan craftsmanship tinggi.'],
    ['branding', 'Kami membantu membangun identitas digital yang kuat melalui desain UI/UX yang modern dan pengembangan web yang andal.'],
    
    // Services: Networking & IT Support
    ['networking', 'Kami menyediakan solusi infrastruktur jaringan yang andal, mulai dari konfigurasi server, manajemen bandwidth, hingga troubleshooting perangkat keras.'],
    ['it support', 'Layanan IT Support kami meliputi pemeliharaan sistem, manajemen server, dan dukungan teknis komprehensif untuk memastikan operasional digital Anda lancar.'],
    ['server', 'Kami melayani konfigurasi dan optimasi server untuk performa maksimal dan keamanan yang terjaga.'],
    
    // Services: Payment Ecosystem
    ['payment', 'Website kami terintegrasi dengan berbagai vendor dan payment gateway terpercaya di Indonesia untuk transaksi yang aman dan lancar.'],
    ['pembayaran', 'Kami mendukung integrasi berbagai metode pembayaran digital untuk memudahkan bisnis Anda menjangkau lebih banyak pelanggan.'],
    ['vendor', 'Kami bekerja sama dengan partner terpercaya seperti Midtrans, Xendit, dan penyedia payment gateway lainnya di Indonesia.'],
    
    // Location & Contact
    ['lokasi', 'Kami berbasis di Lombok, Nusa Tenggara Barat, Indonesia. Namun, kami melayani klien dari seluruh penjuru dunia secara remote.'],
    ['lombok', 'Ya, kami bangga beroperasi dari Lombok, ID. Tempat yang indah untuk membangun masa depan digital.'],
    ['alamat', 'Anda bisa melihat preview lokasi kami di bagian Contact di bawah halaman ini.'],
    ['whatsapp', 'Anda bisa menghubungi kami via WhatsApp di nomor +62 889 8700 4237 untuk respon yang lebih cepat.'],
    ['instagram', 'Ikuti kami di Instagram @ashari.ll untuk melihat update terbaru dan proses kreatif kami.'],
    ['linkedin', 'Hubungkan dengan kami di LinkedIn (Lalu Arif) untuk profesional networking.'],
    
    // Projects & Expertise
    ['expertise', 'Keahlian utama kami meliputi UI/UX Design, Web Development (PHP/JavaScript), Networking, dan IT Support.'],
    ['desain', 'Kami membuat desain yang tidak hanya indah secara visual, tetapi juga fungsional dan berorientasi pada pengalaman pengguna.'],
    ['website', 'Kami membangun website modern, responsif, dan berperforma tinggi menggunakan teknologi terkini.'],
    ['sertifikat', 'Anda bisa melihat berbagai sertifikasi keahlian kami di bagian Works > Certificates.'],
];

try {
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO chatbot_knowledge (keyword, response) VALUES (?, ?)");
    foreach ($new_knowledge as $k) {
        $stmt->execute($k);
    }
    echo "Chatbot knowledge updated successfully with information from the website!";
} catch (PDOException $e) {
    echo "Error updating knowledge: " . $e->getMessage();
}
?>
