<?php
require_once 'db.php';
// Fetch categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <div class="container main-content">
        <aside class="sidebar">
            <h3>Filters</h3>
            <input type="text" id="search" placeholder="Search blogs...">
            
            <h4>Categories</h4>
            <select id="categoryFilter">
                <option value="">All Categories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <h4>Sort By Date</h4>
            <select id="dateFilter">
                <option value="DESC">Newest First</option>
                <option value="ASC">Oldest First</option>
            </select>
        </aside>

        <main class="blogs-list" id="blogsContainer">
            <!-- Blogs will be loaded here via AJAX -->
        </main>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>