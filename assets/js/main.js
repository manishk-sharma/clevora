// 1. Stat counter with IntersectionObserver
document.addEventListener('DOMContentLoaded', () => {
  const counters = document.querySelectorAll('.stat-count');
  if (counters.length > 0) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el = e.target;
        const target = +el.dataset.target;
        const suffix = el.dataset.suffix || '';
        let n = 0;
        let step = Math.ceil(target / 80);
        if (step < 1) step = 1;
        const t = setInterval(() => {
          n = Math.min(n + step, target);
          el.textContent = n.toLocaleString() + suffix;
          if (n >= target) {
            clearInterval(t);
          }
        }, 20);
        io.unobserve(el);
      });
    }, { threshold: 0.2 });
    counters.forEach(c => io.observe(c));
  }
});

// 2. AJAX form handler (home form + contact page form)
document.addEventListener('DOMContentLoaded', () => {
  ['home-contact-form', 'contact-form'].forEach(id => {
    const form = document.getElementById(id);
    if (!form) return;

    form.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = form.querySelector('[type=submit]');
      const msg = document.getElementById(id === 'home-contact-form' ? 'home-form-msg' : 'form-message');

      if (!msg || !btn) return;

      btn.disabled = true;
      const originalText = btn.textContent;
      btn.textContent = 'Sending...';

      // Extract form values
      const formData = new FormData(form);
      const jsonObject = {};
      formData.forEach((value, key) => {
        jsonObject[key] = value;
      });

      try {
        const res = await fetch('/api/contact.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(jsonObject)
        });

        const data = await res.json();
        msg.classList.remove('hidden');

        if (data.success) {
          msg.className = 'mt-4 text-xs font-semibold p-3 rounded bg-green-50 text-green-700 border border-green-100 block';
          msg.textContent = data.message;
          form.reset();
        } else {
          msg.className = 'mt-4 text-xs font-semibold p-3 rounded bg-red-50 text-red-700 border border-red-100 block';
          msg.textContent = (data.errors || ['Error']).join(' ');
        }
      } catch (err) {
        msg.classList.remove('hidden');
        msg.className = 'mt-4 text-xs font-semibold p-3 rounded bg-red-50 text-red-700 border border-red-100 block';
        msg.textContent = 'Network or server connection failed. Please try again.';
      } finally {
        btn.disabled = false;
        btn.textContent = originalText;
      }
    });
  });
});

// 3. Homepage hero slider
document.addEventListener('DOMContentLoaded', () => {
  const hero = document.querySelector('.hero-slider');
  if (!hero) return;

  const slides = Array.from(hero.querySelectorAll('[data-hero-slide]'));
  const dots = Array.from(hero.querySelectorAll('[data-hero-dot]'));
  const prev = hero.querySelector('[data-hero-prev]');
  const next = hero.querySelector('[data-hero-next]');
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  let current = 0;
  let timer = null;

  const showSlide = index => {
    current = (index + slides.length) % slides.length;
    slides.forEach((slide, i) => slide.classList.toggle('is-active', i === current));
    dots.forEach((dot, i) => dot.classList.toggle('is-active', i === current));
  };

  const stopAutoPlay = () => {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }
  };

  const startAutoPlay = () => {
    if (reduceMotion || slides.length < 2 || timer) return;
    timer = setInterval(() => showSlide(current + 1), 6500);
  };

  prev?.addEventListener('click', () => {
    showSlide(current - 1);
    stopAutoPlay();
    startAutoPlay();
  });

  next?.addEventListener('click', () => {
    showSlide(current + 1);
    stopAutoPlay();
    startAutoPlay();
  });

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      showSlide(index);
      stopAutoPlay();
      startAutoPlay();
    });
  });

  hero.addEventListener('mouseenter', stopAutoPlay);
  hero.addEventListener('mouseleave', startAutoPlay);

  showSlide(0);
  startAutoPlay();
});

// 4. Scroll reveal animations across site pages
document.addEventListener('DOMContentLoaded', () => {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const revealItems = [];
  const revealSet = new Set();
  const addReveal = (el, delay = 0, variant = '') => {
    if (!el || revealSet.has(el)) return;
    revealSet.add(el);
    el.classList.add('reveal-on-scroll');
    if (variant) el.classList.add(variant);
    el.style.setProperty('--reveal-delay', `${delay}ms`);
    revealItems.push(el);
  };

  const sectionByTitle = title => {
    const headings = Array.from(document.querySelectorAll('h2'));
    const heading = headings.find(h => h.textContent.trim().toLowerCase() === title.toLowerCase());
    return heading?.closest('section') || null;
  };

  addReveal(document.querySelector('.page-banner__inner'), 0);

  document.querySelectorAll('section').forEach(section => {
    const sectionHeader = section.querySelector('.section-head, h1, h2');
    addReveal(sectionHeader?.closest('.section-head') || sectionHeader, 0);

    const directContent = Array.from(section.children).filter(child => {
      return !child.matches('.page-banner__inner') && !child.querySelector('.section-head');
    });
    directContent.forEach((child, index) => addReveal(child, 80 + index * 80));
  });

  document.querySelectorAll([
    'body > div[style*="max-width"]',
    'main > div',
    '.card',
    '.feature-card',
    '.benefit-card',
    '.feature-item-card',
    '.sidebar-service-card',
    '.contact-item',
    '.service-image-card',
    '.service-details',
    '.leadership-card',
    '.contact-panel',
    '.content-grid > *',
    '.card-grid > *',
    '.benefits-grid > *',
    '.features-grid > *',
    '.grid > *'
  ].join(',')).forEach((el, index) => {
    addReveal(el, Math.min(420, 80 + (index % 6) * 80));
  });

  const howItWorks = sectionByTitle('Go live in 3 simple steps');
  if (howItWorks) {
    addReveal(howItWorks.querySelector('div[style*="text-align:center"]'), 0);
    const line = howItWorks.querySelector('.hidden.md\\:block');
    if (line) {
      line.classList.add('reveal-line');
      line.style.setProperty('--reveal-delay', '250ms');
      revealItems.push(line);
    }
    howItWorks.querySelectorAll('.grid > div').forEach((step, index) => {
      addReveal(step, 180 + index * 140);
      addReveal(step.firstElementChild, 260 + index * 140, 'reveal-pop');
    });
  }

  const apart = sectionByTitle('What Sets Clevora Apart');
  if (apart) {
    addReveal(apart.querySelector('div[style*="text-align:center"]'), 0);
    apart.querySelectorAll('.grid > div').forEach((card, index) => {
      addReveal(card, 120 + index * 90);
    });
  }

  const testimonials = sectionByTitle('What our clients say');
  if (testimonials) {
    addReveal(testimonials.querySelector('div[style*="text-align:center"]'), 0);
    testimonials.querySelectorAll('.grid > div').forEach((card, index) => {
      addReveal(card, 140 + index * 120);
    });
  }

  if (!revealItems.length) return;

  if (!('IntersectionObserver' in window)) {
    revealItems.forEach(item => item.classList.add('is-visible'));
    return;
  }

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-visible');
      observer.unobserve(entry.target);
    });
  }, {
    threshold: 0.16,
    rootMargin: '0px 0px -8% 0px'
  });

  revealItems.forEach(item => observer.observe(item));
});
