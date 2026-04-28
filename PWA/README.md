# ZeroPay PWA

Standalone installable web app mirroring the ZeroPay mobile MVP feature set.

## Technology Stack

- **React 19** + **TypeScript** — UI framework
- **Vite 6** — build tooling with fast HMR
- **vite-plugin-pwa** — service worker and `manifest.webmanifest` generation
- **workbox-window** — offline cache strategies
- **react-router-dom** — SPA routing
- **idb** — IndexedDB wrapper (offline mode)
- **@zxing/browser** — QR code scanner
- **qrcode** — QR code generation
- **axios** — API calls

## Getting Started

```bash
cd PWA
npm install
npm run dev      # starts dev server at http://localhost:5173
```

## Build

```bash
npm run build    # produces dist/ including dist/manifest.webmanifest and dist/sw.js
npm run preview  # preview the production build locally
```

## PWA Features

- Installable (add to home screen) on Chrome/Edge/Safari
- Offline-capable via Workbox precaching
- `manifest.webmanifest` with ZeroPay branding (name, theme color `#1a56db`, icons)
- Service worker registered via `vite-plugin-pwa` (auto-update strategy)
- Runtime caching for `/api/` calls (NetworkFirst, 24h TTL)

## Screens (MVP)

| Route          | Description                       |
|----------------|-----------------------------------|
| `/login`       | Sign-in screen                    |
| `/dashboard`   | Balance overview + quick actions  |
| `/send`        | Send money form                   |
| `/request`     | Request money form                |
| `/qr`          | Show / scan payment QR code       |
| `/history`     | Transaction history               |
| `/notifications` | Push notification inbox         |
| `/profile`     | User profile & settings           |

## Module PWA Manifest

The module-level PWA manifest template lives in
`Modules/ZeroPayModule/PWA/pwa.manifest.json` and defines module-specific
capabilities (offline mode, screens, agent settings).
