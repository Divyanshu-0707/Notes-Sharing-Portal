<?php
require 'db_config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];
// fetch filename
$res = mysqli_query($conn, "SELECT filename FROM notes WHERE id = $id");
if ($res && $row = mysqli_fetch_assoc($res)) {
    $file = __DIR__ . '/uploads/' . $row['filename'];
    if (file_exists($file)) unlink($file);
    mysqli_query($conn, "DELETE FROM notes WHERE id = $id");
}

header('Location: index.php?deleted=1');
exit;
