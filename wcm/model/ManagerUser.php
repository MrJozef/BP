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
const ERROR_ADD_NEW_USER = "Pri registrácii nastala chyba!";

class ManagerUser extends Manager
{
    public function userSignUp($nick, $passwd, $passwd2, $mail) {

        if ($this->checkNick($nick)) {
            if ($this->checkMail($mail)) {
                if ($this->checkTwoPasswd($passwd, $passwd2)) {

                    if (!$this->checkMailInDb($mail)) {

                        if (!$this->checkNickInDb($nick)) {
                            $hash = password_hash($passwd, PASSWORD_DEFAULT);

                            try {
                                $this->addNewUser($nick, $hash, $mail);
                                //todo poslanie mailu a vsetky tieto veci
                            }
                            catch(PDOException $exception) {
                                $this->createMessage(ERROR_ADD_NEW_USER);
                            }
                        }
                        else {
                            $this->createMessage(NICK_ALREADY_EXISTS);
                        }
                    }
                    else {
                        $this->createMessage(MAIL_ALREADY_EXISTS);
                    }
                }
            }
        }
    }

    private function addNewUser($nick, $hash, $mail) {
        $task = 'INSERT INTO user (nick, password, mail) VALUES(?, ?, ?)';

        return DBWrap::queryUniversal($task, [$nick, $hash, $mail]);
    }

    private function checkNick($nick) {
        return $this->checkLengthWMsg($nick, NICK_MAX_LENGTH, NICK_MIN_LENGTH, NICK_LENGTH_ERROR);
    }

    //existuje uz takyto nick v databaze
    private function checkNickInDb($nick) {
        $task = 'SELECT id_user FROM user WHERE nick = ? LIMIT 1';

        return DBWrap::queryUniversal($task, [$nick]);
    }

    //ma zadany mail tvar mailovej adresy
    private function checkMail($mail) {
        if ($this->checkLength($mail, MAIL_MAX_LENGTH, MAIL_MIN_LENGTH) && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        $this->createMessage(MAIL_ERROR);
        return false;
    }

    //existuje takyto mail v databaze
    private function checkMailInDb($mail) {
        $task = 'SELECT id_user FROM user WHERE mail = ? LIMIT 1';

        return DBWrap::queryUniversal($task, [$mail]);
    }

    //su hesla zhodne a spravne dlhe
    private function checkTwoPasswd($passwd1, $passwd2) {
        if ($this->checkPasswd($passwd1)) {
            if ($passwd1 === $passwd2) {
                return true;
            }
            $this->createMessage(PASSWD_NOT_MATCH);
        }
        return false;
    }

    private function checkPasswd($passwd) {
        return $this->checkLengthWMsg($passwd, PASSWD_MAX_LENGTH, PASSWD_MIN_LENGTH, PASSWD_LENGTH_ERROR);
    }
}