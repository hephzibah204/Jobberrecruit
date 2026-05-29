const CACHE_NAME = "jobberrecruit-v2";
const urlsToCache = [
  "/css/style.css",
  "/js/scripts.js",
  "/images/logo.png",
];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(urlsToCache)),
  );
  self.skipWaiting();
});

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log("Deleting old cache:", cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

self.addEventListener("fetch", (event) => {
  // Only handle GET requests
  if (event.request.method !== "GET") {
    return;
  }

  const url = new URL(event.request.url);

  // Bypass cache completely for dynamic/secured routes, ping, and PHP scripts
  if (
    url.pathname.includes("/admin") ||
    url.pathname.includes("/employer") ||
    url.pathname.includes("/candidate") ||
    url.pathname.includes("/login") ||
    url.pathname.includes("/register") ||
    url.pathname.includes("/ping") ||
    url.pathname.endsWith(".php")
  ) {
    event.respondWith(fetch(event.request));
    return;
  }

  // Network-First with Cache Fallback strategy
  event.respondWith(
    fetch(event.request)
      .then((response) => {
        // If response is valid, cache static assets dynamically
        if (
          response.status === 200 &&
          (url.pathname.endsWith(".css") ||
            url.pathname.endsWith(".js") ||
            url.pathname.endsWith(".png") ||
            url.pathname.endsWith(".jpg") ||
            url.pathname.endsWith(".jpeg") ||
            url.pathname.endsWith(".svg") ||
            url.pathname.endsWith(".ico") ||
            url.pathname.includes("/fonts/") ||
            url.pathname.includes("/webfonts/"))
        ) {
          const responseToCache = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseToCache);
          });
        }
        return response;
      })
      .catch(() => {
        // If network fails, try to serve from cache
        return caches.match(event.request).then((cachedResponse) => {
          if (cachedResponse) {
            return cachedResponse;
          }
          // If offline and request is for page, we could return a fallback or nothing
          return null;
        });
      })
  );
});

