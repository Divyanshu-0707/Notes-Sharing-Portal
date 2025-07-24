document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => {
      document.querySelector('.nav-item.active').classList.remove('active');
      item.classList.add('active');
      document.querySelector('.view.active').classList.remove('active');
      document.getElementById(item.dataset.target).classList.add('active');
    });
  });
  const fileInput = document.getElementById('noteFile');
  const fileNameSpan = document.querySelector('.file-selected');
  fileInput.addEventListener('change', e => {
    const file = e.target.files[0];
    fileNameSpan.textContent = file ? file.name : 'No file chosen';
  });
});
