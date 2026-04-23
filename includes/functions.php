<?php
session_start();

function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

/**
 * Toast Notifications
 */
function set_toast($message, $type = 'success') {
    $_SESSION['toasts'][] = ['message' => $message, 'type' => $type];
}

function display_toasts() {
    if (!isset($_SESSION['toasts'])) return '';
    
    $output = '<div class="toast-container">';
    foreach ($_SESSION['toasts'] as $toast) {
        $type = h($toast['type']);
        $msg = h($toast['message']);
        $output .= "<div class='toast $type'>$msg</div>";
    }
    $output .= '</div>';
    
    unset($_SESSION['toasts']);
    return $output;
}

/**
 * Secure file upload handler
 */
function secure_upload($file, $folder) {
    if ($file['error'] !== UPLOAD_ERR_OK) return false;

    $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'application/pdf'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if ($file['size'] > $max_size) return false;

    // Verify MIME type properly
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, $allowed_types)) return false;

    // Generate unique name
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_name = bin2hex(random_bytes(16)) . '.' . $ext;
    
    $target_dir = __DIR__ . '/../uploads/' . $folder . '/';
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $target_path = $target_dir . $new_name;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return 'uploads/' . $folder . '/' . $new_name;
    }

    return false;
}

// CSRF Protection
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
