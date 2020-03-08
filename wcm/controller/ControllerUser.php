<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/ManagerUser.php";

const SUCCESS_SIGN_UP = 'Boli ste úspešne zaregistrovaný! Na Váš mail bude doručený overovací E-mail,
                                pre pokračovanie na stránke ho musíte potvrdiť.';
const SUCCESS_PASSWD = 'Vaše heslo bolo úspešne zmenené!';
const SUCCESS_NICK = 'Váš Nick bol úspešne zmenený!';
const SUCCESS_MAIL = 'Váš E-mail bol úspešne zmenený!';

class ControllerUser extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerUser();
    }

    public function login() {
        try {
            $this->myManager->userLogin($_POST['login-nick'], $_POST['login-passwd']);
            $_SESSION['user'] = $_POST['login-nick'];
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        header("Location: ../../index.php");//todo toto funguje, ale urobit to inak?
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
            $this->myManager->userChangeNick($_SESSION['user'], $_POST['nick-new'], $_POST['nick-passwd']);
            $this->throwSuccMsg(SUCCESS_NICK);
            $_SESSION['user'] = $_POST['nick-new'];
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }


    public function changeMail() {
        try {
            $this->myManager->userChangeMail($_SESSION['user'], $_POST['mail-new'], $_POST['mail-passwd']);
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


    public function loadNoVerified() {//todo catch
        $result = $this->myManager->getNoVerified();

        $this->dataForView['verifAdmin'] = $this->clearHTML($result);
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/verified-admin.phtml");
    }
}