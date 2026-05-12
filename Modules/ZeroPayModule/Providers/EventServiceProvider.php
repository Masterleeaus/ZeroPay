<?php

namespace Modules\ZeroPayModule\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\ZeroPayModule\Events\PaymentCompleted;
use Modules\ZeroPayModule\Events\PaymentFailed;
use Modules\ZeroPayModule\Events\PaymentPending;
use Modules\ZeroPayModule\Events\PaymentStarted;
use Modules\ZeroPayModule\Events\SessionExpiring;
use Modules\ZeroPayModule\Events\SessionOpened;
use Modules\ZeroPayModule\Events\ZeroPaySessionCreated;
use Modules\ZeroPayModule\Listeners\HandlePaymentCompleted;
use Modules\ZeroPayModule\Listeners\HandlePaymentFailed;
use Modules\ZeroPayModule\Listeners\HandleZeroPaySessionCreated;
use Modules\ZeroPayModule\Listeners\SendPushForPaymentCompleted;
use Modules\ZeroPayModule\Listeners\SendPushForPaymentFailed;
use Modules\ZeroPayModule\Listeners\SendPushForPaymentPending;
use Modules\ZeroPayModule\Listeners\SendPushForPaymentStarted;
use Modules\ZeroPayModule\Listeners\SendPushForSessionExpiring;
use Modules\ZeroPayModule\Listeners\SendPushForSessionOpened;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ZeroPaySessionCreated::class => [
            HandleZeroPaySessionCreated::class,
        ],
        SessionOpened::class => [
            SendPushForSessionOpened::class,
        ],
        PaymentStarted::class => [
            SendPushForPaymentStarted::class,
        ],
        PaymentPending::class => [
            SendPushForPaymentPending::class,
        ],
        PaymentCompleted::class => [
            HandlePaymentCompleted::class,
            SendPushForPaymentCompleted::class,
        ],
        PaymentFailed::class => [
            HandlePaymentFailed::class,
            SendPushForPaymentFailed::class,
        ],
        SessionExpiring::class => [
            SendPushForSessionExpiring::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
