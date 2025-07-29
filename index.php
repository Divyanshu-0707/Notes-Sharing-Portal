<?php
session_start();
require 'db_config.php';

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user info from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// --- Handle Form Submission for Note Upload ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['noteFile'])) {
    // Check for file upload errors
    if ($_FILES['noteFile']['error'] !== UPLOAD_ERR_OK) {
        die('File upload error: ' . $_FILES['noteFile']['error']);
    }

    // Validate that the file is a PDF
    $info = pathinfo($_FILES['noteFile']['name']);
    if (strtolower($info['extension']) !== 'pdf') {
        die('Only PDF files are allowed.');
    }

    // Create user-specific directory 'uploads/<username>' if it doesn't exist
    $userUploadsDir = __DIR__ . '/uploads/' . rawurlencode($username);
    if (!is_dir($userUploadsDir)) {
        mkdir($userUploadsDir, 0755, true);
    }

    // Generate a unique filename
    $newName = time() . '_' . uniqid() . '.pdf';
    $destination = "$userUploadsDir/$newName";

    if (!move_uploaded_file($_FILES['noteFile']['tmp_name'], $destination)) {
        die('Failed to move uploaded file.');
    }

    // Sanitize and prepare data for database insertion
    $title = $_POST['title'];
    $desc  = $_POST['description'];

    // Insert the new note record into the database using prepared statements
    $stmt = $conn->prepare(
        "INSERT INTO notes (user_id, title, description, filename) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('isss', $user_id, $title, $desc, $newName);
    
    if (!$stmt->execute()) {
        die('Database insert error: ' . $stmt->error);
    }
    $stmt->close();

    // Redirect to the main page to show the updated list
    header('Location: index.php');
    exit;
}

// --- Fetch Notes for the Logged-in User ---
$stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY uploaded_at DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

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
                <p>Welcome back, your dashboard is ready.</p>
            </div>
            <div class="header-actions">
                <div class="profile" id="profile">
                    <i class="fa-solid fa-user profile-icon"></i>
                    <span class="profile-username"><?= htmlspecialchars($username) ?></span>
                </div>
                <div class="profile-menu" id="profileMenu">
                    <a href="logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
        <div class="dashboard-grid">
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): 
                $file_url = 'uploads/' . rawurlencode($username) . '/' . rawurlencode($row['filename']);
            ?>
              <div class="note-card" data-href="<?= $file_url ?>">
                <div class="note-card-header">
                  <span class="note-title"><?= htmlspecialchars($row['title']) ?></span>
                </div>
                <p class="note-desc"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                <div class="note-footer">
                  <span class="note-date"><?= date('d M Y', strtotime($row['uploaded_at'])) ?></span>
                  <div class="note-actions">
                    <a href="<?= $file_url ?>" class="btn-ghost" target="_blank" title="Open PDF">
                      <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                    <a href="<?= $file_url ?>" class="btn-ghost" download title="Download PDF">
                      <i class="fa-solid fa-download"></i>
                    </a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn-ghost btn-delete" onclick="return confirm('Are you sure you want to delete this note?');" title="Delete Note">
                      <i class="fa-solid fa-trash"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p class="no-notes-message">No notes have been uploaded yet. Click 'Upload' to get started!</p>
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
<?php
$stmt->close();
$conn->close();
?>