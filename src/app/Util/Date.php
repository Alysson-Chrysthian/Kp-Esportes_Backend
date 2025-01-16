<?php

namespace KpEsportes\App\Util;

class Date {

    protected int $timestamp;

    public function __construct(int $timestamp = null) {
        $this->timestamp = $timestamp ?? time();
    }

    public function format(string $format = "Y-m-d H:i:s") {
        return date($format, $this->timestamp);
    }

    public function addHours(int $hours_to_add) {
        $hours = (60 * 60) * $hours_to_add;
        $this->timestamp += $hours;
        return $this;
    }

    public static function now() {
        return new Date();
    }

}