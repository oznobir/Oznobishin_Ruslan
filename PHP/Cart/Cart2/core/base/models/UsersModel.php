<?php

namespace core\base\models;

use core\base\controllers\BaseMethods;
use core\base\controllers\Singleton;
use core\base\exceptions\AuthException;
use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use DateTime;
use Exception;


class UsersModel extends BaseModel
{
    use Singleton, BaseMethods;

    private string $cookieName = 'identifier';
    private string $cookieAdminName = 'UserMS';
    private array $userData = [];
    private ?string $error;
    private string $usersTable = 'visitors';
    private string $adminTable = 'users';
    private string $blockedTable = 'blocked_access';

    /**
     * @throws DbException
     */
    private function __construct()
    {
        $this->connect();
    }

    public function getAdminTable(): string
    {
        return $this->adminTable;
    }

    public function getBlockedTable(): string
    {
        return $this->blockedTable;
    }

    public function getLastError(): string
    {
        return $this->error;
    }

    /**
     * @return void
     * @throws DbException
     * @throws RouteException
     */
    public function setAdmin(): void
    {
        $this->cookieName = $this->cookieAdminName;
        $this->usersTable = $this->adminTable;
        $tables = $this->showTables();
        if (!in_array($this->usersTable, $tables)) {
            $query = 'CREATE TABLE `' . $this->usersTable .
                '` (`id` int AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `credentials` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;';

            if (!$this->query($query, 'ins'))
                throw new RouteException('Ошибка создания таблицы ' . $this->usersTable);

            $this->add($this->usersTable, [
                'fields' => [
                    'name' => 'admin',
                    'login' => 'admin',
                    'password' => md5('123'),
                ],
            ]);
        }
        if (!in_array($this->blockedTable, $tables)) {
            $query = 'CREATE TABLE `' . $this->blockedTable .
                '` (`id` int AUTO_INCREMENT PRIMARY KEY,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `trying` tinyint(1) NULL,
  `time` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;';
            if (!$this->query($query, 'ins'))
                throw new RouteException('Ошибка создания таблицы ' . $this->blockedTable);
        }
    }

    /**
     * @param false|int $id
     * @param bool $admin
     * @return false|array
     * @throws DbException
     * @throws RouteException
     */
    public function checkUser(false|int $id = false, bool $admin = false): false|array
    {
        $admin && $this->usersTable !== $this->adminTable && $this->setAdmin();
        $method = 'unPackage';
        if ($id) {
            $this->userData['id'] = $id;
            $method = 'set';
        }
        try {
            $this->$method();
        } catch (AuthException $e) {
            $this->error = $e->getMessage();
            !empty($e->getCode()) && $this->writeLog($this->error, 'log_user.txt');
            return false;
        }
        return $this->userData;
    }

    /**
     * @return bool
     * @throws AuthException
     * @uses set
     */
    private function set(): bool
    {
        $cookieString = $this->package();
        if ($cookieString) {
            setcookie($this->cookieName, $cookieString, time() + 60 * 60 * 24 * 10, PATH);
            return true;
        }
        throw new AuthException('Ошибка установки cookie', 1);
    }

    /**
     * @return false|string
     * @throws AuthException
     */
    private function package(): false|string
    {
        if (!empty($this->userData['id'])) {
            $data['id'] = $this->userData['id'];
            $data['version'] = COOKIE_VERSION;
            $data['cookieTime'] = date('Y-m-d H:i:s');
            return Crypt::instance()->encrypt(json_encode($data));
        }
        throw new AuthException('Отсутствует ID пользователя - ' . $this->userData['name']);
    }

    /**
     * @return bool
     * @throws AuthException
     * @throws DbException
     * @uses unPackage
     */
    private function unPackage(): bool
    {
        if (empty($_COOKIE[$this->cookieName]))
            throw new AuthException('Отсутствует cookie пользователя');
        $data = json_decode(Crypt::instance()->decrypt($_COOKIE[$this->cookieName]), true);
        if (empty($data['id']) || empty($data['version']) || empty($data['cookieTime'])) {
            $this->logout();
            throw new AuthException('Некорректные данные cookie пользователя', 1);
        }
        $this->validate($data);
        $this->userData = $this->select($this->usersTable, [
            'where' => ['id' => $data['id']],
        ]);
        if (!$this->userData) {
            $this->logout();
            throw new AuthException('Нет данных в таблице - ' . $this->usersTable . ' по ID пользователя - '
                . $data['id'], 1);
        }
        $this->userData = $this->userData[0];
        return true;
    }

    /**
     * @param array $data
     * @return void
     * @throws AuthException
     * @throws Exception
     */
    private function validate(array $data): void
    {
        if (!empty((COOKIE_VERSION))) {
            if ($data['version'] !== COOKIE_VERSION) {
                $this->logout();
                throw new AuthException('Некорректные версия cookie пользователя');
            }
        }
        if (!empty((COOKIE_TIME))) {
            $timeUser = (new DateTime($data['cookieTime']))->modify(COOKIE_TIME . ' minutes');
            if ((new DateTime()) > $timeUser) {
                $this->logout();
                throw new AuthException('Превышено время бездействия пользователя');
            }
        }
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        setcookie($this->cookieName, '', 1, PATH);
    }
}