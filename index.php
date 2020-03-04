<?php
    include "wcm/model/MyException.php";
    include "wcm/model/DBWrap.php";
    include "wcm/controller/ControllerCategory.php";
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
            <ul>
            <?php $result = $categControll->loadNamesOfCat();
                foreach ($result as $categ) {
                    echo $categ["name"];//todo prerobit na button
                }
            ?>
            </ul>
        </nav>
    </header>

    <main>

    </main>

    <footer>
        <ul>
            <li><a href="wcm/view/admin.php">Administračné rozhranie</a></li>
        </ul>
    </footer>
</body>
</html>