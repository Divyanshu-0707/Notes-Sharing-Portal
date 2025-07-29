document.addEventListener('DOMContentLoaded', () => {
  // --- Navigation View Switcher ---
  // This code handles switching between the 'Dashboard' and 'Upload' views.
  document.querySelectorAll('.nav-item').forEach(item => {
    // Only add the view-switching logic to items that have a 'data-target' attribute.
    // This is important to prevent the new logout link (which is also a .nav-item)
    // from being incorrectly treated as a view switcher.
    if (item.dataset.target) {
      item.addEventListener('click', () => {
        // Remove 'active' class from the currently active navigation item
        const currentActiveItem = document.querySelector('.nav-item.active');
        if (currentActiveItem) {
          currentActiveItem.classList.remove('active');
        }
        // Add 'active' class to the clicked item
        item.classList.add('active');

        // Remove 'active' class from the currently visible view
        const currentActiveView = document.querySelector('.view.active');
        if (currentActiveView) {
          currentActiveView.classList.remove('active');
        }
        // Show the view that corresponds to the clicked nav item
        document.getElementById(item.dataset.target).classList.add('active');
      });
    }
  });

  // --- File Input Name Display ---
  // This updates the text to show the name of the selected file in the upload form.
  const fileInput = document.getElementById('noteFile');
  const fileNameSpan = document.querySelector('.file-selected');
  if (fileInput && fileNameSpan) {
    fileInput.addEventListener('change', e => {
      const file = e.target.files[0];
      // If a file is selected, display its name. Otherwise, show the default text.
      fileNameSpan.textContent = file ? file.name : 'No file chosen';
    });
  }

  // --- Make Note Cards Clickable ---
  // This allows users to click anywhere on a note card to open the associated PDF.
  document.querySelectorAll('.note-card').forEach(card => {
    card.addEventListener('click', (e) => {
      // We only want the card click to work if the user isn't clicking on an action button
      // (like download or delete). If they are, we stop this function.
      if (e.target.closest('.note-actions a')) {
        return;
      }
      // Get the file URL from the card's 'data-href' attribute and open it in a new tab.
      const link = card.dataset.href;
      if (link) {
        window.open(link, '_blank');
      }
    });
  });

  // The code for the profile menu dropdown has been completely removed as requested.
});
