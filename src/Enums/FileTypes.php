<?php

namespace Enraiged\Enums;

use Enraiged\Enums\Traits\StaticMethods;

enum FileTypes: string
{
    use StaticMethods;

    case JSON = 'application/json';
    case PDF = 'application/pdf';
    case GIF = 'image/gif';
    case JPG = 'image/jpeg';
    case PNG = 'image/png';
    case TXT = 'text/plain';
    case PHP = 'text/x-php';
}
