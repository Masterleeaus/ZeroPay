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

    public function stripe(Request $request): JsonResponse
    {
        return $this->handle($request, 'stripe');
    }

    public function paypal(Request $request): JsonResponse
    {
        return $this->handle($request, 'paypal');
    }

    public function cryptomus(Request $request): JsonResponse
    {
        return $this->handle($request, 'cryptomus');
    }

    public function bank(Request $request): JsonResponse
    {
        return $this->handle($request, 'bank_transfer');
    }

    public function handle(Request $request, string $gateway): JsonResponse
    {
        $supported = $this->gatewayFactory->supported();

        if (! in_array($gateway, $supported, true)) {
            return response()->json(['error' => 'Unsupported gateway'], 400);
        }

        $rawBody = $request->getContent();
        $parsedPayload = $request->all();

        // Merge in the raw body and gateway-specific signature headers so that
        // each adapter can perform its own signature verification against the
        // untampered request body.
        $payload = array_merge($parsedPayload, [
            'body' => $rawBody,
            'payload' => $rawBody,
            'signature' => $request->header('X-Signature') ?? $parsedPayload['signature'] ?? null,
            'stripe_signature' => $request->header('Stripe-Signature'),
            'sign' => $parsedPayload['sign'] ?? null,
        ]);

        // Always record the raw event before any processing so it is auditable.
        // The company_id from the payload is untrusted at this stage; it is
        // stored as-is for later reconciliation once the signature is verified.
        $event = ZeroPayWebhookEvent::create([
            'company_id' => isset($parsedPayload['company_id']) ? (int) $parsedPayload['company_id'] : 0,
            'gateway' => $gateway,
            'event_type' => $parsedPayload['event'] ?? $parsedPayload['event_type'] ?? 'unknown',
            'payload' => $parsedPayload,
            'signature' => $payload['signature'],
            'idempotency_key' => $parsedPayload['idempotency_key'] ?? null,
            'status' => 'received',
        ]);

        // Delegate the actual payload processing to the gateway adapter.
        // Each adapter is responsible for verifying the signature before
        // trusting any payload values (e.g. amount, company_id).
        $adapter = $this->gatewayFactory->make($gateway);
        $result = $adapter->handleWebhook($payload);

        $event->update(['status' => 'processed', 'processed_at' => now()]);

        return response()->json(['processed' => $result->processed, 'result' => $result->toArray()]);
    }
}
