<?php
    $lesson_data = $lessons[$lesson_number];
?>

<div class="lesson__content container">
    <h3 class="lesson">Урок №<?=$lesson_number?></h3>

    <p><b>Тема:</b> <?=$lesson['title']?></p>
    <p><b>Описание:</b> <?=$lesson['description']?></p>
    <h4>Пример из урока</h4>
    <?=$lesson['example']?>

    <p><b>Плюсы урока:</b> <?=$lesson['plus']?></p>
    <p><?php echo "<b>Минусы урока:</b> {$lesson['minus']} "?></p>
</div>
