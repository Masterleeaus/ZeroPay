# ZeroPay Mobile App

A Flutter mobile application for ZeroPay - a QR-based payment platform. The canonical source and CI path is `Mobile/`; the historical duplicate is retained separately as `mobile-legacy/`.

## Requirements

- Flutter **3.27.0** or later
- Dart SDK (bundled with Flutter)
- Android Studio / Xcode for device/emulator support

## Package

- **Package ID (Android):** `io.zeropay.app`
- **Bundle ID (iOS):** `io.zeropay.app`

## Getting Started

### 1. Install dependencies

```bash
flutter pub get
```

### 2. Run on a connected device or emulator

```bash
flutter run
```

### 3. Build a release APK

```bash
flutter build apk --release
```

### 4. Build for iOS

```bash
flutter build ios --release
```

## Configuration

- Update `lib/backend/services/api_endpoint.dart` with the ZeroPay backend URL before release.
- The public canonical tree does not include Android `google-services.json`; provide Firebase platform configuration through a private/local build setup only when the deployment enables Firebase services. The Google Services plugin is applied only when that file is present.
- Android debug builds do not require `key.properties`. Production release signing uses `Mobile/android/key.properties` with `keyAlias`, `keyPassword`, `storeFile`, and `storePassword`; when that file is absent or incomplete, the release variant intentionally falls back to debug signing so clean CI/debug APK builds do not evaluate `file(null)`.
- Update the Pusher Beams instance ID in the app config with your ZeroPay Pusher instance, and keep provider credentials out of the repository.

## Resources

- [Flutter documentation](https://docs.flutter.dev/)
- [Lab: Write your first Flutter app](https://docs.flutter.dev/get-started/codelab)
- [Cookbook: Useful Flutter samples](https://docs.flutter.dev/cookbook)
