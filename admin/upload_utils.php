<?php
function handle_image_upload(array $file, $required, $uploadDir, &$error) {
    if (!isset($file['error'])) {
        $error = 'Image upload failed.';
        return '';
    }

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        if ($required) {
            $error = 'Please upload an image.';
        }
        return '';
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Image upload failed.';
        return '';
    }

    $maxSize = 2 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        $error = 'Image size must be 2MB or less.';
        return '';
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowedExt, true)) {
        $error = 'Only JPG, PNG, GIF, or WEBP images are allowed.';
        return '';
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mime, $allowedMime, true)) {
        $error = 'Invalid image file.';
        return '';
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = bin2hex(random_bytes(8)) . '.' . $ext;
    $targetPath = rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        $error = 'Failed to save uploaded image.';
        return '';
    }

    return $fileName;
}
