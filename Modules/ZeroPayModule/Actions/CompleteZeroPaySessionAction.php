<?php

namespace Modules\ZeroPayModule\Actions;

use Modules\ZeroPayModule\Models\ZeroPaySession;

class CompleteZeroPaySessionAction
{
    public function execute(ZeroPaySession $session): ZeroPaySession
    {
        $session->update(['status' => ZeroPaySession::STATUS_COMPLETED]);

        return $session->refresh();
    }
}
