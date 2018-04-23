<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 12.02.2018
 * Time: 15:42
 */

namespace Omadonex\Support\Classes\Utils;

class UtilsCustom
{

    public static function random_str($length,
        $keySpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keySpace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keySpace[random_int(0, $max)];
        }
        return $str;
    }

    public static function timezoneList()
    {
        $timezoneIdentifiers = \DateTimeZone::listIdentifiers();
        $utcTime = new \DateTime('now', new \DateTimeZone('UTC'));

        $tempTimezones = array_map(function ($timezoneIdentifier) use ($utcTime) {
            $currentTimezone = new \DateTimeZone($timezoneIdentifier);
            return [
                'offset' => (int)$currentTimezone->getOffset($utcTime),
                'identifier' => $timezoneIdentifier
            ];
        }, $timezoneIdentifiers);

        // Sort the array by offset,identifier ascending
        usort($tempTimezones, function($a, $b) {
            return ($a['offset'] == $b['offset'])
                ? strcmp($a['identifier'], $b['identifier'])
                : $a['offset'] - $b['offset'];
        });

        return $tempTimezones;
    }
}

