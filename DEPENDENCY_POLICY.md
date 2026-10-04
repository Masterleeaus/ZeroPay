# ZeroPay dependency policy

ZeroPay contains several applications plus a reusable PHP module. They do not share one dependency graph, so each maintained application root owns its own lockfile and frozen install command.

| Root | Boundary | Lockfile | Frozen install |
| --- | --- | --- | --- |
| `PWA/` | React/Vite application | `package-lock.json` | `npm ci` |
| `Web/00_App_Core/` | Laravel/PHP application | `composer.lock` | `composer install --no-interaction --prefer-dist --no-progress` |
| `Web/00_App_Core/` | Retained Vite asset manifest | `package-lock.json` | `npm ci` only; no clean-checkout build claim |
| `Mobile/` | Flutter application | `pubspec.lock` | `flutter pub get --enforce-lockfile` |
| `Modules/ZeroPayModule/` | Independently tested PHP module | `composer.lock` | `composer install --no-interaction --prefer-dist --no-progress` |

The module lockfile is authoritative for the module's standalone CI graph. A consuming Titan host must still resolve and lock its complete application graph separately; the module lock is not a claim that host integration has been reproduced.

## Host-managed Web asset boundary

`Web/00_App_Core/package.json` and `vite.config.js` retain a Vite asset manifest, and `package-lock.json` now records that manifest's dependency graph. The checkout does not contain the referenced `resources/` tree, including `resources/sass/app.scss` and `resources/js/app.js`. As a result, `npm ci` can install the declared packages, but a clean-checkout `npm run build` fails before transforming modules.

This is intentionally documented as a host-managed/incomplete boundary, not as a maintained runnable frontend. The repository-root `.nvmrc` records the Node version used to review the manifest, but no Web npm build workflow is enabled until the missing asset sources and their ownership are restored. Do not claim a passing Web frontend build or production-ready asset pipeline from this repository alone.

## Remaining gaps

- `Modules/ExampleModule/` is an example scaffold, not a maintained application root.

For maintained application roots, use the committed lockfiles and frozen commands above from a clean checkout. Do not replace them with `npm install`, `npm update`, `composer update`, or an unlocked Flutter resolution in CI.
