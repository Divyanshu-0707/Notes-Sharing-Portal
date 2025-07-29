<?php
session_start();
require 'db_config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die('You are not logged in.');
}

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid request.');
}

$note_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// First, get the filename to delete the file
$stmt = $conn->prepare("SELECT filename FROM notes WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $note_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($note = $result->fetch_assoc()) {
    $filename = $note['filename'];
    
    // Attempt to delete the file
    $filepath = __DIR__ . '/uploads/' . rawurlencode($username) . '/' . $filename;
    if (file_exists($filepath)) {
        unlink($filepath);
    }

    // Now, delete the record from the database
    $stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $note_id, $user_id);
    $stmt->execute();

    // Redirect back to the dashboard
    header('Location: index.php');
    exit;

} else {
    // Note not found or does not belong to the user
    http_response_code(404);
    die('Note not found or you do not have permission to delete it.');
}

$stmt->close();
$conn->close();