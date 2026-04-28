<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;

class ZeroPayWebhookEvent extends Model
{
    protected $table = 'zeropay_webhook_events';

    protected $fillable = [
        'company_id',
        'gateway',
        'event_type',
        'payload',
        'signature',
        'idempotency_key',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'payload'      => 'array',
        'processed_at' => 'datetime',
    ];
}
