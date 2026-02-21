(() => {
  const yearNodes = document.querySelectorAll('[data-current-year]');
  if (yearNodes.length > 0) {
    const year = String(new Date().getFullYear());
    yearNodes.forEach((node) => {
      node.textContent = year;
    });
  }

  const nav = document.querySelector('[data-nav]');
  const navToggle = document.querySelector('[data-nav-toggle]');
  if (nav && navToggle) {
    navToggle.addEventListener('click', () => {
      const isOpen = nav.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  const copyButtons = document.querySelectorAll('[data-copy-prompt]');
  copyButtons.forEach((button) => {
    button.addEventListener('click', async () => {
      const promptContainer = button.closest('[data-prompt-card]');
      const promptBody = promptContainer?.querySelector('[data-prompt-body]');
      if (!promptBody) {
        return;
      }

      const text = (promptBody.textContent || '').trim().replace(/^"|"$/g, '');
      if (text === '') {
        return;
      }

      const originalLabel = button.textContent;
      try {
        await navigator.clipboard.writeText(text);
        button.textContent = 'Copied!';
        button.classList.add('is-copied');
      } catch (_error) {
        button.textContent = 'Copy failed';
      }

      window.setTimeout(() => {
        button.textContent = originalLabel;
        button.classList.remove('is-copied');
      }, 1800);
    });
  });

  const faqItems = Array.from(document.querySelectorAll('[data-faq-item]'));
  faqItems.forEach((item) => {
    const question = item.querySelector('[data-faq-question]');
    if (!question) {
      return;
    }

    question.addEventListener('click', () => {
      const willOpen = !item.classList.contains('is-open');
      faqItems.forEach((entry) => {
        entry.classList.remove('is-open');
      });
      if (willOpen) {
        item.classList.add('is-open');
      }
    });
  });
})();
