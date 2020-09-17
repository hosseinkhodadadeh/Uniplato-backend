<?php

namespace App\Lib;

use PDO;
use PDOException;

class DB
{

    private string $host;

    private string  $database;

    private string $username;

    private string $password;
    private PDO $pdo;

    public function __construct(string $host, string $db, string $username, string $password)
    {

        $this->host = $host;
        $this->database = $db;
        $this->username = $username;
        $this->password = $password;
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

    }

    public function select(string $query, array $bind = []): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($bind);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function exec(string $query, array $bind = []): bool
    {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($bind);
    }
}