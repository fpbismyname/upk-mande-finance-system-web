<?php

namespace App\Services\UI;

class Toast
{
    public static function success($message = "toast message.")
    {
        $messages = [
            'message' => $message,
            'type' => 'success'
        ];
        return session()->flash('toast', $messages);
    }
    public static function info($message = "toast message.")
    {
        $messages = [
            'message' => $message,
            'type' => 'info'
        ];
        return session()->flash('toast', $messages);
    }
    public static function error($message = "toast message.")
    {
        $messages = [
            'message' => $message,
            'type' => 'error'
        ];
        return session()->flash('toast', $messages);
    }
}