<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

require_login();

$csrf_token = generate_csrf_token();
$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die('CSRF token validation failed');
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'upload_banner') {
        $path = secure_upload($_FILES['image'], 'banners');
        if ($path) {
            $stmt = $pdo->prepare("INSERT INTO banners (image_path, title, subtitle) VALUES (?, ?, ?)");
            $stmt->execute([$path, $_POST['title'], $_POST['subtitle']]);
            set_toast('Banner uploaded successfully');
            redirect('index.php');
        } else {
            $err = 'Failed to upload banner. Check file type and size.';
        }
    } elseif ($action === 'upload_design') {
        $path = secure_upload($_FILES['image'], 'designs');
        if ($path) {
            $stmt = $pdo->prepare("INSERT INTO designs (image_path, title, description) VALUES (?, ?, ?)");
            $stmt->execute([$path, $_POST['title'], $_POST['description']]);
            set_toast('Design project uploaded successfully');
            redirect('index.php');
        } else {
            $err = 'Failed to upload design. Check file type and size.';
        }
    } elseif ($action === 'upload_website') {
        $path = secure_upload($_FILES['image'], 'websites');
        if ($path) {
            $stmt = $pdo->prepare("INSERT INTO websites (image_path, title, url, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$path, $_POST['title'], $_POST['url'], $_POST['description']]);
            set_toast('Website project uploaded successfully');
            redirect('index.php');
        } else {
            $err = 'Failed to upload website project.';
        }
    } elseif ($action === 'upload_certificate') {
        $path = secure_upload($_FILES['image'], 'certificates');
        if ($path) {
            $stmt = $pdo->prepare("INSERT INTO certificates (image_path, title, description, platform) VALUES (?, ?, ?, ?)");
            $stmt->execute([$path, $_POST['title'], $_POST['description'], $_POST['platform']]);
            set_toast('Certificate uploaded successfully');
            redirect('index.php');
        } else {
            $err = 'Failed to upload certificate.';
        }
    } elseif ($action === 'upload_skill') {
        $path = secure_upload($_FILES['icon'], 'skills');
        if ($path) {
            $stmt = $pdo->prepare("INSERT INTO skills (name, icon_path) VALUES (?, ?)");
            $stmt->execute([$_POST['name'], $path]);
            set_toast('Skill added successfully');
            redirect('index.php');
        } else {
            $err = 'Failed to upload skill icon.';
        }
    } elseif ($action === 'add_knowledge') {
        $stmt = $pdo->prepare("INSERT INTO chatbot_knowledge (keyword, response) VALUES (?, ?)");
        $stmt->execute([$_POST['keyword'], $_POST['response']]);
        set_toast('Knowledge added successfully');
        redirect('index.php');
    } elseif ($action === 'update_api_key') {
        $stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (key_name, key_value) VALUES ('ai_provider', ?)");
        $stmt->execute([$_POST['provider']]);
        $stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (key_name, key_value) VALUES ('ai_api_key', ?)");
        $stmt->execute([$_POST['api_key']]);
        set_toast('AI Settings updated successfully');
        redirect('index.php');
    } elseif ($action === 'upload_partner') {
        $path = secure_upload($_FILES['logo'], 'partners');
        if ($path) {
            $stmt = $pdo->prepare("INSERT INTO partners (name, logo_path) VALUES (?, ?)");
            $stmt->execute([$_POST['name'], $path]);
            set_toast('Partner added successfully');
        }
        redirect('index.php');
    } elseif ($action === 'delete_partner') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("SELECT logo_path FROM partners WHERE id = ?");
        $stmt->execute([$id]);
        $path = $stmt->fetchColumn();
        if ($path) @unlink('../' . $path);

        $stmt = $pdo->prepare("DELETE FROM partners WHERE id = ?");
        $stmt->execute([$id]);
        set_toast('Partner deleted');
        redirect('index.php');
    } elseif ($action === 'upload_testimonial') {
        $path = secure_upload($_FILES['image'], 'testimonials');
        if ($path) {
            $stmt = $pdo->prepare("INSERT INTO testimonials (name, role, text, image_path) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['role'], $_POST['text'], $path]);
            set_toast('Testimonial added successfully');
        } else {
            set_toast('Failed to upload testimonial image.', 'error');
        }
        redirect('index.php');
    } elseif ($action === 'delete_testimonial') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("SELECT image_path FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
        $path = $stmt->fetchColumn();
        if ($path) @unlink('../' . $path);

        $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
        set_toast('Testimonial deleted');
        redirect('index.php');
    } elseif ($action === 'edit') {
        $type = $_POST['type'];
        $id = (int)$_POST['id'];
        $title = $_POST['title'] ?? '';
        $name = $_POST['name'] ?? ''; // for skills
        $subtitle = $_POST['subtitle'] ?? ''; // for banners
        $description = $_POST['description'] ?? ''; // for designs/websites/certificates
        $url = $_POST['url'] ?? ''; // for websites
        $platform = $_POST['platform'] ?? ''; // for certificates

        $table = '';
        if ($type === 'banner') $table = 'banners';
        elseif ($type === 'design') $table = 'designs';
        elseif ($type === 'website') $table = 'websites';
        elseif ($type === 'certificate') $table = 'certificates';
        elseif ($type === 'skill') $table = 'skills';

        if ($table) {
            if ($type === 'skill') {
                $params = [$name];
                $sql = "UPDATE $table SET name = ?";
            } else {
                $params = [$title];
                $sql = "UPDATE $table SET title = ?";
            }
            
            if ($type === 'banner') {
                $sql .= ", subtitle = ?";
                $params[] = $subtitle;
            } elseif ($type === 'design' || $type === 'website' || $type === 'certificate') {
                $sql .= ", description = ?";
                $params[] = $description;
                if ($type === 'website') {
                    $sql .= ", url = ?";
                    $params[] = $url;
                }
                if ($type === 'certificate') {
                    $sql .= ", platform = ?";
                    $params[] = $platform;
                }
            }

            // Handle optional icon/image update
            $file_field = ($type === 'skill') ? 'icon' : 'image';
            if (isset($_FILES[$file_field]) && $_FILES[$file_field]['error'] === UPLOAD_ERR_OK) {
                $path = secure_upload($_FILES[$file_field], $type . 's');
                if ($path) {
                    // Delete old file
                    $stmt = $pdo->prepare("SELECT icon_path, image_path FROM $table WHERE id = ?");
                    $stmt->execute([$id]);
                    $old = $stmt->fetch();
                    $old_path = ($type === 'skill') ? $old['icon_path'] : $old['image_path'];
                    if ($old_path && file_exists(__DIR__ . '/../' . $old_path)) {
                        unlink(__DIR__ . '/../' . $old_path);
                    }
                    if ($type === 'skill') {
                        $sql .= ", icon_path = ?";
                    } else {
                        $sql .= ", image_path = ?";
                    }
                    $params[] = $path;
                }
            }

            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            set_toast('Updated successfully');
            redirect('index.php');
        }
    } elseif ($action === 'change_password') {
        $old = $_POST['old_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        if ($new !== $confirm) {
            $err = 'Passwords do not match';
        } else {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();

            if (password_verify($old, $user['password'])) {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hash, $_SESSION['user_id']]);
                set_toast('Password changed successfully');
                redirect('index.php');
            } else {
                $err = 'Incorrect old password';
            }
        }
    } elseif ($action === 'edit_knowledge') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("UPDATE chatbot_knowledge SET keyword = ?, response = ? WHERE id = ?");
        $stmt->execute([$_POST['keyword'], $_POST['response'], $id]);
        set_toast('Knowledge updated successfully');
        redirect('index.php');
    } elseif ($action === 'delete') {
        $type = $_POST['type'];
        $id = (int)$_POST['id'];
        $table = '';
        if ($type === 'banner') $table = 'banners';
        elseif ($type === 'design') $table = 'designs';
        elseif ($type === 'website') $table = 'websites';
        elseif ($type === 'certificate') $table = 'certificates';
        elseif ($type === 'skill') $table = 'skills';
        elseif ($type === 'knowledge') $table = 'chatbot_knowledge';

        if ($table) {
            // Delete file if applicable
            if ($type !== 'knowledge') {
                $stmt = $pdo->prepare("SELECT icon_path, image_path FROM $table WHERE id = ?");
                $stmt->execute([$id]);
                $item = $stmt->fetch();
                $old_path = ($type === 'skill') ? $item['icon_path'] : $item['image_path'];
                if ($old_path && file_exists(__DIR__ . '/../' . $old_path)) {
                    unlink(__DIR__ . '/../' . $old_path);
                }
            }
            // Delete from DB
            $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
            $stmt->execute([$id]);
            set_toast('Deleted successfully');
            redirect('index.php');
        }
    }
}

// Fetch items
$banners = $pdo->query("SELECT * FROM banners ORDER BY id DESC")->fetchAll();
$designs = $pdo->query("SELECT * FROM designs ORDER BY id DESC")->fetchAll();
$websites = $pdo->query("SELECT * FROM websites ORDER BY id DESC")->fetchAll();
$certificates = $pdo->query("SELECT * FROM certificates ORDER BY id DESC")->fetchAll();
$skills = $pdo->query("SELECT * FROM skills ORDER BY id DESC")->fetchAll();
$knowledge = $pdo->query("SELECT * FROM chatbot_knowledge ORDER BY keyword ASC")->fetchAll();
$partners = $pdo->query("SELECT * FROM partners ORDER BY id DESC")->fetchAll();
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll();

// Get AI Settings
$stmt = $pdo->prepare("SELECT key_value FROM settings WHERE key_name = 'ai_provider'");
$stmt->execute();
$ai_provider = $stmt->fetchColumn() ?: 'gemini';

$stmt = $pdo->prepare("SELECT key_value FROM settings WHERE key_name = 'ai_api_key'");
$stmt->execute();
$ai_api_key = $stmt->fetchColumn();

if (!$ai_api_key) {
    $stmt = $pdo->prepare("SELECT key_value FROM settings WHERE key_name = 'gemini_api_key'");
    $stmt->execute();
    $ai_api_key = $stmt->fetchColumn();
}

$stats = [
    'banners' => count($banners),
    'designs' => count($designs),
    'websites' => count($websites),
    'certificates' => count($certificates),
    'skills' => count($skills),
    'knowledge' => count($knowledge)
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Stormbreaker</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root {
            --admin-bg: #0a0a0a;
            --admin-card: #111111;
            --admin-border: #2a2a2a;
            --admin-primary: #f59e0b;
            --admin-primary-dim: rgba(245, 158, 11, 0.15);
            --admin-text: #e5e5e5;
            --admin-muted: #737373;
            --admin-danger: #ef4444;
            --admin-success: #22c55e;
        }

        body {
            background: var(--admin-bg);
            color: var(--admin-text);
            font-family: 'Inter', system-ui, sans-serif;
            margin: 0;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            background: var(--admin-card);
            padding: 1.5rem 2rem;
            border: 1px solid var(--admin-border);
        }
        .header h1 {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--admin-primary);
        }
        .header span { color: var(--admin-muted); font-size: 0.85rem; }
        .logout-btn {
            color: var(--admin-danger);
            text-decoration: none;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.5rem 1rem;
            border: 1px solid var(--admin-danger);
            transition: 0.2s;
        }
        .logout-btn:hover {
            background: var(--admin-danger);
            color: #fff;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 0;
            margin-bottom: 2.5rem;
            border: 1px solid var(--admin-border);
            overflow-x: auto;
        }
        .tab-btn {
            background: transparent;
            border: none;
            border-right: 1px solid var(--admin-border);
            color: var(--admin-muted);
            padding: 0.85rem 1.25rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .tab-btn:last-child { border-right: none; }
        .tab-btn:hover {
            background: var(--admin-primary-dim);
            color: var(--admin-primary);
        }
        .tab-btn.active {
            background: var(--admin-primary);
            color: #000;
            font-weight: 700;
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Form Card */
        .form-card {
            background: var(--admin-card);
            padding: 2rem;
            border: 1px solid var(--admin-border);
            margin-bottom: 2rem;
        }
        .form-card h3 {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--admin-primary);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--admin-border);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--admin-muted);
            font-size: 0.75rem;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: var(--admin-bg);
            border: 1px solid var(--admin-border);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            transition: border-color 0.2s;
            outline: none;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 1px var(--admin-primary-dim);
        }
        .form-group input[type="file"] {
            padding: 0.5rem;
            font-size: 0.8rem;
        }

        .submit-btn {
            background: var(--admin-primary);
            color: #000;
            border: none;
            padding: 0.75rem 2rem;
            cursor: pointer;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 1.5rem;
            transition: 0.2s;
        }
        .submit-btn:hover {
            background: #fbbf24;
            box-shadow: 4px 4px 0px rgba(245, 158, 11, 0.3);
            transform: translate(-2px, -2px);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--admin-card);
            border: 1px solid var(--admin-border);
            padding: 1.5rem;
            text-align: center;
            transition: 0.2s;
        }
        .stat-card:hover {
            border-color: var(--admin-primary);
            transform: translateY(-2px);
        }
        .stat-card h3 {
            font-size: 0.7rem;
            color: var(--admin-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.75rem;
        }
        .stat-card .value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 2rem;
            font-weight: 700;
            color: var(--admin-primary);
        }

        /* Items Grid */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .item-card {
            background: var(--admin-card);
            overflow: hidden;
            border: 1px solid var(--admin-border);
            transition: 0.2s;
        }
        .item-card:hover {
            border-color: var(--admin-primary);
        }
        .item-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid var(--admin-border);
        }
        .item-info {
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-info h4 {
            margin: 0;
            color: #f8fafc;
            font-size: 0.9rem;
        }

        .delete-btn {
            background: none;
            border: 1px solid var(--admin-danger);
            color: var(--admin-danger);
            cursor: pointer;
            font-size: 0.7rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            padding: 0.3rem 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.2s;
        }
        .delete-btn:hover {
            background: var(--admin-danger);
            color: #fff;
        }
        .edit-btn {
            background: var(--admin-primary-dim);
            color: var(--admin-primary);
            padding: 0.3rem 0.75rem;
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            border: 1px solid rgba(245, 158, 11, 0.3);
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.2s;
        }
        .edit-btn:hover {
            background: var(--admin-primary);
            color: #000;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(4px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-content {
            background: var(--admin-card);
            width: 100%;
            max-width: 600px;
            padding: 2rem;
            border: 1px solid var(--admin-border);
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            border: 1px solid #22c55e;
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid #ef4444;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--admin-bg); }
        ::-webkit-scrollbar-thumb { background: var(--admin-border); }
        ::-webkit-scrollbar-thumb:hover { background: var(--admin-primary); }

        /* Table styling */
        table { border-collapse: collapse; }
        table th {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--admin-muted);
        }
        table td { font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="header">
            <h1>Stormbreaker Admin</h1>
            <div>
                <span>Welcome, <?php echo h($_SESSION['username']); ?></span>
                <a href="logout.php" class="logout-btn" style="margin-left: 1.5rem;">Logout</a>
            </div>
        </header>

        <?php if ($msg): ?> <div class="alert alert-success"><?php echo h($msg); ?></div> <?php endif; ?>
        <?php if ($err): ?> <div class="alert alert-error"><?php echo h($err); ?></div> <?php endif; ?>

        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('dashboard')">Dashboard</button>
            <button class="tab-btn" onclick="showTab('banners')">Banners</button>
            <button class="tab-btn" onclick="showTab('designs')">Designs</button>
            <button class="tab-btn" onclick="showTab('websites')">Websites</button>
            <button class="tab-btn" onclick="showTab('certificates')">Certificates</button>
            <button class="tab-btn" onclick="showTab('partners')">Partners</button>
            <button class="tab-btn" onclick="showTab('testimonials')">Testimonials</button>
            <button class="tab-btn" onclick="showTab('skills')">Skills</button>
            <button class="tab-btn" onclick="showTab('chatbot')">Chatbot</button>
            <button class="tab-btn" onclick="showTab('security')">Security</button>
        </div>

        <?php echo display_toasts(); ?>

        <!-- Dashboard Stats Tab -->
        <div id="dashboard" class="tab-content active">
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Banners</h3>
                    <div class="value"><?php echo $stats['banners']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Design Projects</h3>
                    <div class="value"><?php echo $stats['designs']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Web Projects</h3>
                    <div class="value"><?php echo $stats['websites']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Certificates</h3>
                    <div class="value"><?php echo $stats['certificates']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Skills</h3>
                    <div class="value"><?php echo $stats['skills']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Bot Knowledge</h3>
                    <div class="value"><?php echo $stats['knowledge']; ?></div>
                </div>
            </div>
            <div class="form-card">
                <h3>Welcome to Dashboard</h3>
                <p style="color: var(--text-muted);">Manage your portfolio content from the tabs above. You can add new items, edit existing ones, or remove them.</p>
            </div>
        </div>

        <!-- Banners Tab -->
        <div id="banners" class="tab-content">
            <div class="form-card">
                <h3>Add New Banner</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="upload_banner">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" placeholder="Banner Title">
                        </div>
                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" name="subtitle" placeholder="Banner Subtitle">
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" required accept="image/*" onchange="previewImage(this, 'banner-preview')">
                            <div id="banner-preview" style="margin-top: 10px; display: none;">
                                <img src="" style="width: 100%; height: 100px; object-fit: cover; border-radius: 0.5rem;">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Upload Banner</button>
                </form>
            </div>
            
            <div class="items-grid">
                <?php foreach ($banners as $b): ?>
                <div class="item-card">
                    <img src="../<?php echo h($b['image_path']); ?>" alt="">
                    <div class="item-info">
                        <h4><?php echo h($b['title']); ?></h4>
                        <div style="display: flex; gap: 10px;">
                            <button class="edit-btn" onclick='openEditModal("banner", <?php echo json_encode($b); ?>)'>Edit</button>
                            <form method="POST" onsubmit="return confirm('Are you sure?')">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="type" value="banner">
                                <input type="hidden" name="id" value="<?php echo $b['id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Designs Tab -->
        <div id="designs" class="tab-content">
            <div class="form-card">
                <h3>Add Design Project</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="upload_design">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Project Title</label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" required accept="image/*" onchange="previewImage(this, 'design-preview')">
                            <div id="design-preview" style="margin-top: 10px; display: none;">
                                <img src="" style="width: 100%; height: 100px; object-fit: cover; border-radius: 0.5rem;">
                            </div>
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Description</label>
                            <textarea name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Upload Design</button>
                </form>
            </div>
            <div class="items-grid">
                <?php foreach ($designs as $d): ?>
                <div class="item-card">
                    <img src="../<?php echo h($d['image_path']); ?>" alt="">
                    <div class="item-info">
                        <h4><?php echo h($d['title']); ?></h4>
                        <div style="display: flex; gap: 10px;">
                            <button class="edit-btn" onclick='openEditModal("design", <?php echo json_encode($d); ?>)'>Edit</button>
                            <form method="POST" onsubmit="return confirm('Are you sure?')">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="type" value="design">
                                <input type="hidden" name="id" value="<?php echo $d['id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Websites Tab -->
        <div id="websites" class="tab-content">
            <div class="form-card">
                <h3>Add Website Project</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="upload_website">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Site Title</label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Site URL</label>
                            <input type="url" name="url" placeholder="https://...">
                        </div>
                        <div class="form-group">
                            <label>Thumbnail</label>
                            <input type="file" name="image" required accept="image/*" onchange="previewImage(this, 'web-preview')">
                            <div id="web-preview" style="margin-top: 10px; display: none;">
                                <img src="" style="width: 100%; height: 100px; object-fit: cover; border-radius: 0.5rem;">
                            </div>
                        </div>
                        <div class="form-group" style="grid-column: span 3;">
                            <label>Description</label>
                            <textarea name="description" rows="2"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Upload Website</button>
                </form>
            </div>
            <div class="items-grid">
                <?php foreach ($websites as $w): ?>
                <div class="item-card">
                    <img src="../<?php echo h($w['image_path']); ?>" alt="">
                    <div class="item-info">
                        <h4><?php echo h($w['title']); ?></h4>
                        <div style="display: flex; gap: 10px;">
                            <button class="edit-btn" onclick='openEditModal("website", <?php echo json_encode($w); ?>)'>Edit</button>
                            <form method="POST" onsubmit="return confirm('Are you sure?')">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="type" value="website">
                                <input type="hidden" name="id" value="<?php echo $w['id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Certificates Tab -->
        <div id="certificates" class="tab-content">
            <div class="form-card">
                <h3>Add Certificate</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="upload_certificate">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Certificate Title</label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Platform / Issuer</label>
                            <input type="text" name="platform" placeholder="e.g. Coursera, Udemy" required>
                        </div>
                        <div class="form-group">
                            <label>File (Image/PDF)</label>
                            <input type="file" name="image" required accept="image/*,application/pdf" onchange="previewImage(this, 'cert-preview')">
                            <div id="cert-preview" style="margin-top: 10px; display: none;">
                                <img src="" style="width: 100%; height: 100px; object-fit: cover; border-radius: 0.5rem;">
                            </div>
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Description (Optional)</label>
                            <textarea name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Upload Certificate</button>
                </form>
            </div>
            <div class="items-grid">
                <?php foreach ($certificates as $c): ?>
                <div class="item-card">
                    <?php if (pathinfo($c['image_path'], PATHINFO_EXTENSION) === 'pdf'): ?>
                        <div style="height: 180px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.05); font-size: 2rem; color: #f43f5e;">
                            PDF
                        </div>
                    <?php else: ?>
                        <img src="../<?php echo h($c['image_path']); ?>" alt="">
                    <?php endif; ?>
                    <div class="item-info">
                        <div>
                            <h4><?php echo h($c['title']); ?></h4>
                            <div style="font-size: 0.75rem; color: var(--admin-primary); margin-top: 0.2rem;"><?php echo h($c['platform']); ?></div>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button class="edit-btn" onclick='openEditModal("certificate", <?php echo json_encode($c); ?>)'>Edit</button>
                            <form method="POST" onsubmit="return confirm('Are you sure?')">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="type" value="certificate">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Skills Tab -->
        <div id="skills" class="tab-content">
            <div class="form-card">
                <h3>Add New Skill</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="upload_skill">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Skill Name</label>
                            <input type="text" name="name" required placeholder="e.g. PHP / SQL">
                        </div>
                        <div class="form-group">
                            <label>Icon (Logo)</label>
                            <input type="file" name="icon" required accept="image/*" onchange="previewImage(this, 'skill-preview')">
                            <div id="skill-preview" style="margin-top: 10px; display: none;">
                                <img src="" style="width: 80px; height: 80px; object-fit: contain; background: #fff; padding: 10px; border-radius: 0.5rem;">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Add Skill</button>
                </form>
            </div>
            <div class="items-grid">
                <?php foreach ($skills as $s): ?>
                <div class="item-card">
                    <div style="background: white; padding: 2rem; display: flex; justify-content: center;">
                        <img src="../<?php echo h($s['icon_path']); ?>" alt="" style="width: 60px; height: 60px; object-fit: contain;">
                    </div>
                    <div class="item-info">
                        <h4><?php echo h($s['name']); ?></h4>
                        <div style="display: flex; gap: 10px;">
                            <button class="edit-btn" onclick='openEditModal("skill", <?php echo json_encode($s); ?>)'>Edit</button>
                            <form method="POST" onsubmit="return confirm('Are you sure?')">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="type" value="skill">
                                <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

            </div>
        </div>

        <!-- Partners Tab -->
        <div id="partners" class="tab-content">
            <div class="form-card">
                <h3>Add Payment Gateway / Partner</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="upload_partner">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Partner Name</label>
                            <input type="text" name="name" required placeholder="e.g. Midtrans, Xendit">
                        </div>
                        <div class="form-group">
                            <label>Logo (PNG/SVG preferred)</label>
                            <input type="file" name="logo" required accept="image/*">
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Upload Partner</button>
                </form>
            </div>

            <div class="card-grid" style="margin-top: 2rem;">
                <?php foreach ($partners as $p): ?>
                <div class="stat-card" style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                    <img src="../<?php echo h($p['logo_path']); ?>" style="height: 40px; max-width: 100%; object-fit: contain; filter: grayscale(1) invert(1);">
                    <span><?php echo h($p['name']); ?></span>
                    <form method="POST" onsubmit="return confirm('Delete this partner?');">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" name="action" value="delete_partner">
                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.8rem;">Delete</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Chatbot Tab -->

        <!-- Testimonials Tab -->
        <div id="testimonials" class="tab-content">
            <div class="form-card">
                <h3>Add New Testimonial</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="upload_testimonial">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" required placeholder="e.g. John Doe">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" name="role" required placeholder="e.g. CEO, Designer">
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Testimonial Text</label>
                            <textarea name="text" required rows="3" placeholder="What did they say about your work?"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" name="image" required accept="image/*" onchange="previewImage(this, 'testimonial-preview')">
                            <div id="testimonial-preview" style="margin-top: 10px; display: none;">
                                <img src="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Add Testimonial</button>
                </form>
            </div>

            <div class="items-grid" style="margin-top: 2rem;">
                <?php foreach ($testimonials as $t): ?>
                <div class="stat-card" style="display: flex; gap: 1rem; align-items: flex-start; padding: 1.5rem;">
                    <img src="../<?php echo h($t['image_path']); ?>" style="width: 50px; height: 50px; border-radius: 4px; object-fit: cover; flex-shrink: 0;">
                    <div style="flex: 1; min-width: 0;">
                        <strong style="color: white;"><?php echo h($t['name']); ?></strong>
                        <div style="font-size: 0.75rem; color: #f59e0b; margin-bottom: 0.5rem;"><?php echo h($t['role']); ?></div>
                        <p style="font-size: 0.85rem; color: #94a3b8; line-height: 1.5; margin: 0;"><?php echo h($t['text']); ?></p>
                    </div>
                    <form method="POST" onsubmit="return confirm('Delete this testimonial?');" style="flex-shrink: 0;">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" name="action" value="delete_testimonial">
                        <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.8rem;">Delete</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Chatbot Tab -->
        <div id="chatbot" class="tab-content">
            <div class="form-card">
                <h3>AI Assistant Configuration</h3>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="update_api_key">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>AI Provider</label>
                            <select name="provider" class="form-control" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: white; padding: 0.75rem; border-radius: 0.5rem; outline: none;">
                                <option value="gemini" <?php echo $ai_provider === 'gemini' ? 'selected' : ''; ?>>Google Gemini</option>
                                <option value="nvidia" <?php echo $ai_provider === 'nvidia' ? 'selected' : ''; ?>>NVIDIA NIM (Llama 3)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>API Key</label>
                            <input type="password" name="api_key" value="<?php echo h($ai_api_key); ?>" required placeholder="Enter your API Key">
                        </div>
                    </div>
                    <button type="submit" class="submit-btn" style="margin-top: 1rem;">Update AI Settings</button>
                    <p style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.5rem;">
                        Gemini: <a href="https://aistudio.google.com/app/apikey" target="_blank" style="color: #6366f1;">Google AI Studio</a> | 
                        NVIDIA: <a href="https://build.nvidia.com/explore/discover" target="_blank" style="color: #6366f1;">NVIDIA NIM</a>
                    </p>
                </form>
            </div>

            <div class="form-card" style="margin-top: 2rem;">
                <h3>Add Bot Knowledge (Custom Rules)</h3>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="add_knowledge">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Keyword (Trigger)</label>
                            <input type="text" name="keyword" required placeholder="e.g. harga">
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Bot Response</label>
                            <input type="text" name="response" required placeholder="e.g. Harga jasa kami mulai dari...">
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Save Knowledge</button>
                </form>
            </div>
            <div class="table-card" style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 1rem; margin-top: 2rem;">
                <table style="width: 100%; border-collapse: collapse; color: white;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 1px solid var(--border);">
                            <th style="padding: 1rem;">Keyword</th>
                            <th style="padding: 1rem;">Response</th>
                            <th style="padding: 1rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($knowledge as $k): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 1rem;"><strong><?php echo h($k['keyword']); ?></strong></td>
                            <td style="padding: 1rem;"><?php echo h($k['response']); ?></td>
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 10px;">
                                    <button class="edit-btn" onclick='openKnowledgeModal(<?php echo json_encode($k); ?>)'>Edit</button>
                                    <form method="POST" onsubmit="return confirm('Are you sure?')">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="type" value="knowledge">
                                        <input type="hidden" name="id" value="<?php echo $k['id']; ?>">
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Security Tab -->
        <div id="security" class="tab-content">
            <div class="form-card" style="max-width: 500px; margin: 0 auto;">
                <h3>Change Admin Password</h3>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="change_password">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Current Password</label>
                        <input type="password" name="old_password" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>New Password</label>
                        <input type="password" name="new_password" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="submit-btn" style="width: 100%;">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;">
                <h3 id="modalTitle">Edit Item</h3>
                <button onclick="closeModal()" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1.5rem;">&times;</button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="type" id="editType">
                <input type="hidden" name="id" id="editId">
                
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label id="titleLabel">Title</label>
                    <input type="text" name="title" id="editTitleInput">
                    <input type="text" name="name" id="editNameInput" style="display: none;">
                </div>
                
                <div id="bannerFields" style="display: none;">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Subtitle</label>
                        <input type="text" name="subtitle" id="editSubtitleInput">
                    </div>
                </div>
                
                <div id="commonFields" style="display: none;">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Description</label>
                        <textarea name="description" id="editDescInput" rows="3"></textarea>
                    </div>
                </div>
                
                <div id="websiteFields" style="display: none;">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>URL</label>
                        <input type="url" name="url" id="editUrlInput">
                    </div>
                </div>

                <div id="certFields" style="display: none;">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Platform / Issuer</label>
                        <input type="text" name="platform" id="editPlatformInput">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label id="fileLabel">Change File (Optional)</label>
                    <input type="file" name="image" id="editImageInput" accept="image/*,application/pdf" onchange="previewImage(this, 'edit-preview')">
                    <input type="file" name="icon" id="editIconInput" accept="image/*" onchange="previewImage(this, 'edit-preview')" style="display: none;">
                    <div id="edit-preview" style="margin-top: 10px;">
                        <img id="currentImg" src="" style="width: 100%; height: 120px; object-fit: contain; border-radius: 0.5rem; display: none;">
                        <div id="currentPdf" style="width: 100%; height: 120px; display: none; align-items: center; justify-content: center; background: rgba(255,255,255,0.05); font-size: 1.5rem; color: #f43f5e; border-radius: 0.5rem;">PDF FILE</div>
                    </div>
                </div>

                <button type="submit" class="submit-btn" style="width: 100%; margin: 0;">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Knowledge Edit Modal -->
    <div id="knowledgeModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <h3>Edit Bot Knowledge</h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="action" value="edit_knowledge">
                <input type="hidden" name="id" id="editKnowledgeId">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Keyword</label>
                    <input type="text" name="keyword" id="editKeyword" required>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label>Response</label>
                    <input type="text" name="response" id="editResponse" required>
                </div>
                <button type="submit" class="submit-btn" style="width: 100%; margin-bottom: 0.5rem;">Update Knowledge</button>
                <button type="button" class="submit-btn" style="background: #374151; width: 100%;" onclick="closeModal('knowledgeModal')">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const img = preview.querySelector('img');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openEditModal(type, data) {
            const modal = document.getElementById('editModal');
            document.getElementById('editType').value = type;
            document.getElementById('editId').value = data.id;
            
            // Adjust Labels and Inputs
            const titleLabel = document.getElementById('titleLabel');
            const editTitleInput = document.getElementById('editTitleInput');
            const editNameInput = document.getElementById('editNameInput');
            const fileLabel = document.getElementById('fileLabel');
            const editImageInput = document.getElementById('editImageInput');
            const editIconInput = document.getElementById('editIconInput');
            const currentImg = document.getElementById('currentImg');
            const currentPdf = document.getElementById('currentPdf');

            if (type === 'skill') {
                titleLabel.innerText = 'Skill Name';
                editTitleInput.style.display = 'none';
                editNameInput.style.display = 'block';
                editNameInput.value = data.name;
                fileLabel.innerText = 'Change Icon (Optional)';
                editImageInput.style.display = 'none';
                editIconInput.style.display = 'block';
                currentImg.src = '../' + data.icon_path;
                currentImg.style.display = 'block';
                currentPdf.style.display = 'none';
                currentImg.style.objectFit = 'contain';
                currentImg.parentElement.style.background = '#fff';
            } else {
                titleLabel.innerText = 'Title';
                editTitleInput.style.display = 'block';
                editNameInput.style.display = 'none';
                editTitleInput.value = data.title;
                fileLabel.innerText = 'Change File (Optional)';
                editImageInput.style.display = 'block';
                editIconInput.style.display = 'none';
                
                if (data.image_path.toLowerCase().endsWith('.pdf')) {
                    currentImg.style.display = 'none';
                    currentPdf.style.display = 'flex';
                } else {
                    currentImg.src = '../' + data.image_path;
                    currentImg.style.display = 'block';
                    currentPdf.style.display = 'none';
                    currentImg.style.objectFit = 'cover';
                    currentImg.parentElement.style.background = 'transparent';
                }
            }
            
            // Show/Hide fields based on type
            document.getElementById('bannerFields').style.display = (type === 'banner') ? 'block' : 'none';
            document.getElementById('commonFields').style.display = (type === 'design' || type === 'website' || type === 'certificate') ? 'block' : 'none';
            document.getElementById('websiteFields').style.display = (type === 'website') ? 'block' : 'none';
            document.getElementById('certFields').style.display = (type === 'certificate') ? 'block' : 'none';
            
            if (type === 'banner') document.getElementById('editSubtitleInput').value = data.subtitle;
            if (type === 'design' || type === 'website' || type === 'certificate') document.getElementById('editDescInput').value = data.description;
            if (type === 'website') document.getElementById('editUrlInput').value = data.url;
            if (type === 'certificate') document.getElementById('editPlatformInput').value = data.platform;

            modal.style.display = 'flex';
        }

        function openKnowledgeModal(data) {
            const modal = document.getElementById('knowledgeModal');
            document.getElementById('editKnowledgeId').value = data.id;
            document.getElementById('editKeyword').value = data.keyword;
            document.getElementById('editResponse').value = data.response;
            modal.style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id || 'editModal').style.display = 'none';
        }

        // Close on click outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
