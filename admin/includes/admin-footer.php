<script>
// ── Auto-slugifier ──────────────────────────────────────
document.querySelectorAll('[data-slug-source]').forEach(input => {
  const target = document.querySelector(input.dataset.slugSource);
  if (!target) return;
  input.addEventListener('input', () => {
    if (!target.dataset.touched) {
      target.value = input.value.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    }
  });
  target.addEventListener('change', () => { target.dataset.touched = true; });
});

// ── Quill WYSIWYG Initialization ────────────────────────
document.querySelectorAll('[data-quill]').forEach(container => {
  const hiddenInput = document.querySelector(container.dataset.quill);
  if (!hiddenInput) return;
  
  const quill = new Quill(container, {
    theme: 'snow',
    modules: {
      toolbar: [
        ['bold', 'italic'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['link'],
        ['clean']
      ]
    },
    placeholder: container.dataset.placeholder || 'Write content...'
  });
  
  // Set initial content
  if (hiddenInput.value) {
    quill.root.innerHTML = hiddenInput.value;
  }
  
  // Sync content back to hidden input on change
  quill.on('text-change', () => {
    hiddenInput.value = quill.root.innerHTML;
  });
  
  // Also sync on form submit
  const form = container.closest('form');
  if (form) {
    form.addEventListener('submit', () => {
      hiddenInput.value = quill.root.innerHTML;
    });
  }
});

// ── Repeater: Add Row ───────────────────────────────────
document.querySelectorAll('[data-repeater-add]').forEach(btn => {
  btn.addEventListener('click', () => {
    const container = document.querySelector(btn.dataset.repeaterAdd);
    const template = container.querySelector('[data-repeater-template]');
    if (!template) return;
    const clone = template.cloneNode(true);
    clone.removeAttribute('data-repeater-template');
    clone.style.display = '';
    clone.classList.add('repeater-row');
    // Clear input values
    clone.querySelectorAll('input, textarea, select').forEach(i => { i.value = ''; });
    container.appendChild(clone);
  });
});

// ── Repeater: Remove Row ────────────────────────────────
document.addEventListener('click', e => {
  if (e.target.closest('[data-repeater-remove]')) {
    e.target.closest('.repeater-row, [data-repeater-template]')?.remove();
  }
});

// ── Confirm Delete ──────────────────────────────────────
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', e => {
    if (!confirm(el.dataset.confirm || 'Are you sure?')) {
      e.preventDefault();
    }
  });
});
</script>
</body>
</html>
