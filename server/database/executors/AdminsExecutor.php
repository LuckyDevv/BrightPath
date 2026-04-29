<?php

namespace executors;

class AdminsExecutor
{
    public static function CREATE_TABLE(): false|string { return file_get_contents(__DIR__."/../schema/admins.sql"); }
    public static function ADD_ADMIN_USER(): false|string { return file_get_contents(__DIR__ . "/../queries/admins/addAdminUser.sql"); }
    public static function CHECK_ADMIN_USER(): false|string { return file_get_contents(__DIR__ . "/../queries/admins/checkAdminUser.sql"); }
    public static function CHANGE_PASSWORD(): false|string { return file_get_contents(__DIR__."/../queries/admins/changePassword.sql"); }
    public static function CHECK_BLOCK(): false|string { return file_get_contents(__DIR__."/../queries/admins/checkBlock.sql"); }
    public static function CHECK_LOGIN(): false|string { return file_get_contents(__DIR__."/../queries/admins/checkLogin.sql"); }
    public static function GET_ALL_ADMINS(): false|string { return file_get_contents(__DIR__."/../queries/admins/getAllAdmins.sql"); }
    public static function GET_BY_ID(): false|string { return file_get_contents(__DIR__."/../queries/admins/getById.sql"); }
    public static function GET_LAST_PASSWORD_UPDATE(): false|string { return file_get_contents(__DIR__."/../queries/admins/getLastPasswordUpdate.sql"); }
    public static function GET_ROLE(): false|string { return file_get_contents(__DIR__."/../queries/admins/getRole.sql"); }
    public static function GET_TOTP_SECRET(): false|string { return file_get_contents(__DIR__."/../queries/admins/getTotpSecret.sql"); }
    public static function REMOVE_ACCOUNT(): false|string { return file_get_contents(__DIR__."/../queries/admins/removeAccount.sql"); }
    public static function RESET_TOTP(): false|string { return file_get_contents(__DIR__."/../queries/admins/resetTotp.sql"); }
    public static function SETUP_TOTP(): false|string { return file_get_contents(__DIR__."/../queries/admins/setupTotp.sql"); }
    public static function UPDATE_LAST_DATA(): false|string{ return file_get_contents(__DIR__."/../queries/admins/updateLastData.sql"); }
}