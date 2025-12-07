function showTab(tabId) {
  const tabs = ['tab1', 'tab2'];
  tabs.forEach(id => {
    const el = document.getElementById(id);
    const btn = document.getElementById('btn' + id.charAt(0).toUpperCase() + id.slice(1));
    const active = (id === tabId);
    el.style.display = active ? 'block' : 'none';
    el.classList.toggle('show', active);
    btn.classList.toggle('active', active);
  });
}