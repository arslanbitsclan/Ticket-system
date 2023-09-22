<?php

use Spatie\Permission\Models\Role;

//It will return N/A if datetime is null use this in case you need ?? check
if (!function_exists('getTwelveHourDateTime')) {
    function getTwelveHourDateTime($dateTime, $isForForm = false, $show_sec = true)
    {
        if ($dateTime == null || $dateTime == '0000-00-00 00:00:00')
            return null;

        if ($isForForm) {
            return str_replace(",", "<br>", date("h:i:s A,Y/m/d", strtotime($dateTime)));
        } else {
            return date("Y/m/d h:i" . ($show_sec ? ":s" : "") . " A", strtotime($dateTime));
        }
    }
}

//It will return N/A if datetime is null using this in datatables
if (!function_exists('showTwelveHourDateTime')) {
    function showTwelveHourDateTime($dateTime, $show_sec = false)
    {
        return getTwelveHourDateTime($dateTime, false, $show_sec) ?? "N/A";
    }
}



?>