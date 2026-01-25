<?php

namespace App\Helpers;

class TimeHelper
{
    /**
     * Convert seconds to human readable format
     * 
     * @param int $seconds
     * @return string
     */
    public static function secondsToReadable($seconds)
    {
        if (!$seconds || $seconds <= 0) {
            return '0s';
        }

        $units = [
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1,
        ];

        $result = [];

        foreach ($units as $name => $divisor) {
            $quot = intval($seconds / $divisor);
            if ($quot) {
                $result[] = $quot . substr($name, 0, 1); // 'd', 'h', 'm', 's'
                $seconds -= $quot * $divisor;
            }
        }

        return implode(' ', $result);
    }

    /**
     * Convert seconds to readable format with full words
     * 
     * @param int $seconds
     * @return string
     */
    public static function secondsToReadableLong($seconds)
    {
        if (!$seconds || $seconds <= 0) {
            return '0 seconds';
        }

        $units = [
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1,
        ];

        $result = [];

        foreach ($units as $name => $divisor) {
            $quot = intval($seconds / $divisor);
            if ($quot) {
                $plural = $quot > 1 ? $name . 's' : $name;
                $result[] = $quot . ' ' . $plural;
                $seconds -= $quot * $divisor;
            }
        }

        return implode(', ', $result);
    }
}