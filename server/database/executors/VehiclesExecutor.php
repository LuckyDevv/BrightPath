<?php
namespace executors;

class VehiclesExecutor
{
    public static function CREATE_TABLE(): false|string
    {
        return file_get_contents(__DIR__."/../schema/vehicle.sql");
    }

    public static function GET_ALL_VEHICLES(): false|string
    {
        return file_get_contents(__DIR__ . "/../queries/vehicles/getAllVehicles.sql");
    }

    public static function GET_ALL_VEHICLES_ADMIN(): false|string
    {
        return file_get_contents(__DIR__ . "/../queries/vehicles/getAllVehiclesAdmin.sql");
    }

    public static function ADD_VEHICLE(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/addVehicle.sql");
    }

    public static function GET_POPULAR(): false|string
    {
        return file_get_contents(__DIR__ . "/../queries/vehicles/getPopular.sql");
    }

    public static function GET_VEHICLE_BY_ID(): false|string
    {
        return file_get_contents(__DIR__ . "/../queries/vehicles/getVehicleById.sql");
    }

    public static function GET_VEHICLE_BY_ID_ADMIN(): false|string
    {
        return file_get_contents(__DIR__ . "/../queries/vehicles/getVehicleByIdAdmin.sql");
    }

    public static function GET_SIMILAR(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/getSimilar.sql");
    }

    public static function DELETE_VEHICLE_BY_ID(): false|string
    {
        return file_get_contents(__DIR__ . "/../queries/vehicles/deleteVehicleById.sql");
    }

    public static function GET_IMAGE_PATH(): false|string
    {
        return file_get_contents(__DIR__ . "/../queries/vehicles/getImagePath.sql");
    }

    public static function GET_ALL_FOR_CALCULATOR(): false|string
    {
        return file_get_contents(__DIR__ . "/../queries/vehicles/getAllForCalculator.sql");
    }

    public static function GET_VEHICLE_BY_ID_FOR_CART()
    {
        return file_get_contents(__DIR__ . "/../queries/vehicles/getVehicleByIdForCart.sql");
    }
}