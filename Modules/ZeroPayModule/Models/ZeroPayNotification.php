<?php

namespace Modules\ZeroPayModule\Models;

use Illuminate\Database\Eloquent\Model;

class ZeroPayNotification extends Model
{
    protected $table = 'zeropay_notifications';

    protected $fillable = [
        'company_id',
        'user_id',
        'session_id',
        'event',
        'channel',
        'payload',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];
}
