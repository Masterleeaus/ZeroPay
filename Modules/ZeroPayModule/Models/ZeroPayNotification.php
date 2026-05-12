<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;
use Modules\ZeroPayModule\ValueObjects\NotificationEventType;

class ZeroPayNotification extends Model
{
    protected $table = 'zeropay_notifications';

    protected $fillable = [
        'company_id',
        'user_id',
        'session_id',
        'event',
        'event_type',
        'channel',
        'payload',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'event' => NotificationEventType::class,
        'event_type' => NotificationEventType::class,
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }

    public function getEventTypeAttribute(): ?NotificationEventType
    {
        $event = $this->attributes['event'] ?? null;

        return $event !== null ? NotificationEventType::from($event) : null;
    }

    public function setEventTypeAttribute(NotificationEventType|string|null $value): void
    {
        if ($value === null) {
            $this->attributes['event'] = null;

            return;
        }

        $this->attributes['event'] = $value instanceof NotificationEventType ? $value->value : $value;
    }
}
