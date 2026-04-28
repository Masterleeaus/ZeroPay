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

## Target Site — `Site/` (Laravel 12 + Filament 4)

The Filament v4 Laravel site is extracted at `Site/`. Build ZeroPayModule to install here.

### Site Stack
- **Laravel**: 12.57.0 (`^12.0`)
- **PHP**: 8.4
- **Filament**: 4.11.1 (`^4.0`)
- **Module system**: `nwidart/laravel-modules` 12.0.5
- **Filament module panel**: `savannabits/filament-modules` 5.1.0
- **Permissions**: `spatie/laravel-permission` 7.3 + `bezhansalleh/filament-shield` 4.2
- **Settings**: `spatie/laravel-settings` via `filament/spatie-laravel-settings-plugin`
- **Activity log**: `spatie/laravel-activitylog`
- **Charts**: `leandrocfe/filament-apex-charts` (already installed)
- **Excel export**: `pxlrbt/filament-excel` (already installed)
- **Stripe**: `stripe/stripe-php` 20.1.0 (already installed — reuse in StripeGateway)
- **Real-time**: `laravel/reverb` (WebSocket)
- **Inertia/Vue**: `inertiajs/inertia-laravel`

### Existing Module to Follow: `Site/Modules/CRMCore/`
CRMCore is the canonical reference module. Key patterns:
- `module.json` has a **single** provider entry: `Modules\\CRMCore\\Providers\\ModuleServiceProvider`
- `ModuleServiceProvider::register()` bootstraps all sub-providers via `$this->app->register()`
- `ModuleServiceProvider::boot()` loads migrations, views, translations
- Sub-providers: RouteServiceProvider, EventServiceProvider, PolicyServiceProvider,
  RepositoryServiceProvider, AutomationServiceProvider, WorkflowServiceProvider,
  TenancyServiceProvider, BillingServiceProvider, SearchServiceProvider, FilamentServiceProvider

### Filament Admin Panel
`Site/app/Providers/Filament/AdminPanelProvider.php`
- Panel ID: `admin`, path: `/admin`
- Brand: `TITAN ZERO — Admin`
- CRMCorePlugin already registered
- **Add ZeroPayModulePlugin** here when module is ready:
  ```php
  ->plugin(ZeroPayModulePlugin::make())
  ```

### Installing ZeroPayModule
```bash
# Migrations
php artisan migrate --path=Modules/ZeroPayModule/Database/Migrations

# Permissions
php artisan db:seed --class="Modules\ZeroPayModule\Database\Seeders\ZeroPayModulePermissionSeeder"
php artisan shield:generate --resource=ZeroPaySessionResource

# Enable in nwidart config
# (auto-detected if module.json is valid)
php artisan module:list
```

### Unapplied Delta Zips in Site/
Three delta zips still need applying — see issue #53:
- `crmcore_customer_panel_delta.zip` — CRMCore resource updates
- `dashboard_delta.zip` — TitanOverviewWidget
- `fieldops-hub-filament4-esoft-distinct-template-routing-delta-v13.zip` — CMS templates

## GitHub Agent Notes

- All issues are in `masterleeaus/zeropay`
- Feature branch: `claude/restructure-modules-filament-VrcWJ`
- **Reference module**: `Site/Modules/CRMCore/` — follow this exact pattern
- Use the ExampleModule skeleton (`Modules/ExampleModule/`) for the full file list
- Do not modify `Web/` donor code — read only, extract patterns into Modules/
- Mobile renaming is bulk: use `sed` + `change_app_package_name` dart tool
- ZeroPayModule `module.json` must have single provider entry (CRMCore pattern)
