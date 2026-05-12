<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;

class ZeroPayBankDeposit extends Model
{
    use SoftDeletes;

    protected $table = 'zeropay_bank_deposits';

    protected $fillable = [
        'company_id',
        'bank_account_id',
        'transaction_id',
        'amount',
        'currency',
        'depositor_name',
        'depositor_bsb',
        'depositor_account',
        'reference',
        'description',
        'deposited_at',
        'status',
        'match_score',
        'match_method',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'amount' => 'decimal:2',
        'deposited_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(ZeroPayBankAccount::class, 'bank_account_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(ZeroPayTransaction::class, 'transaction_id');
    }
}
