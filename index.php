<?php
require 'db_config.php';

// Handle upload when form POSTS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['noteFile']) || $_FILES['noteFile']['error'] !== UPLOAD_ERR_OK) {
        die('File upload error.');
    }
    $info = pathinfo($_FILES['noteFile']['name']);
    if (strtolower($info['extension']) !== 'pdf') {
        die('Only PDF files are allowed.');
    }
    $uploadsDir = __DIR__ . '/uploads';
    if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
    $newName = time() . '_' . uniqid() . '.pdf';
    if (!move_uploaded_file($_FILES['noteFile']['tmp_name'], "$uploadsDir/$newName")) {
        die('Failed to move uploaded file.');
    }
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc  = mysqli_real_escape_string($conn, $_POST['description']);
    $sql = "INSERT INTO notes (title, description, filename)
            VALUES ('$title', '$desc', '$newName')";
    if (!mysqli_query($conn, $sql)) {
        die('DB insert error: ' . mysqli_error($conn));
    }
    header('Location: index.php');
    exit;
}

// Fetch notes for display
$result = mysqli_query($conn, "SELECT * FROM notes ORDER BY uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Notes Sharing Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
  <div class="app-container">
    <nav class="topnav">
      <img src="images/logo.png" alt="Logo" class="logo">
      <ul>
        <li class="nav-item active" data-target="homeView">
          <i class="fa-solid fa-grid"></i><span>Dashboard</span>
        </li>
        <li class="nav-item" data-target="uploadView">
          <i class="fa-solid fa-upload"></i><span>Upload</span>
        </li>
      </ul>
    </nav>

    <main class="main-content">
      <section id="homeView" class="view active">
        <div class="page-header"><h1>All Notes</h1></div>
        <div class="dashboard-grid">
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <div class="note-card position-relative">
                <div class="note-card-header d-flex justify-content-between align-items-center">
                  <span class="note-title"><?= htmlspecialchars($row['title']) ?></span>
                  <div class="dropdown">
                    <button class="btn btn-sm btn-transparent p-0 text-white"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item text-danger"
                           href="delete.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('Delete this note?');">
                          <i class="fa-solid fa-trash"></i> Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <p class="note-desc"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                <div class="note-footer d-flex justify-content-between align-items-center">
                  <span class="note-date"><?= date('d M Y', strtotime($row['uploaded_at'])) ?></span>
                  <a href="uploads/<?= rawurlencode($row['filename']) ?>"
                     class="btn-ghost" download>
                    <i class="fa-solid fa-download"></i>
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p class="text-secondary">No notes uploaded yet.</p>
          <?php endif; ?>
        </div>
      </section>

      <section id="uploadView" class="view">
        <div class="page-header"><h1>Upload a Note</h1></div>
        <div class="upload-panel">
          <form id="uploadForm" action="" method="post" enctype="multipart/form-data">
            <div class="file-group">
              <input type="file" id="noteFile" name="noteFile"
                     accept="application/pdf" required>
              <label for="noteFile" class="file-label">
                <i class="fa-solid fa-file-upload"></i> Choose PDF
              </label>
              <span class="file-selected">No file chosen</span>
            </div>
            <div class="form-group">
              <label for="noteTitle">Title</label>
              <input type="text" id="noteTitle" name="title"
                     placeholder="Enter note title" required>
            </div>
            <div class="form-group">
              <label for="noteDesc">Description</label>
              <textarea id="noteDesc" name="description" rows="4"
                        placeholder="Enter a brief description" required></textarea>
            </div>
            <button type="submit" class="btn-gradient">
              <i class="fa-solid fa-paper-plane"></i> Upload
            </button>
          </form>
        </div>
      </section>
    </main>
  </div>

  <script src="scripts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
