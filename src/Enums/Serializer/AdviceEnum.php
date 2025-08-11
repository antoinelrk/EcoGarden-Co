<?php

namespace App\Enums\Serializer;

use App\Traits\BaseEnum;

enum AdviceEnum: string
{
    use BaseEnum;

    case ADVICE_LIST = 'advice:list';
    case ADVICE_SHOW = 'advice:show';
}
