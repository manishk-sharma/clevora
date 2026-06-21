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
