<?php


class Cookie {
//verifico la presenza di un determinato cookie
    public static function exists($name) {
        return (isset($_COOKIE[$name])) ? true : false;
    }
//    prendo il valore di un cookie, se esistente
    public static function get($name) {
        return $_COOKIE[$name];
    }
//genero un cookie
    public static function put($name, $value, $expiry) {
        if(setcookie($name, $value, time() + $expiry, '/')) {
            return true;
        }
        return false;
    }
//elimino il cookie
    public static function delete($name) {
        self::put($name, '', time() -1);
    }
}