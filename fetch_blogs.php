<?php
require_once 'db.php';

function truncate_text($text, $limit = 180) {
    $text = trim($text);
    if ($text === '' || strlen($text) <= $limit) {
        return $text;
    }
    $truncated = substr($text, 0, $limit);
    $lastSpace = strrpos($truncated, ' ');
    if ($lastSpace !== false) {
        $truncated = substr($truncated, 0, $lastSpace);
    }
    return $truncated . '...';
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$dateOrder = isset($_GET['date_order']) && in_array(strtoupper($_GET['date_order']), ['ASC', 'DESC']) ? strtoupper($_GET['date_order']) : 'DESC';
$fromDate = isset($_GET['from_date']) ? trim($_GET['from_date']) : '';
$toDate = isset($_GET['to_date']) ? trim($_GET['to_date']) : '';

$query = "SELECT blogs.*, categories.name as category_name FROM blogs 
          LEFT JOIN categories ON blogs.category_id = categories.id WHERE 1=1";
$params = [];

if ($search !== '') {
    $query .= " AND (title LIKE ? OR short_description LIKE ? OR content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category > 0) {
    $query .= " AND category_id = ?";
    $params[] = $category;
}

if ($fromDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)) {
    $query .= " AND DATE(created_at) >= ?";
    $params[] = $fromDate;
}

if ($toDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {
    $query .= " AND DATE(created_at) <= ?";
    $params[] = $toDate;
}

$query .= " ORDER BY created_at $dateOrder";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($blogs) > 0) {
    foreach ($blogs as $blog) {
        $categoryName = $blog['category_name'] ? $blog['category_name'] : 'Uncategorized';
        $contentPreview = truncate_text($blog['content']);

        echo '<div class="blog-card">';
        if ($blog['image']) {
            echo '<img src="uploads/'.htmlspecialchars($blog['image']).'" alt="Blog Image">';
        }
        echo '<div class="blog-content">';
        echo '<span class="category-badge">'.htmlspecialchars($categoryName).'</span>';
        echo '<h2>'.htmlspecialchars($blog['title']).'</h2>';
        echo '<p class="date">'.date('d M Y', strtotime($blog['created_at'])).'</p>';
        echo '<p class="excerpt">'.htmlspecialchars($blog['short_description']).'</p>';
        if ($contentPreview !== '') {
            echo '<p class="content-preview">'.htmlspecialchars($contentPreview).'</p>';
        }
        echo '<a href="blog.php?id='.$blog['id'].'" class="read-more">Read More</a>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p class="empty-state">No blogs found.</p>';
}
?>