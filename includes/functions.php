<?php
require_once __DIR__ . '/../config/database.php';

// ============================================
// HELPER FUNCTIONS
// ============================================

// Generate URL with base path
function url($path = '')
{
    if (empty($path)) {
        return BASE_URL . '/';
    }
    // If path starts with http:// or https://, return as is
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    return BASE_URL . '/' . ltrim($path, '/');
}

// Generate asset URL
function asset($path)
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

// Generate slug from string
function generate_slug($string)
{
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

// Sanitize input
function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Time ago function
function time_ago($timestamp)
{
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;

    $seconds = $time_difference;
    $minutes = round($seconds / 60);
    $hours = round($seconds / 3600);
    $days = round($seconds / 86400);
    $weeks = round($seconds / 604800);
    $months = round($seconds / 2629440);
    $years = round($seconds / 31553280);

    if ($seconds <= 60) {
        return "Just now";
    } else if ($minutes <= 60) {
        return $minutes == 1 ? "1 minute ago" : "$minutes minutes ago";
    } else if ($hours <= 24) {
        return $hours == 1 ? "1 hour ago" : "$hours hours ago";
    } else if ($days <= 7) {
        return $days == 1 ? "Yesterday" : "$days days ago";
    } else if ($weeks <= 4.3) {
        return $weeks == 1 ? "1 week ago" : "$weeks weeks ago";
    } else if ($months <= 12) {
        return $months == 1 ? "1 month ago" : "$months months ago";
    } else {
        return $years == 1 ? "1 year ago" : "$years years ago";
    }
}

// Format date
function format_date($date, $format = 'F j, Y')
{
    return date($format, strtotime($date));
}

// Get article display date
function get_article_display_date($article)
{
    if ($article['use_custom_date'] == 1 && !empty($article['custom_date'])) {
        return format_date($article['custom_date']);
    } else {
        return time_ago($article['created_at']);
    }
}

// Truncate text
function truncate_text($text, $length = 150)
{
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// Upload image
function upload_image($file, $folder = 'articles')
{
    $upload_dir = __DIR__ . '/../assets/uploads/' . $folder . '/';

    // Create directory if not exists
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }

    if ($file['size'] > 5000000) { // 5MB
        return ['success' => false, 'message' => 'File too large'];
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => 'assets/uploads/' . $folder . '/' . $filename];
    }

    return ['success' => false, 'message' => 'Upload failed'];
}

// Extract YouTube ID
function extract_youtube_id($url)
{
    if (empty($url)) {
        return false;
    }
    
    // Remove whitespace
    $url = trim($url);
    
    // Pattern 1: youtube.com/watch?v=VIDEO_ID
    if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return $matches[1];
    }
    
    // Pattern 2: youtu.be/VIDEO_ID
    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return $matches[1];
    }
    
    // Pattern 3: youtube.com/embed/VIDEO_ID
    if (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return $matches[1];
    }
    
    // Pattern 4: youtube.com/v/VIDEO_ID
    if (preg_match('/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return $matches[1];
    }
    
    // Pattern 5: youtube.com/live/VIDEO_ID (for live streams)
    if (preg_match('/youtube\.com\/live\/([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return $matches[1];
    }
    
    // Pattern 6: If it's just the video ID (11 characters)
    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
        return $url;
    }
    
    return false;
}

// Extract Vimeo ID
function extract_vimeo_id($url)
{
    preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
    return isset($matches[1]) ? $matches[1] : false;
}

// ============================================
// CATEGORY FUNCTIONS
// ============================================

function get_categories()
{
    $sql = "SELECT * FROM categories ORDER BY order_num ASC, name ASC";
    return db_fetch_all($sql);
}

function get_category_by_slug($slug)
{
    $sql = "SELECT * FROM categories WHERE slug = ?";
    return db_fetch_one($sql, [$slug]);
}

function get_category_by_id($id)
{
    $sql = "SELECT * FROM categories WHERE id = ?";
    return db_fetch_one($sql, [$id]);
}

// ============================================
// STATE FUNCTIONS
// ============================================

function get_states()
{
    $sql = "SELECT * FROM states ORDER BY order_num ASC, name ASC";
    return db_fetch_all($sql);
}

function get_state_by_slug($slug)
{
    $sql = "SELECT * FROM states WHERE slug = ?";
    return db_fetch_one($sql, [$slug]);
}

function get_article_states($article_id)
{
    $sql = "SELECT s.* FROM states s 
            INNER JOIN article_states ast ON s.id = ast.state_id 
            WHERE ast.article_id = ?";
    return db_fetch_all($sql, [$article_id]);
}

// ============================================
// ARTICLE FUNCTIONS
// ============================================

function get_articles($limit = 10, $offset = 0, $category_id = null, $state_id = null, $status = 'published')
{
    $sql = "SELECT a.*, c.name as category_name, c.slug as category_slug 
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.status = ?";

    $params = [$status];

    if ($category_id) {
        $sql .= " AND a.category_id = ?";
        $params[] = $category_id;
    }

    if ($state_id) {
        $sql .= " AND a.id IN (SELECT article_id FROM article_states WHERE state_id = ?)";
        $params[] = $state_id;
    }

    $sql .= " ORDER BY a.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    return db_fetch_all($sql, $params);
}

function get_article_by_slug($slug)
{
    $sql = "SELECT a.*, c.name as category_name, c.slug as category_slug 
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.slug = ? AND a.status = 'published'";
    return db_fetch_one($sql, [$slug]);
}

function get_article_by_id($id)
{
    $sql = "SELECT a.*, c.name as category_name 
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.id = ?";
    return db_fetch_one($sql, [$id]);
}

function get_related_articles($category_id, $exclude_id, $limit = 3)
{
    $sql = "SELECT a.*, c.name as category_name, c.slug as category_slug 
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.category_id = ? AND a.id != ? AND a.status = 'published' 
            ORDER BY a.created_at DESC LIMIT ?";
    return db_fetch_all($sql, [$category_id, $exclude_id, $limit]);
}

function increment_article_views($id)
{
    $sql = "UPDATE articles SET views = views + 1 WHERE id = ?";
    return db_execute($sql, [$id]);
}

function count_articles($category_id = null, $state_id = null)
{
    $sql = "SELECT COUNT(*) as total FROM articles WHERE status = 'published'";
    $params = [];

    if ($category_id) {
        $sql .= " AND category_id = ?";
        $params[] = $category_id;
    }

    if ($state_id) {
        $sql .= " AND id IN (SELECT article_id FROM article_states WHERE state_id = ?)";
        $params[] = $state_id;
    }

    $result = db_fetch_one($sql, $params);
    return $result['total'];
}

// ============================================
// SETTINGS FUNCTIONS
// ============================================

function get_settings()
{
    $sql = "SELECT * FROM settings LIMIT 1";
    $settings = db_fetch_one($sql);

    if (!$settings) {
        // Create default settings if not exists
        $sql = "INSERT INTO settings (site_name) VALUES ('News Website')";
        db_execute($sql);
        return get_settings();
    }

    return $settings;
}

// ============================================
// ADMIN SESSION FUNCTIONS
// ============================================

function is_logged_in()
{
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function get_current_admin()
{
    if (!is_logged_in()) {
        return null;
    }

    $sql = "SELECT id, username, email FROM users WHERE id = ?";
    return db_fetch_one($sql, [$_SESSION['admin_id']]);
}
?>