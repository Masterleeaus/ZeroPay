# ZeroPay dependency policy

ZeroPay contains several runtime roots. This document records the dependency evidence at the current repository tip; it does not turn an unlocked install into a reproducible one.

## Verified application roots

| Root | Manifest | Lockfile | Frozen command |
| --- | --- | --- | --- |
| `PWA/` | `package.json` | `package-lock.json` | `npm ci` |
| `Web/00_App_Core/` PHP application | `composer.json` | `composer.lock` | `composer install --no-interaction --prefer-dist --no-progress` |
| `Mobile/` | `pubspec.yaml` | `pubspec.lock` | `flutter pub get --enforce-lockfile` |

## Explicit gaps

- `Web/00_App_Core/package.json` defines a Vite build, but the current tip has no sibling `package-lock.json`. Do not describe that frontend install as frozen until its lockfile is committed and verified with `npm ci`.
- `Modules/ZeroPayModule/composer.json` is installed by the dedicated module CI job, but the current tip has no sibling `composer.lock`; `Modules/ZeroPayModule/.gitignore` currently excludes it. That CI dependency resolution is therefore not frozen. The module's PHP 8.3 Composer/PHPUnit workflow remains useful test evidence, but it is an open reproducibility task.
- `Modules/ExampleModule/` is an example scaffold, not a maintained application root.

Use the committed lockfiles for the PWA, Web PHP application, and Mobile roots. For the two gaps above, preserve the manifests and resolve the lockfile policy before claiming clean-checkout reproducibility. Do not replace a frozen install with `npm update`, `composer update`, or an unlocked Flutter resolution in CI.
