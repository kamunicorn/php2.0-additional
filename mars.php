<?php
/* 1. создать абстрактный класс ИНОПЛАНЕТЯНИН со свойствами "имя", "количество конечностей", "количество глаз" и "цвет кожи", а также соответствующие методы - "получить имя", "получить кол-во конечностей", "получить кол-во глаз" и "получить цвет кожи". Значения всех свойств получаем через конструктор
2. создать класс МАРСИАНИН и унаследовать его от класса ИНОПЛАНЕТЯНИН. Дополнить свойством "количество порабощенных землян" и соответствующим методом, которое это кол-во возвращает. Дополнить свойством родительский конструктор
3. создать несколько объектов класса МАРСИАНИН
4. получить полную информацию о каждом марсианине за минимальное кол-во строк кода и вывести это инфо на экран 
5. получить полное количество порабощенных марсианами землян и вывести это значение на экран
6. при желании усложнить задание, дополнив его интерфейсами и константами (на ваше усмотрение) */

interface iEnslave
{
    public function toEnslave(int $slaves);
}
abstract class Alien
{
    protected $name;
    protected $legs;
    protected $eyes;
    protected $color;

    public function __construct(string $name, int $legs, int $eyes, string $color)
    {
        $this->name = $name;
        $this->legs = $legs;
        $this->eyes = $eyes;
        $this->color = $color;
    }

    public function getName()
    {
        echo "Имя марсианина $this->name <br>";
    }
    public function getLegs()
    {
        echo "Количество ног $this->legs <br>";
    }
    public function getEyes()
    {
        echo "Количество глаз $this->eyes <br>";
    }
    public function getColor()
    {
        echo "Цвет кожи $this->color <br>";
    }
    // Класс без абстрактного метода - то же самое, что просто класс
    abstract protected function getAllInfo();
}

class Martian extends Alien implements iEnslave
{
    private $slaves;
    static private $allSlaves = 0;

    public function __construct(string $name, int $legs, int $eyes, string $color, int $slaves = 0)
    {
        $this->slaves = $slaves;
        self::$allSlaves += $slaves;
        parent::__construct($name, $legs, $eyes, $color);
    }

    public function toEnslave(int $number_of_slaves)
    {
        $this->slaves += $number_of_slaves;
        self::$allSlaves += $number_of_slaves;
        echo "Теперь у марсианина $this->name имеется $this->slaves порабощенных землян (на $number_of_slaves больше)<br>";
    }
    static public function getCountAllSlaves()
    {
        echo "Количество порабощенных землян у всех марсиан - " . self::$allSlaves;
    }
    public function getSlavesQuantity()
    {
        echo "Количество порабощенных землян у марсианина $this->name - $this->slaves<br>";
    }
    public function getAllInfo()
    {
        echo "Имя марсианина: $this->name. Количество ног: $this->legs. Количество глаз: $this->eyes. Цвет кожи: $this->color. Количество порабощенных землян: $this->slaves<br>";
    }
}

$tanos = new Martian('Танос', 2, 2, 'фиолетовый', 15);
$john =  new Martian('John', 5, 2, 'yellow', 1);
$rupert =  new Martian('Rupert', 2, 2, 'white', 0);
$jabba =  new Martian('Джабба Хатт', 1, 2, 'зеленый', 25);

$tanos->getAllInfo();
$john->getAllInfo();
$rupert->getAllInfo();
$jabba->getAllInfo();
echo "<br>";

$jabba->getSlavesQuantity();
$tanos->toEnslave(12);

Martian::getCountAllSlaves();

?>