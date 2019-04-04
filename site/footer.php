<?php
    $footer_items = [
        [
            'title' => 'Типы данных',
            'link' => 'https://www.php.net/manual/ru/language.types.php'
        ], [
            'title' => 'Операторы',
            'link' => 'https://www.php.net/manual/ru/language.operators.php'
        ], [
            'title' => 'Управляющие конструкции',
            'link' => 'https://www.php.net/manual/ru/language.control-structures.php'
        ], [
            'title' => 'Функции',
            'link' => 'https://www.php.net/manual/ru/language.functions.php'
        ]
    ];
?>

    <footer>
        <div class="container">
            <ul class="menu menu-footer">
                <? foreach ($footer_items as $item) : ?>
                <li class="menu-footer__item menu__item"><a href="<?=$item['link']?>" target=_blank><?=$item['title']?></a></li>
                <? endforeach; ?>
            </ul>
        </div>
        <!-- /.container -->
    </footer>

</body>
</html>
