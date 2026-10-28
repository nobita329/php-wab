/**
 * ========================================================
 * CYBERPULSE — ADMIN DASHBOARD INTERACTIVITY
 * ========================================================
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Sidebar Toggle for Mobile/Tablet
  const sidebarToggle = document.querySelector('.sidebar-toggle-btn');
  const sidebar = document.querySelector('.admin-sidebar');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });

    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 992 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
        sidebar.classList.remove('open');
      }
    });
  }

  // 2. File Upload Instant Image Preview
  const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');
  imageInputs.forEach(input => {
    input.addEventListener('change', () => {
      const targetSelector = input.getAttribute('data-preview');
      const targetImg = document.querySelector(targetSelector);
      if (targetImg && input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
          targetImg.src = e.target.result;
          targetImg.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
      }
    });
  });

  // 3. Confirm Destructive Operations
  const deleteForms = document.querySelectorAll('form.confirm-delete, a.confirm-delete');
  deleteForms.forEach(el => {
    el.addEventListener('submit', (e) => {
      const msg = el.getAttribute('data-confirm') || 'Are you sure you want to delete this record? This action cannot be reversed.';
      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
    if (el.tagName === 'A') {
      el.addEventListener('click', (e) => {
        const msg = el.getAttribute('data-confirm') || 'Are you sure you want to delete this record? This action cannot be reversed.';
        if (!confirm(msg)) {
          e.preventDefault();
        }
      });
    }
  });

  // 4. Copy to Clipboard Utility (for Media Library)
  const copyButtons = document.querySelectorAll('.copy-btn');
  copyButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const textToCopy = btn.getAttribute('data-clipboard-text') || btn.dataset.url;
      if (textToCopy) {
        navigator.clipboard.writeText(textToCopy).then(() => {
          const originalText = btn.innerHTML;
          btn.innerHTML = 'Copied!';
          setTimeout(() => {
            btn.innerHTML = originalText;
          }, 2000);
        });
      }
    });
  });

  // 5. Slug Auto-generation from Title
  const titleInput = document.querySelector('input[data-slug-target]');
  if (titleInput) {
    const slugTargetSelector = titleInput.getAttribute('data-slug-target');
    const slugTarget = document.querySelector(slugTargetSelector);
    if (slugTarget && slugTarget.value === '') {
      titleInput.addEventListener('input', () => {
        slugTarget.value = titleInput.value
          .toLowerCase()
          .replace(/[^\w ]+/g, '')
          .replace(/ +/g, '-');
      });
    }
  }
});

