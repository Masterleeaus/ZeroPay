<?php
namespace Modules\ZeroPayModule\Actions;
use Modules\ZeroPayModule\Data\ZeroPaySessionData;use Modules\ZeroPayModule\Models\ZeroPaySession;use Modules\ZeroPayModule\Events\ExampleCreated;
class CreateZeroPaySessionAction{public function execute(ZeroPaySessionData $data):ZeroPaySession{$record=ZeroPaySession::create($data->toArray());event(new ExampleCreated($record));return $record;}}
