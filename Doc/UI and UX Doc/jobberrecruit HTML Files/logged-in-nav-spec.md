# Logged-in nav spec — JobberRecruit

How the nav should change when a user is authenticated. The page currently shows
**Log in / Get started free** for everyone. When logged in, those buttons are
replaced by a **user menu** (avatar → dropdown with Dashboard, Saved jobs, My
applications, Settings, Log out). This spec gives you the exact markup, CSS, and
JS, matching the existing nav, plus the CodeIgniter conditional.

Apply it in your **shared header partial** so every page (homepage, /jobs,
detail, etc.) gets it in one place.

---

## 1. The conditional — swap buttons for the user menu

Replace the two static buttons inside `<div class="nav-actions">` with this:

```php
<div class="nav-actions">

<?php if (! session()->get('user_id')): // ── LOGGED OUT ── ?>

  <a href="/login"    class="btn btn-outline">Log in</a>
  <a href="/register" class="btn btn-primary">Get started free</a>

<?php else: // ── LOGGED IN ── ?>

  <div class="user-menu">
    <button type="button" class="user-menu-toggle" aria-expanded="false" aria-haspopup="true" aria-label="Account menu">
      <span class="user-avatar" aria-hidden="true"><?= strtoupper(substr(session()->get('user_name') ?? 'U', 0, 1)) ?></span>
      <span class="user-name"><?= esc(session()->get('user_name') ?? 'Account') ?></span>
      <svg class="nav-caret" aria-hidden="true"><use href="#i-chev-down"/></svg>
    </button>
    <div class="user-menu-dropdown" role="menu">
      <div class="user-menu-head">
        <span class="user-avatar user-avatar--lg" aria-hidden="true"><?= strtoupper(substr(session()->get('user_name') ?? 'U', 0, 1)) ?></span>
        <div>
          <div class="user-menu-name"><?= esc(session()->get('user_name') ?? 'Account') ?></div>
          <div class="user-menu-email"><?= esc(session()->get('user_email') ?? '') ?></div>
        </div>
      </div>
      <a href="/dashboard"            role="menuitem"><svg aria-hidden="true"><use href="#i-chart"/></svg> Dashboard</a>
      <a href="/dashboard/saved"      role="menuitem"><svg aria-hidden="true"><use href="#i-bookmark"/></svg> Saved jobs</a>
      <a href="/dashboard/applications" role="menuitem"><svg aria-hidden="true"><use href="#i-doc"/></svg> My applications</a>
      <a href="/dashboard/profile"    role="menuitem"><svg aria-hidden="true"><use href="#i-users"/></svg> Profile &amp; settings</a>
      <div class="user-menu-sep" role="separator"></div>
      <a href="/logout" role="menuitem" class="user-menu-logout"><svg aria-hidden="true"><use href="#i-arrow-up" style="transform:rotate(90deg)"/></svg> Log out</a>
    </div>
  </div>

<?php endif; ?>

  <!-- hamburger stays the same, OUTSIDE the conditional -->
  <button class="hamburger" aria-label="Open navigation menu" aria-expanded="false" aria-controls="mob-nav" onclick="toggleMenu(this)">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
</div>
```

> Adjust the session keys (`user_id`, `user_name`, `user_email`) to whatever your
> auth library actually sets. Employers vs candidates: if dashboards differ, branch
> the `/dashboard` links on a `user_type` session value.

---

## 2. Mobile nav — same swap inside `#mob-nav`

In the mobile nav, replace the `Log in` / `Get started free →` links with:

```php
<?php if (! session()->get('user_id')): ?>
  <a href="/login">Log in</a>
  <a href="/register" class="mobile-nav-cta">Get started free →</a>
<?php else: ?>
  <div class="mob-group">
    <p class="mob-group-label">Account</p>
    <a href="/dashboard">Dashboard</a>
    <a href="/dashboard/saved">Saved jobs</a>
    <a href="/dashboard/applications">My applications</a>
    <a href="/dashboard/profile">Profile &amp; settings</a>
    <a href="/logout" class="mobile-nav-logout">Log out</a>
  </div>
<?php endif; ?>
```

---

## 3. CSS — add to your stylesheet (uses your existing tokens)

```css
/* ── Logged-in user menu ── */
.user-menu { position: relative; }
.user-menu-toggle {
  display: inline-flex; align-items: center; gap: 8px;
  background: none; border: 1.5px solid var(--border); border-radius: 8px;
  padding: 6px 10px 6px 6px; cursor: pointer; font-family: 'Inter', sans-serif;
  transition: var(--transition); -webkit-tap-highlight-color: transparent;
}
.user-menu-toggle:hover, .user-menu-toggle[aria-expanded="true"] { border-color: var(--brand); }
.user-avatar {
  width: 30px; height: 30px; border-radius: 50%; background: var(--brand); color: #fff;
  display: inline-flex; align-items: center; justify-content: center;
  font-family: 'Sora', sans-serif; font-weight: 700; font-size: .82rem; flex-shrink: 0;
}
.user-avatar--lg { width: 40px; height: 40px; font-size: 1rem; }
.user-name {
  font-size: .85rem; font-weight: 600; color: var(--text);
  max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.user-menu-toggle .nav-caret { width: 13px; height: 13px; color: var(--muted); transition: transform var(--transition); }
.user-menu-toggle[aria-expanded="true"] .nav-caret { transform: rotate(180deg); }

.user-menu-dropdown {
  position: absolute; top: calc(100% + 10px); right: 0;
  min-width: 240px; background: #fff; border: 1px solid var(--border);
  border-radius: 12px; box-shadow: 0 14px 40px rgba(10,47,87,.16);
  padding: 8px; z-index: 60;
  opacity: 0; visibility: hidden; transform: translateY(6px); pointer-events: none;
  transition: opacity .16s ease, transform .16s ease;
}
.user-menu-toggle[aria-expanded="true"] + .user-menu-dropdown {
  opacity: 1; visibility: visible; transform: translateY(0); pointer-events: auto;
}
.user-menu-head { display: flex; align-items: center; gap: 11px; padding: 8px 10px 12px; border-bottom: 1px solid var(--border); margin-bottom: 6px; }
.user-menu-name { font-weight: 700; font-size: .88rem; color: var(--text); }
.user-menu-email { font-size: .76rem; color: var(--muted); overflow: hidden; text-overflow: ellipsis; max-width: 170px; white-space: nowrap; }
.user-menu-dropdown a {
  display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px;
  font-size: .85rem; font-weight: 500; color: var(--text); transition: background var(--transition);
}
.user-menu-dropdown a:hover { background: var(--brand-light); color: var(--brand); text-decoration: none; }
.user-menu-dropdown a svg { width: 16px; height: 16px; color: var(--muted); }
.user-menu-dropdown a:hover svg { color: var(--brand); }
.user-menu-sep { height: 1px; background: var(--border); margin: 6px 0; }
.user-menu-logout { color: #b91c1c !important; }
.user-menu-logout:hover { background: #fdecec !important; color: #b91c1c !important; }
.user-menu-logout svg { color: #b91c1c !important; }
.mobile-nav-logout { color: #b91c1c !important; font-weight: 600 !important; }

/* On small screens the desktop user menu hides (mobile nav handles it) */
@media (max-width: 860px) {
  .user-menu { display: none; }
}
```

---

## 4. JS — dropdown open/close (mirror the existing nav-dropdown pattern)

```javascript
/* ── User menu dropdown (click + outside-click + Esc) ── */
document.querySelectorAll('.user-menu-toggle').forEach(toggle => {
  toggle.addEventListener('click', (e) => {
    e.stopPropagation();
    const open = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!open));
  });
});
document.addEventListener('click', () => {
  document.querySelectorAll('.user-menu-toggle[aria-expanded="true"]')
    .forEach(t => t.setAttribute('aria-expanded', 'false'));
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape')
    document.querySelectorAll('.user-menu-toggle[aria-expanded="true"]')
      .forEach(t => t.setAttribute('aria-expanded', 'false'));
});
```

---

## 5. Wire the save-job auth flag (already half-built)

The save-job script already reads `window.JR_USER.loggedIn`. Set it server-side in
the layout so the save buttons behave correctly for logged-in vs logged-out users:

```php
<script>
  window.JR_USER = { loggedIn: <?= session()->get('user_id') ? 'true' : 'false' ?> };
</script>
```

When logged in, the "Save" buttons save directly; when logged out, they redirect to
`/login?redirect=…` and resume the save after login (that logic is already in the page).

---

## Notes
- Icons reuse the existing sprite (`#i-chart`, `#i-bookmark`, `#i-doc`, `#i-users`,
  `#i-chev-down`, `#i-arrow-up`) — no new assets needed.
- The dropdown follows the same a11y pattern as the existing nav dropdowns
  (aria-expanded, outside-click, Esc), so it's consistent.
- The dashboard pages themselves live in your authenticated app — this spec only
  covers the nav entry points to them.
- Logout should be a POST in production (CSRF-protected) if your framework requires
  it; shown here as a link for simplicity.
