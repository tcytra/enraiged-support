<?php

namespace Enraiged\Enums\Traits;

trait StaticMethods
{
    /**
     *  Return a selectable array of enumerated options.
     *
     *  @return array
     */
    static function associative(): array
    {
        $associative = [];

        foreach (self::cases() as $each) {
            $associative[$each->name] = $each->value;
        }

        return $associative;
    }

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
     *  Return a selectable array of enumerated options.
     *
     *  @return array
     */
    static function options(): array
    {
        return collect(self::cases())
            ->transform(fn ($option)
                => [
                    'id' => $option->name,
                    'name' => $option->value,
                ])
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
