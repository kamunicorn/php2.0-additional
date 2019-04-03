<?php
/* Создать алгоритм на языке PHP, который отслеживает текущее время при заходе на страницу 
и в зависимости от этого времени выводит на экран прямоугольник того или иного размера с тем или иным фоновым цветом:*/

class ColorBlock
{
    /* public $bg_color;
    public $width;
    public $height; */

    public $bg_color = "darkCyan";
    public $width = "100px";
    public $height = "120px";

    // public function __construct(string $bg_color, string)
    public function __construct()
    {
        $second = (int) date('s');
        $minute = (int) date('i');
        $hour = date('G'); // G - Часы в 24-часовом формате без ведущего нуля
        $day = date('j'); // j - День месяца без ведущего нуля
        $month = date('n'); // n - Порядковый номер месяца без ведущего нуля
        $year = date('Y');
        
        echo $second . " секунда<br>";
        echo $minute . " минута<br>";
        echo $hour . " час<br>";
        echo $day . " день<br>";
        echo $month . " месяц<br>";
        echo $year . " год<br>";

/* - Если текущая минута кратна трём (например, 19:21 или 15:57), то выводим на экран красный прямоугольник, 
где ширина (в px) - это текущий час умножить на 20, а высота (в px) - это текущий день месяца в кубе
- Если текущая минута кратная двум, НО не кратна трём (например, 10:02 или 11:14), то выводим на экран желтый квадрат, 
где ширина и высота (в px) - это корень из текущего года, округленный до целого числа и умноженный на 5
- Во всех остальных случаях выводим на экран зеленый прямоугольник, где ширина (в px) - это текущая минута, 
умноженная на текущую секунду, а высота (в px) - это текущий час в квадрате */

        $minute = (int) date('i');
        //  && $minute % 2 != 0
        if ($minute % 3 == 0 && $minute != 0) {
            $this->bg_color = 'red';
            $this->width = $hour * 20 . "px";
            $this->height = $day ** 3 . "px";
        } elseif ($minute % 2 == 0 && $minute != 0) {
            $this->bg_color = 'yellow';
            $this->width = $this->height = 5 * round( sqrt( $year ) ) . "px";
        } else {
            $this->bg_color = 'green';
            $this->width = $minute * $second . "px";
            $this->height = $hour ** 2 . "px";
        }
    }

    public function printColorBlock()
    {
        echo "<div style='
            background-color: $this->bg_color;
            width: $this->width;
            height: $this->height;
            margin: auto;
            margin-top: 30px;
        '>Параметры:<br>
            background-color: $this->bg_color;<br>
            width: $this->width;<br>
            height: $this->height;
        </div>";
    }
}

$block = new ColorBlock();
$block->printColorBlock();
?>
