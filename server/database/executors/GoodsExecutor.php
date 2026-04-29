<?php

namespace executors;

class GoodsExecutor
{
    public static function CREATE_TABLE(): string|false
    {
        return file_get_contents(__DIR__."/../schema/goods.sql");
    }
    public static function GET_POPULAR(): string|false
    {
        return file_get_contents(__DIR__."/../queries/goods/getPopular.sql");
    }
    public static function ADD_GOODS(): false|string
    {
        return file_get_contents(__DIR__."/../queries/goods/addGoods.sql");
    }
    public static function GET_ALL_FOR_CALCULATOR(): string|false
    {
        return file_get_contents(__DIR__."/../queries/goods/getAllForCalculator.sql");
    }
    public static function GET_GOODS_BY_ID(): false|string
    {
        return file_get_contents(__DIR__."/../queries/goods/getGoodsById.sql");
    }
    public static function GET_GOODS_BY_ID_ADMIN(): false|string
    {
        return file_get_contents(__DIR__."/../queries/goods/getAllGoodsAdmin.sql");
    }
    public static function GET_ALL_GOODS(): false|string
    {
        return file_get_contents(__DIR__."/../queries/goods/getAllGoods.sql");
    }
    public static function GET_ALL_GOODS_ADMIN(): false|string
    {
        return file_get_contents(__DIR__."/../queries/goods/getAllGoodsAdmin.sql");
    }
}