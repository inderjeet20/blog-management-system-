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

            <label for="search">Search</label>
            <input type="text" id="search" placeholder="Search title, description, or content">
            
            <label for="categoryFilter">Category</label>
            <select id="categoryFilter">
                <option value="">All Categories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="dateFilter">Sort By Date</label>
            <select id="dateFilter">
                <option value="DESC">Newest First</option>
                <option value="ASC">Oldest First</option>
            </select>

            <div class="filter-group">
                <label for="fromDate">From Date</label>
                <input type="date" id="fromDate">
            </div>
            <div class="filter-group">
                <label for="toDate">To Date</label>
                <input type="date" id="toDate">
            </div>

            <button type="button" id="clearFilters" class="btn btn-secondary">Clear Filters</button>
        </aside>

        <main class="blogs-list" id="blogsContainer">
            <!-- Blogs will be loaded here via AJAX -->
        </main>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>