# Module Agents

Users always talk to **Zero**.

This folder contains internal module-specialist agents that Zero can route to. The canonical ZeroPay route is `zeropay.module.agent`, backed by `Agents/ModuleAgent/agent.manifest.json`.

`DemoAgent` remains as an isolated example scaffold for the explicitly named demo control panel. It is not the default route, is not visible to users, and its empty evaluation suite is not production evidence.
