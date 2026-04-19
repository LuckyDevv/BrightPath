<?php

namespace executors;

class SessionExecutor
{
    public function createTable(): false|string
    {
        return file_get_contents(__DIR__."/../schema/sessions.sql");
    }
}