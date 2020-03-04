<?php
    /*$userDB = new ManagerUser();
    if (isset($_POST['nick'])) {
        $userDB->userSignUp($_POST['signup-nick'], $_POST['signup-passwd'], $_POST['signup-passwd2'], $_POST['signup-mail']);
    }*/

    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/MyException.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/DBWrap.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerCategory.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerUser.php";

try {
    DBWrap::connect('127.0.0.1', 'bp_db', 'root', '');
}
catch (MyException $e) {
    echo $e->errorMessage();
}
    $categControll = new ControllerCategory();
    $userControll = new ControllerUser();



    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'logout') {
            $userControll->logout();
        }
    }

    if (isset($_POST['login-nick'])) {
        $userControll->login();
    }

    if (isset($_POST['signup-nick'])) {
        $userControll->signUp();
    }
?>

<!DOCTYPE html>

<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Jozef Kubík ml.">

    <title>WSL - Administrátorské rozhranie</title>
</head>

<body>
    <header>
        <?php if(isset($_SESSION['user'])) require($_SERVER['DOCUMENT_ROOT']."/wcm/view/category-ul.phtml"); ?>
    </header>

    <main>
        <?php if(!isset($_SESSION['user']))
            {
                require($_SERVER['DOCUMENT_ROOT']."/wcm/view/login.phtml");
                require($_SERVER['DOCUMENT_ROOT']."/wcm/view/signup.phtml");
            }
        ?>
    </main>


    <?php if(isset($_SESSION['user'])) { ?>
        <footer>
            <ul>
                <li><button value="create-article">Napíš článok</button></li>
                <li><button value="create-category">Vytvor kategóriu</button></li>
                <li><button value="user-settings">Spravovať profil</button></li>
                <li><p><?= $userControll->clearPost('user')?></p></li>
                <li><a href="admin.php?action=logout">Odhlásiť sa</a></li>
            </ul>
        </footer>
    <?php } ?>
</body>
</html>