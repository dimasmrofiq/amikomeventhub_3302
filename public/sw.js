const CACHE_NAME = 'amikomeventhub-cache-v1';
const urlsToCache = [
    '/',
    '/manifest.json',
    // Anda bisa menambahkan rute aset statis lainnya di sini seperti CSS atau JS
];

// Install Service Worker dan simpan cache statis
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

// Intercept Request jaringan (Strategi: Network First, Fallback to Cache)
self.addEventListener('fetch', event => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});

// Hapus cache lama saat ada versi baru
self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});