<?php

namespace Enraiged\Enums;

final class Enum implements UnitEnum
{
    private function __construct(public readonly string $name)
    {}
}
