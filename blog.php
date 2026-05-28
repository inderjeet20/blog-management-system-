<?php
require_once 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT blogs.*, categories.name as category_name FROM blogs LEFT JOIN categories ON blogs.category_id = categories.id WHERE blogs.id = ?");
$stmt->execute([$id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    die("Blog not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($blog['title']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Blog Management System</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="admin/login.php">Admin Login</a>
            </nav>
        </div>
    </header>

    <div class="container blog-detail">
        <h1><?= htmlspecialchars($blog['title']) ?></h1>
        <p class="meta">Category: <?= htmlspecialchars($blog['category_name']) ?> | Date: <?= date('d M Y', strtotime($blog['created_at'])) ?></p>
        
        <?php if ($blog['image']): ?>
            <img class="detail-image" src="uploads/<?= htmlspecialchars($blog['image']) ?>" alt="Blog Image">
        <?php endif; ?>

        <div class="content">
            <?= nl2br(htmlspecialchars($blog['content'])) ?>
        </div>
        
        <a href="index.php" class="back-link">&larr; Back to Home</a>
    </div>
</body>
</html>