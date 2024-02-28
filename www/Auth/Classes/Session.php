<?php

namespace Auth\Classes;

class Session
{
    public static function start()
    {

        if (session_status() == PHP_SESSION_NONE || session_status() == 0) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function destroy()
    {
        // Destroy the session
        session_destroy();
    }
}
