document.addEventListener('DOMContentLoaded', () => {
  // Navigation view switcher
  document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => {
      document.querySelector('.nav-item.active').classList.remove('active');
      item.classList.add('active');
      document.querySelector('.view.active').classList.remove('active');
      document.getElementById(item.dataset.target).classList.add('active');
    });
  });

  // File input name display
  const fileInput = document.getElementById('noteFile');
  const fileNameSpan = document.querySelector('.file-selected');
  if (fileInput && fileNameSpan) {
    fileInput.addEventListener('change', e => {
      const file = e.target.files[0];
      fileNameSpan.textContent = file ? file.name : 'No file chosen';
    });
  }

  // EDITED: Profile menu dropdown
  const profile = document.getElementById('profile');
  const profileMenu = document.getElementById('profileMenu');
  if (profile && profileMenu) {
    profile.addEventListener('click', (e) => {
        e.stopPropagation(); // prevent window click event from firing immediately
        profileMenu.classList.toggle('show');
    });
  }
  
  // Close menu if clicked outside
  window.addEventListener('click', () => {
    if (profileMenu && profileMenu.classList.contains('show')) {
      profileMenu.classList.remove('show');
    }
  });

  // EDITED: Make note cards clickable
  document.querySelectorAll('.note-card').forEach(card => {
    card.addEventListener('click', (e) => {
        // Only open the link if the click is not on a button or a link inside the card
        if (e.target.closest('.note-actions a')) {
            return;
        }
        const link = card.dataset.href;
        if(link) {
            window.open(link, '_blank');
        }
    });
  });

});