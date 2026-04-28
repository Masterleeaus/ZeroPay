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

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ZeroPaySessionCreated::class => [
            HandleZeroPaySessionCreated::class,
        ],
        PaymentCompleted::class => [
            HandlePaymentCompleted::class,
        ],
        PaymentFailed::class => [
            HandlePaymentFailed::class,
        ],
        SessionOpened::class   => [],
        PaymentStarted::class  => [],
        PaymentPending::class  => [],
        SessionExpiring::class => [],
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
