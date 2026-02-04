<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format a date for human-readable display.
     */
    public static function formatForDisplay(?Carbon $date): ?string
    {
        if (!$date) {
            return null;
        }

        return $date->format('M j, Y g:i A');
    }

    /**
     * Get a human-readable difference from now.
     */
    public static function diffForHumans(?Carbon $date): ?string
    {
        if (!$date) {
            return null;
        }

        return $date->diffForHumans();
    }

    /**
     * Check if a date is overdue.
     */
    public static function isOverdue(?Carbon $date): bool
    {
        if (!$date) {
            return false;
        }

        return $date->isPast();
    }

    /**
     * Get days until a date.
     */
    public static function daysUntil(?Carbon $date): ?int
    {
        if (!$date) {
            return null;
        }

        return now()->diffInDays($date, false);
    }
}