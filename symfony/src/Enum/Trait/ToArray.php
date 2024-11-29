<?php

namespace App\Enum\Trait;

trait ToArray
{
    public static function toArray()
    {
        $values = [];

        foreach (self::cases() as $props) {
            array_push($values, $props->value);
        }

        return $values;
    }
}
