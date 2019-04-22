<?php
/**
 * Created by PhpStorm.
 * User: Unicorn
 * Date: 12.04.2019
 * Time: 14:00
 */
/*Создать сервис коротких ссылок (простейший аналог https://bitly.com/, форма - кнопка - вывод короткой ссылки)
Требования:
1. основная логика лежит на ООП (простыми словами, нужен класс с методами)
2. в одном из методов должна быть логика создания таблицы со ссылками, если она еще не была создана
3. форма отправляется асинхронно, без перезагрузки страницы (ввел ссылку - без перезагрузки страницы получил короткую ссылку)
4. чистый код с комментариями, без костылей и мусора, понятная структура, приятная архитектура
5. должна быть хотя бы минимальная проверка на вводимое пользователем значение с помощью регулярного выражения
(похоже оно (значение) на ссылку или нет, если нет - асинхронно вывести предупреждение об этом)*/

interface iConnectionDatabase
{
    const DB_NAME = 'reducer';
    const DB_ROOT = 'root';
    const DB_PASS = '';

    /*const DB_NAME = 'vera_reducer';
    const DB_ROOT = 'vera_root';
    const DB_PASS = 'root';*/

    public static function createTable();
    public static function connectToDatabase();
    public function insert();
    public static function hasInDatabase(string $columnName, string $value);
}

interface iGiveResponse
{
    public static function giveResponse($to_response);
}

class ShortURL extends URL implements iConnectionDatabase, iGiveResponse
{
//    режим, когда выдается ссылка вида bit.ly/qwerty (TRUE), а не bit.ly/r.php?url_key=qwerty (FALSE)
    const SUPER_SHORT_URL_MODE = FALSE;
//    длина рандомной строки
    const LENGTH = 10;

    public static $connection;
    public $baseURL;
    public $URLKey;
    public $shortURL;

    public function __construct(string $baseURL)
    {
        if (self::hasInDatabase('base_url', $baseURL)) {
            $this->baseURL = $baseURL;
            $this->URLKey = $this->findURLKey($baseURL);
            $message = "Ссылка уже сформирована!";
            $this->shortURL = $this->createShortURL($this->URLKey);
        } else {
            $this->baseURL = $baseURL;
            $this->URLKey = $this->createURLKey();
            $message = "Ссылка сформирована!";
            $this->shortURL = $this->createShortURL($this->URLKey);
            $this->insert();
        }
        self::giveResponse([
            'type' => 'OK',
            'message' => $message,
            'data' => $this
        ]);
    }

    //    выдает ответ в json (ошибки, сообщения, данные)
    public static function giveResponse($to_response)
    {
        $response = null;
        if ($to_response instanceof ShortURL) {
            $response = [
                'type' => 'OK',
                'message' => 'Ссылка сформирована!',
                'data' => [
                    'baseURL' => $to_response->baseURL,
                    'shortURL' => $to_response->shortURL
                ]
            ];
        }
        if (is_array($to_response)) {
            $response = [
                'type' => $to_response['type'],
                'message' => $to_response['message']
            ];
            if ($to_response['data']) {
                $response['data'] = $to_response['data'];
            }
        }
        echo json_encode($response);
    }

    public function __toString()
    {
        return "baseURL = $this->baseURL<br>URLKey = $this->URLKey<br>shortURL = $this->shortURL<br>";
    }

//    получение полного пути для ссылки
    private function createShortURL(string $URLKey)
    {
        $path = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . "/reducer";
        if (self::SUPER_SHORT_URL_MODE) {
            return "$path/$URLKey";
        } else {
            return "$path/r.php?url_key=$URLKey";
        }
    }

    private function createURLKey()
    {
        $random_string = null;
        do {
            $random_string = self::getRandomString(self::LENGTH);
        } while (self::hasInDatabase('url_key', $random_string));
        return $random_string;
    }

    public function findURLKey(string $baseURL)
    {
        self::connectToDatabase();
        $result = self::$connection->query("SELECT url_key FROM links WHERE base_url='$baseURL';");
        return ($result->fetch())['url_key'];
    }

    public static function findBaseURL(string $URLKey)
    {
        self::connectToDatabase();
        $result = self::$connection->query("SELECT base_url FROM links WHERE url_key='$URLKey';");
        return ($result->fetch())['base_url'];
    }

    public function insert()
    {
        self::createTable();
        self::$connection->query(
            "USE " . self::DB_NAME . "; INSERT INTO links (base_url, url_key) "
            . " VALUES ('$this->baseURL', '$this->URLKey')");
    }

    public static function hasInDatabase(string $columnName, string $value)
    {
        self::connectToDatabase();
        $result = self::$connection->query("SELECT * FROM links WHERE $columnName='$value';");
        if ($result->fetch()) {
            return TRUE;
        }
        return FALSE;
    }

    public static function createTable()
    {
        self::connectToDatabase();
        $query = 'USE ' . self::DB_NAME . '; CREATE TABLE IF NOT EXISTS links (' .
            'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
            'base_url varchar(255) UNIQUE,' .
            'url_key varchar(20) UNIQUE);';

        self::$connection->query($query);
    }

    public static function connectToDatabase()
    {
        self::$connection = new PDO('mysql: host=localhost; dbname=' . self::DB_NAME . '; charset=utf8', self::DB_ROOT , self::DB_PASS);
        if (!self::$connection) {
            self::giveResponse(['type' => 'error', 'message' => 'Соединение с базой не установлено!']);
            die();
        }
        self::$connection->SetAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
}

// Класс-помощник, содержит вспомогательные функции, имеющие не такое прямое отношение к самим коротким ссылкам
abstract class URL
{
    public static function getRandomString(int $required_length)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $count_chars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $required_length; $i++) {
            $string .= substr($chars, rand(1, $count_chars) - 1, 1);
        }
        return $string;
    }

    public static function isValidURL(string $string) {
        if (preg_match("~^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?~i", $string)) {
            return true;
        }
        return false;
    }

    public static function isActiveURL(string $url) {
//        return true;
//       Распознает у https://vk.com и https://facebook.com http_code 301, 302
        $headers = get_headers($url);
        $http_code = substr($headers[0], 9, 3);
        if ($http_code >= 200 && $http_code < 305) {
            return true;
        }
        return false;
    }

    public static function isValidURLKey(string $string) {
        if (strlen($string) == ShortURL::LENGTH
            && preg_match("/[A-Z0-9]/i", $string)) {
            return true;
        }
        return false;
    }
}
