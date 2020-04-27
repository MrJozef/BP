<?php

const SUCCESS_SIGN_UP = 'Boli ste úspešne zaregistrovaný! Na Váš mail bude doručený overovací E-mail,
                                pre pokračovanie na stránke ho musíte potvrdiť.';
const SUCCESS_PASSWD = 'Vaše heslo bolo úspešne zmenené!';
const SUCCESS_NICK = 'Váš Nick bol úspešne zmenený!';
const SUCCESS_MAIL = 'Váš E-mail bol úspešne zmenený!';
const SUCCES_MAIL_VERIF = 'Váš E-mail bol úspešne overený, teraz budete musieť počkať, kým Vašu žiadosť schváli niektorý z administrátorov.';
const ERROR_MAIL_VERIF = 'Váš E-mail nemohol byť overený! Obráťte sa na administrátora s touto chybou.';

class ControllerUser extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerUser();
    }

    public function login() {
        try {
            $idUser = $this->myManager->userLogin($_POST['login-nick'], $_POST['login-passwd']);
            $_SESSION['user'] = $_POST['login-nick'];
            $_SESSION['user-id'] = $idUser;
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        header("Location: ../../index.php");
    }

    public function signUp() {
        try {
            $this->myManager->userSignUp($_POST['signup-nick'], $_POST['signup-passwd'], $_POST['signup-passwd2'], $_POST['signup-mail']);
            $this->throwSuccMsg(SUCCESS_SIGN_UP);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function changeNick() {
        try {
            $this->myManager->userChangeNick($_SESSION['user'], $_POST['nick-new'],
                                                $_POST['nick-passwd']);
            $_SESSION['user'] = $_POST['nick-new'];
            $this->throwSuccMsg(SUCCESS_NICK);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }


    public function changeMail() {
        try {
            $this->myManager->userChangeMail($_SESSION['user'], $_POST['mail-new'],$_POST['mail-passwd']);
            $this->throwSuccMsg(SUCCESS_MAIL);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function changePasswd() {
        try {
            $this->myManager->userChangePasswd($_SESSION['user'], $_POST['passwd-old'], $_POST['passwd-new'], $_POST['passwd-new2']);
            $this->throwSuccMsg(SUCCESS_PASSWD);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function deleteMyself() {
        try {
            $this->myManager->userDelete($_SESSION['user'], $_POST['delete-passwd']);
            $this->logout();
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function adminDelete() {
        try {
            $this->myManager->adminRefuse($_POST['admin-delete']);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function adminConfirm() {
        try {
            $this->myManager->adminConfirm($_POST['admin-confirm']);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function verifiedUser() {
        try {
            if ($this->myManager->userVerified($_GET['verif']) === true) {
                $this->throwSuccMsg(SUCCES_MAIL_VERIF);
            }
            else {
                $this->throwErrorMsg(ERROR_MAIL_VERIF);
            }
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function getUserNameById($userId) {
        $userNick = $this->myManager->getNameById($userId);
        return $this->clearHTML($userNick);
    }

    public function loadNoVerified() {
        $result = $this->myManager->getNoVerified();

        $this->dataForView['verifAdmin'] = $this->clearHTML($result);
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/verified-admin.phtml");
    }
}