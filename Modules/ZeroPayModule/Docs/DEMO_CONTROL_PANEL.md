# Demo Control Panel

This module includes a real assembled demo control panel scaffold.

Important distinction:

- `UI/` is the component library.
- `UI/ControlPanel/DemoControlPanel.php` and `Resources/views/ui/control-panel/demo-control-panel.blade.php` assemble those components into the one-page module panel.

This scaffold intentionally binds to `demo.agent`; it is not the active ZeroPay module route. The canonical module page and voice path use `zeropay.module.agent`. Users still call the assistant **Zero**.
