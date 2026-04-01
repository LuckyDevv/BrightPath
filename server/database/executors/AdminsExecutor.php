<?php

namespace executors;

class AdminsExecutor
{
    public function createTable(): false|string
    {
        return file_get_contents(__DIR__."/../schema/admins.sql");
    }

    public function addAdmin(): false|string
    {
        return file_get_contents(__DIR__."/../queries/admins/newUser.sql");
    }

    public function checkUser(): false|string
    {
        return file_get_contents(__DIR__."/../queries/admins/checkUser.sql");
    }
}