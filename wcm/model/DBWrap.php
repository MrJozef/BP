<?php

const ERROR_DB_CONNECT = "Nepodarilo sa pripojiť k databáze!";

class DBWrap
{
    private static $DbConn;

    public static function connect($host, $dbName, $user, $password) {

        if (!isset(self::$DbConn))      //ak este nebolo vytvorene spojenie s DB vytvorim ho
        {
            try {
                self::$DbConn = new PDO(
                    "mysql:host=$host;dbname=$dbName",
                    $user,
                    $password,
                    //nastavenia spojenia
                    [   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        //chyby budu sposobovat vynimky
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",   //nastavenie kodovania
                        PDO::ATTR_EMULATE_PREPARES => false,                //speci vecicka - přenechá vkládání parametrů do dotazu na databázi
                    ]
                );

            }
            catch (PDOException $exception) {
                throw new MyException(ERROR_DB_CONNECT);
            }
        }
    }

    //z db vracia 1 riadok/zaznam - pri SELECT
    public static function selectOne($statement, $param = []) {
        return self::query($statement, $param)->fetch();
    }

    //z db vracia viac riadkov - pri SELECT
    public static function selectAll($statement, $param = []) {
        return self::query($statement, $param)->fetchAll();
    }

    //ak pri SELECT najdeme vyhovujuci zaznam/zaznamy v tabulke - true; pouziva sa ja pri INSERT, ak sa podarilo vlozit do db - vrati 1, inak exception
    public static function queryUniversal($statement, $param = []) {
        return self::query($statement, $param)->rowCount() ? true : false;
    }

    private static function query($statement, $param = []) {
        $prepared = self::$DbConn->prepare($statement);
        $prepared->execute($param);
        return $prepared;
    }
}