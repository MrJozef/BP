<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/MyException.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/DBWrap.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerCategory.php";
    session_start();

    try {
        DBWrap::connect('127.0.0.1', 'bp_db', 'root', '');
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }

    $categControll = new ControllerCategory();
?>

<!DOCTYPE html>

<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Jozef Kubík ml.">

    <title>Web Start Line</title>
</head>

<body>
    <header>
        <h1>Web Start Line</h1>
        <nav>
            <?php require($_SERVER['DOCUMENT_ROOT']."/wcm/view/category-ul.phtml"); ?>
        </nav>
    </header>

    <main>

    </main>

    <footer>
        <ul>
            <li><a href="wcm/view/admin.php">Administrátorské rozhranie</a></li>
        </ul>
    </footer>
</body>
</html>