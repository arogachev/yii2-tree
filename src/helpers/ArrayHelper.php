<?php

namespace arogachev\tree\helpers;

class ArrayHelper
{
    /**
     * @param array $array
     * @param array $path
     * @param mixed $value
     * @param string|boolean $childrenNodeName
     */
    public static function saveByPath(&$array, $path, $value, $childrenNodeName = 'children')
    {
        $temp = &$array;
        $keysCount = count($path);
        $c = 1;

        foreach ($path as $key) {
            if ($c == $keysCount || $childrenNodeName === false) {
                $temp = &$temp[$key];
            } else {
                $temp = &$temp[$key][$childrenNodeName];
            }

            $c++;
        }

        $temp = $value;
        unset($temp);
    }
}
