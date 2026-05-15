<?php

namespace executors;

class RequestsExecutor
{
    public static function CREATE_TABLE(): false|string
    {
        return file_get_contents(__DIR__."/../schema/requests.sql");
    }
}