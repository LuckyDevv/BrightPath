<?php
namespace executors;

class VehiclesExecutor
{
    public function createTable(): false|string
    {
        return file_get_contents(__DIR__."/../schema/vehicle.sql");
    }

    public function getAllVehicles(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/getAll.sql");
    }

    public function getAllVehiclesForAdmin(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/getAllAdmin.sql");
    }

    public function addVehicle(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/addVehicle.sql");
    }

    public function getPopularVehicles(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/popularVehicles.sql");
    }

    public function getVehicleById(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/getById.sql");
    }

    public function getSimilar(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/getSimilar.sql");
    }

    public function getDeleteVehicle(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/deleteVehicle.sql");
    }
}