<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/Manager.php";

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

const ERROR_LOGIN = "Zadali ste neexistujúci nick!";
const ERROR_ADD_NEW_USER = "Pri registrácii nastala chyba!";
const ERROR_ACTUAL_PASSWD = "Zadali ste nesprávne svoje aktuálne heslo!";
const ERROR_UPDATE = 'Pri zmene nastavenia Vášho účtu prišlo k chybe a zmena sa neuložila!';

const SUCCESS_SIGN_UP = 'Boli ste úspešne zaregistrovaný! Na Váš mail bude doručený overovací E-mail,
                                pre pokračovanie na stránke ho musíte potvrdiť.';
const SUCCESS_PASSWD = 'Vaše heslo bolo úspešne zmenené!';
const SUCCESS_NICK = 'Váš Nick bol úspešne zmenený!';
const SUCCESS_MAIL = 'Váš E-mail bol úspešne zmenený!';

class ManagerUser extends Manager
{
    public function userSignUp($nick, $passwd, $passwd2, $mail) {

        if ($this->checkNick($nick)) {
            if ($this->checkMail($mail)) {
                if ($this->checkTwoPasswd($passwd, $passwd2)) {

                    if (!$this->checkMailInDb($mail)) {

                        if (!$this->checkNickInDb($nick)) {
                            $task = 'INSERT INTO user (nick, password, mail) VALUES(?, ?, ?)';
                            $hash = password_hash($passwd, PASSWORD_DEFAULT);

                            $this->checkUniversal($task, [$nick, $hash, $mail], SUCCESS_SIGN_UP, ERROR_ADD_NEW_USER);
                            return true;
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
        return false;
    }

    //aktualne prihlaseny uzivatel si meni heslo
    public function userChangePasswd($nick, $oldPasswd, $newPasswd, $newPasswd2) {

        if ($this->checkTwoPasswd($newPasswd, $newPasswd2)) {
            if ($this->checkUserPasswd($nick, $oldPasswd)) {
                $task = 'UPDATE user SET password = ? WHERE nick = ?';
                $hash = password_hash($newPasswd, PASSWORD_DEFAULT);

                $this->checkUniversal($task, [$passwd, $nick], SUCCESS_PASSWD, ERROR_UPDATE);
            }
        }
    }

    //aktualne prihlaseny uzivatel si meni nick
    public function userChangeNick($nick, $newNick, $passwd) {

        if ($this->checkPasswd($passwd)) {
            if ($this->checkUserPasswd($nick, $passwd)) {
                $task = 'UPDATE user SET nick = ? WHERE nick = ?';

                $this->checkUniversal($task, [$newNick, $oldNick], SUCCESS_NICK, ERROR_UPDATE);
            }
        }
    }

    //tato funkcia je len pre aktualne prihlasenych uzivatelov
    public function userChangeMail($nick, $newMail, $passwd) {

        if ($this->checkPasswd($passwd)) {
            if ($this->checkUserPasswd($nick, $passwd)) {
                $task = 'UPDATE user SET mail = ? WHERE nick = ?';

                $this->checkUniversal($task, [$newMail, $nick], SUCCESS_MAIL, ERROR_UPDATE);
            }
        }
    }

    public function userLogin($nick, $passwd) {
        if ($this->checkNick($nick)) {
            if ($this->checkPasswd($passwd)) {
                return $this->checkUserPasswd($nick, $passwd);
            }
        }
        return false;
    }

    //táto funkcia vrati tych novoprihlasenych adminov, ktori potvrdili mail, no cakaju na schvalenie adminom
    public function getNoVerified() {
        $task = 'SELECT nick, mail FROM user WHERE verified_mail = 1 AND verified_admin = 0';

        return DBWrap::selectAll($task, []);
    }

    private function checkUniversal($task, $taskParam = [], $succMsg, $errMsg) {
        try {
            DBWrap::queryUniversal($task, $taskParam);
            $this->createMessage($succMsg);
        }
        catch(PDOException $exception) {
            $this->createMessage($errMsg);
        }
    }

    //overi ci uzivatel s danym nickom ma naozaj take heslo, ake zadal - toto sa pouziva aj pre login, preto treba kontrolu ci existuje nick
    private function checkUserPasswd($nick, $passwd) {

        if($this->checkNickInDb($nick)) {
            $task = 'SELECT password FROM user where nick = ?';
            $result = DBWrap::selectOne($task, [$nick]);

            if(password_verify($passwd, $result['password'])) {
                return true;
            }
            else {
                $this->createMessage(ERROR_ACTUAL_PASSWD);
            }
            return false;
        }
        $this->createMessage(ERROR_LOGIN);
        return false;
    }

    //overi ci je nick spravne dlhy
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

    //je heslo spravne dlhe
    private function checkPasswd($passwd) {
        return $this->checkLengthWMsg($passwd, PASSWD_MAX_LENGTH, PASSWD_MIN_LENGTH, PASSWD_LENGTH_ERROR);
    }
}