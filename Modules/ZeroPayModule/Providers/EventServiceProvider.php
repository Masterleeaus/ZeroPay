<?php

namespace Modules\ZeroPayModule\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\ZeroPayModule\Events\PaymentCompleted;
use Modules\ZeroPayModule\Events\PaymentFailed;
use Modules\ZeroPayModule\Events\ZeroPaySessionCreated;
use Modules\ZeroPayModule\Listeners\HandlePaymentCompleted;
use Modules\ZeroPayModule\Listeners\HandlePaymentFailed;
use Modules\ZeroPayModule\Listeners\HandleZeroPaySessionCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event-to-listener mappings for this module.
     *
     * @var array<class-string, array<int, class-string>>
     */
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
    ];

    /**
     * Register any events for this module.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
