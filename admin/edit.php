<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];

// Get Categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get Blog
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->execute([$id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    die("Blog not found");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $short_desc = trim($_POST['short_description']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'];
    
    // Image Upload
    $image = $blog['image'];
    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
    }

    $stmt = $pdo->prepare("UPDATE blogs SET title = ?, short_description = ?, content = ?, category_id = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $short_desc, $content, $category_id, $image, $id]);
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Blog</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Dashboard</h1>
            <nav>
                <a href="index.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container" style="max-width: 800px; margin-top: 20px; background: #fff; padding: 20px; border-radius: 5px;">
        <h2>Edit Blog</h2>
        <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($blog['title']) ?>" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $blog['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Short Description</label>
                <textarea name="short_description" class="form-control" rows="3" required><?= htmlspecialchars($blog['short_description']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="8" required><?= htmlspecialchars($blog['content']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Current Image</label><br>
                <?php if($blog['image']): ?>
                    <img src="../uploads/<?= htmlspecialchars($blog['image']) ?>" width="150" alt="">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Change Image (optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn">Update Blog</button>
            <a href="index.php" class="btn" style="background:#555;">Cancel</a>
        </form>
    </div>
</body>
</html>