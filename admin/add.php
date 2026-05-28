<?php
session_start();
require_once '../db.php';
require_once 'upload_utils.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$title = '';
$short_desc = '';
$content = '';
$category_id = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $short_desc = trim($_POST['short_description']);
    $content = trim($_POST['content']);
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;

    if ($title === '' || $short_desc === '' || $content === '') {
        $error = 'Title, short description, and content are required.';
    } elseif ($category_id <= 0) {
        $error = 'Please select a valid category.';
    }

    $image = '';
    if ($error === '') {
        $image = handle_image_upload($_FILES['image'], false, '../uploads/', $error);
    }

    if ($error === '') {
        $stmt = $pdo->prepare("INSERT INTO blogs (title, short_description, content, category_id, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $short_desc, $content, $category_id, $image]);
        
        header("Location: index.php");
        exit;
    }
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
        <?php if ($error): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (string)$category_id === (string)$cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Short Description</label>
                <textarea name="short_description" class="form-control" rows="3" required><?= htmlspecialchars($short_desc) ?></textarea>
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="8" required><?= htmlspecialchars($content) ?></textarea>
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