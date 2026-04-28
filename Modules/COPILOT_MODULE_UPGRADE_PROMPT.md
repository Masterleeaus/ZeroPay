# Copilot Prompt: Upgrade to Titan AI-Native Module Blueprint

Preserve existing code. Reuse, extend, refactor, repair, replace only if unavoidable.

Upgrade the current Laravel module into the directory tree in TREE.md.

Rules:
- All writes go through Actions.
- Services orchestrate domain flows.
- Queries/ViewModels/Presenters compose reads.
- Policies and permissions authorize all surfaces.
- Every read/write/import/export/job/API/Filament query is tenant-scoped by company_id.
- Filament resources/pages/widgets call Actions, Services, Queries and Policies.
- Providers only register routes, config, bindings, events, migrations, views, translations and Filament plugin integration.
- Add idempotent permission seeding and tests.
- Add module-local `Knowledge/` files for guides, policies, FAQs, pricing, contracts, SOPs and compliance.
- Add `Agents/ModuleAgent/agent.manifest.json`.
- Add `AI/Indexing`, `AI/Retrieval`, `AI/Citations`, `AI/Guardrails`, `AI/Actions` and `AI/Telemetry` manifests.
- Do not add model provider SDKs, vector drivers, usage accounting or global policy engines to the module; those belong to TitanCore.
- TitanZero remains system AI; TitanAgents owns trained module agents.

Output a complete updated module tree, not delta-only patches.
