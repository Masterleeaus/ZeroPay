<?php

namespace Modules\ZeroPayModule\Queries;

class ZeroPaySessionQuery
{
    public function forTenant(int $companyId){ return \Modules\ZeroPayModule\Models\ZeroPaySession::query()->forCompany($companyId); }
}
