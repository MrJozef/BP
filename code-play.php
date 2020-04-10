<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/autoloader.php";

    $exampleControll = new ControllerExample();
?>

<!DOCTYPE html>

<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Jozef Kubík ml.">

    <title>Code-Play</title>

    <link rel="stylesheet" type="text/css" href="style/main-style.css">
    <link rel="stylesheet" type="text/css" href="style/code-play-style.css">

    <script src="js/jq-library/jquery.js"></script>
    <script src="js/AjaxCrier.js"></script>
    <script src="js/Styler.js"></script>
    <script src="js/CPController.js"></script>
    <script src="js/CPViewHandler.js"></script>
    <script src="js/CPdesign.js"></script>
</head>

<body>
    <header>
        <ul>
            <li><button type="button" id="showNav">Menu</button></li>
            <li><a href="index.php">Späť</a></li>
        </ul>
        <nav>
            <div>
                <h1>Code-Play</h1>
                <p>Zvoľte príklad:</p>
                <?php $exampleControll->showExamples(false) ?>
                <button id="closeNav">Zavrieť</button>
            </div>
        </nav>
    </header>

    <main>
        <div>
            <section>
                <p><!-- sem príde prípadný popis príkladu --></p>
            </section>
            <form id="css-form">
                <!-- sem sa vkladajú css vlastnosti -->
            </form>
            <section id="js-section">
                <!-- sem sa vkladá js kód -->
            </section>
        </div>

        <iframe id="result"></iframe>
    </main>
</body>
</html>