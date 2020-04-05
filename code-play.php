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

    <script src="js/jq-library/jquery.js"></script>
    <script src="js/AjaxCrier.js"></script>
    <script src="js/Styler.js"></script>
    <script src="js/CPController.js"></script>
    <script src="js/CPViewHandler.js"></script>

</head>

<body>
    <header>
        <h1>Web Start Line</h1>
        <ul>
            <li><a href="index.php">Späť</a></li>
        </ul>
        <nav>
            <?php $exampleControll->showExamples(false) ?>
        </nav>
    </header>

    <main>
        <section>
            <p><!-- sem príde prípadný popis príkladu --></p>
        </section>
        <form id="css-form">
            <!-- sem sa vkladajú css vlastnosti -->
        </form>
        <section id="js-section">
            <!-- sem sa vkladá js kód -->
        </section>

        <iframe id="result"></iframe>
    </main>
</body>
</html>