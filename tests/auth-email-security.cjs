const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
(async () => {
 const path = 'app/Views/auth/partials/resend_security.php';
 let source = fs.readFileSync(path, 'utf8')
  .replace(/<\?= json_encode\(\(string\) env\('recaptcha_site_key'\)[\s\S]*?\?>/g, '"site-key-test"')
  .replace(/<\?= json_encode\(csrf_token\(\)\) \?>/g, '"csrf_name"')
  .replace(/<\?= json_encode\(csrf_hash\(\)\) \?>/g, '"csrf_value"')
  .replace(/<\?= json_encode\(base_url\('auth\/resend-verification'\)\) \?>/g, '"https://example.test/auth/resend-verification"');
 const script = [...source.matchAll(/<script>([\s\S]*?)<\/script>/g)][0][1];
 const calls = [];
 const context = {Promise, URLSearchParams, fetch: (url, options) => { calls.push([url, options]); return Promise.resolve({ok: true}); }};
 context.window = context;
 vm.createContext(context);
 vm.runInContext(script, context);
 await assert.rejects(context.requestVerificationResend(), /unavailable/);
 assert.equal(calls.length, 0, 'Missing CAPTCHA must not submit');
 context.grecaptcha = {ready: cb => cb(), execute: (key, options) => { assert.equal(options.action, 'resend_verification'); return Promise.resolve('verified-token'); }};
 await context.requestVerificationResend();
 assert.equal(calls[0][1].method, 'POST');
 assert.equal(calls[0][1].body.get('csrf_name'), 'csrf_value');
 assert.equal(calls[0][1].body.get('g-recaptcha-response'), 'verified-token');
 context.grecaptcha.execute = () => Promise.reject(new Error('CAPTCHA failed'));
 await assert.rejects(context.requestVerificationResend(), /CAPTCHA failed/);
 assert.equal(calls.length, 1, 'Failed CAPTCHA must not submit');
 const register = fs.readFileSync('app/Views/auth/register.php', 'utf8');
 assert(!register.includes('dev-bypass'), 'Registration must not fall back to a development token');
 for (const file of ['app/Views/auth/verify_email.php', 'app/Views/auth/verify_email_generic.php']) {
  const view = fs.readFileSync(file, 'utf8');
  assert(view.indexOf("view('auth/partials/resend_security')") > view.indexOf("section('scripts')"), 'Security helper must render inside scripts section');
  assert(view.includes('window.requestVerificationResend()'));
 }
 console.log('PASS: resend POST/CSRF/CAPTCHA, missing or failed CAPTCHA submits nothing, no signup bypass, and helper script renders in both pages.');
})().catch(error => { console.error(error); process.exitCode = 1; });