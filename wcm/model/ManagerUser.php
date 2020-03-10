<?php

include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/Manager.php";

const NICK_MIN_LENGTH = 4;
const NICK_MAX_LENGTH = 30;
const NICK_ERROR = "Dĺžka Vášho nicku musí byť medzi " . NICK_MIN_LENGTH . " a " . NICK_MAX_LENGTH . " znakmi! Zároveň musí obsahovať iba písmená a číslice (číslicou však nesmie začínať)!";
const NICK_ALREADY_EXISTS = "Nick, ktorý ste si zvolili už používa iný užívateľ! Zvoľte iný.";

const PASSWD_MIN_LENGTH = 6;
const PASSWD_MAX_LENGTH = 30;
const PASSWD_LENGTH_ERROR = "Dĺžka Vášho hesla musí byť medzi " . PASSWD_MIN_LENGTH . " a " . PASSWD_MAX_LENGTH . " znakmi!";
const PASSWD_NOT_MATCH = "Zadali ste heslá, ktoré sa nezhodujú!";

const MAIL_MIN_LENGTH = 6;
const MAIL_MAX_LENGTH = 60;
const MAIL_ERROR = "Zadali ste nevalídnu E-mailovú adresu!";
const MAIL_ALREADY_EXISTS = "Na túto E-mailovú adresu je už registrovaný iný užívateľ!";

const ERROR_MAIL_NOT_CONFIRM = "Svoj účet ešte nemôžete používať, pretože ste nepotvrdili E-mail odoslaný na Vašu E-mailovú adresu.";
const ERROR_ACC_NOT_CONFIRM = "Svoj účet ešte nemôžete používať, pretože Vašu žiadosť ešte nepotvrdil žiadny administrátor!";
const ERROR_LOGIN = "Zadali ste neexistujúci nick!";
const ERROR_DELETED_ACC = "Tento účet bol zmazaný! Už ho nemôžete používať.";
const ERROR_ADD_NEW_USER = "Pri registrácii nastala chyba!";
const ERROR_ACTUAL_PASSWD = "Zadali ste nesprávne svoje aktuálne heslo!";
const ERROR_UPDATE = "Pri zmene nastavenia Vášho účtu prišlo k chybe a zmena sa neuložila!";
const ERROR_DELETE = "Pri vymazávaní Vašeho profilu nastala chyba a nezmazal sa!";
const ERROR_DB = "Neočakávaná chyba v databáze! Akcia sa nevykonala.";

// vsetky metody s nazvom check... vyhadzuju vynimky
class ManagerUser extends Manager
{
    public function userSignUp($nick, $passwd, $passwd2, $mail) {
        $this->checkNick($nick);
        $this->checkMail($mail);
        $this->checkTwoPasswd($passwd, $passwd2);

        if (!$this->findMailInDb($mail)) {

            if (!$this->findNickInDb($nick)) {
                $task = 'INSERT INTO user (nick, password, mail) VALUES(?, ?, ?)';
                $hash = password_hash($passwd, PASSWORD_DEFAULT);

                $this->tryQueryDb($task, [$nick, $hash, $mail], ERROR_ADD_NEW_USER);

                //uspech
            } else {
                throw new MyException(NICK_ALREADY_EXISTS);
            }
        } else {
            throw new MyException(MAIL_ALREADY_EXISTS);
        }
    }

    public function userLogin($nick, $passwd) {
        $this->checkNick($nick);
        $this->checkPasswd($passwd);

        if($this->findNickInDb($nick)) {
            //aby sme nevybrali uz mrtve ucty
            $task = 'SELECT id_user, password, verified_mail, verified_admin FROM user WHERE nick = ? AND dead = 0 LIMIT 1';
            $user = DBWrap::selectOne($task, [$nick]);

            if(!empty($user)) {
                if (!password_verify($passwd, $user['password'])) {
                    throw new MyException(ERROR_ACTUAL_PASSWD);
                }

                if($user['verified_mail'] === 0) {
                    throw new MyException(ERROR_MAIL_NOT_CONFIRM);
                }

                if($user['verified_admin'] === 0) {
                    throw new MyException(ERROR_ACC_NOT_CONFIRM);
                }

                return $user['id_user'];
            }
            else {
                throw new MyException(ERROR_DELETED_ACC);
            }

        }
        else {
            throw new MyException(ERROR_LOGIN);
        }
    }


    //aktualne prihlaseny uzivatel si meni heslo
    public function userChangePasswd($nick, $oldPasswd, $newPasswd, $newPasswd2) {

        $this->checkTwoPasswd($newPasswd, $newPasswd2);
        $this->checkUserPasswd($nick, $oldPasswd);

        $task = 'UPDATE user SET password = ? WHERE nick = ?';
        $hash = password_hash($newPasswd, PASSWORD_DEFAULT);
        $this->tryQueryDb($task, [$hash, $nick], ERROR_UPDATE);
    }

    //aktualne prihlaseny uzivatel si meni nick
    public function userChangeNick($nick, $newNick, $passwd) {
        $this->checkNick($newNick);//ten novy nick ci splna podmienky nicku
        $this->checkPasswd($passwd);
        $this->checkUserPasswd($nick, $passwd);

        $task = 'UPDATE user SET nick = ? WHERE nick = ?';
        $this->tryQueryDb($task, [$newNick, $nick], NICK_ALREADY_EXISTS);
    }

    //tato funkcia je len pre aktualne prihlasenych uzivatelov
    public function userChangeMail($nick, $newMail, $passwd) {
        $this->checkMail($newMail);
        $this->checkPasswd($passwd);
        $this->checkUserPasswd($nick, $passwd);

        $task = 'UPDATE user SET mail = ? WHERE nick = ?';
        $this->tryQueryDb($task, [$newMail, $nick], ERROR_UPDATE);
    }

    public function userDelete($nick, $passwd) {
        $this->checkNick($nick);
        $this->checkPasswd($passwd);
        $this->checkUserPasswd($nick, $passwd);

        $task = 'UPDATE user SET dead = 1 WHERE nick = ?';
        $this->tryQueryDb($task, [$nick], ERROR_UPDATE);
    }

    //prihlaseny administrator schvaluje novo-prihlaseneho admina, kt. si uz potvrdil mail
    public function adminConfirm($nick) {
        $task = 'UPDATE user SET verified_admin = 1 WHERE nick = ?';
        $this->tryQueryDb($task, [$nick], ERROR_DB);
    }

    //prihlaseny administrator VYMAZAVA (nie dead = 1) novo-prihlaseneho admina, kt. si uz potvrdil mail
    public function adminRefuse($nick) {
        $task = 'DELETE FROM user WHERE nick = ?';
        $this->tryQueryDb($task, [$nick], ERROR_DB);
    }

    //táto funkcia vrati tych novoprihlasenych adminov, ktori potvrdili mail, no cakaju na schvalenie adminom
    public function getNoVerified() {
        $task = 'SELECT nick, mail FROM user WHERE verified_mail = 1 AND verified_admin = 0';

        return DBWrap::selectAll($task, []);
    }

    //overi ci uzivatel s danym nickom ma naozaj take heslo, ake zadal - toto sa NEpouziva pre login, preto netreba kontrolu ci existuje nick
    private function checkUserPasswd($nick, $passwd) {
        $task = 'SELECT password FROM user where nick = ? LIMIT 1';
        $result = DBWrap::selectOne($task, [$nick]);

        if (!password_verify($passwd, $result['password'])) {
            throw new MyException(ERROR_ACTUAL_PASSWD);
        }
    }

    //overi ci je nick spravne dlhy
    private function checkNick($nick) {
        if(!preg_match("/^[A-Za-z][A-Za-z0-9]{" . (NICK_MIN_LENGTH - 1) . "," . (NICK_MAX_LENGTH - 1) . "}$/", $nick)) {
            throw new MyException(NICK_ERROR);
        }
    }

    //existuje uz takyto nick v databaze
    private function findNickInDb($nick) {
        $task = 'SELECT id_user FROM user WHERE nick = ? LIMIT 1';

        return DBWrap::queryUniversal($task, [$nick]);
    }

    //ma zadany mail tvar mailovej adresy
    private function checkMail($mail) {
        if (!$this->checkLength($mail, MAIL_MAX_LENGTH, MAIL_MIN_LENGTH) && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            throw new MyException(MAIL_ERROR);
        }
    }

    //existuje takyto mail v databaze
    private function findMailInDb($mail) { //return T/F
        $task = 'SELECT id_user FROM user WHERE mail = ? LIMIT 1';

        return DBWrap::queryUniversal($task, [$mail]);
    }

    //su hesla zhodne a spravne dlhe
    private function checkTwoPasswd($passwd1, $passwd2) {
        $this->checkPasswd($passwd1);
        if ($passwd1 !== $passwd2) {
            throw new MyException(PASSWD_NOT_MATCH);
        }
    }

    //je heslo spravne dlhe
    private function checkPasswd($passwd) {
        $this->checkLengthWException($passwd, PASSWD_MAX_LENGTH, PASSWD_MIN_LENGTH, PASSWD_LENGTH_ERROR);
    }
}