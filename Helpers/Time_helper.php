<?php

if (!function_exists('convertisseurTime')) {

    function convertisseurTime($Time)
    {
        if ($Time < 3600) {
            $heures = 0;

            if ($Time < 60) {
                $minutes = 0;
            } else {
                $minutes = round($Time / 60);
            }

            $secondes = floor($Time % 60);
        } else {
            $heures = round($Time / 3600);
            $secondes = round($Time % 3600);
            $minutes = floor($secondes / 60);
        }

        $secondes2 = round($secondes % 60);

        $TimeFinal = $heures . 'h ' . $minutes . 'min ' . $secondes2 . 's';
        return $TimeFinal;
    }
}


if (!function_exists('timeAgo')) {
    function timeAgo($time_ago)
    {
        $time_ago = strtotime($time_ago) ? strtotime($time_ago) : $time_ago;
        $time     = time() - $time_ago;

        //echo $time;

        switch ($time):
                // seconds
            case $time <= 60:
                return lang('Admin.ago') . ' ' . lang('Admin.lessthan_a_minutes_ago');
                // minutes
            case $time >= 60 && $time < 3600:
                return (round($time / 60) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_minute') : lang('Admin.ago') . ' ' . round($time / 60) . ' ' . lang('Admin.minutes');
                // hours
            case $time >= 3600 && $time < 86400:
                return (round($time / 3600) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_hour') : lang('Admin.ago') . ' ' . round($time / 3600) . ' ' . lang('Admin.hours');
                // days
            case $time >= 86400 && $time < 604800:
                return (round($time / 86400) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_day') : lang('Admin.ago') . ' ' . round($time / 86400) . ' ' . lang('Admin.jours');
                // weeks
            case $time >= 604800 && $time < 2600640:
                return (round($time / 604800) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_week') : lang('Admin.ago') . ' ' . round($time / 604800) . ' ' . lang('Admin.weeks');
                // months
            case $time >= 2600640 && $time < 31207680:
                return (round($time / 2600640) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_month') : lang('Admin.ago') . ' ' . round($time / 2600640) . ' ' . lang('Admin.months');
                // years
            case $time >= 31207680:
                return (round($time / 31207680) === 1) ? lang('Admin.ago') . ' ' . lang('Admin.a_year') : lang('Admin.ago') . ' ' . round($time / 31207680) . ' ' . lang('Admin.years');

        endswitch;
    }
}

if (!function_exists('relative_time')) {
    /**
     * Return a string representing how long ago a given UNIX timestamp was,
     * e.g. "moments ago", "2 weeks ago", etc.
     *
     * @todo Consider updating this to use date_diff() and/or DateInterval.
     * @todo Internationalization.
     *
     * @param integer $timestamp A UNIX timestamp.
     *
     * @return string A human-readable amount of time 'ago'.
     */
    function relative_time($time)
    {
        if ($time !== '' && !is_int($time)) {
            $time = strtotime($time);
        }
        // Calculate difference between current
        // time and given timestamp in seconds
        $diff = time() - $time;

        // Time difference in seconds
        $sec = $diff;

        // Convert time difference in minutes
        $min = round($diff / 60);

        // Convert time difference in hours
        $hrs = round($diff / 3600);

        // Convert time difference in days
        $days = round($diff / 86400);

        // Convert time difference in weeks
        $weeks = round($diff / 604800);

        // Convert time difference in months
        $mnths = round($diff / 2600640);

        // Convert time difference in years
        $yrs = round($diff / 31207680);

        // Check for seconds
        if ($sec <= 60) {
            echo  lang('Core.seconds_ago', [$sec]);
        }

        // Check for minutes
        elseif ($min <= 60) {
            if ($min === 1) {
                echo lang('Core.an_minute_ago', [$hrs]);
            } else {
                echo lang('Core.minutes_ago', [$min]);
            }
        }

        // Check for hours
        elseif ($hrs <= 24) {
            if ($hrs === 1) {
                echo lang('Core.an_hour_ago', [$hrs]);
            } else {
                echo lang('Core.hours_ago', [$hrs]);
            }
        }

        // Check for days
        elseif ($days <= 7) {
            if ($days === 1) {
                echo 'Yesterday';
            } else {
                echo "$days days ago";
            }
        }

        // Check for weeks
        elseif ($weeks <= 4.3) {
            if ($weeks === 1) {
                echo 'a week ago';
            } else {
                echo lang('Core.weeks ago', [$weeks]);
            }
        }

        // Check for months
        elseif ($mnths <= 12) {
            if ($mnths === 1) {
                echo 'a month ago';
            } else {
                echo "$mnths months ago";
            }
        }

        // Check for years
        else {
            if ($yrs === 1) {
                echo 'one year ago';
            } else {
                echo "$yrs years ago";
            }
        }
    }
    if (!function_exists('encryptUrl')) {
        function encryptUrl($name)
        {
            return sha1($name . $_SERVER['REQUEST_URI']);
        }
    }
}


/**
 * now () method
 */
if (!function_exists('now')) {
    function now()
    {
        // uses the default timezone.
        return date_create('now')->format('Y-m-d H:i:s');
    }
}

// -----------------------------------------------------------------------

/**
 * nowTimeZone () method
 */
if (!function_exists('nowTimeZone')) {
    function nowTimeZone($timeZone)
    {
        // $timeZone format 'America/New_York'
        return date_create('now', timezone_open($timeZone))->format('Y-m-d H:i:s');
    }
}
