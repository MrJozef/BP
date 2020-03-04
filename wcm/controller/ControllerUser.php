<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/ManagerUser.php";


class ControllerUser extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerUser();
    }

    public function login() {
        $result = $this->myManager->userLogin($_POST['login-nick'], $_POST['login-passwd']);
        if ($result) {
            $_SESSION['user'] = $_POST['login-nick'];
        }
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        header("Location: ../../index.php");//todo toto funguje, ale urobit to inak?
    }

    public function signUp() {//todo catch
        $result = $this->myManager->userSignUp($_POST['signup-nick'], $_POST['signup-passwd'], $_POST['signup-passwd2'], $_POST['signup-mail']);

            if ($result) {
                $_SESSION['user'] = $_POST['signup-nick'];
            }
    }
}