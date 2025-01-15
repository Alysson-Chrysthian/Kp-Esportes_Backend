<?php

namespace KpEsportes\App\Http\Mail;

abstract class MailTemplate {

    abstract public static function view($args);

    abstract protected static function setUp();
    abstract protected static function getBody($args);

}