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

    public function addVehicle(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/addVehicle.sql");
    }

    public function getVehicleById(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/getById.sql");
    }

    public function getSimilar(): false|string
    {
        return file_get_contents(__DIR__."/../queries/vehicles/getSimilar.sql");
    }
}