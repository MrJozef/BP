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

    <script src="js/jq-library/jquery.js"></script>
    <script src="https://cdn.tiny.cloud/1/y5c3tk6y8gwt9pitzkk1ilg3xyaex53pkv4s1zdcyig9dj08/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="js/AjaxCrier.js"></script>
    <script src="js/FPController.js"></script>
    <script src="js/FPViewHandler.js"></script>
</head>

<body>
    <header>
        <h1>Web Start Line</h1>
        <ul>
            <li><a href="code-play.php">Code-Play</a></li>
        </ul>
        <nav>
            <?php $categControll->loadCatMenu() ?>
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