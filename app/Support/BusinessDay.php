<?php

namespace App\Support;

use App\Models\Holiday;
use Carbon\Carbon;

class BusinessDay
{
    public static function isBusinessDay(Carbon $date, ?string $region = null): bool
    {
        if (in_array($date->dayOfWeekIso, [6, 7])) {
            return false;
        }
        $exists = false;
        try {
            $exists = Holiday::whereDate('holiday_date', $date->toDateString())
                ->when($region, function ($q) use ($region) {
                    $q->where('region', $region)->orWhereNull('region');
                })->exists();
        } catch (\Throwable $e) {
            $exists = false;
        }
        return !$exists;
    }

    public static function nextBusinessDay(Carbon $date, ?string $region = null): Carbon
    {
        $d = $date->copy();
        while (!self::isBusinessDay($d, $region)) {
            $d->addDay();
        }
        return $d;
    }
}
