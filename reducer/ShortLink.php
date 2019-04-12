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

//$config = require_once 'config.php';
//$type = $config['type'];
//var_dump($config);


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
    public static function getRow($columnName, $value);
    public function insert();
//    public static function isExistsInDatabase($columnName, $value);
}

interface iGiveResponse
{
    public static function giveResponse($to_response);
}

interface iDiagnostic
{
    public function print_message($message_text);
}

class ShortURL implements iConnectionDatabase, iGiveResponse
{
    private static $connection;
    public $baseURL;
    public $URLKey;
    public $shortURL;
    const LENGTH = 10;

    public function __construct($baseURL)
    {
        if (self::hasInDatabase('base_url', $baseURL)) {
            $this->getShortUrl($baseURL);
            /*self::giveResponse(['error', "Ссылка уже сформирована! \"$baseURL\" уже существует в базе данных. " .
                "Ее сокращенная ссылка = " . self::getShortUrl($baseURL)]);*/
            self::giveResponse(['OK', "Ссылка уже сформирована!", 'data' => [
                'base_url' => $baseURL, 'short_url' => self::getShortUrl($baseURL)]
            ]);
        } else {
            $this->baseURL = $baseURL;
            $this->URLKey = $this->createURLKey();
            $this->shortURL = $this->createShortURL($this->URLKey);
            $this->insert();
//            echo $this;
            self::giveResponse($this);
        }
    }
    public function __toString()
    {
        return "baseURL = $this->baseURL<br>URLKey = $this->URLKey<br>shortURL = $this->shortURL<br>";
    }

    private function createShortURL($URLKey)
    {
        return "r.php?url_key=$URLKey";
    }

    private function createURLKey()
    {
        $random_string = null;
        do {
            $random_string = generate_random_string(self::LENGTH);
        } while (self::hasInDatabase('url_key', $random_string));
        return $random_string;
    }

    public static function giveResponse($to_response)
    {
        $response = null;
        if ($to_response instanceof ShortURL) {
            $response = [
                'type' => 'OK',
                'message' => 'Ссылка сформирована!',
                'data' => [
                    'base_url' => $to_response->baseURL,
                    'short_url' => $to_response->shortURL
//                    ,'url_key' => $to_response->URLKey
                ]
            ];
        }
        if (is_string($to_response)) {
            $response = $to_response;
        }
        if (is_array($to_response)) {
            $response = [
                'type' => $to_response[0],
                'message' => $to_response[1]
            ];
        }
        if (is_array($to_response) && $to_response['data']) {
            $response['data'] = $to_response['data'];
        }
        /*if (is_array($to_response[2])) {
            $response['data'] = [
                'base_url' => $to_response->baseURL,
                'short_url' => $to_response->shortURL,
                'url_key' => $to_response->URLKey
            ];
        }*/
        echo json_encode($response);
    }

    public static function getRow($columnName, $value)
    {
        self::connectToDatabase();
        $query = "SELECT * FROM links WHERE $columnName='$value';";
        if (self::$connection) {
            $result = self::$connection->query($query);
            $result = $result->fetch(PDO::FETCH_ASSOC);
//            print_result($query, $result);
            return $result;
        } else {
            self::giveResponse(['error', 'Соединение с базой не установлено!']);
        }
    }

    public static function getShortURL($baseURL)
    {
        $result = self::getRow('base_url', $baseURL);
//        print_result('', $result);
        return $result['short_url'];
    }

    public static function getBaseURL($URLKey)
    {
        $result = self::getRow('url_key', $URLKey);
//        print_result('', $result);
        return $result['base_url'];
    }

    public function insert()
    {
        self::connectToDatabase();
        self::createTable();
        $query = "USE " . self::DB_NAME . "; INSERT INTO links (base_url, short_url, url_key) VALUES ('$this->baseURL', '$this->shortURL', '$this->URLKey');";
        $result = self::$connection->query($query);
//        print_result($query, $result->fetch(PDO::FETCH_ASSOC));
    }

    public static function hasInDatabase($columnName, $value)
    {
        self::connectToDatabase();
        $query = "SELECT * FROM links WHERE $columnName='$value';";

        if (self::$connection) {
            /*$result = self::$connection->query($query);
            $result = $result->fetch(PDO::FETCH_ASSOC);*/

            $result = self::$connection->query($query);
            if ($result == FALSE) {
                self::giveResponse(['error', 'Эта противная ошибка']);
                return FALSE;
            }
            $result = $result->fetch(PDO::FETCH_ASSOC);
            
//            print_result($query, $result);
            if ($result) {
                return TRUE;
            }
        } else {
            self::giveResponse(['error', 'Соединение с базой не установлено!']);
        }
    }

    public static function createTable()
    {
        $query = 'USE ' . self::DB_NAME . '; CREATE TABLE IF NOT EXISTS links (' .
            'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
            'base_url varchar(255) UNIQUE,' .
            'short_url varchar(30) UNIQUE,' .
            'url_key varchar(20) UNIQUE);';

        if (self::$connection) {
            $result = self::$connection->query($query);
//            print_result($query, $result->fetch(PDO::FETCH_ASSOC));
        } else {
            self::giveResponse(['error', 'Соединение с базой не установлено!']);
        }
    }
    public static function connectToDatabase()
    {
        self::$connection = new PDO('mysql: host=localhost; dbname=' . self::DB_NAME . '; charset=utf8', self::DB_ROOT , self::DB_PASS);
    }
}

function generate_random_string($required_length)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $count_chars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $required_length; $i++) {
        $string .= substr($chars, rand(1, $count_chars) - 1, 1);
    }
    return $string;
}

function is_valid_url($string) {
    if (preg_match("~^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?~i", $string)) {
        return true;
    }
    return false;
//    return true;
    /*if (filter_var($string, FILTER_VALIDATE_URL) === FALSE) {
        return false;
    }
    return true;*/
}

function is_valid_url_key($string) {
    return true;
}

function print_message($message_text) {
    echo $message_text;
}

function print_result($query, $result){
    echo "Запрос = $query<br>";
    echo "<pre>";
    var_dump($result);
    echo "</pre>";
}