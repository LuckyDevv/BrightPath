<?php

namespace executors;

class ServicesExecutor
{
    public static function GET_POPULAR(): false|string
    {
        return file_get_contents(__DIR__ . "/../queries/services/getPopular.sql");
    }
    public static function CREATE_TABLE(): false|string
    {
        return file_get_contents(__DIR__."/../schema/services.sql");
    }
    public static function ADD_SERVICE(): false|string
    {
        return file_get_contents(__DIR__."/../queries/services/addService.sql");
    }
    public static function EDIT_SERVICE(): false|string
    {
        return file_get_contents(__DIR__."/../queries/services/editService.sql");
    }
    public static function GET_ALL_FOR_CALCULATOR(): false|string
    {
        return file_get_contents(__DIR__."/../queries/services/getAllForCalculator.sql");
    }
    public static function DELETE_SERVICE_BY_ID(): false|string
    {
        return file_get_contents(__DIR__."/../queries/services/deleteServiceByID.sql");
    }
    public static function GET_ALL_SERVICES(): false|string
    {
        return file_get_contents(__DIR__."/../queries/services/getAllServices.sql");
    }
    public static function GET_ALL_SERVICES_ADMIN(): false|string
    {
        return file_get_contents(__DIR__."/../queries/services/getAllServicesAdmin.sql");
    }
    public static function GET_SERVICE_BY_ID(): false|string
    {
        return file_get_contents(__DIR__."/../queries/services/getServiceByID.sql");
    }
    public static function GET_SERVICE_BY_ID_ADMIN(): false|string
    {
        return file_get_contents(__DIR__."/../queries/services/getServiceByIDAdmin.sql");
    }
}