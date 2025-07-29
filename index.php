<?php
session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';


require 'db_config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_FILES['noteFile']) || $_FILES['noteFile']['error'] !== UPLOAD_ERR_OK) {
        die('File upload error.');
    }

    
    $info = pathinfo($_FILES['noteFile']['name']);
    if (strtolower($info['extension']) !== 'pdf') {
        die('Only PDF files are allowed.');
    }

    // Create the 'uploads' directory if it doesn't exist
    $uploadsDir = __DIR__ . '/uploads';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }

    
    $newName = time() . '_' . uniqid() . '.pdf';
    if (!move_uploaded_file($_FILES['noteFile']['tmp_name'], "$uploadsDir/$newName")) {
        die('Failed to move uploaded file.');
    }

   
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc  = mysqli_real_escape_string($conn, $_POST['description']);
    $sql = "INSERT INTO notes (title, description, filename) VALUES ('$title', '$desc', '$newName')";
    if (!mysqli_query($conn, $sql)) {
        die('Database insert error: ' . mysqli_error($conn));
    }

    
    header('Location: index.php');
    exit;
}


$result = mysqli_query($conn, "SELECT * FROM notes ORDER BY uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notes Sharing Portal</title>
  
  <link rel="stylesheet" href="styles.css">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
 
  <div class="app-container">
   
    <nav class="topnav">
      <div class="logo-container">
        
        <img src="images/logo.png" alt="Logo" class="logo">
      </div>
      <ul class="menu-items">
        
        <li class="nav-item active" data-target="homeView">
          <i class="fa-solid fa-table-columns"></i><span>Dashboard</span>
        </li>
        
        <li class="nav-item" data-target="uploadView">
          <i class="fa-solid fa-upload"></i><span>Upload</span>
        </li>
      </ul>
    </nav>

   
    <main class="main-content">
      
      <section id="homeView" class="view active">
        <div class="page-header">
            <div class="header-text">
                <h1>Hi, <?= htmlspecialchars($username) ?>!</h1>
                <p>Welcome, your dashboard is ready.</p>
            </div>
            <div class="header-actions">
                <button class="btn-ghost"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>
        <div class="dashboard-grid">
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              
              <div class="note-card">
                <div class="note-card-header">
                  <span class="note-title"><?= htmlspecialchars($row['title']) ?></span>
                </div>
                <p class="note-desc"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                <div class="note-footer">
                  <span class="note-date"><?= date('d M Y', strtotime($row['uploaded_at'])) ?></span>
                  <div class="note-actions">
                     
                    <a href="uploads/<?= rawurlencode($row['filename']) ?>" class="btn-ghost" download>
                      <i class="fa-solid fa-download"></i>
                    </a>
                    
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn-ghost btn-delete" onclick="return confirm('Are you sure you want to delete this note?');">
                      <i class="fa-solid fa-trash"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p class="no-notes-message">No notes have been uploaded yet.</p>
          <?php endif; ?>
        </div>
      </section>

      
      <section id="uploadView" class="view">
        <div class="page-header">
          <h1>Upload a Note</h1>
        </div>
        <div class="upload-panel">
          <p>Share your knowledge by uploading notes. Please ensure the file is in PDF format.</p>
          <form id="uploadForm" action="index.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="noteTitle">Title</label>
              <input type="text" id="noteTitle" name="title" placeholder="Enter note title" required>
            </div>
            <div class="form-group">
              <label for="noteDesc">Description</label>
              <textarea id="noteDesc" name="description" rows="4" placeholder="Enter a brief description" required></textarea>
            </div>
             <div class="file-group">
              <label for="noteFile" class="file-label">
                <i class="fa-solid fa-file-arrow-up"></i> Choose PDF
              </label>
              <input type="file" id="noteFile" name="noteFile" accept="application/pdf" required>
              <span class="file-selected">No file chosen</span>
            </div>
            <button type="submit" class="btn-primary">
              <i class="fa-solid fa-paper-plane"></i> Upload Note
            </button>
          </form>
        </div>
      </section>
    </main>
  </div>

  <script src="scripts.js"></script>
</body>
</html>