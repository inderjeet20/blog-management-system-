<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $short_desc = trim($_POST['short_description']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'];
    
    // Image Upload
    $image = '';
    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
    }

    $stmt = $pdo->prepare("INSERT INTO blogs (title, short_description, content, category_id, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $short_desc, $content, $category_id, $image]);
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Blog</title>
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
        <h2>Add New Blog</h2>
        <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Short Description</label>
                <textarea name="short_description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="8" required></textarea>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn">Save Blog</button>
            <a href="index.php" class="btn" style="background:#555;">Cancel</a>
        </form>
    </div>
</body>
</html>