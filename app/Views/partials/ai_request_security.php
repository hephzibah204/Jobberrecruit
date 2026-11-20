<script>
(function() {
  if (window.aiRequestSecurityInstalled) return;
  window.aiRequestSecurityInstalled = true;
  const tokenHeader = <?= json_encode(csrf_header()) ?>;
  const token = <?= json_encode(csrf_hash()) ?>;
  function protects(url, method) {
    const target = new URL(url, location.href);
    return target.origin === location.origin && !/^(GET|HEAD|OPTIONS)$/i.test(method || 'GET') &&
      /\/(candidate\/(resumes|career-tools|coach)|api\/interview)(\/|$)/.test(target.pathname);
  }
  const nativeFetch = window.fetch;
  window.fetch = function(input, options) {
    const url = typeof input === 'string' || input instanceof URL ? input : input.url;
    const method = options && options.method || input.method || 'GET';
    if (protects(url, method)) {
      options = Object.assign({}, options);
      const headers = new Headers(options.headers || input.headers || {});
      headers.set(tokenHeader, token);
      headers.set('X-Requested-With', 'XMLHttpRequest');
      options.headers = headers;
    }
    return nativeFetch.call(this, input, options);
  };
  const open = XMLHttpRequest.prototype.open, send = XMLHttpRequest.prototype.send, setHeader = XMLHttpRequest.prototype.setRequestHeader;
  XMLHttpRequest.prototype.open = function(method, url) { this.aiProtected = protects(url, method); this.aiHasToken = false; return open.apply(this, arguments); };
  XMLHttpRequest.prototype.setRequestHeader = function(name, value) {
    if (this.aiProtected && name.toLowerCase() === tokenHeader.toLowerCase()) { this.aiHasToken = true; value = token; }
    return setHeader.call(this, name, value);
  };
  XMLHttpRequest.prototype.send = function() {
    if (this.aiProtected && !this.aiHasToken) setHeader.call(this, tokenHeader, token);
    return send.apply(this, arguments);
  };
})();
</script>
