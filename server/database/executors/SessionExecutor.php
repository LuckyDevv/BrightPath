<?php

namespace executors;

class SessionExecutor
{
    public static function CREATE_TABLE(): false|string
    {
        return file_get_contents(__DIR__."/../schema/sessions.sql");
    }
    public static function CREATE_SESSION(): false|string
    {
        return file_get_contents(__DIR__."/../queries/session/createSession.sql");
    }
    public static function REMOVE_SESSION(): false|string
    {
        return file_get_contents(__DIR__."/../queries/session/removeSession.sql");
    }
    public static function VERIFY_SESSION(): false|string
    {
        return file_get_contents(__DIR__."/../queries/session/verifySession.sql");
    }
}