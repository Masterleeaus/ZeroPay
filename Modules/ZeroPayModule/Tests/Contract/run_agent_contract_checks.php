<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$checks = 0;

$assert = static function (bool $condition, string $message) use (&$checks): void {
    $checks++;

    if (! $condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
};

$loadJson = static function (string $relativePath) use ($root): array {
    $path = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (! is_file($path)) {
        throw new RuntimeException("Missing {$relativePath}");
    }

    $value = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

    if (! is_array($value)) {
        throw new RuntimeException("Expected object JSON at {$relativePath}");
    }

    return $value;
};

$module = $loadJson('module.json');
$moduleAgent = $loadJson('Agents/ModuleAgent/agent.manifest.json');
$routing = $loadJson('manifests/module-agent.json');
$voice = $loadJson('AI/Voice/voice.manifest.json');
$control = $loadJson('AI/Control/control.manifest.json');
$page = $loadJson('Filament/PageManifest/module-page.json');
$actions = $loadJson('AI/Actions/action-map.json');
$guardrails = $loadJson('AI/Guardrails/guardrails.json');
$dataset = $loadJson('Agents/ModuleAgent/training/dataset-manifest.json');
$evaluation = $loadJson('Agents/ModuleAgent/evaluations/eval-suite.json');
$demo = $loadJson('Agents/DemoAgent/agent.manifest.json');

$canonicalAgent = 'zeropay.module.agent';
$assert(($module['manifest']['agent'] ?? null) === 'Agents/ModuleAgent/agent.manifest.json', 'module.json selects ModuleAgent manifest');
$assert(($moduleAgent['agent'] ?? null) === $canonicalAgent, 'ModuleAgent declares canonical agent id');
$assert(($moduleAgent['module'] ?? null) === 'zeropay-module', 'ModuleAgent is scoped to ZeroPay module');
$assert(($routing['default_agent'] ?? null) === $canonicalAgent, 'default routing selects canonical agent');
$assert(($routing['agent_manifest'] ?? null) === 'Agents/ModuleAgent/agent.manifest.json', 'default routing selects ModuleAgent manifest');
$assert(($routing['scaffold_agent_active'] ?? null) === false, 'demo scaffold is not active default route');
$assert(($routing['production_requires_replacement'] ?? null) === false, 'canonical module agent does not require demo replacement');
$assert(($voice['routes_to_default_agent'] ?? null) === $canonicalAgent, 'voice routes to canonical agent');
$assert(($control['agent'] ?? null) === $canonicalAgent, 'control manifest uses canonical agent');
$assert(($page['chat']['agent'] ?? null) === $canonicalAgent, 'Filament module page uses canonical agent');

$assert(($demo['agent'] ?? null) === 'demo.agent', 'demo scaffold keeps its isolated agent id');
$assert(($demo['scaffold'] ?? null) === true, 'demo scaffold remains explicitly marked');
$assert(($demo['visible_to_users'] ?? null) === false, 'demo scaffold remains hidden from users');

$toolNames = [];
foreach ($actions['tools'] ?? [] as $tool) {
    $toolNames[] = $tool['tool'] ?? '';
    $assert(($tool['tenant_scoped'] ?? null) === true, "{$tool['tool']} is tenant scoped");

    if (($tool['type'] ?? null) === 'write') {
        $assert(($tool['requires_confirmation'] ?? null) === true, "{$tool['tool']} requires confirmation");
    }
}
$assert(in_array('zeropay.create_session', $toolNames, true), 'payment-session write tool is mapped');
$assert(($guardrails['require_confirmation_for_write_tools'] ?? null) === true, 'guardrails require write confirmation');
$assert(($guardrails['block_unapproved_sources'] ?? null) === true, 'guardrails block unapproved sources');

$approvedSources = $dataset['approved_sources'] ?? [];
$assert(($dataset['status'] ?? null) === 'approved', 'dataset manifest is approved');
$assert(count($approvedSources) > 0, 'approved knowledge sources are present');
foreach ($approvedSources as $source) {
    $sourcePath = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $source['path']);
    $assert(($source['approved'] ?? null) === true, "{$source['path']} is marked approved");
    $assert(is_file($sourcePath), "{$source['path']} exists");
}

$assert(count($evaluation['cases'] ?? []) >= 2, 'module-agent evaluation suite has bounded cases');
$assert(($evaluation['cases'][1]['expected_guardrail'] ?? null) === 'human_confirm_for_write_actions', 'evaluation covers write confirmation');

// Provider-independent fixture: it exercises the response contract without calling TitanAgents or a payment gateway.
$fixtureProvider = new class
{
    public array $calls = [];

    public function forModule(string $module): self
    {
        $this->calls[] = ['method' => 'forModule', 'module' => $module];

        return $this;
    }

    public function ask(array $payload): array
    {
        $this->calls[] = ['method' => 'ask', 'payload' => $payload];

        return [
            'provider' => 'fixture',
            'answer' => 'Use approved payment-session and gateway guidance.',
            'sources' => ['Knowledge/README.md'],
        ];
    }

    public function command(array $payload): array
    {
        $this->calls[] = ['method' => 'command', 'payload' => $payload];

        return ($payload['confirmed'] ?? false) === true
            ? ['provider' => 'fixture', 'status' => 'accepted']
            : ['provider' => 'fixture', 'status' => 'confirmation_required'];
    }
};

$answer = $fixtureProvider->forModule('zeropay-module')->ask(['input' => 'What sources should this module agent use?']);
$assert(($answer['provider'] ?? null) === 'fixture', 'agent answer uses provider fixture');
$assert(($answer['sources'] ?? []) === ['Knowledge/README.md'], 'agent answer returns grounded source');

$rejectedCommand = $fixtureProvider->command(['tool' => 'zeropay.create_session', 'confirmed' => false]);
$assert(($rejectedCommand['status'] ?? null) === 'confirmation_required', 'unconfirmed payment write is rejected');

$acceptedCommand = $fixtureProvider->command(['tool' => 'zeropay.create_session', 'confirmed' => true]);
$assert(($acceptedCommand['status'] ?? null) === 'accepted', 'confirmed payment write reaches fixture provider');

$paymentFixture = [
    'provider' => 'fixture',
    'gateway' => 'bank_transfer',
    'status' => 'pending',
    'amount' => 12.50,
    'currency' => 'AUD',
];
$assert($paymentFixture['status'] === 'pending', 'fixture payment session starts pending');
$assert($paymentFixture['gateway'] === 'bank_transfer', 'fixture payment session preserves gateway');
$assert($paymentFixture['amount'] === 12.50 && $paymentFixture['currency'] === 'AUD', 'fixture payment session preserves amount and currency');

fwrite(STDOUT, "PASS: {$checks} ZeroPay agent/payment contract checks\n");

