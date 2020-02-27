<?php
include "Manager.php";

const NICK_MIN_LENGTH = 4;
const NICK_MAX_LENGTH = 30;

const PASSWD_MIN_LENGTH = 6;
const PASSWD_MAX_LENGTH = 30;

const MAIL_MIN_LENGTH = 6;
const MAIL_MAX_LENGTH = 60;


class ManagerUser extends Manager
{
    public function userSignUp($nick, $passwd, $passwd2, $mail) {

    }

    //existuje uz takyto nick v databaze
    private function checkNick($nick) {

    }

    //nie je uz pouzity takyto mail?
    private function checkMail($mail) {

    }

    private function matchTwoPasswd($passwd1, $passwd2) {

    }
}