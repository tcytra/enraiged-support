<?php

namespace Enraiged\Database\Track;

trait AtTimestamp
{
    /**
     *  @param  string  $value
     *  @return array|null
     */
    private function atTimestamp($value): ?array
    {
        if ($value) {
            $timestamp = strtotime($value);

            return [
                'date' => date('M j, Y', $timestamp),
                'long' => date('l, F j, Y g:i a', $timestamp),
                'time' => date('g:i a', $timestamp),
                'timestamp' => $timestamp,
            ];
        }

        return null;
    }
}
