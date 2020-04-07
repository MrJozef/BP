<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/autoloader.php";

    $categControll = new ControllerCategory();
?>

<!DOCTYPE html>

<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Jozef Kubík ml.">

    <title>Web Start Line</title>

    <link rel="stylesheet" type="text/css" href="style/main-style.css">
    <link rel="stylesheet" type="text/css" href="style/index-style.css">

    <script src="js/jq-library/jquery.js"></script>
    <script src="js/AjaxCrier.js"></script>
    <script src="js/FPController.js"></script>
    <script src="js/FPViewHandler.js"></script>
</head>

<body>
    <header>
        <ul>
            <li><a href="code-play.php">Code-Play</a></li>
        </ul>
        <h1>Web Start Line</h1>
        <nav>
            <?php $categControll->loadCatMenu() ?>
        </nav>
    </header>

    <main>

    </main>

    <footer>
        <ul>
            <li><a href="wcm/view/admin.php"><span>Administrátorské rozhranie</span></a></li>
        </ul>
        <p>Made by Jozef Kubík with love <span>♥</span></p>
        <p>2020</p>
    </footer>
</body>
</html>