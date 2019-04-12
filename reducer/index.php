<?php
/**
 * Created by PhpStorm.
 * User: Unicorn
 * Date: 11.04.2019
 * Time: 21:29
 */

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Укротитель ссылок</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="main-wrapper">
        <h1>Укротитель ссылок</h1>
        <form class="form-reducer" action="handler.php" target="_self" method="post">
            <input class="form-reducer__link" id="url_base" type="text" name="url_base" placeholder="Вставьте исходную ссылку">
            <input class="form-reducer__button" type="submit" value="Сократить!">
        </form>
        <div class="response">
            <div class="response__message"></div>
            <div class="response__base-url"></div>
            <div class="response__short-url"></div>
        </div>
    </div>

</body>
</html>
