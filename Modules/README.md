# Titan AI-Native Full Module Blueprint Starter Pack

Copy `Modules/ExampleModule` and rename it. The module owns domain logic, files, workflows, actions and tools. Filament is only the operator surface.

AI-native additions:
- `Knowledge/` stores module-local guides, policies, contracts, pricing, FAQs, SOPs and checklists.
- `Agents/ModuleAgent/` declares the trained module agent owned by TitanAgents.
- `AI/` declares indexing, retrieval, citation, guardrail, action and telemetry contracts.
- TitanCore provides AI runtime infrastructure.
- TitanZero provides system AI, governance and diagnostics.

A Booking module can ship booking guides and a Booking AI; a Cleaning module can ship estimating guides and a Cleaning Estimator AI; a Contracts module can ship clauses and a Contract AI.

## PASS17 Update: Demo Agent + Demo Control Panel

The skeleton now includes a clearly marked `DemoAgent`, an assembled `DemoControlPanel`, ZeroChat runtime adapters, and vendor slots for A2UI/Tambo-style UI tools.

Users always talk to **Zero**; internal module agents are routed invisibly.
