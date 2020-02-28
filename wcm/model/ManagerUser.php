<?php
include "Manager.php";

const NICK_MIN_LENGTH = 4;
const NICK_MAX_LENGTH = 30;
const NICK_LENGTH_ERROR = "Dĺžka Vášho nicku musí byť medzi " . NICK_MIN_LENGTH . " a " . NICK_MAX_LENGTH . " znakmi!";
const NICK_ALREADY_EXISTS = "Nick, ktorý ste si zvolili už používa iný užívateľ! Zvoľte iný.";

const PASSWD_MIN_LENGTH = 6;
const PASSWD_MAX_LENGTH = 30;
const PASSWD_LENGTH_ERROR = "Dĺžka Vášho hesla musí byť medzi " . PASSWD_MIN_LENGTH . " a " . PASSWD_MAX_LENGTH . " znakmi!";
const PASSWD_NOT_MATCH = "Zadali ste heslá, ktoré sa nezhodujú!";

const MAIL_MIN_LENGTH = 6;
const MAIL_MAX_LENGTH = 60;
const MAIL_ERROR = "Zadali ste nevalídnu E-mailovú adresu!";
const MAIL_ALREADY_EXISTS = "Na túto E-mailovú adresu je už registrovaný iný užívateľ!";

const ERROR_LOGIN = "Zadali ste zlé meno alebo heslo!";

class ManagerUser extends Manager
{
    public function userSignUp($nick, $passwd, $passwd2, $mail) {
        $this->checkMail($mail);
    }

    //existuje uz takyto nick v databaze
    private function checkNickInDb($nick) {
        $task = 'SELECT id_user FROM user WHERE nick = ? LIMIT 1';

        if(DBWrap::existInDb($task, [$nick])) {
            echo "nick je uz pouzivany";
        }
        echo "nick je OK";
        return true;
    }

    //ma zadany mail tvar mailovej adresy
    private function checkMail($mail) {
        if ($this->checkLength($mail, MAIL_MAX_LENGTH, MAIL_MIN_LENGTH) && filter_var($parMail, FILTER_VALIDATE_EMAIL)) {
            echo "mail je spravny";
            return true;
        }
        echo "mail je nespravny";
        return false;//todo throw error namiesto tohoto
    }

    //existuje takyto mail v databaze?
    private function checkMailInDb($mail) {
        $task = 'SELECT id_user FROM user WHERE mail = ? LIMIT 1';

        if(DBWrap::existInDb($task, [$mail])) {
            echo "takyto mail je uz pouzity!";//todo throw error
        }
        else {echo "takyto mail je este nepouzity";}
    }

    private function checkTwoPasswd($passwd1, $passwd2) {

    }
}