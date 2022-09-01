<?php

namespace app\models;

class util
{
    const SALT = '4dm1nk3y';

    public static function hash($password) {
        return hash('md5', self::SALT . $password);
    }

    public static function verify($password, $hash) {
        return ($hash == self::hash($password));
    }
}