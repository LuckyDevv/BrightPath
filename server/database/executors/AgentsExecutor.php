<?php

namespace executors;

class AgentsExecutor
{
    public static function CREATE_TABLE(): false|string
    {
        return file_get_contents(__DIR__."/../schema/agents.sql");
    }

    public static function GET_ALL(): false|string
    {
        return file_get_contents(__DIR__."/../queries/agents/getAll.sql");
    }

    public static function ADD_AGENT(): false|string
    {
        return file_get_contents(__DIR__."/../queries/agents/addAgent.sql");
    }

    public static function GET_BY_ID(): false|string
    {
        return file_get_contents(__DIR__."/../queries/agents/getById.sql");
    }

    public static function UPDATE_AGENT(): false|string
    {
        return file_get_contents(__DIR__."/../queries/agents/updateAgent.sql");
    }

    public static function GET_IMAGE_PATH_BY_ID(): false|string
    {
        return file_get_contents(__DIR__."/../queries/agents/getImagePathByID.sql");
    }

    public static function DELETE_AGENT(): false|string
    {
        return file_get_contents(__DIR__."/../queries/agents/deleteAgent.sql");
    }
}