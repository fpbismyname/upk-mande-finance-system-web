<?php

namespace App\Services\Utils;

class Debug
{
    public static $debug = false;
    public static $deadDump = false;
    public static function dump(...$data)
    {
        switch (self::$deadDump) {
            case true:
                if (self::$debug == true) {
                    dd($data);
                }
                break;
            case false:
                if (self::$debug == true) {
                    dump($data);
                }
                break;
        }
    }
}