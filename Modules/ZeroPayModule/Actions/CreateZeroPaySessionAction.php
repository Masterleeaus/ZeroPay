<?php

namespace Modules\ZeroPayModule\Actions;

use Modules\ZeroPayModule\Data\ZeroPaySessionData;
use Modules\ZeroPayModule\Events\ZeroPaySessionCreated;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class CreateZeroPaySessionAction
{
    public function execute(ZeroPaySessionData $data): ZeroPaySession
    {
        $record = ZeroPaySession::create($data->toArray());
        event(new ZeroPaySessionCreated(['session_id' => $record->id, 'session' => $record->toArray()]));

        return $record;
    }
}
