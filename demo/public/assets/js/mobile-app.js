/* ==========================================================================
   Section 8 — Native Mobile App Feel (behaviour)
   Requires: Bootstrap 5 bundle (Offcanvas), jQuery (already loaded by theme).
   Progressive enhancement: every feature no-ops if its markup is absent, so
   this one file is safe to load on every page.
   ========================================================================== */
(function () {
  'use strict';

  var MOBILE = function () { return window.matchMedia('(max-width: 767.98px)').matches; };

  /* ----------------------------------------------------------------------
     8.2 — Filter bottom sheet
     The filter form lives in ONE place in the DOM (.sidebar-filters). On
     mobile we move that node into the offcanvas on open and move it back on
     close, so the sidebar and the sheet are never two desyncing copies.
     ---------------------------------------------------------------------- */
  function initFilterSheet() {
    var sheet = document.getElementById('jrFilterSheet');
    var fab = document.getElementById('jrFilterFab');
    var filters = document.querySelector('.sidebar-filters');
    if (!sheet || !fab || !filters || !window.bootstrap) return;

    var homeParent = filters.parentNode;          // remember original slot
    var homeNext = filters.nextSibling;
    var sheetBody = sheet.querySelector('[data-filter-target]');
    var oc = bootstrap.Offcanvas.getOrCreateInstance(sheet);

    sheet.addEventListener('show.bs.offcanvas', function () {
      sheetBody.appendChild(filters);
    });
    sheet.addEventListener('hidden.bs.offcanvas', function () {
      // restore to exactly where it came from
      homeParent.insertBefore(filters, homeNext);
    });

    fab.addEventListener('click', function () { oc.show(); });

    // "Show N results" + "Clear all" footer buttons
    var showBtn = sheet.querySelector('[data-sheet-apply]');
    if (showBtn) showBtn.addEventListener('click', function () { oc.hide(); });

    var clearBtn = sheet.querySelector('[data-sheet-clear]');
    if (clearBtn) clearBtn.addEventListener('click', function () {
      var reset = filters.querySelector('.link-reset');
      if (reset && reset.href) { window.location.href = reset.href; }
    });

    updateFilterCount(fab);
    // Recount whenever a filter control changes (theme JS handles the actual fetch)
    document.addEventListener('change', function (e) {
      if (e.target.closest('.sidebar-filters')) updateFilterCount(fab);
    });
  }

  // Count active (non-default) filters and reflect it on the FAB badge.
  function updateFilterCount(fab) {
    var filters = document.querySelector('.sidebar-filters');
    if (!filters) return;
    var count = 0;
    filters.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
      if (cb.checked && cb.value !== 'all') count++;
    });
    filters.querySelectorAll('select').forEach(function (sel) {
      if (sel.value) count++;
    });
    var badge = fab.querySelector('.jr-fab-count');
    if (badge) badge.textContent = count;
    fab.classList.toggle('has-filters', count > 0);
  }

  // Keep the sheet's live "Show N results" label in sync with the page's
  // result count (dynamically sourced — never hardcoded, per Section 10).
  function syncResultCount() {
    var src = document.querySelector('[data-result-count]');
    var dest = document.querySelector('[data-sheet-count]');
    if (src && dest) dest.textContent = src.getAttribute('data-result-count');
  }

  /* ----------------------------------------------------------------------
     8.3 — Sticky bottom action bar
     Hide the fixed bar while the in-flow Apply/Enrol CTA is visible, so the
     user never sees two competing primary CTAs at the same scroll position.
     ---------------------------------------------------------------------- */
  function initActionBar() {
    var bar = document.querySelector('.jr-action-bar');
    if (!bar) return;
    document.body.classList.add('jr-has-action-bar');

    var anchor = document.querySelector('[data-inflow-cta]');
    if (anchor && 'IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          bar.classList.toggle('is-hidden', en.isIntersecting);
        });
      }, { rootMargin: '0px 0px -40% 0px' });
      io.observe(anchor);
    }
  }

  function init() {
    initFilterSheet();
    syncResultCount();
    initActionBar();
  }

  if (document.readyState !== 'loading') init();
  else document.addEventListener('DOMContentLoaded', init);
})();
