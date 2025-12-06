// Service Worker for DSOG STORES - Auto Update Version
const APP_VERSION = '2.0.0';
const CACHE_NAME = `dsog-stores-${APP_VERSION}`;

// Install - Force update when version changes
self.addEventListener('install', event => {
  console.log(`Service Worker v${APP_VERSION}: Installing...`);
  
  self.skipWaiting(); // Activate immediately
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        // Cache the manifest FIRST
        return fetch('/manifest.json')
          .then(response => {
            cache.put('/manifest.json', response);
            return cache.addAll([
              '/',
              '/index.html',
              // Add other critical files here
            ]);
          });
      })
      .then(() => {
        console.log(`Service Worker v${APP_VERSION}: Installed successfully`);
      })
  );
});

// Activate - Clean old caches and update clients
self.addEventListener('activate', event => {
  console.log(`Service Worker v${APP_VERSION}: Activating...`);
  
  event.waitUntil(
    Promise.all([
      // Clean up old caches
      caches.keys().then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            if (cacheName !== CACHE_NAME) {
              console.log(`Deleting old cache: ${cacheName}`);
              return caches.delete(cacheName);
            }
          })
        );
      }),
      
      // Check if app needs update notification
      fetch('/manifest.json')
        .then(response => response.json())
        .then(manifest => {
          // Store current version
          localStorage.setItem('dsog_app_version', manifest.version || APP_VERSION);
          
          // Notify all clients about update
          self.clients.matchAll().then(clients => {
            clients.forEach(client => {
              client.postMessage({
                type: 'APP_UPDATE_AVAILABLE',
                version: manifest.version || APP_VERSION,
                oldVersion: localStorage.getItem('dsog_previous_version') || '1.0.0'
              });
            });
          });
          
          // Store previous version for comparison
          localStorage.setItem('dsog_previous_version', manifest.version || APP_VERSION);
        })
    ]).then(() => {
      console.log(`Service Worker v${APP_VERSION}: Activated`);
      return self.clients.claim();
    })
  );
});

// Fetch with version checking
self.addEventListener('fetch', event => {
  // Don't cache API requests
  if (event.request.url.includes('script.google.com')) {
    event.respondWith(fetch(event.request));
    return;
  }
  
  // For manifest, always fetch fresh
  if (event.request.url.includes('manifest.json')) {
    event.respondWith(
      fetch(event.request)
        .then(response => {
          const responseClone = response.clone();
          
          // Check if manifest changed
          responseClone.json().then(manifest => {
            const storedVersion = localStorage.getItem('dsog_app_version');
            if (storedVersion !== manifest.version) {
              console.log(`Manifest updated from ${storedVersion} to ${manifest.version}`);
              // Trigger update flow
              self.registration.update().then(() => {
                self.skipWaiting();
              });
            }
          });
          
          return response;
        })
        .catch(() => caches.match(event.request))
    );
    return;
  }
  
  // Cache-first for other resources
  event.respondWith(
    caches.match(event.request)
      .then(cachedResponse => {
        if (cachedResponse) {
          return cachedResponse;
        }
        
        return fetch(event.request)
          .then(response => {
            // Don't cache if not successful
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }
            
            // Cache the new resource
            const responseToCache = response.clone();
            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(event.request, responseToCache);
              });
            
            return response;
          });
      })
  );
});

// Listen for messages from the page
self.addEventListener('message', event => {
  if (event.data.type === 'CHECK_UPDATE') {
    fetch('/manifest.json')
      .then(response => response.json())
      .then(manifest => {
        event.ports[0].postMessage({
          currentVersion: localStorage.getItem('dsog_app_version'),
          newVersion: manifest.version
        });
      });
  }
  
  if (event.data.type === 'FORCE_UPDATE') {
    self.skipWaiting();
    self.registration.update();
  }
});

// Periodically check for updates
self.addEventListener('periodicsync', event => {
  if (event.tag === 'check-updates') {
    event.waitUntil(checkForUpdates());
  }
});

async function checkForUpdates() {
  try {
    const response = await fetch('/manifest.json');
    const manifest = await response.json();
    const storedVersion = localStorage.getItem('dsog_app_version');
    
    if (storedVersion !== manifest.version) {
      console.log(`Update found: ${storedVersion} → ${manifest.version}`);
      
      // Notify all clients
      const clients = await self.clients.matchAll();
      clients.forEach(client => {
        client.postMessage({
          type: 'FORCE_UPDATE_REQUIRED',
          newVersion: manifest.version
        });
      });
      
      // Update service worker
      self.registration.update();
    }
  } catch (error) {
    console.error('Update check failed:', error);
  }
}
