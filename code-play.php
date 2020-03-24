<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/wcm/model/MyException.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/wcm/model/DBWrap.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/wcm/controller/code-play/ControllerExample.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";


    try {
        DBWrap::connect(HOST, DB_NAME, USER, PASSWORD);
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }

    $exampleControll = new ControllerExample();
?>

<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Jozef Kubík ml.">

    <title>Code-Play</title>

    <script src="js/jq-library/jquery.js"></script>

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
        <form id="css-form">
            <!-- sem sa vkladajú css vlastnosti -->
        </form>

        <iframe id="result"></iframe>
    </main>
</body>
</html>