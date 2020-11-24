<?php

if (!function_exists('arrayToArray')) {
    function arrayToArray(array $array)
    {
        $newArray = [];
        $i = 0;
        foreach ($array as $k => $v) {
            $newArray[$v['name']] = $v['value'];
            $i++;
        }
        return $newArray;
    }
}
