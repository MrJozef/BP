<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/MyException.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/DBWrap.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerCategory.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerUser.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerArticle.php";
    session_start();

    try {
        DBWrap::connect('127.0.0.1', 'bp_db', 'root', '');
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }
    $categControll = new ControllerCategory();
    $userControll = new ControllerUser();
    $articControll = new ControllerArticle();



    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'logout') {
            $userControll->logout();
        }
    }

    if (isset($_POST['login-nick'])) {
        $userControll->login();
    }

    if (isset($_POST['signup-nick'])) {
        $userControll->signUp();
    }

    if (isset($_POST['passwd-old'])) {
        $userControll->changePasswd();
    }

    if (isset($_POST['nick-new'])) {
        $userControll->changeNick();
    }

    if (isset($_POST['mail-new'])) {
        $userControll->changeMail();
    }

    if (isset($_POST['delete-passwd'])) {
        $userControll->deleteMyself();
    }

    if (isset($_POST['admin-delete'])) {
        $userControll->adminDelete();
    }

    if (isset($_POST['admin-confirm'])) {
        $userControll->adminConfirm();
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
        <?php if(isset($_SESSION['user'])) $categControll->loadCatMenu() ?>
    </header>

    <main>
        <?php
            if(!isset($_SESSION['user']))
            {
                require($_SERVER['DOCUMENT_ROOT']."/wcm/view/login.phtml");
                require($_SERVER['DOCUMENT_ROOT']."/wcm/view/signup.phtml");
            }
            else {
                if(!isset($_POST['subpage'])){
                    $userControll->loadNoVerified();
                }
                else {
                    if($_POST['subpage'] === 'verified-admin') {
                        $userControll->loadNoVerified();
                    }
                    else {
                        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/".$_POST['subpage'].".phtml");
                    }
                }
            }
        ?>
    </main>


    <?php if(isset($_SESSION['user'])) { ?>
        <footer>
            <form method="post">
                <ul>
                    <li><button type="submit" name="subpage" value="create-article">Napísať článok</button></li>
                    <li><button type="submit" name="subpage" value="create-category">Vytvoriť kategóriu</button></li>
                    <li><button type="submit" name="subpage" value="user-settings">Spravovať profil</button></li>
                    <li><button type="submit" name="subpage" value="verified-admin">Spravovať administrátorov</button></li>
                    <li><p><?= $userControll->clearPost('user')?></p></li>
                    <li><button type="submit" name="action" value="logout">Odhlásiť sa</button></li>
                </ul>
            </form>
        </footer>
    <?php } ?>
</body>
</html>