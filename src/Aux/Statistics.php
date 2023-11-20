<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Models\VisitStatistic;
use Illuminate\Support\Carbon;

class Statistics
{

    public static function data(string $from, string $to)
    {
        $cTo = Carbon::parse($to)->endOfDay();
        $cFrom = Carbon::parse($from)->startOfDay();

        return VisitStatistic::whereBetween('created_at', [$cFrom->toDateTimeString(), $cTo->toDateTimeString()])->get()->toArray();
    }

    public static function hourly(array $data, string $from, string $to)
    {
        $cTo = Carbon::parse($to)->endOfDay();
        $cFrom = Carbon::parse($from)->startOfDay();
        $format = 'd.m.Y G';
        if ($cTo->diffInDays($cFrom) < 1) {
            $format = 'G';
        }
        $start = $cFrom;
        $cdata = [];
        $udata = [];
        while ($start <= $cTo) {
            $key = $start->format($format) . ' ' . __('elfcms::default.oclock');
            $cdata[$key] = 0;
            $udata[$key] = [];
            $start->addHour();
        }

        if (!empty($data)) {
            foreach ($data as $item) {
                $time = Carbon::parse($item['created_at']);
                $key = $time->format($format) . ' ' . __('elfcms::default.oclock');
                $cdata[$key]++;
                $udata[$key][$item['ip'].$item['uri'].$item['agent'].$item['user_id']] = 1;
            }
        }

        $uvisits = [];
        $visits = [];
        $labels = [];
        foreach ($udata as $k => $visit) {
            $uvisits[] = count($visit);
            $labels[] = $k;
        }
        foreach ($cdata as $visit) {
            $visits[] = $visit;
        }

        return [
            'all_visits' => $visits,
            'unique_visits' => $uvisits,
            'labels' => $labels,
        ];
    }

    public static function byTime(array $data, string $from, string $to)
    {
        $cTo = Carbon::parse($to)->endOfDay();
        $cFrom = Carbon::parse($from)->startOfDay();
        $format = 'd.m.Y';
        $daytimes = [
            /* __('elfcms::default.night'),
            __('elfcms::default.morning'),
            __('elfcms::default.afternoon'),
            __('elfcms::default.evening'), */
            'night',
            'morning',
            'afternoon',
            'evening',
        ];
        $start = $cFrom;
        $cdata = [];
        $udata = [];
        while ($start <= $cTo) {
            $h = $start->format('G');
            $daytime = $daytimes[0];
            if ($h >= 18) {
                $daytime = $daytimes[3];
            }
            elseif ($h >= 12) {
                $daytime = $daytimes[2];
            }
            elseif ($h >= 6) {
                $daytime = $daytimes[1];
            }
            $key = $start->format($format) . ' ' . __('elfcms::default.'.$daytime);
            $cdata[$key] = 0;
            $udata[$key] = [];
            $start->addHour();
        }

        if (!empty($data)) {
            foreach ($data as $item) {
                $time = Carbon::parse($item['created_at']);
                $h = $time->format('G');
                $daytime = $daytimes[0];
                if ($h >= 18) {
                    $daytime = $daytimes[3];
                }
                elseif ($h >= 12) {
                    $daytime = $daytimes[2];
                }
                elseif ($h >= 6) {
                    $daytime = $daytimes[1];
                }
                $key = $time->format($format) . ' ' . __('elfcms::default.'.$daytime);
                $cdata[$key]++;
                $udata[$key][$item['ip'].$item['uri'].$item['agent'].$item['user_id']] = 1;
            }
        }

        $uvisits = [];
        $visits = [];
        $labels = [];
        foreach ($udata as $k => $visit) {
            $uvisits[] = count($visit);
            $labels[] = $k;
        }
        foreach ($cdata as $visit) {
            $visits[] = $visit;
        }

        return [
            'all_visits' => $visits,
            'unique_visits' => $uvisits,
            'labels' => $labels,
        ];
    }

    public static function daily(array $data, string $from, string $to)
    {
        $cTo = Carbon::parse($to)->endOfDay();
        $cFrom = Carbon::parse($from)->startOfDay();
        $format = 'd.m.Y';
        $start = $cFrom;
        $cdata = [];
        $udata = [];
        while ($start <= $cTo) {
            $key = $start->format($format) . ' ' . __('elfcms::default.oclock');
            $cdata[$key] = 0;
            $udata[$key] = [];
            $start->addHour();
        }

        if (!empty($data)) {
            foreach ($data as $item) {
                $time = Carbon::parse($item['created_at']);
                $key = $time->format($format) . ' ' . __('elfcms::default.oclock');
                $cdata[$key]++;
                $udata[$key][$item['ip'].$item['uri'].$item['agent'].$item['user_id']] = 1;
            }
        }

        $uvisits = [];
        $visits = [];
        $labels = [];
        foreach ($udata as $k => $visit) {
            $uvisits[] = count($visit);
            $labels[] = $k;
        }
        foreach ($cdata as $visit) {
            $visits[] = $visit;
        }

        return [
            'all_visits' => $visits,
            'unique_visits' => $uvisits,
            'labels' => $labels,
        ];
    }

    public static function monthly(array $data, string $from, string $to)
    {
        $cTo = Carbon::parse($to)->endOfDay();
        $cFrom = Carbon::parse($from)->startOfDay();
        $format = 'Y';
        $start = $cFrom;
        $cdata = [];
        $udata = [];
        while ($start <= $cTo) {
            $key = $start->format($format) . ' ' . __('elfcms::default.'.strtolower($start->format('F')));
            $cdata[$key] = 0;
            $udata[$key] = [];
            $start->addHour();
        }

        if (!empty($data)) {
            foreach ($data as $item) {
                $time = Carbon::parse($item['created_at']);
                $key = $time->format($format) . ' ' . __('elfcms::default.'.strtolower($time->format('F')));
                $cdata[$key]++;
                $udata[$key][$item['ip'].$item['uri'].$item['agent'].$item['user_id']] = 1;
            }
        }

        $uvisits = [];
        $visits = [];
        $labels = [];
        foreach ($udata as $k => $visit) {
            $uvisits[] = count($visit);
            $labels[] = $k;
        }
        foreach ($cdata as $visit) {
            $visits[] = $visit;
        }

        return [
            'all_visits' => $visits,
            'unique_visits' => $uvisits,
            'labels' => $labels,
        ];
    }

    public static function get(string $from, string $to, string|null $method = null)
    {

        if (!$method || !method_exists(self::class, $method)) {
            $cTo = Carbon::parse($to)->endOfDay();
            $cFrom = Carbon::parse($from)->startOfDay();
            $diff = $cTo->diffInDays($cFrom);

            if ($diff > 60) {
                $method = 'monthly';
            } elseif ($diff > 3) {
                $method = 'daily';
            } elseif ($diff > 1) {
                $method = 'byTime';
            } else {
                $method = 'hourly';
            }
        }

        return self::$method(self::data($from, $to),$from, $to);
    }
}
