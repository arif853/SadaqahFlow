const staticCacheName = 'cpds-static-v2'; // Incremented version
const dynamicCacheName = 'cpds-dynamic-v2';

const assets = [
  '/assets/css/style.css',
  '/assets/css/vendor/bootstrap.css',
  '/assets/js/custom-script.js',
  '/assets/images/logo-2.png',
  '/manifest.json'
];

// URLs that should NEVER be cached
const neverCache = [
  '/login',
  '/logout',
  '/register',
  '/password',
  '/email/verification',
  '/dashboard',
  '/members',
  '/users',
  '/khedmots',
  '/fund',
  '/reports'
];

// Cache size limit function
const limitCacheSize = (name, size) => {
  caches.open(name).then(cache => {
    cache.keys().then(keys => {
      if (keys.length > size) {
        cache.delete(keys[0]).then(limitCacheSize(name, size));
      }
    });
  });
};

// Install event
self.addEventListener('install', evt => {
  evt.waitUntil(
    caches.open(staticCacheName)
      .then(cache => {
        console.log('Caching app shell');
        return cache.addAll(assets);
      })
      .catch(err => console.error('Cache failed:', err))
  );
  self.skipWaiting();
});

// Activate event - clean old caches
self.addEventListener('activate', evt => {
  evt.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(keys
        .filter(key => key !== staticCacheName && key !== dynamicCacheName)
        .map(key => caches.delete(key))
      );
    })
  );
  self.clients.claim();
});

// Fetch event - network first for auth, cache for static assets
self.addEventListener('fetch', evt => {
  const { request } = evt;
  const url = new URL(request.url);
  
  // Skip non-http requests
  if (url.protocol !== 'http:' && url.protocol !== 'https:') return;
  
  // Never cache authentication, logout, or POST requests
  const shouldNeverCache = 
    request.method !== 'GET' ||
    neverCache.some(path => url.pathname.includes(path)) ||
    url.pathname.includes('sanctum/csrf-cookie');
  
  if (shouldNeverCache) {
    // Always fetch from network, never cache
    evt.respondWith(
      fetch(request).catch(() => {
        // If offline and trying to access auth page, redirect to offline page
        return new Response('You are offline. Please check your connection.', {
          status: 503,
          statusText: 'Service Unavailable',
          headers: new Headers({
            'Content-Type': 'text/html'
          })
        });
      })
    );
    return;
  }
  
  // For static assets, use cache-first strategy
  if (url.pathname.startsWith('/assets/') || url.pathname.startsWith('/storage/')) {
    evt.respondWith(
      caches.match(request).then(cacheRes => {
        return cacheRes || fetch(request).then(fetchRes => {
          return caches.open(dynamicCacheName).then(cache => {
            cache.put(request.url, fetchRes.clone());
            limitCacheSize(dynamicCacheName, 50);
            return fetchRes;
          });
        });
      })
    );
    return;
  }
  
  // For other GET requests, use network-first strategy
  evt.respondWith(
    fetch(request)
      .then(fetchRes => {
        // Cache successful responses
        if (fetchRes.ok) {
          return caches.open(dynamicCacheName).then(cache => {
            cache.put(request.url, fetchRes.clone());
            limitCacheSize(dynamicCacheName, 30);
            return fetchRes;
          });
        }
        return fetchRes;
      })
      .catch(() => {
        // Fallback to cache only if network fails
        return caches.match(request);
      })
  );
});