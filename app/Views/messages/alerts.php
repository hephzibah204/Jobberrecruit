<?php
$alertUser = auth()->user();
if (!$alertUser || !in_array($alertUser->user_type, ['employer', 'job_seeker'], true)) return;
$alertPrefix = $alertUser->user_type === 'employer' ? 'employer' : 'candidate';
?>
<style>
.jr-msg-badge{display:inline-flex;align-items:center;justify-content:center;background:#dc2626;color:white;border-radius:20px;min-width:20px;padding:2px 6px;font:700 12px/18px system-ui;margin-left:6px}.jr-msg-notice[hidden],.jr-msg-badge[hidden]{display:none!important}
.jr-msg-notice{display:block;padding:12px;color:#0861a9;font-weight:600}
</style>
<script>
(() => {
 const endpoint = <?= json_encode(base_url($alertPrefix . '/messages/alerts')) ?>;
 const inbox = <?= json_encode(base_url($alertPrefix . '/messages')) ?>;
 const userKey = <?= json_encode('jr-message-alert-' . $alertUser->id) ?>;
 const originalTitle = document.title;
 let latest = 0, busy = false, stopped = false;
 try { latest = Number(sessionStorage.getItem(userKey)) || 0; } catch (_) {}

 document.querySelectorAll('a.sb-link').forEach(link => {
   if (link.href.replace(/\/$/, '') === inbox.replace(/\/$/, '')) {
     const badge = document.createElement('span'); badge.className = 'jr-msg-badge'; badge.hidden = true; link.append(badge);
   }
 });
 const notice = document.createElement('a'); notice.href = inbox; notice.className = 'jr-msg-notice'; notice.hidden = true;
 const notificationList = document.querySelector('#notif-drop .tn-list');
 if (notificationList) notificationList.prepend(notice);
 const notificationPage = document.querySelector('.nc-wrap');
 const pageNotice = notice.cloneNode(true);
 if (notificationPage) notificationPage.before(pageNotice);
 const bell = document.querySelector('#notif-drop > button');
 const bellBadge = document.createElement('span'); bellBadge.className = 'jr-msg-badge'; bellBadge.hidden = true;
 if (bell) bell.append(bellBadge);

 async function poll() {
   if (busy || stopped || document.hidden) return;
   busy = true;
   try {
     const response = await fetch(endpoint, {headers:{'X-Requested-With':'XMLHttpRequest'},cache:'no-store'});
     if ([401,403].includes(response.status) || response.redirected) { stopped = true; return; }
     if (!response.ok) throw new Error('unavailable');
     const data = await response.json();
     if (!Number.isInteger(data.count) || !Array.isArray(data.items)) throw new Error('invalid response');
     document.querySelectorAll('.jr-msg-badge').forEach(b => { b.hidden = data.count === 0; b.textContent = data.count > 99 ? '99+' : String(data.count); });
     const emptyNotifications = document.getElementById('jr-notifications-empty');
     if (emptyNotifications) emptyNotifications.hidden = data.count > 0;
     document.title = data.count ? `(${data.count}) ${originalTitle}` : originalTitle;
     [notice,pageNotice].forEach(n => { n.hidden = data.count === 0; n.textContent = `${data.count} unread message${data.count === 1 ? '' : 's'} — Open inbox →`; });
   } catch (_) {
   } finally { busy = false; }
 }
 poll();
 setInterval(poll, 10000);
 document.addEventListener('visibilitychange', () => { if (!document.hidden) poll(); });
 window.addEventListener('focus', poll);
})();
</script>
