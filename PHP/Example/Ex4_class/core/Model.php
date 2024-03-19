<?php
/**
 * Model
 */
namespace Core;
//use mysqli;

use PDO;

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
}
//    private static mysqli|false $link;
//
//    public function __construct()
//    {
////			if (!self::$link) {
//        self::$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//        mysqli_query(self::$link, "SET NAMES 'utf8'");
////			}
//    }
//
//    protected function findOne($query)
//    {
//        $result = mysqli_query(self::$link, $query) or die(mysqli_error(self::$link));
//        return mysqli_fetch_assoc($result);
//    }
//    protected function findMany($query, $by = 'id')
//    {
//        $result = mysqli_query(self::$link, $query) or die(mysqli_error(self::$link));
//        for ($data = []; $row = mysqli_fetch_assoc($result); ){
//            $data[$row[$by]] = $row;
//        }
//        return $data;
//    }