<?php

namespace Core;
use PDO;

/**
 * Base Model
 */
class Model
{
    protected static object $pdo;
    protected static string $dsn;
    protected static string $user;
    protected static string $password;
    protected array $query;

    public function __construct()
    {
        self::connect();
        $this->setQueries();
    }

    protected function setQueries(): void
    {
        $this->query = [];
    }

    public static function init($dsn, $user, $password): void
    {
        self::$dsn = $dsn;
        self::$user = $user;
        self::$password = $password;
    }

    public static function connect(): void
    {
//        if (!self::$pdo) {
        self::$pdo = new PDO(self::$dsn, self::$user, self::$password);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    }
    }

    public static function selectAll($sql, $fetch,  $parameters = null)
    {
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        return $pdostmt->fetchAll($fetch);
    }

    public static function selectRow($sql, $fetch, $parameters = null)
    {
        $sql .= " limit 1";
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        return $pdostmt->fetch($fetch);
    }
    public static function exec($sql, $parameters = null) {
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        return $pdostmt->rowCount();
    }
    public static function selectAllCount($sql,  $parameters = null)
    {
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        $row = $pdostmt->fetch(\PDO::FETCH_NUM);
        return $row? $row[0]: null;
    }
}
