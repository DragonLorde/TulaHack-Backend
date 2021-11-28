<?php


class Base {
    protected static $datasourse = "mysql:host=26.190.183.122;dbname=delivery";
    protected static $login = "root";
    protected static $pass = "";
    protected static $base;
    protected static $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    function __construct () {}

    private static function crateBd() {
        if(!isset(self::$base)) {
            try {
                self::$base = new PDO(self::$datasourse , self::$login , self::$pass , self::$opt);
                self::$base->exec("set names utf8");
            }
            catch (PDOExceptin $e) {
                echo $error=$e->getMessage();
                exit();
            }
            return self::$base;
        }
    }
    public static function getBd() {
        return self::crateBd();
    }

}

$conn = Base::getBd();




?>