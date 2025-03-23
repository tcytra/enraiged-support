<?php

namespace Enraiged\Database;

trait Tracking
{
    use Track\Created,
        Track\Deleted,
        Track\Updated;
}
