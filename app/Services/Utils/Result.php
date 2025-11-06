<?php

namespace App\Services\Utils;

class Result
{
    public function __construct(
        public bool $ok,
        public string $message,
        public string $type_message
    ) {
    }
    public static function success(string $message)
    {
        return new self(true, $message, 'success');
    }
    public static function info(string $message)
    {
        return new self(true, $message, 'info');
    }
    public static function error(string $message)
    {
        return new self(false, $message, 'error');
    }
}