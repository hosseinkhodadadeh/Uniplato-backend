<?php

function getDb(): \App\Lib\DB
{
    static $db = null;
    if ($db === null) {
        $dbConfig = require ROOT . '/config/db.php';
        $db = new \App\Lib\DB($dbConfig['host'], $dbConfig['db'], $dbConfig['user'], $dbConfig['password']);

    }
    return $db;
}