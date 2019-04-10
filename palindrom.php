<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Поиск палиндрома</title>
</head>
<body>
<form action="" target="_self" method="post">
    <label for="text">Введите фразу для поиска в ней палиндрома:</label><br>
    <input type="text" name="text" required>
    <input type="submit">
</form>
</body>
</html>

<?php
if (isset($_POST['text'])) {
    $input = $_POST['text'];

    //$input = 'лопрвопыаг лоооол ннннррррнннн Вор в лесу сел в ров';
    //$input = 'фраз без палиндрома';

    $text = str_replace(' ', '', mb_strtolower($input));
    $length = mb_strlen($text);

    if ($length < 3) {
        echo "Фраза \"$input\" слишком короткая";
    } elseif ($text == string_reverse($text)) {
        echo "Фраза \"$input\" является палиндромом!";
    } else {
        //    миниполиндром (внутренний)
        $mini_p = '';
        for ($i = 0; $i <= $length - 2; $i++) {
            for ($k = 3; $k <= $length - $i; $k++) {
                $sub_str = mb_substr($text, $i, $k);
                if ($sub_str == string_reverse($sub_str) && mb_strlen($sub_str) >= mb_strlen($mini_p)) {
                    $mini_p = $sub_str;
                }
            }
        }
        if ($mini_p == '') {
            echo "Фраза \"$input\" не содержит в себе подпалиндромов!<br>";
            echo "Первый символ фразы - " . mb_substr($text, 0, 1);
        } else {
            echo "В фразе \"$input\" самый длинный подпалиндром - \"$mini_p\"<br>";
        }
    }
}


function string_reverse($string) {
    $chars = [];
    for ($i = mb_strlen($string) -1; $i >= 0; $i--) {
        $chars[] = mb_substr($string, $i, 1);
    }
    return implode($chars);
}

?>

