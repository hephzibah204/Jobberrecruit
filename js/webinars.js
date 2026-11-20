/* Webinar listing controls and confirmed registration flow. */
var webinarTopic = 'all';
var webinarView = 'upcoming';

function applyFilters() {
  const grid = document.getElementById('wgrid');
  if (!grid) return;
  const search = (document.getElementById('wsearch')?.value || '').trim().toLowerCase();
  const sort = document.getElementById('wsort')?.value || 'date';
  const cards = Array.from(grid.querySelectorAll('.wb-card'));
  cards.sort(function(a, b) {
    if (sort === 'popular') return Number(b.dataset.pop) - Number(a.dataset.pop) || Date.parse(a.dataset.start) - Date.parse(b.dataset.start);
    if (sort === 'price') return Number(a.dataset.price) - Number(b.dataset.price) || Date.parse(a.dataset.start) - Date.parse(b.dataset.start);
    return Date.parse(a.dataset.start) - Date.parse(b.dataset.start);
  });
  let visible = 0;
  cards.forEach(function(card) {
    const matches = webinarView === 'upcoming' && Date.parse(card.dataset.start) > Date.now()
      && (webinarTopic === 'all' || (card.dataset.cat || '').split(' ').includes(webinarTopic))
      && (card.dataset.search || card.textContent).toLowerCase().includes(search);
    card.hidden = !matches;
    card.style.display = matches ? '' : 'none';
    if (matches) visible++;
    grid.appendChild(card);
  });
  const label = document.getElementById('wcount');
  if (label) label.textContent = 'Showing ' + visible + ' webinars';
  const live = document.getElementById('wcount-live');
  if (live) live.textContent = visible + ' webinars match your selection.';
  const empty = document.getElementById('empty-state');
  if (empty) {
    empty.hidden = visible > 0;
    empty.style.display = visible ? 'none' : 'block';
    empty.querySelector('h3').textContent = webinarView === 'ondemand' ? 'No recordings available yet' : 'No matching upcoming webinars';
    empty.querySelector('p').textContent = webinarView === 'ondemand' ? 'Published webinar recordings will appear here when available.' : 'Try another search or topic, or check back for new sessions.';
  }
}

function doFilter(topic, button) {
  webinarTopic = topic;
  document.querySelectorAll('[data-filter]').forEach(function(item) {
    const active = item === button;
    item.classList.toggle('on', active); item.setAttribute('aria-pressed', String(active));
  });
  applyFilters();
}

function setView(view, button) {
  webinarView = view;
  document.querySelectorAll('.view-tab').forEach(function(item) {
    const active = item === button;
    item.classList.toggle('active', active); item.setAttribute('aria-selected', String(active));
  });
  const heading = document.getElementById('sessions-h');
  if (heading) heading.textContent = view === 'ondemand' ? 'On-demand recordings' : 'Upcoming sessions';
  applyFilters();
}

function toggleFaq(button) {
  const item = button.closest('.faq-item');
  if (!item) return;
  const open = item.classList.toggle('open');
  button.setAttribute('aria-expanded', String(open));
}

function addToCal(url) { window.open(url, '_blank', 'noopener'); }

async function webinarResponse(response) {
  let data;
  try { data = await response.json(); } catch (_) { throw new Error('The request could not be confirmed. Please try again.'); }
  if (!response.ok || data.success === false || data.error) {
    throw new Error(data.messages?.error || data.message || 'The request could not be completed.');
  }
  return data;
}

async function registerWebinarButton(button) {
  if (button.disabled) return;
  const config = window.webinarPage;
  const original = button.innerHTML;
  button.disabled = true; button.textContent = 'Processing…';
  try {
    const response = await fetch(config.registerUrl + button.dataset.id, {
      method:'POST', headers:{'X-Requested-With':'XMLHttpRequest',[config.csrfHeader]:config.csrfToken}
    });
    if (response.status === 401) { window.location.assign(config.loginUrl); return; }
    const data = await webinarResponse(response);
    if (data.requires_payment && data.authorization_url) {
      window.location.assign(data.authorization_url);
    } else if (data.success === true && data.redirect) {
      window.location.assign(data.redirect);
    } else {
      throw new Error('Registration was not confirmed. Please try again.');
    }
  } catch (error) {
    window.alert(error.message || 'Connection failed. Registration has not been confirmed. Please try again.');
  } finally { button.disabled = false; button.innerHTML = original; }
}

function refreshWebinarDates() {
  let changed = false;
  document.querySelectorAll('.wb-card[data-start]').forEach(function(card) {
    if (Date.parse(card.dataset.start) <= Date.now()) { card.remove(); changed = true; }
  });
  const countdown = document.querySelector('.countdown[data-time]');
  if (countdown) {
    const diff = Date.parse(countdown.dataset.time) - Date.now();
    if (diff <= 0) {
      const featured = countdown.closest('.feat-card');
      if (featured) featured.textContent = 'This session has started. Registration is closed.';
    } else {
      const values = [Math.floor(diff/864e5),Math.floor(diff%864e5/36e5),Math.floor(diff%36e5/6e4),Math.floor(diff%6e4/1e3)];
      ['cd-d','cd-h','cd-m','cd-s'].forEach(function(id,index) {
        const cell = document.getElementById(id); if (cell) cell.textContent = String(values[index]).padStart(2,'0');
      });
    }
  }
  if (changed) applyFilters();
}

async function handleSub(event) {
  event.preventDefault();
  const form = event.target, button = form.querySelector('button[type="submit"]');
  button.disabled = true;
  try {
    const response = await fetch(window.webinarPage.subscribeUrl, {method:'POST',body:new FormData(form),
      headers:{'X-Requested-With':'XMLHttpRequest',[window.webinarPage.csrfHeader]:window.webinarPage.csrfToken}});
    const data = await webinarResponse(response); window.alert(data.message || 'Subscribed.'); form.reset();
  } catch (error) { window.alert(error.message); } finally { button.disabled = false; }
}

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.btn-register').forEach(function(button) {
    button.addEventListener('click', function() { registerWebinarButton(button); });
  });
  const topic = new URLSearchParams(window.location.search).get('topic');
  const topicButton = Array.from(document.querySelectorAll('[data-filter]')).find(button => button.dataset.filter === topic);
  if (topicButton) doFilter(topic, topicButton); else applyFilters();
  refreshWebinarDates();
  setInterval(refreshWebinarDates, 1000);
});
