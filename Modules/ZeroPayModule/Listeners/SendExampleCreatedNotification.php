<?php

namespace Modules\ZeroPayModule\Listeners;

class SendExampleCreatedNotification
{
    public function handle(\Modules\ZeroPayModule\Events\ExampleCreated $event): void {}
}
