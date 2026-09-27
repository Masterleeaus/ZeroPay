# ZeroPay

**A multi-surface payment orchestration platform for QR, wallet, bank-transfer and gateway-based payments.**

ZeroPay is a payment operating layer designed to coordinate the full payment lifecycle across mobile, web, PWA and administrative surfaces. It combines payment sessions, QR-driven transactions, wallets, bank-transfer matching, pluggable payment gateways, event-driven notifications and operational tooling with an extensible automation and intelligence layer.

Rather than coupling an application directly to a single payment processor, ZeroPay separates payment intent, orchestration and provider execution. Applications interact with a consistent ZeroPay domain while gateway adapters handle provider-specific behaviour.

## Core architecture

```text
Customer / Operator / Connected Application
                    |
                    v
          Mobile / PWA / Web / API
                    |
                    v
             Payment Sessions
                    |
          +---------+---------+
          |                   |
          v                   v
      QR / Wallet       Bank Transfer
          |                   |
          +---------+---------+
                    |
                    v
          Payment Orchestration
                    |
          Gateway Contract Layer
                    |
      +-------------+-------------+
      |       |       |      |    |
    Stripe  PayPal  PayID  Bank  ...
                    Transfer
                    |
                    v
        Events / Notifications
                    |
                    v
       Operations + Reconciliation
```

## Capabilities

### Payment orchestration

- Payment-session lifecycle management
- Create, open, expire, complete and fail payment flows
- Transaction records and history
- QR-code payment payloads and scanner-driven flows
- Receive-money and request-money workflows
- Wallet and balance operations
- REST API integration surface
- Webhook handling and payment event processing

### Gateway abstraction

ZeroPay uses a gateway contract rather than embedding provider logic throughout the application. The repository contains work for multiple payment paths including:

- Stripe
- PayPal
- Cryptomus
- cash/manual payment flows
- PayID
- direct bank transfer

The adapter model is intended to make payment providers replaceable and allow additional gateways to be integrated without redesigning the core transaction domain.

### Bank-transfer reconciliation

The platform includes a bank-transfer matching pathway for reconciling incoming deposits against expected payments, together with an unmatched-deposit review workflow for cases requiring operator intervention.

### Mobile application

The Flutter application provides a native mobile payment surface for Android and iOS, including QR-oriented payment interaction, authentication/onboarding work, transaction workflows and backend API integration.

### Progressive Web App

A dedicated PWA surface extends ZeroPay beyond native mobile installation and includes work around offline-capable payment flows and push-notification integration.

### Operations and control

The backend module contains administrative and operational capabilities including:

- payment-session management
- transaction resources
- unmatched-deposit review
- KPI widgets
- control-panel functionality
- notification handling
- event-driven processing

### Intelligence and automation

`Modules/ZeroPayModule` includes dedicated `AI`, `Agents` and `Automation` domains alongside the payment engine. This provides an architectural foundation for intelligent payment operations such as exception triage, reconciliation assistance, workflow automation and operational decision support while keeping the payment domain itself explicit and inspectable.

## Repository structure

```text
ZeroPay/
├── Modules/
│   └── ZeroPayModule/     # Payment domain, APIs, gateways and operations
├── Mobile/                # Flutter Android/iOS application
├── PWA/                   # Progressive web application
├── Web/                   # Web-facing surface
├── mobile/                # Legacy/alternate mobile tree retained for reconciliation
└── .github/workflows/     # Module and Flutter CI
```

> The repository currently retains both `Mobile/` and `mobile/` trees because they contain divergent historical material. They should be reconciled deliberately rather than deleting one solely on filename casing.

## ZeroPayModule

The backend is organised as a modular application domain rather than a collection of payment-provider calls. Major areas include:

```text
AI/
Actions/
Adapters/
Agents/
Automation/
Config/
Console/
Contracts/
Data/
Database/
Docs/
Events/
Exceptions/
```

This separation keeps orchestration, provider adapters, domain contracts, persistence, automation and operational intelligence independently maintainable.

## Mobile development

The canonical Flutter application under `Mobile/` targets Flutter 3.27+.

```bash
cd Mobile
flutter pub get
flutter run
```

Release builds:

```bash
flutter build apk --release
flutter build ios --release
```

## Engineering principles

ZeroPay is being developed around several core principles:

1. **Provider independence** — payment applications should not be structurally bound to one processor.
2. **Explicit transaction state** — payment state transitions should be observable and auditable.
3. **Multiple payment rails** — QR, wallet, gateway and bank-transfer flows belong behind one operating model.
4. **Operational recovery** — unmatched or exceptional payments require first-class workflows rather than manual database intervention.
5. **Multi-surface access** — the same payment system should support native mobile, PWA, web, API and operator interfaces.
6. **Automation without obscurity** — intelligent automation should assist payment operations without hiding the underlying transaction state or execution path.

## Status

ZeroPay is an active engineering project. The repository contains implemented application surfaces and payment-domain components alongside areas still being consolidated. Production deployment requires environment-specific payment-provider credentials, security configuration, webhook configuration and infrastructure validation.

## Technology

The project currently spans:

- PHP / Laravel-style modular backend architecture
- Filament administrative resources
- Flutter / Dart mobile application
- Progressive Web App
- REST APIs and webhooks
- Firebase/mobile notification integration
- GitHub Actions CI

---

ZeroPay explores a simple architectural idea: **payments should be an orchestrated business capability, not a collection of unrelated gateway integrations.**
