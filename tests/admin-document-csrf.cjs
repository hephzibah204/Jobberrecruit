const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
for (const file of ['app/Views/admin/employer.php', 'app/Views/admin/employers.php', 'app/Views/admin/partials/employer_documents.php']) {
  const source = fs.readFileSync(file, 'utf8')
    .replace(/<\?= csrf_header\(\) \?>/g, 'X-CSRF-TOKEN')
    .replace(/<\?= csrf_hash\(\) \?>/g, 'session-test-token')
    .replace(/<\?[\s\S]*?\?>/g, 'fixture');
  const calls = [];
  const context = {
    confirm: () => true, prompt: () => 'Document is unreadable',
    fetch: (url, options) => { calls.push([url, options]); return Promise.resolve({json: () => Promise.resolve({success: false, message: 'test'})}); },
    document: {getElementById: () => ({addEventListener() {}}), querySelectorAll: () => [], addEventListener() {}},
    toastr: {error() {}, success() {}}, console, AbortController,
  };
  context.window = context;
  vm.createContext(context);
  for (const script of source.matchAll(/<script>([\s\S]*?)<\/script>/g)) vm.runInContext(script[1], context);
  context.verifyDocument(4);
  context.rejectDocument(4);
  assert.equal(calls.length, 2, file);
  for (const [url, options] of calls) {
    assert.equal(options.method, 'POST', file);
    assert.equal(options.headers['X-CSRF-TOKEN'], 'session-test-token', file);
    assert.equal(JSON.parse(options.body).document_id, 4, file);
  }
}
console.log('PASS: approval and rejection send the session CSRF header from all three admin document interfaces.');