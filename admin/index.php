<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT blogs.*, categories.name as category_name FROM blogs LEFT JOIN categories ON blogs.category_id = categories.id ORDER BY created_at DESC");
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Dashboard</h1>
            <nav>
                <a href="../index.php" target="_blank">View Site</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container" style="margin-top: 20px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Manage Blogs</h2>
            <a href="add.php" class="btn">Add New Blog</a>
        </div>
        
        <div style="background: #fff; padding: 20px; border-radius: 5px; margin-top: 20px; overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($blogs as $blog): ?>
                    <tr>
                        <td><?= $blog['id'] ?></td>
                        <td><?= htmlspecialchars($blog['title']) ?></td>
                        <td><?= htmlspecialchars($blog['category_name']) ?></td>
                        <td><?= date('d M Y', strtotime($blog['created_at'])) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $blog['id'] ?>" class="btn" style="padding: 5px 10px;">Edit</a>
                            <a href="delete.php?id=<?= $blog['id'] ?>" class="btn btn-danger" style="padding: 5px 10px;" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(count($blogs) == 0): ?>
                    <tr><td colspan="5">No blogs found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>