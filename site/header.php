<?php
    require_once('data.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?="Урок $lesson_number. {$lesson['title']}"?></title>

    <!-- Стили -->
    <style>
        a, a:visited {
            text-decoration: none;
            color: black;
        }
        body {margin: 0;}
        header {
            background-color: darkcyan;
            padding-top: 30px;
        }
        footer {
            padding-bottom: 30px;
            background-color: cadetblue;
        }
        .container {
            margin: auto;
            width: 1140px;
            max-width: 90%;
        }
        ul {padding-left: 0;}
        .menu, .menu-footer {
            display: inline-block;
        }
        .menu__item {
            display: inline-block;
            list-style: none;
            color: white;
            font-weight: bold;
            font-size: 26px;
            margin-left: 60px;
        }
        .menu__item:first-child {margin-left: 0;}
        .menu__item a {color: white;}
        .lesson__content {
            border: 1px solid darkcyan;
            margin-bottom: 30px;
            margin-top: 30px;
            padding: 10px 30px;
            box-sizing: border-box;
        }
        .example {
            line-height: 1;
            font-size: 16px;
            margin-left: 30px;
        }
    </style>
    
</head>
<body>

    <header>
        <div class="container">
            <ul class="menu">
                <? for ($i = 1; $i <= 3; $i++) : ?>
                <li class="menu__item"><a href="lesson<?=$i?>.php">Урок <?=$i?></a></li>
                <? endfor; ?>
            </ul>
        </div>
        <!-- /.container -->
    </header>