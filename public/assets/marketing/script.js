/* ============================================================
  QISTAS — script.js
   ============================================================ */

(function () {
  'use strict';

  /* ===== NAVBAR ===== */
  const navbar    = document.getElementById('navbar');
  const navToggle = document.getElementById('navToggle');
  const navLinks  = document.getElementById('navLinks');
  const navLinkEls = document.querySelectorAll('.nav-link');

  // Scroll class
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
    updateActiveLink();
  }, { passive: true });

  // Mobile toggle
  navToggle.addEventListener('click', () => {
    navToggle.classList.toggle('open');
    navLinks.classList.toggle('open');
  });

  // Close on link click
  navLinkEls.forEach(link => {
    link.addEventListener('click', () => {
      navToggle.classList.remove('open');
      navLinks.classList.remove('open');
    });
  });

  /* ===== SMOOTH SCROLL ===== */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href === '#') return;
      const target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      const navHeight = navbar.offsetHeight;
      const targetTop = target.getBoundingClientRect().top + window.pageYOffset - navHeight - 12;
      window.scrollTo({ top: targetTop, behavior: 'smooth' });
    });
  });

  /* ===== ACTIVE NAV LINK ===== */
  const sections = document.querySelectorAll('section[id]');

  function updateActiveLink() {
    const scrollPos = window.scrollY + navbar.offsetHeight + 40;
    let current = '';

    sections.forEach(section => {
      const sectionTop = section.offsetTop;
      const sectionH   = section.offsetHeight;
      if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionH) {
        current = section.getAttribute('id');
      }
    });

    navLinkEls.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href') === '#' + current) {
        link.classList.add('active');
      }
    });
  }

  /* ===== SCROLL ANIMATIONS ===== */
  const animEls = document.querySelectorAll('[data-animate]');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
        observer.unobserve(entry.target); // Animate once
      }
    });
  }, {
    threshold: 0.12,
    rootMargin: '0px 0px -40px 0px'
  });

  animEls.forEach(el => observer.observe(el));

  // Staggered feature cards
  const featureCards = document.querySelectorAll('.feature-card');
  const featureObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const delay = parseInt(entry.target.dataset.delay || '0');
        setTimeout(() => {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'none';
        }, delay);
        featureObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });

  featureCards.forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(24px)';
    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    featureObserver.observe(card);
  });

  /* ===== PRODUCT PREVIEW TABS ===== */
  const tabs   = document.querySelectorAll('.preview-tab');
  const panels = document.querySelectorAll('.preview-panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.tab;

      tabs.forEach(t => t.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));

      tab.classList.add('active');
      const panel = document.getElementById('panel-' + target);
      if (panel) {
        panel.classList.add('active');
        // Animate panel entrance
        panel.style.opacity = '0';
        panel.style.transform = 'translateY(10px)';
        setTimeout(() => {
          panel.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
          panel.style.opacity = '1';
          panel.style.transform = 'none';
        }, 10);
      }
    });
  });

  /* ===== FORM VALIDATION ===== */
  const contactForm = document.getElementById('contactForm');
  const formSuccess = document.getElementById('formSuccess');

  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      let valid = true;

      // Name
      const nameInput = document.getElementById('name');
      const nameError = document.getElementById('nameError');
      const nameVal   = nameInput.value.trim();
      if (!nameVal || nameVal.length < 2) {
        showError(nameInput, nameError, 'يرجى إدخال اسمك الكامل (حرفان على الأقل)');
        valid = false;
      } else {
        clearError(nameInput, nameError);
      }

      // Email
      const emailInput = document.getElementById('email');
      const emailError = document.getElementById('emailError');
      const emailVal   = emailInput.value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailVal || !emailRegex.test(emailVal)) {
        showError(emailInput, emailError, 'يرجى إدخال بريد إلكتروني صحيح');
        valid = false;
      } else {
        clearError(emailInput, emailError);
      }

      // Message
      const msgInput = document.getElementById('message');
      const msgError = document.getElementById('messageError');
      const msgVal   = msgInput.value.trim();
      if (!msgVal || msgVal.length < 10) {
        showError(msgInput, msgError, 'يرجى كتابة رسالتك (10 أحرف على الأقل)');
        valid = false;
      } else {
        clearError(msgInput, msgError);
      }

      if (valid) {
        // Simulate send
        const btn = contactForm.querySelector('.btn-submit');
        const btnText = btn.querySelector('.btn-text');
        btn.disabled = true;
        btnText.textContent = 'جارٍ الإرسال...';

        setTimeout(() => {
          contactForm.reset();
          btn.disabled = false;
          btnText.textContent = 'إرسال الرسالة';
          formSuccess.classList.add('visible');
          setTimeout(() => formSuccess.classList.remove('visible'), 5000);
        }, 1200);
      }
    });

    // Real-time validation on blur
    ['name', 'email', 'message'].forEach(id => {
      const input = document.getElementById(id);
      input.addEventListener('blur', () => {
        if (input.classList.contains('error')) {
          input.dispatchEvent(new Event('input'));
        }
      });
      input.addEventListener('input', () => {
        const error = document.getElementById(id + 'Error');
        if (input.value.trim()) {
          clearError(input, error);
        }
      });
    });
  }

  function showError(input, errorEl, msg) {
    input.classList.add('error');
    errorEl.textContent = msg;
    errorEl.classList.add('visible');
  }
  function clearError(input, errorEl) {
    input.classList.remove('error');
    errorEl.classList.remove('visible');
  }

  /* ===== HERO BARS ANIMATION ===== */
  // Animate the bar chart fills on page load after a short delay
  const barFills = document.querySelectorAll('.bar-fill');
  barFills.forEach(bar => {
    const targetH = bar.style.getPropertyValue('--h') || '50%';
    bar.style.setProperty('--h', '0%');
    setTimeout(() => {
      bar.style.transition = 'height 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)';
      bar.style.setProperty('--h', targetH);
    }, 800);
  });

  /* ===== COUNTER ANIMATION ===== */
  const counterEls = document.querySelectorAll('.hero-stat strong');

  const countObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        countObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  counterEls.forEach(el => countObserver.observe(el));

  function animateCounter(el) {
    const text  = el.textContent;
    const hasPlus = text.startsWith('+');
    const hasPct  = text.endsWith('%');
    const numStr  = text.replace('+', '').replace('%', '');
    const target  = parseInt(numStr);

    if (isNaN(target)) return;

    const duration = 1400;
    const start    = performance.now();

    function update(now) {
      const elapsed  = now - start;
      const progress = Math.min(elapsed / duration, 1);
      const eased    = 1 - Math.pow(1 - progress, 3);
      const current  = Math.floor(eased * target);

      let display = (hasPlus ? '+' : '') + current + (hasPct ? '%' : '');
      el.textContent = display;

      if (progress < 1) requestAnimationFrame(update);
      else el.textContent = text; // Restore original
    }
    requestAnimationFrame(update);
  }

  /* ===== NAVBAR CLOSE ON OUTSIDE CLICK ===== */
  document.addEventListener('click', (e) => {
    if (!navbar.contains(e.target)) {
      navToggle.classList.remove('open');
      navLinks.classList.remove('open');
    }
  });

  /* ===== HERO IMMEDIATE ANIMATION ===== */
  document.addEventListener('DOMContentLoaded', () => {
    const heroContent = document.querySelector('.hero-content');
    const heroMockup  = document.querySelector('.hero-mockup');
    if (heroContent) {
      setTimeout(() => heroContent.classList.add('in-view'), 100);
    }
    if (heroMockup) {
      setTimeout(() => heroMockup.classList.add('in-view'), 350);
    }
  });

  /* ===== P-BAR ANIMATION (Preview section) ===== */
  const pBars = document.querySelectorAll('.p-bar');
  const pBarObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Bars use CSS pseudo-element with custom property, trigger reflow
        entry.target.style.opacity = '1';
        pBarObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });
  pBars.forEach(b => pBarObserver.observe(b));

})();
