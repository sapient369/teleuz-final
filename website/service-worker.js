const CACHE_NAME = 'teleuz-cache-v1';
const STATIC_EXTENSIONS = ['.js','.css','.png','.jpg','.jpeg','.gif','.svg','.woff','.woff2','.ttf','.eot','.json'];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(caches.open(CACHE_NAME));
});

self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') {
    return;
  }

  const url = new URL(event.request.url);
  if (STATIC_EXTENSIONS.some(ext => url.pathname.endsWith(ext))) {
    event.respondWith(
      caches.match(event.request).then(cached => {
        return cached || fetch(event.request).then(response => {
          const resClone = response.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(event.request, resClone));
          return response;
        });
      })
    );
  } else {
    event.respondWith(fetch(event.request));
  }
});