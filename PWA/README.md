# ZeroPay PWA

Progressive Web App for ZeroPay — built with **Vite + React + TypeScript**.

## Quick start

```bash
cd PWA
cp .env.example .env        # set VITE_API_BASE_URL
npm install
npm run dev                 # http://localhost:3000
```

## Build for production

```bash
npm run build               # output → PWA/dist/
```

## Screens

| Route | Screen |
|-------|--------|
| `/splash` | Splash / loading |
| `/onboard` | Onboarding carousel |
| `/auth/login` | Sign in |
| `/auth/register` | Create account |
| `/auth/forgot-password` | Forgot password |
| `/auth/verify-email` | Email verification |
| `/auth/otp` | OTP verification |
| `/auth/kyc` | KYC / identity upload |
| `/dashboard` | Dashboard (balance + quick actions) |

## Auth flow

```
/splash
  ├─ token exists  → /dashboard
  └─ no token
       ├─ first visit → /onboard → /auth/login
       └─ returning   → /auth/login
                           ↓
                     /auth/register (new user)
                           ↓
                     /auth/verify-email
                           ↓
                     /auth/otp  (if SMS 2FA enabled)
                           ↓
                     /auth/kyc
                           ↓
                     /dashboard  ← protected by ProtectedRoute
```

## Environment variables

| Variable | Description | Default |
|----------|-------------|---------|
| `VITE_API_BASE_URL` | Base URL of the ZeroPay API server | `''` (same origin via Vite proxy) |

## API endpoints consumed

| Method | Path | Description |
|--------|------|-------------|
| `POST` | `/api/auth/login` | Login — returns `{ token, user }` |
| `POST` | `/api/auth/register` | Register — returns `{ token, user }` |
| `POST` | `/api/auth/forgot-password` | Send reset email |
| `POST` | `/api/auth/verify-email` | Verify email token |
| `POST` | `/api/auth/verify-otp` | Verify OTP code |
| `POST` | `/api/auth/kyc` | Submit KYC documents (multipart) |
| `POST` | `/api/auth/logout` | Invalidate token |

## Token storage

- `auth_token` stored in `localStorage` (Bearer token added to all API requests)
- `user` JSON stored in `localStorage`
- On 401 response both keys are cleared and user is redirected to `/auth/login`
- Unauthenticated access to any protected route (`/dashboard`, etc.) redirects to `/auth/login`
