<?php

// questa classe permette di creare il salt per cifrare la password, eseguire l'hash della password e generare un id
class Hash {
    public static function make($string, $salt = '') {
        return hash('sha256', $string . $salt);
    }

    public static function salt($length) {
        return mcrypt_create_iv($length);
    }

    public static function unique() {
        return self::make(uniqid());
    }
}