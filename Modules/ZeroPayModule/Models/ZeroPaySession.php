<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;

class ZeroPaySession extends Model
{
    use SoftDeletes;

    protected $table = 'zeropay_sessions';

    protected $fillable = [
        'company_id',
        'user_id',
        'session_token',
        'gateway',
        'amount',
        'currency',
        'status',
        'meta',
        'expires_at',
    ];

    protected $casts = [
        'meta'       => 'array',
        'amount'     => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(ZeroPayTransaction::class, 'session_id');
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(ZeroPayQrCode::class, 'session_id');
    }

    public function gatewayLogs(): HasMany
    {
        return $this->hasMany(ZeroPayGatewayLog::class, 'session_id');
    }
}
