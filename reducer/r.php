<?php
require_once 'ShortLink.php';

if (isset($_GET['url_key'])) {
    $url_key = $_GET['url_key'];
    if (! URL::isValidURLKey($url_key)) {
        echo "Это неверный ключ!";
    } elseif (! ShortURL::hasInDatabase('url_key', $url_key) ) {
        echo "По такому ключу нет ссылки!";
    } else {
        $base_url = ShortURL::findBaseURL($url_key);
        header('Location: ' . $base_url);
    }
}

if (isset($_POST['base_url'])) {
    if (! URL::isValidURL($_POST['base_url'])) {
        ShortURL::giveResponse(['type' => 'error', 'message' => 'Строка не является ссылкой! Вставьте ссылку в формате http://site.com/path/file']);
    } elseif (! URL::isActiveURL($_POST['base_url'])) {
        ShortURL::giveResponse(['type' => 'error', 'message' => 'Ссылка не действительна :(']);
    } else {
        $short_link = new ShortURL($_POST['base_url']);
    }
}

?>