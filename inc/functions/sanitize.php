<?php
require_once 'inc/init.php';

// questa fuzione serve a evitare che vengano eseguiti script javascript inseriti nel database da utenti malevoli
function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}