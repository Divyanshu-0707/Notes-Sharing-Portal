document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.nav-item').forEach(item => {
    if (item.dataset.target) {
      item.addEventListener('click', () => {
        const currentActiveItem = document.querySelector('.nav-item.active');
        if (currentActiveItem) {
          currentActiveItem.classList.remove('active');
        }
        item.classList.add('active');
        const currentActiveView = document.querySelector('.view.active');
        if (currentActiveView) {
          currentActiveView.classList.remove('active');
        }
        document.getElementById(item.dataset.target).classList.add('active');
      });
    }
  });

  const fileInput = document.getElementById('noteFile');
  const fileNameSpan = document.querySelector('.file-selected');
  if (fileInput && fileNameSpan) {
    fileInput.addEventListener('change', e => {
      const file = e.target.files[0];
      fileNameSpan.textContent = file ? file.name : 'No file chosen';
    });
  }
  document.querySelectorAll('.note-card').forEach(card => {
    card.addEventListener('click', (e) => {
      if (e.target.closest('.note-actions a')) {
        return;
      }
      const link = card.dataset.href;
      if (link) {
        window.open(link, '_blank');
      }
    });
  });
});
