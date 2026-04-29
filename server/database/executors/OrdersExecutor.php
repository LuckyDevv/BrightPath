<?php

namespace executors;

class OrdersExecutor
{
    // Схема создания таблицы
    public static function CREATE_TABLE(): false|string
    {
        return file_get_contents(__DIR__."/../schema/orders.sql");
    }

    // Добавление нового заказа
    public static function ADD_ORDER(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/addOrder.sql");
    }

    // Получение всех заказов
    public static function GET_ALL_ORDERS(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getAllOrders.sql");
    }

    // Получение последних 5 заказов
    public static function GET_LAST_ORDERS(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getLastOrders.sql");
    }

    // Получение уникальных клиентов за сегодня
    public static function GET_NEW_CLIENTS_TODAY(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getNewClientsToday.sql");
    }

    // Получение уникальных клиентов за вчера
    public static function GET_NEW_CLIENTS_YESTERDAY(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getNewClientsYesterday.sql");
    }

    // Получение заказа по ID и email
    public static function GET_ORDER_BY_ID_AND_EMAIL(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getOrderByIdAndEmail.sql");
    }

    // Получение данных для графика за 30 дней
    public static function GET_ORDERS_ALL(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getOrdersAll.sql");
    }

    // Количество заказов за сегодня
    public static function GET_ORDERS_TODAY(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getOrdersToday.sql");
    }

    // Количество заказов за вчера
    public static function GET_ORDERS_YESTERDAY(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getOrdersYesterday.sql");
    }

    // Средний чек за всё время
    public static function GET_ROUND_SUMMARY(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getRoundSummary.sql");
    }

    // Средний чек за сегодня
    public static function GET_ROUND_SUMMARY_TODAY(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getRoundSummaryToday.sql");
    }

    // Сумма заказов за сегодня
    public static function GET_SUMMARY_TODAY(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getSummaryToday.sql");
    }

    // Сумма заказов за вчера
    public static function GET_SUMMARY_YESTERDAY(): false|string
    {
        return file_get_contents(__DIR__."/../queries/orders/getSummaryYesterday.sql");
    }
}