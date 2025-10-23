<?php

namespace Enraiged\Enums;

use Enraiged\Enums\Traits\StaticMethods;

enum TemplateSources: string
{
    use StaticMethods;

    case Database = 'data';
    case Filesystem = 'file';
}
