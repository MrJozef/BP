<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/MyException.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/DBWrap.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerCategory.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerUser.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerArticle.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/code-play/ControllerCssCategory.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/code-play/ControllerCssProperty.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/code-play/ControllerExample.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
    session_start();

    date_default_timezone_set('Europe/Bratislava');


    try {
        DBWrap::connect(HOST, DB_NAME, USER, PASSWORD);
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }
    $categControll = new ControllerCategory();
    $userControll = new ControllerUser();
    $articControll = new ControllerArticle();

    $cssCategControll = new ControllerCssCategory();
    $cssPropControll = new ControllerCssProperty();
    $exampleControll = new ControllerExample();


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

    if(isset($_POST['category-name'])) {
        $categControll->saveNewCat();
    }

    if(isset($_POST['article-title'])) {
        $articControll->saveNewArtic();
    }

    if(isset($_GET['verif'])) {
        $userControll->verifiedUser();
    }

    if(isset($_POST['category'])) {
        $_SESSION['category'] = $_POST['category'];//pri opatovnom nacitani stranky sa $_POST vymaze -> sposobi chybu
        $_SESSION['actpage'] = "edit-category";
    }

    if(isset($_POST['edit-category'])) {
        $categControll->saveEditCat($_SESSION['category']);
    }

    if(isset($_POST['delete-category'])) {
        $categControll->deleteCat();
        unset($_SESSION['category']);
        $_SESSION['actpage'] = 'verified-admin';
    }

    if(isset($_POST['article'])) {
        $_SESSION['article'] = $_POST['article'];//pri opatovnom nacitani stranky sa $_POST vymaze -> sposobi chybu
        $_SESSION['actpage'] = "edit-article";
    }

    if(isset($_POST['edit-article'])) {
        $articControll->saveEditArtic($_SESSION['article']);
    }

    if(isset($_POST['delete-article'])) {
        $articControll->deleteArtic($_SESSION['article']);
        unset($_SESSION['article']);
        $_SESSION['actpage'] = 'edit-category';
    }

    //code-play
    //css-category
    if (isset($_POST['subpage']) == 'manage-css-category') {
        unset($_SESSION['css-categ']);              //toto je dolezite pri kliknuti na nova kategoria
    }

    if(isset($_POST['css-categ'])) {
        $_SESSION['css-categ'] = $_POST['css-categ'];//pri opatovnom nacitani stranky sa $_POST vymaze -> sposobi chybu
    }

    if (isset($_POST['create-css-categ'])) {
        $cssCategControll->saveNewCssCateg();
    }

    if (isset($_POST['edit-css-categ'])) {
        $cssCategControll->saveEditedCssCateg();
    }

    if (isset($_POST['delete-css-categ'])) {
        $cssCategControll->deleteCssCateg();
        unset($_SESSION['css-categ']);
    }

    //css-property
    if (isset($_POST['subpage']) == 'manage-css-property') {
        unset($_SESSION['css-prop']);              //toto je dolezite pri kliknuti na nova kategoria
    }

    if(isset($_POST['css-prop'])) {
        $_SESSION['css-prop'] = $_POST['css-prop'];
    }

    if (isset($_POST['create-css-prop'])) {
        $cssPropControll->saveNewProp();
    }

    if (isset($_POST['edit-css-prop'])) {
        $cssPropControll->saveEditedProp();
    }

    if (isset($_POST['delete-css-prop'])) {
        $cssPropControll->deleteProp();
        unset($_SESSION['css-prop']);
    }

    //example
    if(isset($_POST['example'])) {
        $_SESSION['example'] = $_POST['example'];
        $_SESSION['actpage'] = 'example-form';
    }

    //ak klikáme na tlačítko vytvoriť nový príklad, je potrebné, aby s v $_SESSION nezostalo id predtým upravovaného example-u
    if (isset($_POST['subpage']) && $_POST['subpage'] === 'example-form') {
        unset($_SESSION['example']);
    }

    if (isset($_POST['use-save'])) {
        $exampleControll->saveNewUse();
    }

    if (isset($_POST['use-edit'])) {
        $exampleControll->saveEditedUse();
        unset($_POST['for-element']);
    }

    if (isset($_POST['use-delete'])) {
        $exampleControll->deleteUse();
        unset($_POST['for-element']);
    }

    if (isset($_POST['create-example'])) {
        $id_example = $exampleControll->saveNewExample();
        if($id_example) {
            $_SESSION['example'] = $id_example;
        }
    }

    if (isset($_POST['edit-example'])) {
        $exampleControll->saveEditedExample();
    }

    if (isset($_POST['delete-example'])) {
        $exampleControll->deleteExample();
        unset($_SESSION['example']);
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
            if (!isset($_SESSION['user'])) {
                require($_SERVER['DOCUMENT_ROOT']."/wcm/view/login.phtml");
                require($_SERVER['DOCUMENT_ROOT']."/wcm/view/signup.phtml");
            }
            else {
                if (!isset($_POST['subpage']) && !isset($_SESSION['actpage'])) {
                    $userControll->loadNoVerified();
                }
                else {
                    if (isset($_POST['subpage'])) {
                        $_SESSION['actpage'] = $_POST['subpage'];
                    }

                    if($_SESSION['actpage'] === 'verified-admin') {
                        $userControll->loadNoVerified();
                    }
                    elseif($_SESSION['actpage'] === 'edit-article') {
                        $articControll->aLoadAllArticNamesOfCat($_SESSION['category']); //vypise vsetky clanky v kategorii
                        $categorySelect = $categControll->onlyLoadCatSelect();
                        $exampleSelect = $exampleControll->loadExamplesNames();
                        $articControll->editArtic($_SESSION['article'], $categorySelect, $exampleSelect);
                    }
                    elseif($_SESSION['actpage'] === 'edit-category') {
                        $articControll->aLoadAllArticNamesOfCat($_SESSION['category']);
                        $categControll->editCat($_SESSION['category']);
                    }
                    elseif($_SESSION['actpage'] === 'manage-css-category') {
                        $cssCategControll->showCssCateg();
                    }
                    elseif($_SESSION['actpage'] === 'manage-css-property') {
                        $cssCategSelect = $cssCategControll->getCssCateg();
                        $cssPropControll->showCssProp($cssCategSelect);
                    }
                    elseif($_SESSION['actpage'] === 'example-form') {
                        $properties = $cssPropControll->loadCssPropNames();
                        $exampleControll->manageExample($properties);
                    }
                    elseif($_SESSION['actpage'] === 'show-examples') {
                        $exampleControll->showExamples();
                    }
                    else {
                        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/".$_SESSION['actpage'].".phtml");
                    }
                }
            }
        ?>
    </main>


    <?php if(isset($_SESSION['user'])) { ?>
        <footer>
            <form method="post">
                <ul>
                    <li>Code-play</li>
                    <li><button type="submit" name="subpage" value="manage-css-category">CSS kategórie</button></li>
                    <li><button type="submit" name="subpage" value="manage-css-property">CSS vlastnosti</button></li>
                    <li><button type="submit" name="subpage" value="show-examples">Spravovať príklady</button></li>
                </ul>
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