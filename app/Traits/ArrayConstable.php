<?php


namespace App\Traits;


trait ArrayConstable
{
    /**
     * Получить все константы по префиксу
     *
     * @param $prefix
     * @return array
     * @throws \ReflectionException
     */
    private static function getConstsArray($prefix)
    {
        // Some magic of ReflectionClass
        $constants = (new \ReflectionClass(static::class))->getConstants();

        return array_filter($constants, function ($k) use ($prefix) {
            return substr($k, 0, strlen($prefix)) == $prefix;
        }, ARRAY_FILTER_USE_KEY);
    }
}
