<?php

namespace App\Enums;

use App\Traits\BaseEnum;

enum HttpMethodsEnum: string
{
    use BaseEnum;

    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    case PATCH = 'PATCH';
}
