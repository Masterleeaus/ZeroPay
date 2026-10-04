# ZeroPay source and license evidence map

Verified against `main` commit `b923d09f0468f27304426ae8fd8c77ea0519ed0a` on 2026-10-04. This is a repository inventory for engineering and review purposes, not a legal conclusion.

## Verified boundaries

| Boundary | Evidence in this checkout | What it supports | What it does not support |
| --- | --- | --- | --- |
| Root aggregate | No tracked `LICENSE`, `NOTICE`, `COPYING`, `AUTHORS`, or `CONTRIBUTORS` path was found. The root README also says that no repository-level license or notice was verified. | The absence of a visible repository-wide license/notice is recorded. | No whole-repository MIT, proprietary, or open-source redistribution claim. |
| `Mobile/` | The root README and `Mobile/README.md` identify `Mobile/` as the canonical Flutter source and CI path. Its `pubspec.yaml` does not declare a license, and no separate license/notice file is tracked in the tree. | Canonical active mobile ownership for build and CI navigation. | License or redistribution rights for the mobile application or its bundled assets. |
| `mobile-legacy/` | Its README identifies the directory as retained legacy/provenance material and says it is not the CI target. Its `pubspec.yaml` does not declare a license, and no separate license/notice file is tracked in the tree. | Retention for comparison and provenance; it remains outside the active mobile path. | Permission to delete, publish, or relicense the retained tree. |
| `Modules/ZeroPayModule/` | `composer.json` declares package metadata `"license": "MIT"` and author metadata `ZeroPay <dev@zeropay.com>`. | A module-level package declaration that can be cited for that package boundary. | An MIT grant for the root aggregate, `Mobile/`, `mobile-legacy/`, third-party dependencies, or generated/vendor material. |

## Provenance and attribution posture

- The repository history and retained legacy material are preserved; this map does not assign new authorship or remove donor/archive source.
- The canonical/legacy distinction is an engineering ownership statement derived from the checked-in READMEs and workflow, not a legal license grant.
- Dependencies and bundled assets may carry their own terms. Their package metadata and notices must be preserved and reviewed at distribution time.
- No source in this repository is relabelled as AI-authored or original solely because it is present here.

## Redistribution decision

Do not describe the entire ZeroPay repository as MIT based only on the module manifest. Before redistribution, obtain the applicable owner/legal terms for the root aggregate, mobile trees, retained legacy material, assets, and dependencies. Preserve attribution and notices discovered during that review.

## Re-check commands

From a clean checkout, the visible-license inventory can be repeated with:

```powershell
git ls-tree -r --name-only main |
  Select-String -Pattern '(^|/)(LICENSE|NOTICE|COPYING|AUTHORS|CONTRIBUTORS)(\..*)?$'
Get-Content Modules/ZeroPayModule/composer.json |
  Select-String -Pattern '"license"|"authors"'
```

An empty first command is evidence that no matching tracked path was found; it is not evidence that no third-party terms exist.
