<?php
/**
 * Created by PhpStorm.
 * User: Unicorn
 * Date: 12.04.2019
 * Time: 0:58
 */

require_once 'ShortLink.php';

if (isset($_POST['url_base'])) {
    if (is_valid_url($_POST['url_base'])) {
        $short_link = new ShortURL($_POST['url_base']);
    } else {
        ShortURL::giveResponse(['error', 'Строка не является ссылкой!']);
    }
}

//$s_url = 'http://habr.com/ru/sandbox/32648/';
//$link = new ShortURL($s_url);
//echo "<br>короткая ссылка  = " . ShortURL::getShortURL($s_url) . "<br>";
//echo "<br>базовая ссылка  = " . ShortURL::getBaseURL('LKbDLzB7G0') . "<br>";

?>