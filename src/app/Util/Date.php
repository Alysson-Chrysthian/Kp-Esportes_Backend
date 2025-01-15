<?php

namespace KpEsportes\App\Util;

class Date {

    public static function now(string $format = "Y-m-d") {
        return date($format);
    }

}