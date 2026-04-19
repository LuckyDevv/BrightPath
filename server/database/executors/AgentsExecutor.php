<?php

namespace executors;

class AgentsExecutor
{
    public function createTable(): false|string
    {
        return file_get_contents(__DIR__."/../schema/agents.sql");
    }

    public function getAll(): false|string
    {
        return file_get_contents(__DIR__."/../queries/agents/getAll.sql");
    }

    public function addAgent(): false|string
    {
        return file_get_contents(__DIR__."/../queries/agents/addAgent.sql");
    }

    public function getById(): false|string
    {
        return file_get_contents(__DIR__."/../queries/agents/getById.sql");
    }

    public function updateAgent(): false|string
    {
        return file_get_contents(__DIR__."/../queries/agents/updateAgent.sql");
    }
}