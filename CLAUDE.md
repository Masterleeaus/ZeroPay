# ZeroPay — Claude Code Agent Instructions

## Repository Overview

ZeroPay is a fintech payment platform with three deliverables:

| Deliverable | Location | Status |
|---|---|---|
| Flutter mobile app | `Mobile/` | Rebrand from QRPay → ZeroPay |
| ZeroPay PWA | `PWA/` | Build from scratch |
| ZeroPayModule (Filament v4) | `Modules/ZeroPayModule/` | Build from ExampleModule skeleton |

## Branch

Development branch: `claude/restructure-modules-filament-VrcWJ`
Always push to this branch. Never push to `main` without a PR.

## Key Source Locations

### Donor Code
- `Web/` — QRPay web app split into 11 feature modules (Laravel 9, Blade)
  - `00_App_Core/` — Core config, providers, composer.json
  - `01_Merchant_Advanced/` — Merchant controllers + views
  - `02_Gateway_Checkout/` — QrPay checkout flows
  - `03_Notifications_Mail/` — All email notifications
  - `04_Developer_API/` — REST API controllers
  - `05_Transactions_Charges/` — Transaction models + merchant models
  - `06_Receive_Money/` — Receive money views
  - `07_Manual_Payments_Withdrawals/` — Withdrawals + Flutterwave
  - `08_Future_Expansion/` — Virtual cards, remittance, bill pay, agents
  - `09_Routes_Config_Migrations/` — All routes (666-line admin.php) + 82 migrations
  - `10_Shared_Views_Assets/` — Traits, helpers, constants, public assets

### Module Skeleton
- `Modules/ExampleModule/` — AI-native Filament module blueprint (PASS17)
- `Modules/COPILOT_MODULE_UPGRADE_PROMPT.md` — Rules for agents building modules
- `Modules/AI_CONTROL_MODULE_STANDARD.md` — AI control standards

### Documentation
- `docs/zeropay/` — ZeroPay-specific specs (DB schema, gateway interface, QR spec, bank matching, notifications, payment flow)
- `docs/titan/` — Titan platform docs (workflows, signals, PWA, AI, automation, communications)

### Mobile App (Flutter)
- `Mobile/lib/` — 359 Dart source files
- `Mobile/pubspec.yaml` — Current package name: `qrpay` → target: `zeropay`
- `Mobile/FEATURE_MAP.md` — What to keep/hide for MVP
- `Mobile/android/app/build.gradle` — App ID: `net.appdevs.qrpayuser` → `io.zeropay.app`
- `Mobile/ios/Runner/Info.plist` — Display name: `QRPay` → `ZeroPay`

## Module Architecture Rules (from Copilot prompt)

All writes go through `Actions/`.
Services orchestrate domain flows.
Queries / ViewModels / Presenters compose reads.
Policies and permissions authorize all surfaces.
Every query is tenant-scoped by `company_id`.
Filament resources call Actions, Services, Queries, Policies.
Providers only register routes, config, bindings, events, migrations, views, translations, Filament plugin.

## ZeroPay Database Tables (from docs/zeropay/07_database_schema.md)

```
zeropay_sessions
zeropay_transactions
zeropay_qr_codes
zeropay_bank_accounts
zeropay_bank_deposits
zeropay_gateway_logs
zeropay_webhook_events
zeropay_notifications
```
All tables must include: `company_id`, `created_at`, `updated_at`, `status`.

## Gateway Interface (from docs/zeropay/08_gateway_interface.md)

All gateways implement: `createPayment(session)`, `verifyPayment(reference)`, `handleWebhook(payload)`, `calculateFee(amount)`, `refundPayment(transaction_id)`.

Supported gateways: PayID, BankTransfer, Cash, Stripe, PayPal, Cryptomus.

## QR Payload Spec (from docs/zeropay/09_payid_qr_spec.md)

Dynamic QR encodes: PayID, merchant_name, amount, currency, reference, session_token, expiry_timestamp.
Fallback URL: `/pay/session/{token}`

## Notifications Matrix (from docs/zeropay/11_notifications_matrix.md)

Events: session.created, session.opened, payment.started, payment.pending, payment.completed, payment.failed, session.expiring.
Channels: email, push, portal, omni, Titan Go.

## Mobile Feature Priorities (from Mobile/FEATURE_MAP.md)

**MVP (keep visible):** auth/onboarding, dashboard, request money, received money, make payment, payment QR, payment log, share link, edit payment, merchant/payment flows, notifications, transaction history.

**Phase 2 (hide initially):** add money, withdraw, remittance, agent money out, gift cards, bill pay, mobile topup, virtual cards.

## Package Naming

- Flutter package: `zeropay` (was `qrpay`)
- Android App ID: `io.zeropay.app` (was `net.appdevs.qrpayuser`)
- iOS Bundle ID: `io.zeropay.app` (was `net.appdevs.qrpayuser`)
- Laravel module namespace: `Modules\ZeroPayModule`
- Module alias: `zeropay-module`

## Missing: latest-site.zip

The user referenced `latest-site.zip` (the target Filament v4 Laravel site). This file is NOT in the repository. The ZeroPayModule and PWA modules should be built to the module skeleton standard so they can be dropped into any Filament v4 Laravel installation. When the site zip is uploaded, install the modules per `Modules/ExampleModule/module.json` → update `requires` and `optional_requires`.

## GitHub Agent Notes

- All issues are in `masterleeaus/zeropay`
- Feature branch: `claude/restructure-modules-filament-VrcWJ`
- Use the ExampleModule as the canonical scaffold — copy it, rename, adapt
- Do not modify `Web/` donor code — read only, extract patterns into Modules/
- Mobile renaming is bulk: use `sed` + `change_app_package_name` dart tool
