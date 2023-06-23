<?php

namespace engine\helpers;

class ArrayHelper
{
    public static function is_multidimensional($arr): bool
    {
        foreach ($arr as $value) {
            if (is_array($value)) return true;
        }
        return false;
    }

    public static function all_numeric_keys($arr): bool
    {
        foreach ($arr as $key => $val) {
            if (!is_int($key)) {
                return false;
            }
        }
        return true;
    }
}