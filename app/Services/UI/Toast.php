<?php

namespace App\Services\UI;

class Toast
{
    /**
     * Summary of show
     * @param string $message
     * @param "error" | "info" | "success" $type
     */
    public static function show($message = "toast message.", $type = "info")
    {
        $messages = [
            'message' => $message,
            'type' => $type
        ];
        return session()->flash('toast', $messages);
    }
}