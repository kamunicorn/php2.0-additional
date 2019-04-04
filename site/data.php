<?php

function get_multiply_table() {
    $table = '<table><caption>Таблица умножения<caption>';

    for ($y = 0; $y <= 10; $y++) {
        $table .= '<tr>';
        
        if ($y == 0) {
            $table .= "<th bgcolor='#333'></th>";
            for ($x = 1; $x <= 10; $x++) {
                $table .= "<th width='20px' bgcolor='#ee4'>$x</th>";
            }
        } else {
            $table .= "<td bgcolor='#ee4'>$y</td>";
            for ($x = 1; $x <= 10; $x++) {
                $table .= ($x == $y) ? 
                    "<td bgcolor='#ddd'><b>" . $x * $y . "</b></td>" : 
                    "<td>" . $x * $y . "</td>";
            }
        }
        $table .= '</tr>';
    }
    $table .= '</table><br>';
    return $table;
};

$lessons = [
    [
        'title' => 'Переменные и операторы',
        'description' => 'Что такое переменные и какие бывают операторы в язке PHP. Как можно называть переменные.',
        'example' => '<p class="example">PHP поддерживает десять простых типов:</p>
        <ul class="example">
            <li>boolean</li>
            <li>integer</li>
            <li>float (число с плавающей точкой, также известное как double)</li>
            <li>string</li>
            <li>array</li>
            <li>object</li>
            <li>callable</li>
            <li>iterable</li>
            <li>resource</li>
            <li>NULL</li>
        </ul>
        ',
        'plus' => 'Все просто и понятно объяснено',
        'minus' => 'Много ссылок для чтения'
    ], [
        'title' => 'Типы данных, циклы, условия, функции',
        'description' => 'Вы узнаете, какие типы данных есть в PHP и что такое управляющие конструкции и как с ними работать. Познакомимся с таким понятием, как функции.',
        'example' => get_multiply_table(),
        'plus' => 'Все просто и понятно объяснено',
        'minus' => 'Много ссылок для чтения. В видео не показан альтернативный синтаксис строк (для сложных переменных) вида "Text {$array[\'index\']}"'
    ], [
        'title' => 'Практика. Делаем сайт динамическим',
        'description' => 'На уроке мы превратим статический html-шаблон в динамический с помощью языка php. Вам будут даны исходники - html-шаблон, стили, картинки, js-файлы.',
        'example' => 'Альтернативный синтаксис циклов foreach и for применен в файлах footer.php и header.php соответственно.',
        'plus' => 'Показано, как легко встраивать php-код в html-документ',
        'minus' => 'Легкое дополнительное задание'
    ]
];
$lesson = $lessons[$lesson_number-1];
?>
