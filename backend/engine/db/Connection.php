<?php

namespace engine\db;

use core\Config;
use core\Singleton;
use PDO;
use PDOStatement;

class Connection extends Singleton
{
    private static PDO $conn;

    protected string $sql;
    protected array $binds;
    protected string $fetch_argument;
    protected string $fetch_mode;

    private array $bind_types = [
        'NULL' => PDO::PARAM_NULL,
        'integer' => PDO::PARAM_INT,
        'string' => PDO::PARAM_STR,
        'boolean' => PDO::PARAM_BOOL,
        'double ' => PDO::PARAM_INT
    ];

    public static function getConn(): PDO
    {
        if (!isset(self::$conn)) {
//            $config = require Config::get('db');
            self::init(Config::get('db'));
        }
        return self::$conn;
    }

    protected static function init(string $config_db): void
    {
        $config = require dirname(__DIR__, 2) . "/" . $config_db;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        self::$conn = new PDO('mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['dbname'], $config['username'], $config['password'], $options);
    }

    public function fetchOne(): PDOStatement
    {
        $conn = self::getConn();
        $stmt = $conn->prepare($this->sql);
        $this->bind($stmt);
        $stmt->execute();
        return $stmt;

    }

    public function fetchAll(): array
    {
        $conn = self::getConn();
        $stmt = $conn->prepare($this->sql);
        $this->bind($stmt);
        $stmt->execute();
        $this->setFetchMode($stmt);
        return $stmt->fetchAll();
    }

    public function fetchInsert(): int
    {
        $conn = self::getConn();
        $stmt = $conn->prepare($this->sql);
        $this->bind($stmt);
        $stmt->execute();
        return self::$conn->lastInsertId();
    }

    public function fetchUpdate(): bool
    {
        $conn = self::getConn();
        $stmt = $conn->prepare($this->sql);
        $this->bind($stmt);
        $stmt->execute();
        return true;
    }

    public function fetchDelete(): bool
    {
        $conn = self::getConn();
        $stmt = $conn->prepare($this->sql);
        $this->bind($stmt);
        $stmt->execute();
        return true;
    }

    // private

    private function bind(PDOStatement $stmt): void
    {
        if (isset($this->binds)) {
            foreach ($this->binds as $key => $value) {
                $stmt->bindValue($key, $value, $this->bind_types[gettype($value)]);
            }
        }
    }

    private function setFetchMode(PDOStatement $stmt): void
    {
        if (isset($this->fetch_argument)) {
            $stmt->setFetchMode($this->fetch_mode, $this->fetch_argument);
        } else {
            $stmt->setFetchMode($this->fetch_mode);
        }
    }

}