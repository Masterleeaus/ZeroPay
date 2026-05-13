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
        'event_type',
        'event',
        'channel',
        'payload',
        'status',
        'sent_at',
        'read_at',
    ];

    protected $casts = [
        'event_type' => NotificationEventType::class,
        'payload' => 'array',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * 'event' is a virtual alias for 'event_type'. Both are kept in sync so
     * that callers may use either name and getAttributes()['event'] always
     * reflects the current value (needed by unit tests and API serialisation).
     */
    public function getEventAttribute(): ?NotificationEventType
    {
        $raw = $this->attributes['event'] ?? $this->attributes['event_type'] ?? null;

        return $raw !== null ? NotificationEventType::tryFrom((string) $raw) : null;
    }

    public function setEventAttribute(mixed $value): void
    {
        $raw = $value instanceof NotificationEventType ? $value->value : $value;
        $this->attributes['event'] = $raw;
        $this->attributes['event_type'] = $raw;
    }

    /**
     * Keep the virtual 'event' alias in sync whenever 'event_type' is set
     * directly (e.g. $model->event_type = …).
     */
    public function setEventTypeAttribute(mixed $value): void
    {
        $raw = $value instanceof NotificationEventType ? $value->value : $value;
        $this->attributes['event_type'] = $raw;
        $this->attributes['event'] = $raw;
    }

    /**
     * Exclude the virtual 'event' alias from database writes — the real column
     * is 'event_type'; 'event' is an in-memory convenience attribute that is
     * kept in sync with 'event_type' via the setEventAttribute /
     * setEventTypeAttribute mutators. Writing it to the DB would fail because
     * no 'event' column exists in the zeropay_notifications table.
     */
    public function getDirty(): array
    {
        $dirty = parent::getDirty();
        unset($dirty['event']);

        return $dirty;
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }
}
