<?php

namespace Modules\ZeroPayModule\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ZeroPayModule\Models\ZeroPayWebhookEvent;
use Modules\ZeroPayModule\Services\GatewayFactory;

class ZeroPayWebhookController extends Controller
{
    public function __construct(protected GatewayFactory $gatewayFactory) {}

    public function handle(Request $request, string $gateway): JsonResponse
    {
        $supported = $this->gatewayFactory->supported();

        if (!in_array($gateway, $supported, true)) {
            return response()->json(['error' => 'Unsupported gateway'], 400);
        }

        $payload   = $request->all();
        $companyId = isset($payload['company_id']) ? (int) $payload['company_id'] : null;

        if (!$companyId) {
            return response()->json(['error' => 'Missing required company_id in webhook payload'], 422);
        }

        ZeroPayWebhookEvent::create([
            'company_id'      => $companyId,
            'gateway'         => $gateway,
            'event_type'      => $payload['event'] ?? 'unknown',
            'payload'         => $payload,
            'signature'       => $request->header('X-Signature'),
            'idempotency_key' => $payload['idempotency_key'] ?? null,
            'status'          => 'received',
        ]);

        $adapter = $this->gatewayFactory->make($gateway);
        $result  = $adapter->handleWebhook($payload);

        return response()->json(['processed' => true, 'result' => $result]);
    }
}
