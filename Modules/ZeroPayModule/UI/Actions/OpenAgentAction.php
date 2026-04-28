<?php

namespace Modules\ZeroPayModule\UI\Actions;

use Illuminate\Support\Str;

final class OpenAgentAction
{
    public static function name(): string
    {
        return str(static::class)->afterLast('\\')->beforeLast('Action')->kebab()->toString();
    }

}
