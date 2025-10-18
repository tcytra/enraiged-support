<?php

namespace Enraiged\Enums\Traits;

trait StaticMethods
{
    /**
     *  Return an array of the enum case names.
     *
     *  @return array
     */
    static function names(): array
    {
        return collect(self::cases())
            ->transform(fn ($enum) => $enum->name)
            ->toArray();
    }

    /**
     *  Return a randomly selected enum instance.
     *
     *  @return self
     */
    static function random(): self
    {
        $names = self::values();
        //dd($names[array_rand($names)]);

        return self::from($names[array_rand($names)]);
    }

    /**
     *  Return an array of the enum case values.
     *
     *  @return array
     */
    static function values(): array
    {
        return collect(self::cases())
            ->transform(fn ($enum) => $enum->value)
            ->toArray();
    }
}
