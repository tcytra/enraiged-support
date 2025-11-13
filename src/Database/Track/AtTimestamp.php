<?php

namespace Enraiged\Database\Track;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

trait AtTimestamp
{
    /**
     *  @param  string  $value
     *  @return array|null
     */
    private function atTimestamp($value): ?array
    {
        if ($value) {
            $timezone = Auth::check() && Auth::user()->timezone
                ? Auth::user()->timezone
                : config('enraiged.app.timezone');

            $datetime = Carbon::parse($value)
                ->timezone($timezone);

            return [
                'date' => $datetime->format('M j, Y'),
                'long' => $datetime->format('l, F j, Y g:i a'),
                'time' => $datetime->format('g:i a'),
                'timestamp' => strtotime($datetime->format('Y-m-d H:i:s')),
            ];
        }

        return null;
    }
}
