(() => {
  const yearNodes = document.querySelectorAll('[data-current-year]');
  if (yearNodes.length === 0) {
    return;
  }

  const year = String(new Date().getFullYear());
  yearNodes.forEach((node) => {
    node.textContent = year;
  });
})();
