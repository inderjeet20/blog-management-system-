<?php
require_once 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : '';
$dateOrder = isset($_GET['date_order']) && in_array(strtoupper($_GET['date_order']), ['ASC', 'DESC']) ? strtoupper($_GET['date_order']) : 'DESC';

$query = "SELECT blogs.*, categories.name as category_name FROM blogs 
          LEFT JOIN categories ON blogs.category_id = categories.id WHERE 1=1";
$params = [];

if ($search !== '') {
    $query .= " AND (title LIKE ? OR content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category !== 0 && $category !== '') {
    $query .= " AND category_id = ?";
    $params[] = $category;
}

$query .= " ORDER BY created_at $dateOrder";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($blogs) > 0) {
    foreach ($blogs as $blog) {
        echo '<div class="blog-card">';
        if($blog['image']) {
            echo '<img src="uploads/'.htmlspecialchars($blog['image']).'" alt="Blog Image">';
        }
        echo '<div class="blog-content">';
        echo '<span class="category-badge">'.htmlspecialchars($blog['category_name']).'</span>';
        echo '<h2>'.htmlspecialchars($blog['title']).'</h2>';
        echo '<p class="date">'.date('d M Y', strtotime($blog['created_at'])).'</p>';
        echo '<p>'.htmlspecialchars($blog['short_description']).'</p>';
        echo '<a href="blog.php?id='.$blog['id'].'" class="read-more">Read More</a>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p>No blogs found.</p>';
}
?>