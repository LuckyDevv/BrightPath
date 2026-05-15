<?php

namespace executors;

class PresetsExecutor
{
    public static function CREATE_TABLE(): false|string
    {
        return file_get_contents(__DIR__."/../schema/presets.sql");
    }
}