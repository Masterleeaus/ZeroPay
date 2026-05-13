<?php

namespace Modules\ZeroPayModule\Actions;

use Modules\ZeroPayModule\Models\ZeroPaySession;

class ExpireZeroPaySessionAction
{
    public function execute(ZeroPaySession $session): ZeroPaySession
    {
        $session->update(['status' => ZeroPaySession::STATUS_EXPIRED]);

        return $session->refresh();
    }
}
