<?php
namespace Modules\ZeroPayModule\Providers;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;use Modules\ZeroPayModule\Events\ExampleCreated;use Modules\ZeroPayModule\Listeners\SendExampleCreatedNotification;
class EventServiceProvider extends ServiceProvider{protected $listen=[ExampleCreated::class=>[SendExampleCreatedNotification::class]];}
