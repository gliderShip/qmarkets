<?php
namespace App\Config;

class DbConfig
{
    public const SQLITE_FILE = 'config/db.sqlite3';
    public const DATABASE_DSN = "sqlite:" . self::SQLITE_FILE;
}
