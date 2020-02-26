<?php


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
                echo "Spojenie s DB bolo uspesne!";                         //TODO toto 1. uspesne echo asi nebude treba

            }
            catch (PDOException $exception) {
                echo "Spojenie s DB zlyhalo!<br>Error: " . $exception->getMessage();    //TODO toto 2. echo inak
                return false;
            }
        }

        return true;
    }

    //z db vracia 1 riadok/zaznam - pri SELECT
    public static function queryOne($statement, $param = []) {
        return self::query($statement, $param)->fetch();
    }

    //z db vracia viac riadkov - pri SELECT
    public static function queryAll($statement, $param = []) {
        return self::query($statement, $param)->fetchAll();
    }

    //ak pri selecte najdeme vyhovujuci zaznam/zaznamy v tabulke - true
    public static function existInDb($statement, $param = []) {
        return self::query($statement, $param)->rowCount() ? true : false;
    }

    private static function query($statement, $param = []) {
        $prepared = self::$DbConn->prepare($statement);
        return $prepared->execute($param);
    }
}