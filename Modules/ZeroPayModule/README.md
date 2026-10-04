# ZeroPayModule

Domain-first, tenant-safe, Filament-thin Titan module.

## AI-controllable module standard

This module follows the Titan AI-native module pattern:

- one Filament sidebar link
- one Filament module command page
- top half: quick cards, widgets, module AI chatbot, shortcuts, settings
- bottom half: one tabbed table card for all module tables
- PWA/channel/voice control through the same agent and action map
- TitanZero supervises system AI; TitanAgents runs the trained module agent; TitanCore provides AI infrastructure

See:

- `Docs/AI_CONTROL_AND_CHANNELS.md`
- `Docs/FILAMENT_ONE_PAGE_STANDARD.md`
- `AI/Control/control.manifest.json`
- `PWA/pwa.manifest.json`


## Agent routing and provider-independent verification

The canonical internal agent is `zeropay.module.agent`, declared by `Agents/ModuleAgent/agent.manifest.json` and selected by the module, control and voice manifests. `Agents/DemoAgent/` remains an isolated example scaffold for the explicitly named demo control panel; it is not the default route and its empty evaluation suite is not production evidence.

From the repository root, run the host-independent contract check with PHP:

```bash
php Modules/ZeroPayModule/Tests/Contract/run_agent_contract_checks.php
```

The check validates manifest alignment, tenant/confirmation guardrails, approved knowledge paths and a fixture-only agent/payment response contract. It does not call TitanAgents, payment gateways or banking rails.

No repository-level `LICENSE` or `NOTICE` file was verified in this checkout. Do not infer redistribution terms from the module structure; confirm the applicable legal/provenance terms before publication or deployment.
