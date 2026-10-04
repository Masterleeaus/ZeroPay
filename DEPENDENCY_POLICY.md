# ZeroPay dependency policy

ZeroPay contains several applications plus a reusable PHP module. They do not share one dependency graph, so each maintained application root owns its own lockfile and frozen install command.

| Root | Boundary | Lockfile | Frozen install |
| --- | --- | --- | --- |
| `PWA/` | React/Vite application | `package-lock.json` | `npm ci` |
| `Web/00_App_Core/` | Laravel/PHP application | `composer.lock` | `composer install --no-interaction --prefer-dist --no-progress` |
| `Web/00_App_Core/` | Vite asset application | `package-lock.json` | `npm ci` |
| `Mobile/` | Flutter application | `pubspec.lock` | `flutter pub get --enforce-lockfile` |
| `Modules/ZeroPayModule/` | Independently tested PHP module | `composer.lock` | `composer install --no-interaction --prefer-dist --no-progress` |

The module lockfile is authoritative for the module's standalone CI graph. A consuming Titan host must still resolve and lock its complete application graph separately; the module lock is not a claim that host integration has been reproduced.

The maintained Web frontend uses the repository-root `.nvmrc` and its own `package-lock.json`. The Web CI lane uses `npm ci`, runs the declared Vite build, and fails if the lockfile changes during the job.

## Remaining gaps

- `Modules/ExampleModule/` is an example scaffold, not a maintained application root.

For maintained application roots, use the committed lockfiles and frozen commands above from a clean checkout. Do not replace them with `npm install`, `npm update`, `composer update`, or an unlocked Flutter resolution in CI.
