<?php
session_start();
require 'db_config.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die('You are not logged in.');
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid request.');
}

$note_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT filename FROM notes WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $note_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($note = $result->fetch_assoc()) {
    $filename = $note['filename'];
    
    $filepath = __DIR__ . '/uploads/' . rawurlencode($username) . '/' . $filename;
    if (file_exists($filepath)) {
        unlink($filepath);
    }

    $stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $note_id, $user_id);
    $stmt->execute();

    header('Location: index.php');
    exit;

} else {
    http_response_code(404);
    die('Note not found or you do not have permission to delete it.');
}

$stmt->close();
$conn->close();