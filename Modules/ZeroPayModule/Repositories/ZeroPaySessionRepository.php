<?php

namespace Modules\ZeroPayModule\Repositories;

class ZeroPaySessionRepository
{
    public function findForCompany(int $id,int $companyId){ return \Modules\ZeroPayModule\Models\ZeroPaySession::query()->forCompany($companyId)->find($id); }
}
