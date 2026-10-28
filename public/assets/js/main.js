/**
 * ========================================================
 * CYBERPULSE — CLIENT INTERACTIVE LOGIC & GSAP ANIMATIONS
 * ========================================================
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Mobile Menu Toggle
  const mobileToggle = document.querySelector('.mobile-toggle');
  const navLinks = document.querySelector('.nav-links');

  if (mobileToggle && navLinks) {
    mobileToggle.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      const expanded = navLinks.classList.contains('open');
      mobileToggle.setAttribute('aria-expanded', expanded);
    });

    // Close on link click
    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('open');
      });
    });
  }

  // 2. Glass Navbar Scroll Effect
  const navbar = document.querySelector('.glass-nav');
  if (navbar) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 40) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  }

  // 3. Project Category Filter
  const filterBtns = document.querySelectorAll('.filter-btn');
  const projectCards = document.querySelectorAll('.project-card');

  if (filterBtns.length > 0 && projectCards.length > 0) {
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const cat = btn.dataset.category;
        projectCards.forEach(card => {
          if (cat === 'all' || card.dataset.category === cat) {
            card.style.display = 'flex';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  // 4. Contact Form AJAX Submission with Spam Protection
  const contactForm = document.getElementById('contact-form');
  const contactStatus = document.getElementById('contact-form-status');

  if (contactForm && contactStatus) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Check honeypot field
      const honeypot = contactForm.querySelector('input[name="website_hp"]');
      if (honeypot && honeypot.value.trim() !== '') {
        // Silent bot discard
        contactStatus.innerHTML = '<div class="alert alert-success">Message sent successfully!</div>';
        contactForm.reset();
        return;
      }

      const submitBtn = contactForm.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = 'Transmitting Message...';

      contactStatus.innerHTML = '';

      try {
        const formData = new FormData(contactForm);
        const response = await fetch('/contact.php', {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          contactStatus.innerHTML = `<div class="alert alert-success">${data.message || 'Your message has been transmitted successfully. I will get back to you shortly.'}</div>`;
          contactForm.reset();
        } else {
          contactStatus.innerHTML = `<div class="alert alert-error">${data.error || 'Failed to deliver message. Please review the form and try again.'}</div>`;
        }
      } catch (err) {
        contactStatus.innerHTML = '<div class="alert alert-error">Network error occurred while sending message. Please try again.</div>';
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      }
    });
  }

  // 5. GSAP Smooth Reveal Animations (if GSAP is available)
  if (typeof gsap !== 'undefined') {
    gsap.from('.hero-badge', { opacity: 0, y: -20, duration: 0.8, delay: 0.2 });
    gsap.from('.hero-title', { opacity: 0, y: 30, duration: 1, delay: 0.4 });
    gsap.from('.hero-subtitle', { opacity: 0, y: 20, duration: 0.8, delay: 0.7 });
    gsap.from('.hero-buttons', { opacity: 0, y: 20, duration: 0.8, delay: 0.9 });
  }
});

