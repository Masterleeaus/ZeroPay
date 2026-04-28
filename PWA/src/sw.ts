/**
 * ZeroPay Service Worker
 *
 * Extends the Workbox-generated service worker (injected by VitePWA) with:
 *   - Background sync for offline payment submissions
 *   - Push notification subscription and receipt
 *
 * Compile target: tsconfig.sw.json (lib: ES2020, types: @types/serviceworker)
 * VitePWA strategy: 'injectManifest' – set srcDir/filename in vite.config.ts
 * when switching from the default 'generateSW' strategy.
 */

// Background Sync API – not yet in @types/serviceworker
interface SyncEvent extends ExtendableEvent {
  readonly tag: string
  readonly lastChance: boolean
}

// Augment ServiceWorkerGlobalScopeEventMap to include the Background Sync event
interface ServiceWorkerGlobalScopeEventMap {
  sync: SyncEvent
}

// ─── Background Sync ─────────────────────────────────────────────────────────

const PAYMENT_SYNC_TAG = 'zeropay-offline-payment'

self.addEventListener('sync', (event: SyncEvent) => {
  if (event.tag === PAYMENT_SYNC_TAG) {
    event.waitUntil(replayOfflinePayments())
  }
})

async function replayOfflinePayments(): Promise<void> {
  const db = await openPaymentQueue()
  const tx = db.transaction('offlinePayments', 'readwrite')
  const store = tx.objectStore('offlinePayments')
  const allRequests: OfflinePayment[] = await storeGetAll(store)

  for (const req of allRequests) {
    try {
      const response = await fetch('/api/zeropay/payments', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${req.token}` },
        body: JSON.stringify(req.payload),
      })
      if (response.ok) {
        await storeDelete(store, req.id)
      }
    } catch {
      // Network still unavailable – leave in queue for next sync
    }
  }
}

// ─── Push Notifications ───────────────────────────────────────────────────────

self.addEventListener('push', (event: PushEvent) => {
  const data = event.data?.json() ?? {}
  const title: string = data.title ?? 'ZeroPay'
  const options: NotificationOptions = {
    body: data.body ?? 'You have a new notification',
    icon: '/icons/icon-192.png',
    badge: '/icons/icon-192.png',
    data: data.url ? { url: data.url } : undefined,
  }
  event.waitUntil(self.registration.showNotification(title, options))
})

self.addEventListener('notificationclick', (event: NotificationEvent) => {
  event.notification.close()
  const url: string = event.notification.data?.url ?? '/'
  event.waitUntil(
    self.clients
      .matchAll({ type: 'window', includeUncontrolled: true })
      .then((clients: readonly WindowClient[]) => {
        const existing = clients.find((c) => c.url === url)
        if (existing) return existing.focus()
        return self.clients.openWindow(url)
      }),
  )
})

// ─── IndexedDB helpers (no external dependency in SW scope) ──────────────────

interface OfflinePayment {
  id: IDBValidKey
  token: string
  payload: unknown
}

function openPaymentQueue(): Promise<IDBDatabase> {
  return new Promise((resolve, reject) => {
    const req = indexedDB.open('zeropay-offline', 1)
    req.onupgradeneeded = () => {
      req.result.createObjectStore('offlinePayments', { keyPath: 'id', autoIncrement: true })
    }
    req.onsuccess = () => resolve(req.result)
    req.onerror = () => reject(req.error)
  })
}

function storeGetAll(store: IDBObjectStore): Promise<OfflinePayment[]> {
  return new Promise((resolve, reject) => {
    const req = store.getAll()
    req.onsuccess = () => resolve(req.result as OfflinePayment[])
    req.onerror = () => reject(req.error)
  })
}

function storeDelete(store: IDBObjectStore, key: IDBValidKey): Promise<void> {
  return new Promise((resolve, reject) => {
    const req = store.delete(key)
    req.onsuccess = () => resolve()
    req.onerror = () => reject(req.error)
  })
}

