<?php
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['noteFile']) || $_FILES['noteFile']['error'] !== UPLOAD_ERR_OK) {
        die('File upload error.');
    }
    $info = pathinfo($_FILES['noteFile']['name']);
    if (strtolower($info['extension']) !== 'pdf') {
        die('Only PDF files are allowed.');
    }
    $dir = __DIR__ . '/uploads';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $newName = time() . '_' . uniqid() . '.pdf';
    if (!move_uploaded_file($_FILES['noteFile']['tmp_name'], "$dir/$newName")) {
        die('Failed to move file.');
    }
    $title = $conn->real_escape_string($_POST['title']);
    $desc  = $conn->real_escape_string($_POST['description']);
    $stmt = $conn->prepare(
      "INSERT INTO notes (title, description, filename) VALUES (?, ?, ?)"
    );
    $stmt->bind_param('sss', $title, $desc, $newName);
    $stmt->execute();
    $stmt->close();
    header('Location: index.html');
    exit;
}
