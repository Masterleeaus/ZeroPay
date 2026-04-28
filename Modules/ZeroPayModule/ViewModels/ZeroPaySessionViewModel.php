<?php

namespace Modules\ZeroPayModule\ViewModels;

class ZeroPaySessionViewModel
{
    public function __construct(public readonly \Modules\ZeroPayModule\Models\ZeroPaySession $record){}
}
